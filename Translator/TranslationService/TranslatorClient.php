<?php

namespace Seogravity\Translator\TranslationService;

use Seogravity\DTO\TranslateRequestDto;


class TranslatorClient
{
    protected $apiUrl = TRANSLATOR_API_URL;
    protected $project = "moscow-city-guide";

    /**
     * @param string $text
     * @param string $lang
     * @param string $key
     * @param array $services
     * @return array
     */
    public function translate(TranslateRequestDto $dto)
    {
        $payload = $dto->toArray();

        $response = file_get_contents($this->apiUrl . 'translate', false, stream_context_create([
            'http' => [
                'method' => "POST",
                'header' => "Content-Type: application/json",
                'content' => json_encode($payload)
            ]
        ]));

        return json_decode($response, true);
    }
}
