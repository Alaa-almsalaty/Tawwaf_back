<?php
namespace App\Services\Otp;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class WhatsappProvider
{
    public function sendTemplateOtp(string $toE164, string $code): string
    {
        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $template = config('services.whatsapp.otp_template');
        $lang = config('services.whatsapp.language');

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $toE164,        // e.g., +2189XXXXXXXX
            'type' => 'template',
            'template' => [
                'name' => $template,
                'language' => ['code' => $lang],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $code
                            ],
                        ],
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => '0',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $code
                            ],
                        ],
                    ]
                ],
            ],
        ];

        $url = "https://graph.facebook.com/v21.0/{$phoneNumberId}/messages";

        //dd($payload);
        $response = Http::withToken($token)
            ->acceptJson()
            ->post($url, $payload);

        if (!$response->successful() || isset($response['error'])) {
            throw new RequestException($response);
        }

        // Cloud API returns a message id we can store for webhook correlation
        return $response['messages'][0]['id'] ?? '';
    }
}
