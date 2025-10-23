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

The system is designed to easily integrate multiple payment gateways without modifying core business logic, following SOLID principles, especially the Open/Closed Principle.

1. Architecture Overview

PaymentProcessor: Handles payment processing for orders. It delegates the actual payment execution to a gateway.

PaymentGatewayManager: Responsible for selecting the correct gateway based on the payment method.

PaymentGatewayInterface: Defines the contract for any payment gateway. Each gateway class implements this interface.

interface PaymentGatewayInterface {
    public function process(Order $order, array $data): array;
}
## Payment Gateway Extensibility

Gateway Implementations: Each payment provider (e.g., Cash, Credit Card, Stripe) has its own class implementing PaymentGatewayInterface.

2. Adding a New Gateway

Create a new class implementing PaymentGatewayInterface.
Example:

class StripeGateway implements PaymentGatewayInterface {
    public function process(Order $order, array $data): array {
        // Stripe API logic
        return [
            'payment_id' => $stripePaymentId,
            'status' => 'successful',
        ];
    }
}


Register the new gateway in PaymentGatewayManager.

$this->gateways['stripe'] = new StripeGateway();


No changes are required in PaymentProcessor or the controller.

3. Benefits

Open/Closed Principle: Add new gateways without modifying existing code.

Testability: Each gateway can be tested independently using mocks.

Consistency: PaymentProcessor and services remain agnostic to specific gateway implementations.

Scalability: Easy to support multiple payment methods for different regions or providers.

4. Flow Example

User initiates a payment.

PaymentProcessor receives the order and payment data.

The processor asks PaymentGatewayManager to return the correct gateway for the chosen method.

Gateway executes the payment and returns the result.

Processor saves the transaction via the repository.

This structure ensures that extending the payment system is fast, safe, and requires minimal changes.