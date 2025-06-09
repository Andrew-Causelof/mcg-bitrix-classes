<?php

namespace Seogravity\Translator;

use Seogravity\Iblock\IblockService;
use Seogravity\Iblock\IblockTranslationHelper;
use Seogravity\DTO\TranslateRequestDto;
use Seogravity\Translator\TranslationService\TranslatorClient;
use Seogravity\DTO\TranslateBatchRequestDto;
use Seogravity\DTO\HlBlockRecordDto;

class TranslationProcessor
{
    private $iblockService;
    private $helper;
    private $translator;
    private $repository;

    public function __construct()
    {
        $this->iblockService = new IblockService();
        $this->helper = new IblockTranslationHelper();
        $this->translator = new TranslatorClient();
        $this->repository = new TranslationRepository();
    }

    public function translateElement($iblockId, $elementId, $lang)
    {
        $element = $this->iblockService->getElementData($iblockId, $elementId);
        $pairs = $this->helper->extractTranslatableFields($element, $iblockId);

        if (empty($pairs)) {
            return [];
        }

        // Подготовим DTO для батч-запроса
        $dto = new TranslateBatchRequestDto([
            'lang' => $lang,
            'items' => $pairs,
        ]);

        // Отправим запрос на API
        $translated = $this->translator->translateBatch($dto); // вернёт key => translatedText 

        $translations = [];

        foreach ($translated as $key => $text) {
            $translations[] = new HlBlockRecordDto([
                'key' => $key,
                'lang' => $lang,
                'original' => $pairs[$key] ?? '',
                'text' => $text,
            ]);
        }

        $this->repository->saveMany($translations);

        return $translated;
    }

    public function translateSection(int $iblockId, int $sectionId, string $lang)
    {
        $section = $this->iblockService->getSectionData($iblockId, $sectionId);
        $pairs = $this->helper->extractTranslatableSectionFields($section, $iblockId);

        if (empty($pairs)) {
            return;
        }

        $dto = new TranslateBatchRequestDto([
            'lang' => $lang,
            'items' => $pairs,
        ]);


        $translated = $this->translator->translateBatch($dto);

        // Добавим оригинальные строки
        foreach ($translated as $key => &$value) {
            $value = [
                'text' => $value,
                'original' => $pairs[$key] ?? '',
            ];
        }

        // Преобразуем в HlBlockRecordDto и сохраняем
        $records = [];
        foreach ($translated as $key => $data) {
            $records[] = new HlBlockRecordDto([
                'key' => $key,
                'lang' => $lang,
                'text' => $data['text'],
                'original' => $data['original'],
            ]);
        }

        $this->repository->saveMany($records);

        return $translated;
    }
}
