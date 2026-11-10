# Changelog

Changes are grouped by release line, newest version first within each numeric line.
This repository-wide history includes all release tags; entries for other lines
do not change the compatibility of the installed package. Historical entries
were reconstructed from tag diffs and the former README history. Legacy tag names
and dates are retained even where their numbering or chronological order differs.

## 3.6

### [3.6.4] - 2026-11-10

- Moved release history from the README into a complete, dated changelog with links for every release tag.
- Retained update, template and navigation instructions in the README; no runtime or dependency changes.

### [3.6.3] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.
- Included the previously committed LGPL-3.0-or-later license normalization and expanded feature/configuration documentation.

### [3.6.2] - 2026-10-31

- Replaced backend survey icons with locally bundled Lucide SVGs.

### [3.6.1] - 2026-10-28

- Added twelve language catalogues, bringing coverage to fourteen languages including English and German.

### [3.6.0] - 2026-10-28

* Added support for Contao 5.7 and PHP 8.3 or newer.
* Migrated all supplied frontend, widget, preview and backend templates to Twig while retaining existing survey data and saved template selections.
* Updated both survey themes and matrix interactions to work without jQuery or MooTools.
* Improved output escaping for answers and ordinary text while preserving rich text formatting.
* Changed CSV exports to UTF-8 and improved CSV formatting.
* Preserved leading zeroes in numeric-looking text, such as TANs, in XLSX exports.
* Updated generated TAN links to use the `?code=…` query parameter.
* Updated conditional page navigation with ordered rules and support for existing navigation settings.
* Preserved navigation rules when duplicating surveys and pages, adjusting references to copied questions and pages.

## 3.5

### [3.5.2] - 2026-11-10

- Moved release history from the README into the same repository-wide changelog used by the main branch.
- Retained update, template and navigation instructions in the README; no runtime or dependency changes.

### [3.5.1] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.
- Included the previously committed LGPL-3.0-or-later license normalization.

### [3.5.0] - 2026-11-01

* Based on the Contao 5.7 version, bringing its survey features, Twig templates and translations to Contao 5.3 LTS.
* Supports PHP 8.1 or newer and Textwizard 3.5.6.
* Restored the familiar icons from the Contao 4 version instead of the Lucide icons used in the Contao 5.7 version.
* Adapted backend compatibility for Contao 5.3.
* Expanded survey feature and configuration documentation and corrected integration-test status output.

## 3.4

### [3.4.1] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.

### [3.4.0] - 2025-03-26

- Updated dependencies and survey API usage for Contao 4.13 and PHP 7.4/PHP 8.
- Adjusted survey content elements, helper classes and backend configuration.

## 3.2

### [3.2.8] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.

### [3.2.7] - 2020-03-26

- Fixed the TAN check when starting a survey.

### [3.2.6] - 2020-02-10

- Fixed a warning in FormMatrixQuestion::validator().

### [3.2.5] - 2019-03-01

- Fixed text output for multiple-choice questions and survey completion.
- Extended condition checks and corrected a typo.

### [3.2.4.1] - 2019-02-28

- Extended navigation conditions and added condition groups.

### [3.2.4] - 2019-02-28

- Added conditional jumps between survey pages and a navigation table to track survey execution and support backward navigation.
- Corrected the condition table definition and removed condition elements from the survey-page configuration.

### [3.2.3] - 2019-02-27

- Fixed identity and class-instance checks that could create duplicate result data sets.

### [3.2.2] - 2019-02-26

- Fixed the immediate-start option check.

### [3.2.1] - 2019-02-23

- Fixed the check for personalized surveys.

### [3.2.0] - 2018-12-15

- Released the Contao 4 bundle.
- Made hook calls compatible with PHP 7 and updated the README after the release candidate.

### [3.2.0-RC1] - 2018-12-15

- Published the Contao 4 bundle release candidate with survey configuration documentation.

## 3.1

### [3.1.0.2] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.
- Asset/documentation update based on the legacy 3.1.0.1 tag.

### [3.1.0.1] - 2016-04-07

- Added an error indicator to the legacy survey forms.
- Includes preceding form-layout and widget-generation changes; this tag points to the same commit as release/3.1.0.

## Legacy release labels

### [release/3.1.0.1] - 2015-08-12

- Historical tagged snapshot of XLSX survey-data export development.

### [release/3.1.0] - 2016-04-07

- Added an error indicator to the legacy survey forms.
- This historical label points to the same commit as 3.1.0.1; its name is retained unchanged.

### [release/3.0.1] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.
- Asset/documentation update based on release/3.0.0.

### [release/3.0.0] - 2013-11-21

- Adapted the legacy extension to the Contao directory structure.

### [legacy-contao-2-icon-1] - 2026-11-10

- Added the shared questionnaire SVG package icon, README preview and Contao Manager logo documentation.
- Asset/documentation update for the legacy Contao 2 branch.

[3.6.4]: https://github.com/hschottm/survey_ce/releases/tag/3.6.4
[3.6.3]: https://github.com/hschottm/survey_ce/releases/tag/3.6.3
[3.6.2]: https://github.com/hschottm/survey_ce/releases/tag/3.6.2
[3.6.1]: https://github.com/hschottm/survey_ce/releases/tag/3.6.1
[3.6.0]: https://github.com/hschottm/survey_ce/releases/tag/3.6.0
[3.5.2]: https://github.com/hschottm/survey_ce/releases/tag/3.5.2
[3.5.1]: https://github.com/hschottm/survey_ce/releases/tag/3.5.1
[3.5.0]: https://github.com/hschottm/survey_ce/releases/tag/3.5.0
[3.4.1]: https://github.com/hschottm/survey_ce/releases/tag/3.4.1
[3.4.0]: https://github.com/hschottm/survey_ce/releases/tag/3.4.0
[3.2.8]: https://github.com/hschottm/survey_ce/releases/tag/3.2.8
[3.2.7]: https://github.com/hschottm/survey_ce/releases/tag/3.2.7
[3.2.6]: https://github.com/hschottm/survey_ce/releases/tag/3.2.6
[3.2.5]: https://github.com/hschottm/survey_ce/releases/tag/3.2.5
[3.2.4.1]: https://github.com/hschottm/survey_ce/releases/tag/3.2.4.1
[3.2.4]: https://github.com/hschottm/survey_ce/releases/tag/3.2.4
[3.2.3]: https://github.com/hschottm/survey_ce/releases/tag/3.2.3
[3.2.2]: https://github.com/hschottm/survey_ce/releases/tag/3.2.2
[3.2.1]: https://github.com/hschottm/survey_ce/releases/tag/3.2.1
[3.2.0]: https://github.com/hschottm/survey_ce/releases/tag/3.2.0
[3.2.0-RC1]: https://github.com/hschottm/survey_ce/releases/tag/3.2.0-RC1
[3.1.0.2]: https://github.com/hschottm/survey_ce/releases/tag/3.1.0.2
[3.1.0.1]: https://github.com/hschottm/survey_ce/releases/tag/3.1.0.1
[release/3.1.0.1]: https://github.com/hschottm/survey_ce/releases/tag/release/3.1.0.1
[release/3.1.0]: https://github.com/hschottm/survey_ce/releases/tag/release/3.1.0
[release/3.0.1]: https://github.com/hschottm/survey_ce/releases/tag/release/3.0.1
[release/3.0.0]: https://github.com/hschottm/survey_ce/releases/tag/release/3.0.0
[legacy-contao-2-icon-1]: https://github.com/hschottm/survey_ce/releases/tag/legacy-contao-2-icon-1
