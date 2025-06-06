<?php

namespace Seogravity\Iblock;

class IblockTranslationHelper
{
    /**
     * Извлекает переводимые поля из элемента инфоблока
     */
    public function extractTranslatableFields(array $element, int $iblockId): array
    {
        $result = [];
        $elementId = $element['ID'] ?? 0;

        $fieldsToTranslate = ['NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT'];
        foreach ($fieldsToTranslate as $field) {
            if (!empty($element[$field]) && is_string($element[$field])) {
                $result["iblock_{$iblockId}_element_{$elementId}_{$field}"] = $element[$field];
            }
        }

        if (!empty($element['PROPERTIES']) && is_array($element['PROPERTIES'])) {
            foreach ($element['PROPERTIES'] as $code => $property) {
                if (!empty($property['VALUE']) && is_string($property['VALUE'])) {
                    $result["iblock_{$iblockId}_element_{$elementId}_PROPERTY_{$code}"] = $property['VALUE'];
                }
            }
        }

        return $result;
    }

    /**
     * Извлекает переводимые поля из секции инфоблока
     */
    public function extractTranslatableSectionFields(array $section, int $iblockId): array
    {
        $result = [];
        $sectionId = $section['ID'] ?? 0;

        $fieldsToTranslate = ['NAME', 'DESCRIPTION'];
        foreach ($fieldsToTranslate as $field) {
            if (!empty($section[$field]) && is_string($section[$field])) {
                $result["iblock_{$iblockId}_section_{$sectionId}_{$field}"] = $section[$field];
            }
        }

        foreach ($section as $field => $value) {
            if (strpos($field, 'UF_') === 0 && is_string($value) && !empty($value)) {
                $result["iblock_{$iblockId}_section_{$sectionId}_{$field}"] = $value;
            }
        }

        return $result;
    }
}
