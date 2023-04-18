pdir Fork

[![Latest Version on Packagist](http://img.shields.io/packagist/v/pdir/contao-survey.svg?style=flat)](https://packagist.org/packages/pdir/contao-survey)
[![Installations via composer per month](http://img.shields.io/packagist/dm/pdir/contao-survey.svg?style=flat)](https://packagist.org/packages/pdir/contao-survey)
[![Installations via composer total](http://img.shields.io/packagist/dt/pdir/contao-survey.svg?style=flat)](https://packagist.org/packages/pdir/contao-survey)
<a href="https://github.com/pdir/contao-survey/issues?q=is%3Aissue+is%3Aopen+sort%3Aupdated-desc"><img alt="Issue Resolution time" src="http://isitmaintained.com/badge/resolution/pdir/contao-survey.svg"></a>
<a href="https://github.com/pdir/contao-survey/issues?q=is%3Aissue+is%3Aopen+sort%3Aupdated-desc"><img alt="Open issues" src="http://isitmaintained.com/badge/open/pdir/contao-survey.svg"></a>
<a href="https://codecov.io/gh/pdir/contao-survey"><img src="https://codecov.io/gh/pdir/contao-survey/branch/master/graph/badge.svg" alt></a>
<a href="https://github.com/pdir/contao-survey/actions"><img src="https://github.com/pdir/contao-survey/actions/workflows/ci.yml/badge.svg?branch=master" alt></a>

Original Package

[![Latest Version on Packagist](http://img.shields.io/packagist/v/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer per month](http://img.shields.io/packagist/dm/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
[![Installations via composer total](http://img.shields.io/packagist/dt/hschottm/contao-survey.svg?style=flat)](https://packagist.org/packages/hschottm/contao-survey)
<a href="https://github.com/hschottm/survey_ce/issues?q=is%3Aissue+is%3Aopen+sort%3Aupdated-desc"><img alt="Issue Resolution time" src="http://isitmaintained.com/badge/resolution/hschottm/survey_ce.svg"></a>
<a href="https://github.com/hschottm/survey_ce/issues?q=is%3Aissue+is%3Aopen+sort%3Aupdated-desc"><img alt="Open issues" src="http://isitmaintained.com/badge/open/hschottm/survey_ce.svg"></a>

# contao-survey
A contao bundle to create online surveys. Supports multiple choice questions, openended questions, matrix questions and constant sum questions. Surveys can be run as anonymized or personalized surveys for specific members. Anonymized surveys can limited to TAN access only to run a representative survey.

Survey results are available as cumulated and detailed results with an option to export the results.

Exports will be in csv format. If the bundle [hschottm/contao-xls-export](https://packagist.org/packages/hschottm/contao-xls-export) is installed, exports will be in Excel xls format, if the bundle [phpoffice/phpspreadsheet](https://packagist.org/packages/phpoffice/phpspreadsheet) is installed, exports will be in Excel xlsx format.

A special thanks goes to Georg Rehfeld for his development of the detailed survey export and the enhancements of the survey tool.

# Contributors

<a href = "https://github.com/pdir/contao-survey/graphs/contributors">
  <img src = "https://contrib.rocks/image?repo=pdir/contao-survey"/>
</a>

Made with [contributors-img](https://contrib.rocks).

# Notes for developers

#### Run before commit

    vendor/bin/ecs check src tests

#### Test your changes using the survey tables included in the package.

With version 3.4.3 we have added survey tables to the package to allow consistent testing. You can find the tables in the
reference-survey.sql file in the _misc folder. So if you want to test the behavior of your changed code in a
reproducible way, please use these tables.

If you make changes to the survey tables, please commit them also to the reference-survey.sql so developers can
test their own code against this reference survey.

Load the tables into your DB and activate Survey 1, which consists of
five question pages and one results page. For now, you'll need to manually load these tables into your DB, but
we're working on automating the tests a bit more.

The question pages cover all the questions that the package currently offers. The results page shows the results
of the question pages. Currently, however, there are unfortunately still errors on the results page.


