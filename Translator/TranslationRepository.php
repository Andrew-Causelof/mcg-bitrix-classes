<?php

namespace Seogravity\Translator;

use Seogravity\HLB\HLBlockTable;

class TranslationRepository
{
    protected $hl;

    public function __construct()
    {
        $this->hl = new HLBlockTable(HL_BLOCK_ID__TRANSLATION);
    }

    public function getTranslation($key, $lang)
    {
        return $this->hl->getByKeyAndLang($key, $lang);
    }

    public function saveTranslation($key, $original, $lang, $text)
    {
        $this->hl->add([
            'UF_KEY' => $key,
            'UF_LANG' => $lang,
            'UF_ORIGINAL' => $original,
            'UF_TEXT' => $text
        ]);
    }
}
