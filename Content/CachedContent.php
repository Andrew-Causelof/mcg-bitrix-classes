<?php

namespace Seogravity\Content;

use Bitrix\Main\Data\Cache;

class CachedContent
{
    public static function get($iblock, $elementId = null, $cacheTime = 3600, callable $callback)
    {
        $cacheId = 'page_content_' . $iblock . '_' . $elementId;
        $cacheDir = '/seogravity/page_content/'; 

        $cache = Cache::createInstance();

        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            $content = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $content = $callback();
            if (!empty($content)) {
                $cache->endDataCache($content);
            } else {
                $cache->abortDataCache();
            }
        }

        return $content;
    }
}
