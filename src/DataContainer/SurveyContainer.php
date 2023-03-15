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

namespace Hschottm\SurveyBundle\DataContainer;

use Contao\DataContainer;
use Hschottm\SurveyBundle\SurveyResultModel;
use Symfony\Component\HttpFoundation\RequestStack;

class SurveyContainer
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onLoadCallback(DataContainer $dc = null): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $dc || !$dc->id || !$request || 'edit' !== $request->query->get('act')) {
            return;
        }

        $resultData = ($id = $request->query->get('id', false)) ? (is_numeric($id) ? SurveyResultModel::findByPid((int) $id) : null) : null;

        if ($resultData) {
            $GLOBALS['TL_DCA']['tl_survey']['fields']['access']['eval']['disabled'] = 'disabled';
        }
    }
}
