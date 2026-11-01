[![Latest Version on Packagist](http://img.shields.io/packagist/v/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer per month](http://img.shields.io/packagist/dm/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer total](http://img.shields.io/packagist/dt/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)

# contao-survey

contao-survey lets you create and run online questionnaires in Contao. Build
surveys with several pages, combine different question types, guide participants
with conditional navigation and evaluate or export their answers. Surveys are
managed in the backend and displayed on your website using the **Survey** content
element.

## Features

### Question types

| Type | Available options |
| --- | --- |
| Open-ended questions | Single-line or multi-line text, integers, decimal numbers, dates and times. Depending on the subtype, configure placeholders, labels, text length or lower and upper limits. |
| Multiple-choice questions | One answer, several answers or a Yes/No choice. Arrange choices horizontally or vertically; a dropdown is also available for single answers. Add an optional “Other” answer with a text field. |
| Matrix questions | Define your own rows and columns with one or several answers per row. Add a neutral column such as “Don't know”, or opposite labels such as “Good” and “Bad”. |
| Constant-sum questions | Let participants distribute values across several items, for example allocating 100 points. Require an exact total or set a maximum total. |

Questions can be mandatory or optional and can include formatted question text
and explanatory help. You can show or hide the question title independently of
the question text.

### Pages and conditional navigation

Organize a questionnaire into pages, each with its own introduction and questions.
Reorder or duplicate pages and questions to build the desired sequence.

Define rules for what happens after a page: an answer can lead participants to a
specific page, skipping sections that do not apply to them. For example, a “Yes”
answer can open a follow-up section, while “No” continues to the next topic.
Rules can combine several conditions and have a defined priority. Without a
matching rule, the survey continues to the next page in sequence. See
[Conditional page navigation](#conditional-page-navigation) for supported
comparisons and details.

### Reusable answer scales

Create your own answer scales, such as “Very satisfied” through “Very
dissatisfied”, and organize them in folders. Scales have a language and can be
copied into the answer choices of multiple-choice questions in that language.
This makes it easier to use consistent choices across a questionnaire. The choices
are copied into the question; later changes to a saved scale do not automatically
change existing questions.

### Participation and access

Choose the access method that fits your survey:

* **Open anonymous access:** participation without a member login or TAN code.
* **Anonymous access with TAN codes:** participation requires a valid code, with
  one completed submission per TAN. Answers can be associated with that TAN.
* **Personalized access:** participation requires a frontend member login, with
  one completed submission per member. Answers can be associated with the member.
  Access can be restricted to selected member groups.

Generate TAN codes in the backend, inspect whether they have been used and export
codes for distribution. Select the website page containing the survey to include
participation links in the export. A cookie option can remember participants.

### Survey settings

For each survey, you can configure:

* **Basic information:** title, author, description and language.
* **Availability:** start and end dates for participation.
* **Welcome and completion:** an introduction, a final message and an optional
  redirect to a website page after completion.
* **Navigation and display:** show the survey title or an exit link, allow going
  back to the previous page, or start immediately without the introduction page.
* **Confirmation e-mails:** recipients, sender, reply-to address, subject,
  message text, an HTML template and attachments. An alternative confirmation
  message can have its own sending condition and settings.
* **Presentation:** select a survey template in the content element and a page
  template for individual survey pages.

### Results and export

View aggregated results and detailed answers in the backend. Depending on the
question type, statistics include answer counts, distributions and numerical
summaries. Participant records are available separately from the questionnaire
structure.

Export results for further analysis. CSV export is included; Excel XLSX export
is available when [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)
is installed. The legacy
[hschottm/contao-xls-export](https://packagist.org/packages/hschottm/contao-xls-export)
integration also supports XLS export. The available export format depends on the
installed export package.

Surveys can be duplicated for reuse, including their pages, questions and
navigation rules. Participant records, submitted answers and TAN codes are not
copied into the new survey.

A special thanks goes to Georg Rehfeld for his development of the detailed survey
export and the enhancements of the survey tool.

## Requirements

* PHP 8.3 or newer
* Contao 5.7 (`^5.7`)
* A Contao 5.7-compatible version of `hschottm/contao-textwizard`

## Release history

### Contao 5.7 support

* Added support for Contao 5.7 and PHP 8.3 or newer.
* Migrated all supplied frontend, widget, preview and backend templates to Twig while retaining existing survey data and saved template selections.
* Updated both survey themes and matrix interactions to work without jQuery or MooTools.
* Improved output escaping for answers and ordinary text while preserving rich text formatting.
* Changed CSV exports to UTF-8 and improved CSV formatting.
* Preserved leading zeroes in numeric-looking text, such as TANs, in XLSX exports.
* Updated generated TAN links to use the `?code=…` query parameter.
* Updated conditional page navigation with ordered rules and support for existing navigation settings.
* Preserved navigation rules when duplicating surveys and pages, adjusting references to copied questions and pages.

## Updating existing installations

After updating the extension, clear the Contao cache, reinstall the bundle assets
and run the Contao database migrations:

```sh
vendor/bin/contao-console cache:clear
vendor/bin/contao-console assets:install public
vendor/bin/contao-console contao:migrate
```

This update does not change the extension's database schema. Run the normal
Contao migration process for the application upgrade.

CSV exports now use UTF-8 instead of ISO-8859-1. Adjust downstream import settings
accordingly. PhpSpreadsheet remains optional for XLSX exports.

Custom `.html5` template overrides must be converted to Twig. Contao may still
prefer a legacy override over the corresponding Twig template; renaming a PHP
template to `.twig` is not sufficient.

## Customizing templates

Template base names remain unchanged, for example `ce_survey.html.twig`,
`survey_question_matrix.html.twig` and `be_survey_create_tan.html.twig`.
Templates are available through the `@Contao` namespace. Use the `survey_content`
block to customize the survey output:

```twig
{% extends '@Contao/ce_survey.html.twig' %}

{% block survey_content %}
    <div class="my-survey">{{ parent() }}</div>
{% endblock %}
```

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

Existing navigation rules load automatically. Unsupported legacy
rules remain visible and can be reordered or removed; unchanged rules are
preserved. Changes are saved when the page form is submitted successfully. No database schema migration is needed.
Condition records are separate from the page's version history.

Duplicating a survey also duplicates its navigation rules and remaps both question
and destination page IDs to the copied survey. Duplicating a single page remaps
references to its copied questions and keeps references to other pages/questions
in the same survey. Participants, answers and TANs are not copied.
