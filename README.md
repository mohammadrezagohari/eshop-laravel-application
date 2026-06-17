# E-commerce Laravel API

A Laravel-based e-commerce backend focused on API-first shopping workflows: authentication, product discovery, basket management, checkout data modeling, invoice notifications, and documented API usage through a Postman collection.

## Highlights

- Token-based authentication with Laravel Sanctum.
- Role-based access for customers, sellers, and admins.
- Register, login, logout, and authenticated user endpoints.
- Product listing and product detail endpoints.
- Seller product ownership endpoints.
- Basket flow for guest or authenticated customers using user identity or cookie identity.
- Basket item add, list, update, view, and delete operations.
- Ticket support flow with customer tickets and staff replies.
- Checkout-oriented database design with cards, card products, addresses, and payments.
- Invoice notification endpoints backed by Laravel notifications.
- Repository and service layers around core basket, product, card, user, ticket, and auth logic.
- Feature tests for auth, role access, products, baskets, cards, tickets, and users.
- Postman collection included for API exploration.

## Tech Stack

- PHP 8.3
- Laravel 13
- Laravel Sanctum
- PHPUnit 12
- MySQL or another Laravel-supported relational database

## Main API Areas

### Authentication

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `POST` | `/api/auth/register` | Create a user and return an access token |
| `POST` | `/api/auth/login` | Authenticate a user and return an access token |
| `POST` | `/api/auth/logout` | Revoke the current access token |
| `GET` | `/api/user` | Return the authenticated user |

Public registration supports `customer` and `seller` roles. Admin users are intentionally excluded from public registration and should be promoted by an existing admin.

### Products

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/products` | List visible products |
| `GET` | `/api/products/show/{id}` | Show a product detail |
| `GET` | `/api/seller/products` | List products owned by the authenticated seller |
| `GET` | `/api/admin/products` | List all products for admins |

### Basket Flow

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/baskets` | List the current customer's basket items |
| `POST` | `/api/baskets/user/store` | Add a product to the basket |
| `GET` | `/api/baskets/item/{id}` | Show a selected basket item |
| `PATCH` | `/api/baskets/update/{id}` | Update a basket item quantity |
| `DELETE` | `/api/baskets/delete/{id}` | Delete a basket item |

### Card and Notifications

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/card/send-notification/{id}` | Send an invoice notification for a card |
| `GET` | `/api/card/get-notification/{id}` | Retrieve card notifications |

### Access Control

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `PATCH` | `/api/admin/users/{id}/role` | Update a user role as an admin |

Supported roles are `customer`, `seller`, and `admin`.

- `customer`: can browse products, manage a basket, and create or manage their own support tickets.
- `seller`: can access seller product ownership endpoints and reply to support tickets.
- `admin`: can access all products, update user roles, and manage staff ticket workflows.

### Tickets

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/tickets` | List the authenticated user's tickets |
| `POST` | `/api/tickets` | Create a support ticket |
| `GET` | `/api/tickets/{id}` | Show a ticket available to the current user |
| `PATCH` | `/api/tickets/{id}/close` | Close a ticket |
| `GET` | `/api/staff/tickets` | List tickets for admins and sellers |
| `PATCH` | `/api/staff/tickets/{id}/reply` | Reply to a ticket as admin or seller |

## Database Design

The schema separates commerce concerns into focused tables:

- `users` and `personal_access_tokens` for authentication.
- `users.role` for customer, seller, and admin access control.
- `products`, `categories`, `details`, `prices`, and `category_product` for catalog data.
- `baskets` and `basket_product` for basket state and item quantities.
- `cards` and `card_product` for checkout snapshots.
- `addresses` for customer delivery data.
- `payments` for payment records, including bank name, tracking code, amount, status, and card linkage.
- `notifications` for invoice notification history.
- `tickets` for customer support requests, staff assignments, replies, status, and priority.

## API Documentation

Import `gandom.postman_collection.json` into Postman to explore the available endpoints and request payloads.

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Configure your database connection in `.env` before running migrations.

## Tests

Run the feature test suite with:

```bash
php artisan test
```

The current suite covers authentication, role access, basket operations, card notifications, product endpoints, tickets, and user endpoints.

Recent role and ticket coverage includes:

- Seller registration and rejection of public admin registration.
- Customer restrictions on seller and admin routes.
- Seller restrictions on admin-only role updates.
- Admin user role updates and all-product visibility.
- Ticket creation, validation, staff replies, staff listing, ownership checks, and ticket closing.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
