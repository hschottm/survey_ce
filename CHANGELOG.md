# Changelog

[//]: <> (
Types of changes
    Added for new Addeds.
    Changed for changes in existing functionality.
    Deprecated for soon-to-be removed Addeds.
    Removed for now removed Addeds.
    Fixed for any bug fixes.
    Security in case of vulnerabilities.
)

## [3.6.0](https://github.com/pdir/contao-survey/tree/3.6.0) – 2023-09-13

- [Added] an **autostart** feature for an anonymous survey with TAN. To use this feature, the page with the corresponding survey **must be accessed using a link** of the form https://domain/my-survey-page/code/NNNNNN.html with N as the TAN.
- [Added] some Functions that allow contao survey to be **used without notification** center. The core functions for the notification center remain in contao survey, but they will not be used until it is installed.
- [Changed] Logic for limiting surveys to user groups
- [Changed] Added rules to lock and unlock certain features for surveys and TANs
- [Changed] Processing of survey types was extended. Now also personalized surveys with a TAN are possible, to which the participants can be invited and reminded if the Notifications Center is installed, implements: [#45](https://github.com/pdir/contao-survey/issues/45) 🤗 [AI ANTWORT:INTERNET GmbH](https://www.antwortinternet.com/)
- [Added] font awesome svg icons
- [Added] new field **survey duration** to show the estimated duration of a survey
- [Added] Possibility of using invitation and reminder e-mails together with some new tokens for the Notification Center, implements: [#46](https://github.com/pdir/contao-survey/issues/46) 🤗 [AI ANTWORT:INTERNET GmbH](https://www.antwortinternet.com/)
- [Added] Support for [Notification Center](https://github.com/terminal42/contao-notification_center), implements: [#46](https://github.com/pdir/contao-survey/issues/46)

## [3.5.1](https://github.com/pdir/contao-survey/tree/3.5.1) – 2023-08-20

- [Added] extended member related TAN generation added
- [Added] TAN export extended by members
- [Fixed] Error message whilst exporting TANS from the backend [#43](https://github.com/pdir/contao-survey/issues/43)
- [Fixed] the error whilst creating TANs [#43](https://github.com/pdir/contao-survey/issues/42)

## [3.5.0](https://github.com/pdir/contao-survey/tree/3.5.0) – 2023-06-05

- [Added] "mark as finished" option to result page  [#35](https://github.com/pdir/contao-survey/issues/35) 🤗 [koertho](https://github.com/koertho)
- [Fixed] issues with export 🤗 [koertho](https://github.com/koertho)

## [3.4.3](https://github.com/pdir/contao-survey/tree/3.4.3) – 2023-04-18

- [Removed] ...the obsolete attribute **summary** were replaced by the html5-attribute **details** in the templates.
- [Added] ...survey reference tables to define a consistent basis for survey testing [#36](https://github.com/pdir/contao-survey/issues/36).
- [Fixed] ...an error in the widget title display [#33](https://github.com/pdir/contao-survey/issues/33).
- [Fixed] ...some more PHP8 warnings caused by wrong array keys. The answer option 'other' is now displayed correctly in all cases [#31](https://github.com/pdir/contao-survey/issues/31).
- [Fixed] ...some PHP8 warnings caused by wrong array keys [#30](https://github.com/pdir/contao-survey/issues/30).

## [3.4.2](https://github.com/pdir/contao-survey/tree/3.4.2) – 2023-02-27

- [Fixed] An error in the condition that determines the necessity of choices field migration. 🤗 [akroii](https://github.com/akroii) for the financial support.
- [Fixed] Fixes a bug in multi-page surveys [#19](https://github.com/pdir/contao-survey/issues/19)

## [3.4.1](https://github.com/pdir/contao-survey/tree/3.4.1) – 2022-12-21

- [Fixed] Fix Migration [#21](https://github.com/pdir/contao-survey/issues/21)
- [Fixed] Remove warnings
- [Changed] Increase PHP version to 7.4 || 8.0

## [3.4.0](https://github.com/pdir/contao-survey/tree/3.4.0) – 2022-10-11

- [Added] Add categories to multiple choice questions 🤗 [koertho](https://github.com/koertho)
- [Changed] Allow hschottm/contao-textwizard 3.4
- [Fixed] Remove warnings

## [3.3.0](https://github.com/pdir/contao-survey/tree/3.3.0) – 2022-09-02

- [Added] Add result page type 🤗 [koertho](https://github.com/koertho)
- [Fixed] Fix export for german umlauts (C4.9) 🤗 [arboc](https://github.com/arboc)
- [Changed] Increase PHP version to 7.2 || 8.0

## [3.2.12](https://github.com/pdir/contao-survey/tree/3.2.12) – 2022-06-08

- [Fixed] Do not use global namespace 🤗 [fritzmg](https://github.com/fritzmg)

## [3.2.11](https://github.com/pdir/contao-survey/tree/3.2.11) – 2022-02-16

- [Fixed] Fix result export 🤗 [arboc](https://github.com/arboc)

## [3.2.10](https://github.com/pdir/contao-survey/tree/3.2.10) – 2021-11-24

- [Fixed] Fix symfony warnings

## [3.2.9](https://github.com/pdir/contao-survey/tree/3.2.9) – 2021-04-16

- [Fixed] Eliminate second parameter in getTemplateGroup function 🤗 [akroii](https://github.com/akroii)

## [3.2.8](https://github.com/pdir/contao-survey/tree/3.2.8) – 2021-03-22

- [Fixed]  Fix error in Contao 4.9
