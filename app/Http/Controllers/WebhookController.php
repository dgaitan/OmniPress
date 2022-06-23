<?php

namespace App\Http\Controllers;

use App\Services\Printforia\PrintforiaService;
use Illuminate\Http\Request;
use Exception;

class WebhookController extends Controller
{
    public function printforiaWebhook(Request $request) {
        try {
            PrintforiaService::validateWebhookSignature($request);
            PrintforiaService::webhookActions($request);

            return response()->json([
                'message' => 'Webhook Processed'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 403);
        }
    }
}
