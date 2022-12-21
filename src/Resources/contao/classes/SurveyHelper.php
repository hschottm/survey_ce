<?php

declare(strict_types=1);

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	       https://github.com/hschottm/survey_ce
 *
 * forked by pdir
 * @author     Mathias Arzberger <develop@pdir.de>
 * @link       https://github.com/pdir/contao-survey
 */

namespace Hschottm\SurveyBundle;

use Contao\Backend;

class SurveyHelper extends Backend
{
    /**
     * Load the database object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('\Database');
    }

    public function replaceTags($source, $uidOrPin, $replacements = [], $blnIsHtml = false)
    {
        $source = preg_replace(['/\{\{/', '/\}\}/'], ['__BRCL__', '__BRCR__'], $source);
        $blnEvalSource = $this->replaceConditionTags($source);

        $tags = [];
        preg_match_all('/__BRCL__.*?__BRCR__/si', $source, $tags);
        // Replace tags of type {{q::<alias>}}
        // .. {{q::uploadfieldname}}
        // .. {{q::fieldname }}
        foreach ($tags[0] as $tag) {
            $elements = explode('::', preg_replace(['/^__BRCL__/i', '/__BRCR__$/i'], ['', ''], $tag));

            switch (strtolower($elements[0])) {
                // Formdata field
                case 'q':
                    $strKey = $elements[1];

                    if (is_numeric($uidOrPin)) {
                        $found = $this->Database->prepare('SELECT tl_survey_question.questiontype, tl_survey_result.* FROM tl_survey_result, tl_survey_question WHERE tl_survey_result.qid = tl_survey_question.id AND tl_survey_result.uid = ? AND tl_survey_question.alias = ?')
                            ->execute($uidOrPin, $strKey)
                            ->fetchAssoc()
                        ;
                    } else {
                        $found = $this->Database->prepare('SELECT tl_survey_question.questiontype, tl_survey_result.* FROM tl_survey_result, tl_survey_question WHERE tl_survey_result.qid = tl_survey_question.id AND tl_survey_result.pin = ? AND tl_survey_question.alias = ?')
                            ->execute($uidOrPin, $strKey)
                            ->fetchAssoc()
                        ;
                    }
                    $strClass = $GLOBALS['TL_SVY']['q_'.$found['questiontype']];
                    // Continue if the class is not defined
                    if (!$this->classFileExists($strClass)) {
                        continue;
                    }
                    $questionObject = new $strClass($found['qid']);
          $strVal = $questionObject->resultAsString($found['result']);

                    // Replace insert tags in subject
                    if (!empty($source)) {
                        $source = str_replace($tag, $strVal, $source);
                    }
                    break;
            }
        }
        $source = preg_replace(['/__BRCL__/', '/__BRCR__/'], ['{{', '}}'], $source);
        $source = $this->replaceInsertTags($source, false);

        if ($blnEvalSource) {
            $source = $this->evalConditionTags($source);
        }

        if (!$blnIsHtml) {
            $source = strip_tags($source);
            $source = html_entity_decode($source, ENT_QUOTES, $GLOBALS['TL_CONFIG']['characterSet']);
        }

        if (\is_array($replacements)) {
            foreach ($replacements as $key => $value) {
                $source = str_replace($key, $value, $source);
            }
        }

        return $source;
    }

    /**
     * Replace 'condition tags': {if ...}, {elseif ...}, {else} and {endif}.
     *
     * @param string $strBuffer String to parse
     *
     * @return bool
     */
    public function replaceConditionTags(& $strBuffer)
    {
        if (!\strlen($strBuffer)) {
            return false;
        }

        $blnEval = false;
        $strReturn = '';

        $arrTags = preg_split('/(\{[^}]+\})/sim', $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        if (!empty($arrTags)) {
            // Replace tags
            foreach ($arrTags as $strTag) {
                if (0 === strncmp($strTag, '{if', 3)) {
                    $strReturn .= preg_replace('/\{if (.*)\}/i', '<?php if ($1): ?>', $strTag);
                    $blnEval = true;
                } elseif (0 === strncmp($strTag, '{elseif', 7)) {
                    $strReturn .= preg_replace('/\{elseif (.*)\}/i', '<?php elseif ($1): ?>', $strTag);
                    $blnEval = true;
                } elseif (0 === strncmp($strTag, '{else', 5)) {
                    $strReturn .= '<?php else: ?>';
                    $blnEval = true;
                } elseif (0 === strncmp($strTag, '{endif', 6)) {
                    $strReturn .= '<?php endif; ?>';
                    $blnEval = true;
                } else {
                    $strReturn .= $strTag;
                }
            }

            $strBuffer = $strReturn;
        }

        return $blnEval;
    }

    /**
     * Eval code.
     *
     * @param string $strBuffer
     *
     * @throws \Exception
     *
     * @return mixed|string
     */
    public function evalConditionTags($strBuffer)
    {
        if (!\strlen($strBuffer)) {
            return;
        }

        $strReturn = str_replace('?><br />', '?>', $strBuffer);
        // Eval the code
        ob_start();
        $blnEval = eval('?>'.$strReturn);
        $strReturn = ob_get_contents();
        ob_end_clean();

        // Return the evaled code
        return $strReturn;
    }
}
