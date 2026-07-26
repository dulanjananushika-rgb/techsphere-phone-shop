# TechSphere Phone Shop Management System

TechSphere is a Laravel phone shop management system. Customers can browse phones and accessories, compare device specifications, save phones to a wishlist, and place reservation orders. Admin users can manage phones, variants, brands, accessories, offers, orders, notifications, and shop settings.

## Features

- Public phone catalog with search and filters
- Phone details page with specs and WhatsApp order link
- Accessory catalog by category
- Three-phone comparison page
- User registration, login, logout, and wishlist
- Customer order requests with admin status management
- Payment method, payment reference, and admin payment status tracking
- Auto-generated invoice numbers with printable invoice view
- Notification log for new orders and customer status updates
- SKU-level variants for color, storage, price, and stock
- Stock reservation and restore logic for order/cancel flows
- Admin dashboard with inventory statistics
- Admin CRUD for phones, variants, brands, accessories, offers, and notification logs
- Offers can be assigned to selected phones and accessories
- Global WhatsApp number and shop email settings
- SQLite database with demo seed data

## Tech Stack

- Laravel 12
- PHP 8.2+
- SQLite by default
- Blade templates
- Custom CSS
- Laravel authentication using the built-in auth guard
- Eloquent models, migrations, seeders, controllers, and middleware

## Demo Credentials

Admin:

```text
Email: admin@techsphere.test
Password: password
```

Customer:

```text
Email: user@techsphere.test
Password: password
```

## Run Locally

```bash
composer install
php artisan migrate:fresh --seed
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

Admin panel:

```text
http://127.0.0.1:8000/admin
```

## Notes

This version intentionally uses hosted image URLs and SQLite so it is easy to run locally. The original raw PHP project was rebuilt into Laravel structure with routes, controllers, models, migrations, seeders, middleware, and Blade views.
