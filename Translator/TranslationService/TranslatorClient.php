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
        $payload = json_encode($dto->toArray());

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . 'translate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \RuntimeException('CURL error: ' . curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response, true);
    }
}
