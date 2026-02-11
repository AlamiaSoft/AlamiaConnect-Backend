<div class="mt-4 flex flex-col gap-2">
    <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">
        Test Webhook Connection
    </label>

    <div class="flex items-center gap-2">
        <button 
            type="button"
            class="secondary-button"
            onclick="sendTestWebhook('invoice_overdue')"
        >
            Trigger Overdue
        </button>

        <button 
            type="button"
            class="secondary-button !bg-red-600 !border-red-600 text-white"
            onclick="sendTestWebhook('client_suspended')"
        >
            Trigger Suspension
        </button>
        
        <span id="webhook-response-msg" class="text-sm"></span>
    </div>
    
    <p class="text-xs text-gray-500">
        This will send a mock event to your local webhook endpoint to verify status updates.
    </p>

    <script>
        function sendTestWebhook(eventType) {
            const btns = document.querySelectorAll('.secondary-button');
            const msg = document.getElementById('webhook-response-msg');
            
            // Try to find the secret in the form fields first
            let secret = document.getElementsByName('billing[subscription][settings][webhook_secret]')[0]?.value;

            if (!secret) {
                // Fallback to searching all password/text fields if names are different
                const inputs = document.querySelectorAll('input');
                for (let input of inputs) {
                    if (input.name.includes('webhook_secret')) {
                        secret = input.value;
                        break;
                    }
                }
            }

            if (!secret) {
                alert('Could not find secret token. Please save the configuration first.');
                return;
            }

            btns.forEach(b => b.disabled = true);
            msg.innerHTML = 'Sending...';
            msg.className = 'text-sm text-gray-500';

            fetch('/api/v1/billing/webhook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Alamia-Billing-Token': secret,
                },
                body: JSON.stringify({
                    event_type: eventType,
                    is_test: true
                })
            })
            .then(response => {
                if (response.ok) {
                    msg.innerHTML = '✅ Success! Status updated.';
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
                btns.forEach(b => b.disabled = false);
            });
        }
    </script>
</div>
