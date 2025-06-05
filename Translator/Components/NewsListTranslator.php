<?php

namespace Seogravity\Translator\Components;

use Seogravity\Translator\Components\AbstractContentTranslator;

class NewsListTranslator extends AbstractContentTranslator
{
    public function prepare(&$arResult, $lang)
    {
        foreach ($arResult['ITEMS'] as &$item) {
            $item['NAME'] = $this->translate('news_' . $item['ID'] . '_name', $item['NAME'], $lang);
            $item['PREVIEW_TEXT'] = $this->translate('news_' . $item['ID'] . '_preview', $item['PREVIEW_TEXT'], $lang);
        }
    }
}
