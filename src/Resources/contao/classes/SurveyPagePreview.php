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
use Contao\FrontendTemplate;
use Contao\StringUtil;

class SurveyPagePreview extends Backend
{
    /**
     * Import String library.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Compile format definitions and return them as string.
     *
     * @param array
     * @param bool
     * @param mixed $row
     * @param mixed $blnWriteToFile
     *
     * @return string
     */
    public function compilePreview($row, $blnWriteToFile = false)
    {
        $surveyPageCollection = SurveyPageModel::findBy(['pid=?', 'sorting<?'], [$row['pid'], $row['sorting']]);
        $position = null !== $surveyPageCollection ? $surveyPageCollection->count() + 1 : 1;

        $template = new FrontendTemplate('be_survey_page_preview');
        $template->page = $GLOBALS['TL_LANG']['tl_survey_page']['page'];
        $template->position = $position;
        $template->title = StringUtil::specialchars($row['title']);
        $template->description = StringUtil::specialchars($row['description']);

        return $template->parse();
    }
}
