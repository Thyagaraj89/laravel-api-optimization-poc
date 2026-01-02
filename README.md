# Laravel API Optimization POC

A production-style **Laravel REST API Proof of Concept** demonstrating **secure authentication, optimized database queries, reporting endpoints, and clean API architecture**.

This project simulates a real-world backend used by internal dashboards, admin panels, or frontend applications that require **fast, secure, and maintainable APIs**.

---

## ✨ Key Features

### 🔐 API Authentication
- Token-based authentication using **Laravel Sanctum**
- Secure login endpoint
- Protected API routes with `auth:sanctum`

### 📦 Core API Modules
- Products API (paginated)
- Orders API with nested order items
- Sales reporting API with date range filters

### ⚡ Performance & Optimization
- Eager loading to prevent N+1 queries
- Optimized aggregation queries for reports
- Pagination for large datasets
- Clean separation of controllers, models, and routes

### 🧪 Demo Data Ready
- SQLite database for zero-setup local testing
- Seeded users, products, orders, and order items
- Realistic sales data for reporting endpoints

---

## 🧱 Tech Stack

- **Laravel 12**
- **PHP 8.4**
- **Laravel Sanctum**
- **SQLite**
- **RESTful API design**
- **Postman for API testing**

---

## 📂 Project Structure (Relevant Parts)

app/
├── Http/Controllers/Api
│ ├── AuthController.php
│ ├── ProductController.php
│ ├── OrderController.php
│ └── ReportController.php
├── Models
│ ├── Product.php
│ ├── Order.php
│ ├── OrderItem.php
│ └── User.php

routes/
├── api.php
├── web.php
└── console.php

yaml


---

## ⚙️ Local Setup

### 1. Clone Repository
```bash
git clone https://github.com/Thyagaraj89/laravel-api-optimization-poc.git
cd laravel-api-optimization-poc
2. Install Dependencies
bash

composer install
3. Environment Setup
bash

cp .env.example .env
php artisan key:generate
Update .env:

env

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
4. Create SQLite Database
bash

touch database/database.sqlite
5. Run Migrations & Seeders
bash

php artisan migrate:fresh --seed
6. Start Development Server
bash

php artisan serve
🔐 Authentication Flow
Login
POST

bash

/api/auth/login
Request Body (JSON):

json

{
  "email": "demo@example.com",
  "password": "Password123!"
}
Response:

json

{
  "token": "YOUR_API_TOKEN"
}
Use the token in all protected requests:

makefile

Authorization: Bearer YOUR_API_TOKEN
📡 API Endpoints
Products
bash

GET /api/products
Orders
bash

GET /api/orders
Sales Report
vbnet

GET /api/reports/sales?from=2025-01-01&to=2026-12-31
Sample Response:

json

{
  "range": {
    "from": "2025-01-01",
    "to": "2026-12-31"
  },
  "orders_count": 15,
  "total_cents": 295300,
  "top_products": [
    {
      "sku": "SKU-1003",
      "qty_sold": 16,
      "revenue_cents": 79840
    }
  ]
}
🧠 Design Considerations
API-only architecture (no Blade views)

Centralized authentication handling

Clean error handling for API consumers

Scalable structure for caching, queues, or async jobs

Suitable for frontend frameworks or mobile apps

🎯 Intended Use Cases
Internal admin dashboards

Headless backend APIs

Reporting and analytics services

Laravel performance optimization reference

Freelance portfolio / technical assessment project

👤 Author
Thyagaraj Thanaraj
Senior Backend / Full-Stack Engineer

Laravel & PHP

.NET & C#

API Design & Optimization

Cloud & Automation

GitHub:
https://github.com/Thyagaraj89

🚀 Possible Enhancements
Redis caching

Rate limiting

API Resource transformers

OpenAPI / Swagger documentation

Docker support

Automated tests

This project is intentionally designed as a clean, realistic backend reference, reflecting production patterns rather than tutorial code.

yaml


