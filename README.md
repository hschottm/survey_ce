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

# Features from 3.6.0
Notification Center support was added with version 3.6.0. Also, some new Notification Center tokens and new fields have been added.
#### The following NC tokens have been added:
- ##survey_title##
- ##survey_link##
- ##survey_duration##
- ##survey_recipient_email##
- ##survey_recipient_firstname##
- ##survey_recipient_lastname##
- ##survey_recipient_fullname##

For these tokens a German and an English translation is currently implemented.

#### The generation of TANs has been completely revised and the call of some TAN-related actions has been better secured.
- When generating TANs, the system now distinguishes between the **individual survey types** itself.
- **The number** of "non-member related" TANs that can be generated with one request **is now limited to 999** and
can be configured using the environment variable **MAX_ALLOWED_TAN**.
- Previously, it was possible to generate an infinite (or very large) number of TANs with one request,
which could cause the PHP process to crash. Here, too, the environment variable **should be configured with care!**
- In most cases you **do not need more** than 999 TANs

#### When the NC is installed, notifications can be used for specific surveys.
- Of course, Notifications must be **configured beforehand**.
- Invitations and reminders should be used together with **member groups**.
- Upon the user's instruction, the system **sends** invitations and reminders **to all** participants of a survey. **This can be serveral groups!**
- **Members who are in several groups** are automatically **notified only at once**.
- If the participants are not limited to one group, the system **sends invitations to all members!**
- The system takes into account the circumstances of **whether and when** participants to a survey have **already been invited** and **when and how often** they have been **reminded**.
- Thus, participants who **have already** been invited are **not invited again** and participants who have already been reminded are **logged**.
- The number of reminders sent is counted and is currently **not limited**.
- Participants who have **already started or finished** the survey **will not be** invited or reminded **again**.
- Sending an invitation or reminder **always requires** an association between the **survey and the survey page** in order to assign a **personalized link** to each participant.
- Invitations and reminders should be sent as a **single notification** - as **opposed to** sending via **BCC**.
**The advantage** is that all **subscriber-related data are available** in each notification.
**The disadvantage** is that several/many notifications have to be sent individually, **which costs time and resources**.

### A simple template for an invitation
Copy and paste them into your notification.
````
We would like to invite you to participate in the following survey: ##survey_title##

the following tokens can be used:

survey_title: ##survey_title##
survey_link: ##survey_link##
survey_duration: ##survey_duration##
survey_recipient_email: ##survey_recipient_email##
survey_recipient_firstname: ##survey_recipient_firstname##
survey_recipient_lastname: ##survey_recipient_lastname##
survey_recipient_fullname: ##survey_recipient_fullname##`
````

A new **surveyDuration** field has been added. Now it is possible to include the estimated duration (in minutes, max 255 min) of a survey. Either it is estimated or the survey creator determines the duration during a self-test.

# Licenses
### Font Awesome
The full suite of pictographic icons, examples, and documentation can be found at: https://fontawesome.com/
The license file for font awesome is included in this package under [LICENSE_FONT_AWESOME](https://github.com/contao-themes-net/font-awesome-inserttag-bundle/blob/main/LICENSE_FONT_AWESOME)
- The Font Awesome font is licensed under the SIL Open Font License - http://scripts.sil.org/OFL
- Font Awesome CSS, LESS, and SASS files are licensed under the MIT License - http://opensource.org/licenses/mit-license.html
- The Font Awesome pictograms are licensed under the CC BY 3.0 License - http://creativecommons.org/licenses/by/3.0/
- Attribution is no longer required in Font Awesome 3.0, but much appreciated: "Font Awesome by Dave Gandy - http://fortawesome.github.com/Font-Awesome"

# Contributors

<a href = "https://github.com/pdir/contao-survey/graphs/contributors">
  <img src = "https://contrib.rocks/image?repo=pdir/contao-survey"/>
</a>

Made with [contributors-img](https://contrib.rocks).

# Notes for developers

#### Run before commit

    vendor/bin/ecs check src tests
    vendor/bin/phpstan analyse
    vendor/bin/phpunit --colors=always


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


