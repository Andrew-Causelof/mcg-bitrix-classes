<?php

namespace Seogravity\Translator\Components;

use Seogravity\Translator\TranslationRepository;
use Seogravity\Translator\TranslationService\TranslatorClient;
use Seogravity\DTO\TranslateRequestDto;
use Seogravity\DTO\HlBlockRecordDto;

abstract class AbstractContentTranslator
{
    protected $translator;
    protected $repository;

    public function __construct()
    {
        $this->translator = new TranslatorClient();
        $this->repository = new TranslationRepository();
    }

    abstract public function prepare($componentResult, $lang);

    protected function translate($text, $lang, $key)
    {
        $existing = $this->repository->getTranslation($key, $lang);
        if ($existing) return $existing['UF_TEXT'];

        $translated = $this->translator->translate(new TranslateRequestDto([
            'text' => $text,
            'lang' => $lang,
            'key'  => $key,
        ]));

        $this->repository->saveTranslation(new HlBlockRecordDto([
            'key'       => $key,
            'lang'      => $lang,
            'original'  => $text,
            'text'      => $translated['text']
        ]));

        return $translated['text'];
    }
}
