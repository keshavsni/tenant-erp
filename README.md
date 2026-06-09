# tenant-erp

# Multi-Tenant Inventory Management API

A RESTful Inventory Management System built with Laravel 11, supporting multi-tenancy, warehouse stock management, order processing, inventory deduction, queued email notifications, and Sanctum authentication.

## Features

- Multi-Tenant Architecture (Company-based data isolation)
- Laravel Sanctum Authentication
- Product Management (CRUD)
- Warehouse Management (CRUD)
- Warehouse Stock Management
- Order Management
- Inventory Validation & Deduction
- Queue-based Order Confirmation Emails
- Pagination & Search
- Soft Deletes
- API Resources
- Form Request Validation
- Consistent JSON Responses

---

## Technology Stack

- PHP 8.2+
- Laravel 11
- MySQL
- Laravel Sanctum
- Laravel Queue
- REST API

---

## Installation

### Clone Repository

```bash
git clone https://github.com/keshavsni/tenant-erp.git
cd tanent-erp
```

### Install Dependencies

```bash
composer install
```

### Copy Environment File

```bash
cp .env.example .env
```

### Generate Application Key

```bash
php artisan key:generate
```

---

## Database Configuration

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tenant_erp
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```sql
CREATE DATABASE tenant_erp;
```

---

## Configure Queue

Set queue driver:

```env
QUEUE_CONNECTION=database
```

Generate queue tables:

```bash
php artisan queue:table

php artisan migrate --path=database/migrations/2026_06_09_110217_create_companies_table.php

php artisan migrate
```

---

## Configure Mail

Update mail credentials in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="Inventory Management"
```

You may use Mailtrap for testing emails.

---

Run migrations:

```bash
php artisan migrate
```

---

## Seed Database

Run all seeders:

```bash

First Create an company account then run seeder to storing products

php artisan db:seed



```

---

## Start Queue Worker

Required for order confirmation emails.

```bash
php artisan queue:work
```

---

## Start Application

```bash
php artisan serve
```

Application URL:

```text
http://127.0.0.1:8000
```

---

# API Authentication

All protected APIs require:

```http
Authorization: Bearer {access_token}
Accept: application/json
```

---

# API Endpoints

## Authentication

### Register

```http
POST /api/register
```

### Login

```http
POST /api/auth/login
```

### Logout

```http
POST /api/auth/logout
```

---

## Products

### Get Products

```http
GET /api/products
```

Query Parameters:

```text
?page=1
&per_page=10
&search=laptop
```

### Create Product

```http
POST /api/products
```

### Get Product

```http
GET /api/products/{id}
```

### Update Product

```http
PUT /api/products/{id}
```

### Delete Product

```http
DELETE /api/products/{id}
```

---

## Warehouses

### Get Warehouses

```http
GET /api/warehouses
```

### Create Warehouse

```http
POST /api/warehouses
```

### Update Warehouse

```http
PUT /api/warehouses/{id}
```

### Delete Warehouse

```http
DELETE /api/warehouses/{id}
```

### Update Warehouse Stock

```http
POST /api/warehouses/{warehouse}/stock
```

Example:

```json
{
  "product_id": 1,
  "stock": 100
}
```

---

## Orders

### Create Order

```http
POST /api/orders
```

Example:

```json
{
  "warehouse_id": 1,
  "products": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 2,
      "quantity": 5
    }
  ]
}
```

### List Orders

```http
GET /api/orders
```

Optional Filters:

```text
?status=completed
?warehouse_id=1
?order_id=10
```

### Get Order Details

```http
GET /api/orders/{id}
```

---

# Multi-Tenant Architecture

Each user belongs to a company.

All Products, Warehouses, and Orders are scoped to the authenticated user's company.

Users cannot access data belonging to another company.

---

# Queue Processing

When an order is successfully created:

1. Inventory is validated.
2. Inventory is deducted.
3. Order is saved.
4. Order confirmation email job is dispatched.
5. Queue worker sends the email asynchronously.

---

# Project Structure

```text
app/
├── Http/
│   ├── Controllers/Api
│   ├── Requests
│   └── Resources
│
├── Jobs
├── Models
└── Traits
```

---

# Running Tests

```bash
php artisan test
```

---

# Postman Collection

Import the provided Postman collection:

```text
postman/Inventory-Management.postman_collection.json
```

---

# Default Seed Data

Company:

```text
Demo Company
```

User:

```text
Email: admin@example.com
Password: password
```

Use these credentials to log in and test the APIs.

---

# Author

Kokil Soni

Laravel Fullstack Developer
