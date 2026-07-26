# TechSphere Mobile

A Laravel storefront and back-office system for a Sri Lankan mobile phone shop. Customers can discover products, compare devices, save a wishlist, reserve stock, select delivery and payment options, track orders, and print invoices. Staff can manage the full catalogue, variants, stock, offers, payments, fulfilment, notifications, and store settings.

## Main Features

- Searchable phone and accessory catalogues with stock-aware filters
- Product variants for storage, colour, SKU, price, status, and inventory
- Date-controlled offers assignable to selected phones and accessories
- Three-device comparison with duplicate and limit protection
- Customer accounts, wishlists, secure order tracking, and printable invoices
- Pickup or delivery checkout with configurable delivery fee
- Cash, bank transfer, or card-at-store payment records
- Atomic stock reservation, cancellation restore, and expired-order release
- Idempotent checkout submission and rate limiting
- Admin dashboard with low-stock, order, revenue, and fulfilment views
- Catalogue publishing controls and local image uploads
- Email notification delivery with retryable audit logs
- Configurable store contact, WhatsApp, opening hours, bank, and delivery details
- Responsive storefront and compact mobile-ready admin interface

## Technology

- PHP 8.2+ and Laravel 12
- Blade, vanilla JavaScript, and custom responsive CSS
- Eloquent ORM, middleware, validation, rate limiting, mail, and scheduler
- SQLite by default; MySQL or PostgreSQL can be configured through `.env`
- PHPUnit feature tests

## Local Setup

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item database/database.sqlite -ItemType File -Force
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`. The admin panel is available at `http://127.0.0.1:8000/admin`.

Local and test environments receive these seed accounts:

```text
Admin:    admin@techsphere.test / password
Customer: user@techsphere.test / password
```

For a production environment, create an administrator interactively:

```powershell
php artisan admin:create admin@example.com --name="Store Admin"
```

## Background Tasks

Run the Laravel scheduler so expired unpaid reservations are cancelled and their stock is returned:

```powershell
php artisan schedule:work
```

For production, configure the server to execute `php artisan schedule:run` every minute.

## Production Checklist

- Set `APP_ENV=production`, `APP_DEBUG=false`, and the public `APP_URL`.
- Configure a production database and run `php artisan migrate --force`.
- Configure SMTP variables for customer and staff email notifications.
- Replace the seeded store, bank, phone, WhatsApp, address, and delivery details in Admin > Settings.
- Create a secure administrator with `admin:create`; fixed demo accounts are never seeded in production.
- Serve the application through a web server with HTTPS and keep the scheduler running.
- Connect a PCI-compliant payment provider before accepting online card payments. The included card option records payment at the physical store; it is not an online gateway.

## Verification

```powershell
php artisan test
php artisan route:list
```
