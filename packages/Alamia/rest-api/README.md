# Alamia Rest API

Alamia Rest API provides JSON:API compliant endpoints for the AlamiaConnect ecosystem. It extends the core CRM capabilities with custom business logic and mobile-ready services.

## 🛠️ Features
-   JSON:API 1.0 Compliant
-   Sanctum Token Authentication
-   Modular Resource Controllers
-   Relationship Management

## 📂 Structure
-   `src/Http/Controllers`: V1 API Controllers
-   `src/Http/Resources`: JSON:API Resource definitions
-   `src/Routes`: API Route definitions for Leads, Contacts, Visits, etc.

## 🚀 Routes
API routes are prefixed with `/api/v1/`.
-   `POST /login`: Authentication
-   `GET /leads`: Leads management
-   `GET /contacts/persons`: Customer management
-   `GET /sales-visits`: Field activities

---
© 2026 AlamiaConnect. All rights reserved.
