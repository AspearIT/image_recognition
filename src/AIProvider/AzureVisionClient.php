<?php

namespace Imreg\AIProvider;

use Aws\S3\S3Client;
use Imreg\Value\Image;

class AzureVisionClient implements AIProvider
{
//curl.exe -H "Ocp-Apim-Subscription-Key: <subscriptionKey>" -H "Content-Type: application/json" "<endpoint>/computervision/imageanalysis:analyze?features=caption,read&model-version=latest&language=en&api-version=2024-02-01" -d "{'url':'https://learn.microsoft.com/azure/ai-services/computer-vision/media/quickstarts/presentation.png'}"
    public function __construct(
        private readonly S3Client $s3Client,
        private readonly string $subscriptionKey,
        private readonly string $endpoint
    ) {
    }

    public function startConversation(Image $file, string $question): string
    {
        $url = 'https://learn.microsoft.com/azure/ai-services/computer-vision/media/quickstarts/presentation.png';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->endpoint . "/computervision/imageanalysis:analyze?features=caption,read&model-version=latest&language=en&api-version=2024-02-01",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'url' => $url,
            ]),
            CURLOPT_HTTPHEADER => array(
                "Ocp-Apim-Subscription-Key: " . $this->subscriptionKey,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        dd(json_decode($response, true));
    }
}