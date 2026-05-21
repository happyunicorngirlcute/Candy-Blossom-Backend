<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ResendMailer
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey
    ) {}

    public function sendEmail(string $to, string $subject, string $html): void
    {
        $response = $this->client->request('POST', 'https://api.resend.com/emails', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'from' => 'onboarding@resend.dev',
                'to' => $to,
                'subject' => $subject,
                'html' => $html,
            ],
        ]);

        if ($response->getStatusCode() >= 400) {
            throw new \RuntimeException($response->getContent(false));
        }
    }
}
