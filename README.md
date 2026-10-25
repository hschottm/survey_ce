[![Latest Version on Packagist](http://img.shields.io/packagist/v/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer per month](http://img.shields.io/packagist/dm/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer total](http://img.shields.io/packagist/dt/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)

# contao-survey
A contao bundle to create online surveys. Supports multiple choice questions, openended questions, matrix questions and constant sum questions. Surveys can be run as anonymized or personalized surveys for specific members. Anonymized surveys can limited to TAN access only to run a representative survey.

Survey results are available as cumulated and detailed results with an option to export the results.

Exports will be in csv format. If the bundle [hschottm/contao-xls-export](https://packagist.org/packages/hschottm/contao-xls-export) is installed, exports will be in Excel xls format, if the bundle [phpoffice/phpspreadsheet](https://packagist.org/packages/phpoffice/phpspreadsheet) is installed, exports will be in Excel xlsx format.

A special thanks goes to Georg Rehfeld for his development of the detailed survey export and the enhancements of the survey tool.

## Contao 5.7 LTS

This version requires PHP 8.3+ and Contao 5.7 (`^5.7`). Install it together with
the Contao-5.7-compatible version of `hschottm/contao-textwizard`. When testing
these unpublished changes, use Composer path repositories for **both** local
packages; installing the released TextWizard package will not include the local
compatibility fixes.

All 23 frontend, widget, preview and backend templates now use Twig. Their base
names remain unchanged, e.g. `ce_survey.html.twig`,
`survey_question_matrix.html.twig` and `be_survey_create_tan.html.twig`. Existing
survey data, DCA configuration and saved template selections are retained.
Templates are available in the flat `@Contao` namespace and expose the
`survey_content` block for customization. For example:

```twig
{% extends '@Contao/ce_survey.html.twig' %}

{% block survey_content %}
    <div class="my-survey">{{ parent() }}</div>
{% endblock %}
```

Migrate project-specific `.html5` overrides separately: Contao can still prefer
a legacy override over the corresponding Twig template. Copying PHP template
code into a `.twig` file is not sufficient. Rich text and generated widget HTML
remain markup; answer values and ordinary text are escaped by Twig. Both survey
themes and the matrix cell interaction work without jQuery or MooTools.

CSV exports now use PHP's native CSV writer and UTF-8 (instead of ISO-8859-1).
Update downstream import settings accordingly. PhpSpreadsheet remains optional
for XLSX exports; numeric-looking text such as TANs retains its leading zeroes.
Generated TAN links use the `?code=…` query parameter.

After updating the packages in the Contao application:

```sh
vendor/bin/contao-console cache:clear
vendor/bin/contao-console assets:install public
vendor/bin/contao-console contao:migrate
```

The extension does not change its database schema in this update. Run the normal
Contao migration process for the application upgrade.

## Tests

Run `composer test` after installing dependencies. The test suite uses the real
Contao and Twig classes with isolated application services. It covers template
compilation, DCA loading, widget rendering, answer selection, escaping, validation, survey
navigation markup, model registration, backend TAN forms, result reports and CSV
export. If PhpSpreadsheet is installed, its exporter is tested as well.

These tests do not replace a complete installation test with a database: verify
anonymous and member-only participation, TAN/resume, conditional navigation,
saving answers, confirmation email and result exports in the target application.

## Conditional page navigation

Edit a survey page and open **Navigation after this page** (German:
**Weiterleitung nach dieser Seite**). Each rule contains AND-connected conditions
and one destination page. Rules run from top to bottom; the first matching rule
wins. Use the up/down buttons to change priority. An **Always** rule can provide
a final default destination; otherwise navigation continues to the next page.

The editor supports single/multiple-choice questions, text answers and numeric
answers from the current or preceding pages of the same survey. Missing answers
do not match, including “does not equal” and “does not contain answer”. Numeric
zero remains a valid numeric answer. Matrix, constant-sum and date/time
conditions are not offered for new rules yet.

Existing records in `tl_survey_condition` load automatically. Unsupported legacy
rules remain visible and can be reordered or removed; unchanged rules are
preserved. Changes are written transactionally after successful page submission,
not when editing the rule cards. No database schema migration is needed.
Condition records are separate from the page's version history.

Duplicating a survey also duplicates its navigation rules and remaps both question
and destination page IDs to the copied survey. Duplicating a single page remaps
references to its copied questions and keeps references to other pages/questions
in the same survey. Participants, answers and TANs are not copied.

The integration test `tests/integration/copy.php` uses Contao's actual child-copy
routine. Run it from the Contao application's directory with
`SURVEY_COPY_TEST_ID=<survey-id>` set in a test installation. Test records are
created inside a transaction and rolled back, including on failure.
