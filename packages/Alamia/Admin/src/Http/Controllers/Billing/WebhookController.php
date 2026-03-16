<?php

namespace Alamia\Admin\Http\Controllers\Billing;

use Alamia\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Webkul\Core\Models\CoreConfig;

class WebhookController extends Controller
{
    /**
     * Handle Zoho Invoice Webhook.
     *
     * Expected Events from Zoho:
     * - invoice_sent
     * - invoice_overdue
     * - payment_received
     * - payment_refunded (optional)
     */
    public function handle(Request $request)
    {
        $secret = core()->getConfigData('billing.subscription.settings.webhook_secret');
        $token = $request->header('X-Alamia-Billing-Token');

        if (empty($secret) || $token !== $secret) {
            Log::warning('Unauthorized or unconfigured billing webhook attempt.', [
                'received_token' => $token,
                'client_ip'      => $request->ip(),
            ]);

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();

        // Zoho typically sends JSON or Form-data depending on configuration.
        // If it's a JSON payload, 'event_type' is a common field.
        $event = $payload['event_type'] ?? $request->input('event_type');

        Log::info('Zoho Billing Webhook received.', ['event' => $event, 'payload_keys' => array_keys($payload)]);

        if (! $event) {
            return response()->json(['message' => 'No event type found in payload'], 422);
        }

        switch ($event) {
            case 'invoice_sent':
            case 'payment_received':
            case 'invoice_partially_paid':
                $this->updateSubscriptionStatus('active');
                break;

            case 'invoice_overdue':
                $this->updateSubscriptionStatus('overdue');
                break;

            case 'client_suspended':
                $this->updateSubscriptionStatus('suspended');
                break;

            case 'customer_active':
            case 'invoice_sent':
            case 'payment_received':
                $this->updateSubscriptionStatus('active');
                break;

            case 'invoice_voided':
            case 'payment_refunded':
                // Optional: handle refunds or cancellations
                break;
        }

        return response()->json(['status' => 'success', 'processed_event' => $event]);
    }

    /**
     * Update the subscription status in core_config.
     */
    protected function updateSubscriptionStatus(string $status)
    {
        $code = 'billing.subscription.settings.status';

        CoreConfig::updateOrCreate(
            ['code' => $code],
            ['value' => $status]
        );

        Log::info("CRM Subscription status updated via webhook to: {$status}");
    }
}
