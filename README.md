[![Latest Version on Packagist](http://img.shields.io/packagist/v/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer per month](http://img.shields.io/packagist/dm/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer total](http://img.shields.io/packagist/dt/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)

# contao-survey
A contao bundle to create online surveys. Supports multiple choice questions, openended questions, matrix questions and constant sum questions. Surveys can be run as anonymized or personalized surveys for specific members. Anonymized surveys can limited to TAN access only to run a representative survey.

Survey results are available as cumulated and detailed results with an option to export the results.

Exports will be in csv format. If the bundle [hschottm/contao-xls-export](https://packagist.org/packages/hschottm/contao-xls-export) is installed, exports will be in Excel xls format, if the bundle [phpoffice/phpspreadsheet](https://packagist.org/packages/phpoffice/phpspreadsheet) is installed, exports will be in Excel xlsx format.

A special thanks goes to Georg Rehfeld for his development of the detailed survey export and the enhancements of the survey tool.

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
