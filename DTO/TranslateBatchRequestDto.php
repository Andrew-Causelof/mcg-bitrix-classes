<?php

namespace Seogravity\DTO;

class TranslateBatchRequestDto
{
    readonly public string $lang;
    readonly public string $project;
    readonly public array $services;
    /** @var array<string, string> */
    readonly public array $items;

    public function __construct(array $data)
    {

        foreach (['lang', 'items'] as $required) {
            if (empty($data[$required])) {
                throw new \InvalidArgumentException("Missing required field: $required");
            }
        }

        if (!in_array($data['lang'], ['en', 'cn', 'ar'], true)) {
            throw new \InvalidArgumentException("Unsupported lang: {$data['lang']}");
        }

        if (!is_array($data['items']) || count($data['items']) === 0) {
            throw new \InvalidArgumentException("Items must be non-empty array.");
        }

        foreach ($data['items'] as $key => $text) {
            if (!is_string($key) || !is_string($text)) {
                throw new \InvalidArgumentException("Invalid item pair: $key => $text");
            }
        }

        $this->lang = $data['lang'];
        $this->project = $data['project'] ?? 'moscow-city-guide';
        $this->services = $data['services'] ?? ['deepseek'];
        $this->items = $data['items'];
    }

    public function toArray(): array
    {
        $mappedItems = [];

        foreach ($this->items as $key => $text) {
            $mappedItems[] = [
                'key' => $key,
                'text' => $text,
            ];
        }

        return [
            'lang' => $this->lang,
            'project' => $this->project,
            'services' => $this->services,
            'items' => $mappedItems
        ];
    }
}
