# Payment Checkout API

A simple Payment Checkout service built with Laravel and MySQL as part of the Backend Developer Technical Test.

The application allows merchants to create payment transactions, generate a checkout URL, display a checkout page, process a simulated payment, and retrieve transaction details through a REST API.

## Features

- Create payment checkout
- Request validation
- Unique merchant order ID
- Unique transaction ID
- Checkout URL generation
- Payment checkout page
- Simulated payment processing
- PENDING → PAID transaction flow
- Payment timestamp (`paid_at`)
- Transaction detail API
- API Key authentication
- JSON error handling
- Feature tests
- Postman Collection

## Tech Stack

- PHP 8.2.12
- Laravel 12
- MySQL
- Composer
- PHPUnit
- Postman

## Requirements

Make sure the following are installed:

- PHP 8.2 or higher
- Composer
- MySQL or MariaDB
- Git

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/Frealy0901/payment-checkout.git
cd payment-checkout
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env`.

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

### 4. Configure Database

Create a MySQL database named:

```text
payment_checkout
```

Then configure the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=payment_checkout
DB_USERNAME=root
DB_PASSWORD=

API_KEY=your-api-key
```

Adjust `DB_USERNAME` and `DB_PASSWORD` according to your local MySQL configuration.

### 5. Run Database Migration

```bash
php artisan migrate
```

### 6. Start the Application

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

---

# API Documentation

## Authentication

Merchant API endpoints require an API Key.

Add the following HTTP header:

```text
X-API-Key: your-api-key
```

The following endpoints require authentication:

```text
POST /api/checkout
GET  /api/transactions/{transaction_id}
```

Requests without a valid API Key return:

```text
401 Unauthorized
```

Example response:

```json
{
    "message": "Invalid or missing API key."
}
```

---

## 1. Create Checkout

Creates a new payment transaction.

### Endpoint

```http
POST /api/checkout
```

### Headers

```text
Content-Type: application/json
X-API-Key: your-api-key
```

### Request Body

```json
{
    "merchant_order_id": "ORDER-10001",
    "amount": 150000,
    "currency": "IDR",
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "callback_url": "https://merchant.test/callback",
    "return_url": "https://merchant.test/return"
}
```

### Validation

- `merchant_order_id` is required and must be unique.
- `amount` must be greater than 0.
- `customer_name` is required.
- `customer_email` must be a valid email.
- `callback_url` must be a valid URL.
- `return_url` must be a valid URL.

### Success Response

Status:

```text
201 Created
```

Example:

```json
{
    "transaction_id": "TRX123456",
    "checkout_url": "http://127.0.0.1:8000/payment/TRX123456",
    "status": "PENDING"
}
```

---

## 2. Checkout Page

Displays the payment checkout page for a transaction.

### Endpoint

```http
GET /payment/{transaction_id}
```

Example:

```text
http://127.0.0.1:8000/payment/TRX123456
```

The checkout page displays:

- Merchant Order ID
- Customer Name
- Customer Email
- Amount
- Payment Status
- Pay Now button

When the transaction is already `PAID`, the Pay Now button is replaced with:

```text
This transaction has already been paid.
```

---

## 3. Complete Payment

Simulates a successful payment.

### Endpoint

```http
POST /payment/{transaction_id}/pay
```

Example:

```text
http://127.0.0.1:8000/payment/TRX123456/pay
```

When the transaction is `PENDING`, clicking **Pay Now** will:

1. Change the status from `PENDING` to `PAID`.
2. Record the payment time in `paid_at`.
3. Redirect the user to the configured `return_url`.

A transaction that has already been paid cannot be paid again.

---

## 4. Get Transaction

Retrieves transaction information.

### Endpoint

```http
GET /api/transactions/{transaction_id}
```

### Headers

```text
X-API-Key: your-api-key
```

### Example

```text
GET /api/transactions/TRX123456
```

### Success Response

```json
{
    "transaction_id": "TRX123456",
    "merchant_order_id": "ORDER-10001",
    "amount": "150000.00",
    "customer_name": "John Doe",
    "payment_status": "PAID",
    "created_at": "2026-08-11T10:00:00.000000Z",
    "paid_at": "2026-08-11T10:05:00.000000Z"
}
```

### Transaction Not Found

Status:

```text
404 Not Found
```

Response:

```json
{
    "message": "Transaction not found."
}
```

---

# Database Design

The main table is `transactions`.

| Column | Description |
|---|---|
| id | Primary key |
| transaction_id | Unique transaction identifier |
| merchant_order_id | Unique merchant order identifier |
| customer_name | Customer name |
| customer_email | Customer email |
| amount | Transaction amount |
| currency | Transaction currency |
| callback_url | Merchant callback URL |
| return_url | Merchant return URL |
| status | Payment status |
| paid_at | Payment completion time |
| created_at | Transaction creation time |
| updated_at | Last update time |

Database migration is available in:

```text
database/migrations/
```

Run:

```bash
php artisan migrate
```

to create the required database tables.

---

# Testing

The project includes Feature Tests for the main application functionality.

Run all tests:

```bash
php artisan test
```

Current test coverage includes:

- Successful checkout creation
- Missing API Key
- Invalid API Key
- Duplicate merchant order ID
- Invalid amount
- Transaction not found

Current test result:

```text
Tests: 7 passed
Assertions: 17
```

---

# Postman Collection

The Postman Collection is included in:

```text
docs/Payment Checkout API.postman_collection.json
```

The collection contains:

- Create Checkout
- Get Transaction
- Checkout Page
- Pay Transaction

---

# Assumptions

- This application is a payment gateway simulation and does not connect to a real payment provider.
- Clicking `Pay Now` is treated as a successful payment.
- `merchant_order_id` must be unique for each transaction.
- API Key authentication is used for merchant API endpoints.
- `callback_url` and `return_url` are provided by the merchant during checkout creation.
- Payment status uses `PENDING` and `PAID`.

---

# Known Limitations

The following features are not implemented:

- Real payment gateway integration
- Callback/Webhook delivery
- Transaction expiration
- Payment cancellation
- Queue-based webhook delivery
- Swagger/OpenAPI documentation
- Rate limiting
- Production payment security

---

# Project Structure

```text
payment-checkout/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── Models/
├── bootstrap/
├── config/
├── database/
│   └── migrations/
├── docs/
│   └── Payment Checkout API.postman_collection.json
├── resources/
│   └── views/
│       └── payment/
├── routes/
├── tests/
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

---

# Security Notes

The `.env` file contains local configuration and secret values such as the API Key and Laravel application key.

The `.env` file must not be committed to the repository.

Use `.env.example` as the configuration template when setting up the application.

Example:

```env
API_KEY=your-api-key
```

---

# License

This project was developed as part of a Backend Developer Technical Test.