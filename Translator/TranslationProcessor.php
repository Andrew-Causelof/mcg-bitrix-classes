<?php

namespace Seogravity\Translator;

use Seogravity\Iblock\IblockService;
use Seogravity\Iblock\IblockTranslationHelper;
use Seogravity\DTO\TranslateRequestDto;
use Seogravity\Translator\TranslationService\TranslatorClient;

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

        $translated = $this->translator->translate(new TranslateRequestDto([
            'text' => array_values($pairs),
            'key' => array_keys($pairs),
            'lang' => $lang,
            'project' => 'moscow-city-guide',
            'services' => ['deepseek'],
        ]));

        $result = array_combine(array_keys($pairs), $translated->getTexts());
        $this->repository->saveMany($result, $lang);

        return $result;
    }
}
