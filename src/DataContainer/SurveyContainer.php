<?php

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

        $found = ($id = $request->query->get('id', false)) ? (is_numeric($id) ? (SurveyResultModel::findByPid((int)$id)) : null) : null;

        if ($found) {
            $GLOBALS['TL_DCA']['tl_survey']['fields']['access']['eval']['disabled'] = 'disabled';
        }

    }
}