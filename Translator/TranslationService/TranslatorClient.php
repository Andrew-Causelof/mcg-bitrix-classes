<?php

namespace Seogravity\Translator\TranslationService;

use Seogravity\DTO\TranslateRequestDto;
use Seogravity\DTO\TranslateBatchRequestDto;

class TranslatorClient
{
    protected $apiUrl = TRANSLATOR_API_URL;
    protected $project = "moscow-city-guide";


    /**
     * Sends a translation request to the translation service.
     * 
     * @param TranslateRequestDto $dto Translation request data transfer object
     * 
     * @return array Response from the translation service
     */

    public function translate(TranslateRequestDto $dto): array
    {
        return $this->curlRequest('translate', $dto->toArray());
    }


    public function translateBatch(TranslateBatchRequestDto $dto): array
    {
        return $this->curlRequest('translate/batch', $dto->toArray());
    }

    private function curlRequest(string $endpoint, array $payload): array
    {
        $json = json_encode($payload);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \RuntimeException('CURL error: ' . curl_error($curl));
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid response from translation API: ' . $response);
        }

        return $decoded;
    }
}
