# Laravel Gem & Jewelry E-commerce

## Setup Instructions

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   - Copy `.env.example` to `.env`.
   - Update database credentials in `.env`.
   - Run `php artisan key:generate`.
   - **Note on Database**: If you encounter connection errors, ensure `DB_HOST` is set correctly for your system (e.g., `localhost` vs `127.0.0.1`). XAMPP often requires `localhost`.

3. **Database**
   ```bash
   php artisan migrate --seed
   ```
   *Seeds Admin User: admin@example.com / password*

4. **Authentication**
   Authentication is powered by Laravel Breeze and is already installed.
   Default Admin User: `admin@example.com` / `password`
   Default Customer User: `john@example.com` / `password`

5. **Run Application**
   ```bash
   npm run build
   php artisan serve
   ```

## Admin Access
- Middleware checks for `role` = 'admin' on the User model.
- Admin Dashboard is at `/admin`.

## Features
- **Public**: Home, Shop, Product Details, Cart (Session-based), Checkout, Wishlist, User Order History, Reviews.
- **Admin**: Dashboard, Product Management (CRUD), Category Management, Review Moderation, Stats.
- **Tech**: Laravel 11, Tailwind CSS, MySQL.
