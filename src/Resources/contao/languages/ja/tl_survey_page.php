<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

$GLOBALS['TL_LANG']['tl_survey_page']['title'][0] = 'タイトル';
$GLOBALS['TL_LANG']['tl_survey_page']['title'][1] = 'ページタイトルを入力してください。';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][0] = '説明';
$GLOBALS['TL_LANG']['tl_survey_page']['description'][1] = 'ページの説明を入力してください。';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][0] = '最終変更';
$GLOBALS['TL_LANG']['tl_survey_page']['tstamp'][1] = '最後に変更が行われた日時。';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][0] = 'はじめに';
$GLOBALS['TL_LANG']['tl_survey_page']['introduction'][1] = 'ページの紹介文を入力してください。紹介文はページの冒頭に表示されます。';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][0] = 'ページテンプレート';
$GLOBALS['TL_LANG']['tl_survey_page']['page_template'][1] = 'ここでページテンプレートを選択できます。';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][0] = '新しいページ';
$GLOBALS['TL_LANG']['tl_survey_page']['new'][1] = '新しいページを作成する';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][0] = '詳細を表示';
$GLOBALS['TL_LANG']['tl_survey_page']['show'][1] = 'ページID %sの詳細を表示';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][0] = 'ページを移動する';
$GLOBALS['TL_LANG']['tl_survey_page']['cut'][1] = 'ページID %sを移動';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][0] = '編集ページ';
$GLOBALS['TL_LANG']['tl_survey_page']['edit'][1] = 'ページID %s を編集';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][0] = '重複ページ';
$GLOBALS['TL_LANG']['tl_survey_page']['copy'][1] = 'ページ ID %s が重複しています';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][0] = 'ページの削除';
$GLOBALS['TL_LANG']['tl_survey_page']['delete'][1] = 'ページID %s を削除します';
$GLOBALS['TL_LANG']['tl_survey_page']['page'] = 'ページ';
$GLOBALS['TL_LANG']['tl_survey_page']['title_legend'] = 'タイトルと説明';
$GLOBALS['TL_LANG']['tl_survey_page']['intro_legend'] = 'はじめに';
$GLOBALS['TL_LANG']['tl_survey_page']['template_legend'] = 'テンプレートの設定';
$GLOBALS['TL_LANG']['tl_survey_page']['condition_legend'] = 'このページ以降のナビゲーション';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][0] = 'ナビゲーションルール';
$GLOBALS['TL_LANG']['tl_survey_page']['conditions'][1] = 'このページの後に続行すると、ルールが評価されます。';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['help'] = 'ルールは上から下までチェックされます。最初に一致したルールによって次のページが決まります。ルール内のすべての条件が一致する必要があります。答えが欠けていると条件が満たされることはありません。';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['fallback'] = '一致するルールがない場合は、次のページが続きます。最後の「Always」ルールでは、別のデフォルトの宛先を定義できます。';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchQuestions'] = '質問の選択肢をフィルタリングする';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['searchHelp'] = '質問のタイトルまたはIDで検索';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['rule'] = 'ルール';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['when'] = 'いつ';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['all'] = '以下の条件がすべて一致する (AND)';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['always'] = 'いつも';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['question'] = '質問';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operator'] = '比較';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['answer'] = '答え';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['target'] = 'その後、続けて';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseQuestion'] = '質問を選択してください';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['chooseAnswer'] = '答えを選択してください';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['choosePage'] = 'ページを選択してください';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addRule'] = '別のルールを追加する';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeRule'] = 'ルールの削除';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['addCondition'] = '別の AND 条件を追加します';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['removeCondition'] = '条件の削除';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['up'] = '上へ移動';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['down'] = '下へ移動';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['contains'] = '答えが含まれています';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['notContains'] = '答えは含まれていません';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['='] = '等しい';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['!='] = '等しくない';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>'] = 'より大きい';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<'] = '未満です';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['>='] = '以上です';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['operators']['<='] = '以下である';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['invalid'] = 'すべてのナビゲーション ルールを確認してください。質問、比較、回答、目的地が有効である必要があります。宛先は同じアンケートの別のページである必要があります。それぞれ 100 個の条件を持つ最大 100 個のルールが許可されます。';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacy'] = 'この既存のルールには、使用できない、または現在サポートされていない条件が含まれています。変わらず保存されています。ルールを移動または削除して、新しいルールに置き換えることができます。';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['legacyValue'] = '既存の値:';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['unreachable'] = 'このルールは常に一致します。それ以下のルールには到達しません。';
$GLOBALS['TL_LANG']['tl_survey_page']['conditionEditor']['javascript'] = 'ルールを編集するには JavaScript を有効にする必要があります。既存のルールは保存時に保持されます。';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][0] = '質問を編集する';
$GLOBALS['TL_LANG']['tl_survey_page']['children'][1] = '質問を編集する (ID %s)';
