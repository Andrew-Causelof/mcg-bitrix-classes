<?php

namespace Seogravity\DTO;

class HlBlockRecordDto
{
    public string $key;
    public string $lang;
    public string $original;
    public string $text;

    public function __construct(array $data)
    {
        foreach (['key', 'lang', 'original', 'text'] as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Missing field: $field");
            }
        }

        $this->key = $data['key'];
        $this->lang = $data['lang'];
        $this->original = $data['original'];
        $this->text = $data['text'];
    }

    public function toArray(): array
    {
        return [
            'UF_KEY' => $this->key,
            'UF_LANG' => $this->lang,
            'UF_ORIGINAL' => $this->original,
            'UF_TEXT' => $this->text,
        ];
    }
}
