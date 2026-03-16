<div class="mt-4 flex flex-col gap-2">
    <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">
        Test Webhook Connection
    </label>

    <div class="flex items-center gap-2">
        <button 
            type="button"
            id="test-webhook-btn"
            class="secondary-button"
            onclick="sendTestWebhook()"
        >
            Trigger Test Webhook
        </button>
        
        <span id="webhook-response-msg" class="text-sm"></span>
    </div>
    
    <p class="text-xs text-gray-500">
        This will send a mock <code>invoice_overdue</code> event to your local webhook endpoint to verify the бан flow and status updates.
    </p>

    <script>
        function sendTestWebhook() {
            const btn = document.getElementById('test-webhook-btn');
            const msg = document.getElementById('webhook-response-msg');
            const secret = document.getElementsByName('billing[subscription][settings][webhook_secret]')[0]?.value;

            if (!secret) {
                alert('Please save the configuration first to generate a secret token.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = 'Sending...';
            msg.innerHTML = '';
            msg.className = 'text-sm text-gray-500';

            fetch('/api/v1/billing/webhook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Alamia-Billing-Token': secret,
                },
                body: JSON.stringify({
                    event_type: 'invoice_overdue',
                    is_test: true
                })
            })
            .then(response => {
                if (response.ok) {
                    msg.innerHTML = '✅ Success! Status updated to Overdue.';
                    msg.className = 'text-sm text-green-600 font-bold';
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    msg.innerHTML = '❌ Failed. Check console/logs.';
                    msg.className = 'text-sm text-red-600 font-bold';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                msg.innerHTML = '❌ Error sending request.';
                msg.className = 'text-sm text-red-600 font-bold';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Trigger Test Webhook';
            });
        }
    </script>
</div>
