<?php

namespace Seogravity\Iblock;

use CIBlockElement;
use Bitrix\Main\Loader;


class IblockService
{
    public function __construct()
    {
        Loader::includeModule('iblock');
    }

    /**
     * Получает все поля и свойства элемента инфоблока
     */
    public function getElementData(int $iblockId, int $elementId): array
    {
        $result = [];

        $res = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                'ID' => $elementId,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            ['*']
        );

        if ($element = $res->GetNextElement()) {
            $fields = $element->GetFields();
            $props = $element->GetProperties();
            $result = array_merge($fields, ['PROPERTIES' => $props]);
        }

        return $result;
    }

    /**
     * Получает ID всех активных элементов инфоблока
     */
    public function getActiveElementIds(int $iblockId): array
    {
        $ids = [];

        $res = \CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            ['ID']
        );

        while ($row = $res->Fetch()) {
            $ids[] = (int)$row['ID'];
        }

        return $ids;
    }

    /**
     * Получает данные активной секции инфоблока по ID
     */
    public function getSectionData(int $iblockId, int $sectionId): array
    {
        $res = \CIBlockSection::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                'ID' => $sectionId,
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y'
            ],
            false,
            ['*']
        );

        if ($section = $res->Fetch()) {
            return $section;
        }

        return [];
    }

    /**
     * Получает ID всех активных секций инфоблока
     */
    public function getActiveSectionIds(int $iblockId): array
    {
        $ids = [];

        $res = \CIBlockSection::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y'
            ],
            false,
            ['ID']
        );

        while ($row = $res->Fetch()) {
            $ids[] = (int)$row['ID'];
        }

        return $ids;
    }
}
