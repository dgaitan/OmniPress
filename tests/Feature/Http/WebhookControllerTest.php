<?php

namespace Tests\Feature\Http;

class WebhookControllerTest extends BaseHttp
{
    public function test_printforia_webhook_should_return_ok(): void
    {
        $data = [
            'status' => 'feliz',
            'type' => 'order_status_change'
        ];

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        $response = $this->withHeaders([
            'X-Signature' => $signature,
        ])->post('/webhooks/printforia', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Webhook Processed'
            ]);
    }

    public function test_printforia_webhook_should_return_error_by_missing_signature(): void
    {
        $data = [
            'status' => 'feliz',
            'type' => 'order_status_change'
        ];

        $response = $this->post('/webhooks/printforia', $data);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Siganture is not present in request.'
            ]);
    }

    public function test_printforia_webhook_should_return_error_by_missing_status_in_request(): void
    {
        $data = [
            'type' => 'order_status_change'
        ];

        $payload = json_encode($data, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', "1656985209.{$payload}", env('PRINTFORIA_API_KEY', 'foo'));
        $signature = sprintf('t=1656985209;s=%s', $signature);

        $response = $this->withHeaders([
            'X-Signature' => $signature,
        ])->post('/webhooks/printforia', $data);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Status is not present in response'
            ]);
    }
}
