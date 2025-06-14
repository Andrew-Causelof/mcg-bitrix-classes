<?php

namespace Seogravity\DTO;

class TranslateRequestDto
{
    readonly public string $text;
    readonly public string $lang;
    readonly public string $project;
    readonly public string $key;
    readonly public array $services;

    public function __construct(array $data)
    {
        foreach (['text', 'lang', 'key'] as $required) {
            if (empty($data[$required])) {
                throw new \InvalidArgumentException("Missing required field: $required");
            }
        }

        $this->text = $data['text'];
        $this->lang = $data['lang'];
        $this->project = $data['project'] ?? 'moscow-city-guide';
        $this->key = $data['key'];
        $this->services = $data['services'] ?? ['deepseek'];
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'lang' => $this->lang,
            'project' => $this->project,
            'services' => $this->services,
            'key' => $this->key,
        ];
    }
}
