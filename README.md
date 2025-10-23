# Laravel Payment & Orders API

##  Overview

This project is a **modular Laravel-based API** designed to handle:

- **Orders Management** (create, update, view, delete)  
- **Payment Processing** via different gateways  
-  **Payment Transactions** tracking and validation  

The system supports extensibility — new payment gateways can be integrated easily by implementing a single interface.

---


## Installation & Setup

###  Clone the Repository

```bash
git clone https://github.com/Nesma94/crosswork.git
cd crosswork
```

###  Install Dependencies

```bash
composer install
```

###  Environment Setup

Copy `.env.example` to `.env` and configure your database and app keys:

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file:
```env
APP_NAME="crosswork"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_DATABASE=crosswork
DB_USERNAME=root
DB_PASSWORD=root
```

### 4️⃣ Run Migrations & Seeders

```bash
php artisan migrate --seed
```


---

## 🧪 Running Tests

This project includes **unit** and **feature** tests for all major functionalities.

### Run All Tests
```bash
php artisan test
```

### Or with PHPUnit
```bash
vendor/bin/phpunit
```

### Example Test Files
- `tests/Unit/Services/PaymentProcessorTest.php`

These ensure:
- Payment is processed only for confirmed orders  
- Payment amount matches order total  
- Users cannot pay for others’ orders  