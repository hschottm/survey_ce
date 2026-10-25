(() => {
    if (customElements.get('survey-condition-editor')) return;

    class SurveyConditionEditor extends HTMLElement {
        connectedCallback() {
            if (this.initialized) return;
            this.initialized = true;
            this.config = JSON.parse(this.dataset.configuration);
            this.labels = this.config.labels;
            this.input = this.querySelector('input[type="hidden"]');
            this.list = this.querySelector('[data-rules]');
            try {
                this.groups = JSON.parse(this.input.value);
                if (!Array.isArray(this.groups)) throw new Error();
            } catch {
                const error = document.createElement('p');
                error.className = 'tl_error';
                error.textContent = this.labels.invalid;
                this.list.append(error);
                this.querySelector('[data-add-rule]').disabled = true;
                return;
            }
            this.querySelector('[data-add-rule]').addEventListener('click', () => {
                this.groups.push({target: '', conditions: [this.emptyCondition()]});
                this.update();
                this.list.lastElementChild.querySelector('select').focus();
            });
            this.search = '';
            this.querySelector('[data-question-search]').addEventListener('input', event => {
                this.search = event.target.value.toLocaleLowerCase();
                this.render();
            });
            this.render();
        }

        emptyCondition() { return {question: '', operator: '=', value: ''}; }

        element(tag, text, className) {
            const node = document.createElement(tag);
            if (text !== undefined) node.textContent = text;
            if (className) node.className = className;
            return node;
        }

        button(label, handler, disabled = false) {
            const button = this.element('button', label, 'tl_submit');
            button.type = 'button';
            button.disabled = disabled;
            button.addEventListener('click', handler);
            return button;
        }

        select(options, value, handler, disabled = false) {
            const select = this.element('select', undefined, 'tl_select');
            for (const option of options) {
                select.add(new Option(option.label, option.value));
            }
            if (value !== '' && !options.some(option => option.value === value)) {
                select.add(new Option(this.labels.legacyValue + ' ' + value, value));
            }
            select.value = value;
            select.disabled = disabled;
            select.addEventListener('change', () => handler(select.value));
            return select;
        }

        field(label, control) {
            const wrapper = this.element('label', undefined, 'survey-condition-field');
            wrapper.append(this.element('span', label), control);
            return wrapper;
        }

        update(render = true) {
            this.input.value = JSON.stringify(this.groups);
            this.input.dispatchEvent(new Event('change', {bubbles: true}));
            if (render) this.render();
        }

        render() {
            this.list.replaceChildren();
            this.groups.forEach((group, index) => {
                const card = this.element('fieldset', undefined, 'survey-condition-rule');
                card.append(this.element('legend', this.labels.rule + ' ' + (index + 1)));
                const always = group.conditions.length === 1 && group.conditions[0].question === '0';
                const legacy = group.conditions.some(condition => {
                    if (condition.question === '0') return !always;
                    if (condition.question === '') return false;
                    const q = this.config.questions.find(q => q.id === condition.question);
                    return !q || !q.supported || !q.operators.includes(condition.operator)
                        || (q.choices.length && condition.value !== '' && !q.choices.some(choice => choice.value === condition.value));
                });
                if (legacy) card.append(this.element('p', this.labels.legacy, 'tl_help'));

                const type = this.select([
                    {value: 'all', label: this.labels.all},
                    {value: 'always', label: this.labels.always}
                ], always ? 'always' : 'all', value => {
                    group.conditions = value === 'always' ? [{question: '0', operator: '=', value: ''}] : [this.emptyCondition()];
                    this.update();
                }, legacy);
                card.append(this.field(this.labels.when, type));
                if (!always) {
                    group.conditions.forEach((condition, conditionIndex) => {
                        const row = this.element('div', undefined, 'survey-condition-row');
                        const question = this.config.questions.find(q => q.id === condition.question);
                        const questions = [{value: '', label: this.labels.chooseQuestion}, ...this.config.questions
                            .filter(q => q.id === condition.question || (q.supported && q.label.toLocaleLowerCase().includes(this.search)))
                            .map(q => ({value: q.id, label: q.label}))];
                        row.append(this.field(this.labels.question, this.select(questions, condition.question, value => {
                            condition.question = value;
                            condition.operator = '=';
                            condition.value = '';
                            this.update();
                        }, legacy)));
                        const operators = (question?.operators || ['=', '!=']).map(operator => ({
                            value: operator,
                            label: question?.multiple && operator === '=' ? this.labels.contains
                                : question?.multiple && operator === '!=' ? this.labels.notContains
                                : this.labels.operators[operator]
                        }));
                        row.append(this.field(this.labels.operator, this.select(operators, condition.operator, value => {
                            condition.operator = value;
                            this.update(false);
                        }, legacy)));
                        let answer;
                        if (question?.choices.length) {
                            answer = this.select([{value: '', label: this.labels.chooseAnswer}, ...question.choices], condition.value, value => {
                                condition.value = value;
                                this.update(false);
                            }, legacy);
                        } else {
                            answer = this.element('input', undefined, 'tl_text');
                            answer.type = question?.numeric ? 'number' : 'text';
                            if (question?.numeric) answer.step = 'any';
                            answer.maxLength = 1024;
                            answer.value = condition.value;
                            answer.disabled = legacy;
                            answer.addEventListener('input', () => {
                                condition.value = answer.value;
                                this.update(false);
                            });
                        }
                        row.append(this.field(this.labels.answer, answer));
                        row.append(this.button(this.labels.removeCondition, () => {
                            group.conditions.splice(conditionIndex, 1);
                            this.update();
                        }, legacy || group.conditions.length === 1));
                        card.append(row);
                    });
                    card.append(this.button(this.labels.addCondition, () => {
                        group.conditions.push(this.emptyCondition());
                        this.update();
                        this.list.children[index].querySelectorAll('.survey-condition-row')[group.conditions.length - 1].querySelector('select').focus();
                    }, legacy));
                }
                card.append(this.field(this.labels.target, this.select([
                    {value: '', label: this.labels.choosePage}, ...this.config.pages.filter(p => p.value !== this.config.currentPage || p.value === group.target)
                ], group.target, value => {
                    group.target = value;
                    this.update(false);
                }, legacy)));
                const actions = this.element('div', undefined, 'survey-condition-actions');
                actions.append(
                    this.button(this.labels.up, () => {
                        [this.groups[index - 1], this.groups[index]] = [this.groups[index], this.groups[index - 1]];
                        this.update();
                    }, index === 0),
                    this.button(this.labels.down, () => {
                        [this.groups[index + 1], this.groups[index]] = [this.groups[index], this.groups[index + 1]];
                        this.update();
                    }, index === this.groups.length - 1),
                    this.button(this.labels.removeRule, () => {
                        this.groups.splice(index, 1);
                        this.update();
                    })
                );
                card.append(actions);
                if (always && index < this.groups.length - 1) card.append(this.element('p', this.labels.unreachable, 'tl_help'));
                this.list.append(card);
            });
        }
    }
    customElements.define('survey-condition-editor', SurveyConditionEditor);
})();
