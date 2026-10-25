<?php

/*
 * @copyright  Helmut Schottmüller 2005-2018 <http://github.com/hschottm>
 * @author     Helmut Schottmüller (hschottm)
 * @package    contao-survey
 * @license    LGPL-3.0+, CC-BY-NC-3.0
 * @see	      https://github.com/hschottm/survey_ce
 */

namespace Hschottm\SurveyBundle\Picker;

use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\CoreBundle\Picker\AbstractPickerProvider;
use Contao\CoreBundle\Picker\DcaPickerProviderInterface;
use Contao\CoreBundle\Picker\PickerConfig;
use Contao\NewsArchiveModel;
use Contao\NewsModel;

/**
 * Provides the news picker.
 *
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class SurveyPickerProvider extends AbstractPickerProvider implements DcaPickerProviderInterface, FrameworkAwareInterface
{
    use FrameworkAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'surveyPicker';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsContext(string $context): bool
    {
        return 'link' == $context; // && $this->getUser()->hasAccess('news', 'modules');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsValue(PickerConfig $config): bool
    {
        return false; // != strpos($config->getValue(), '{{news_url::');
    }

    /**
     * {@inheritdoc}
     */
    public function getDcaTable(?PickerConfig $config = null): string
    {
        return 'tl_survey';
    }

    /**
     * {@inheritdoc}
     */
    public function getDcaAttributes(PickerConfig $config): array
    {
        $attributes = ['fieldType' => 'radio'];

        if ($source = $config->getExtra('source')) {
            $attributes['preserveRecord'] = $source;
        }

        if ($this->supportsValue($config)) {
            $attributes['value'] = str_replace(['{{news_url::', '}}'], '', $config->getValue());
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function convertDcaValue(PickerConfig $config, mixed $value): int|string
    {
        return '{{news_url::'.$value.'}}';
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteParameters(?PickerConfig $config = null): array
    {
        $params = ['do' => 'survey'];

        if (null == $config || !$config->getValue() || false == strpos($config->getValue(), '{{news_url::')) {
            return $params;
        }

        $value = str_replace(['{{news_url::', '}}'], '', $config->getValue());
        return $params;
    }
}
