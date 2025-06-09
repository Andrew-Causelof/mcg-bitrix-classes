<?php

namespace Seogravity\Translator;

use Seogravity\HLB\HLBlockTable;
use Seogravity\DTO\HlBlockRecordDto;

class TranslationRepository
{
    protected $hl;

    public function __construct()
    {
        $this->hl = new HLBlockTable(HL_BLOCK_ID__TRANSLATION);
    }

    /**
     * Returns a translation record from the database by key and language.
     *
     * @param string $key
     * @param string $lang
     *
     * @return array|null
     */
    public function getTranslation($key, $lang)
    {
        return $this->hl->getByKeyAndLang($key, $lang);
    }

    /**
     * @param HlBlockRecordDto $dto
     *
     * @return bool|\Bitrix\Main\ORM\Data\AddResult
     */
    public function saveTranslation(HlBlockRecordDto $dto)
    {
        return $this->hl->add($dto->toArray());
    }

    public function saveMany(array $records): array
    {
        $results = [
            'created' => [],
            'updated' => [],
            'failed' => [],
        ];

        foreach ($records as $record) {

            try {
                $existing = $this->hl->getByKeyAndLang($record->key, $record->lang);
                if ($existing) {
                    $this->hl->update($existing['ID'], $record->toArray());
                    $results['updated'][] = $record->key;
                } else {
                    $this->hl->add($record->toArray());
                    $results['created'][] = $record->key;
                }
            } catch (\Throwable $e) {
                $results['failed'][$record->key] = $e->getMessage();
            }
        }

        return $results;
    }
}
