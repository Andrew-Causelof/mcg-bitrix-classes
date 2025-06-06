<?php

namespace Seogravity\DTO;

class ElementForTranslationDto
{
    /** @var array<string, string> */
    public array $keyValuePairs = [];

    public function __construct(array $keyValuePairs)
    {
        foreach ($keyValuePairs as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                throw new \InvalidArgumentException("Translation pairs must be strings: $key => $value");
            }
            $this->keyValuePairs[$key] = $value;
        }
    }

    public function getKeys(): array
    {
        return array_keys($this->keyValuePairs);
    }

    public function getValues(): array
    {
        return array_values($this->keyValuePairs);
    }

    public function toArray(): array
    {
        return $this->keyValuePairs;
    }
}
