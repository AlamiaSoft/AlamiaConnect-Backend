# Zoho Invoice Billing Integration
**Status:** Implemented (feature branch)
**Date:** 2026-02-11
**Branch:** `feature/billing-integration` (Backend & Frontend)

## Overview
Implemented a Zero-Cost billing management system using Zoho Invoice (Free Tier) to handle KTD client subscriptions. The system monitors payment status via webhooks and enforces UI-level restrictions (banners/suspension overlays) on both the legacy Blade UI and the Next.js Frontend.

## Architecture
### 1. Backend (Laravel)
- **Settings**: Added `billing` section to `core_config.php`.
    - Keys: `billing.subscription.settings.status`, `webhook_secret`, `portal_url`.
- **Webhook**: `Alamia\Admin\Http\Controllers\Billing\WebhookController`
    - Endpoint: `POST /api/v1/billing/webhook`
    - Security: `X-Alamia-Billing-Token` header validation.
    - Logic: Updates `core_config` status based on `event_type`.
- **API**: Enhanced `UserResource.php` to include billing status for frontend consumption.

### 2. UI Enforcements
- **Legacy UI (Blade)**:
    - Modified `Webkul\Admin\src\Resources\views\components\layouts\index.blade.php`.
    - **Overdue**: Warning banner at the top.
    - **Suspended**: Full-screen red lockout overlay.
- **Next.js Frontend**:
    - Created `components/billing-banner.tsx`.
    - Integrated into `app/layout.tsx`.
    - Mirrored logic (Banners/Lockout) using backend-provided status.

## Configuration Guide
1. **Zoho Webhook**:
    - URL: `https://[domain]/api/v1/billing/webhook`
    - Events: `invoice_sent`, `invoice_overdue`, `payment_received`.
    - Header: `X-Alamia-Billing-Token: [Secret from Config]`
2. **CRM Settings**:
    - Configure `Zoho Invoice Portal URL` in **Settings > Configuration > Billing** so links point to the correct payment page.

## Verification
- Change status to `overdue` in Admin Settings -> Verify orange banner.
- Change status to `suspended` in Admin Settings -> Verify full-screen lockout.
- Send a mock POST request to the webhook -> Verify status updates in `core_config`.
