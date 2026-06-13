# Pet QR Identity & Recovery Platform

Production-ready Pet QR Identity & Recovery Platform built with CodeIgniter 4, Bootstrap 5, and AdminLTE 3.

## Features

- **QR Inventory Management**: Generate and manage QR tags in batches
- **Pet Registration**: Complete pet profile management with vaccinations
- **E-commerce**: Product management, orders, and payments
- **Public Recovery Portal**: Lost pet alerts and finder messages
- **Admin Dashboard**: Comprehensive analytics and management
- **OTP Verification**: Secure user activation
- **Audit Logs**: Track all system changes
- **REST APIs**: Full API support

## Requirements

- PHP >= 7.4
- MySQL >= 8.0
- Composer
- Node.js (optional, for frontend build tools)

## Installation

1. Clone the repository
```bash
git clone https://github.com/drringhealth/qrcode.git
cd qrcode
```

2. Install dependencies
```bash
composer install
```

3. Copy environment file
```bash
cp .env.example .env
```

4. Generate encryption key
```bash
php spark key:generate
```

5. Create database
```bash
mysql -u root -p
CREATE DATABASE pet_qr_platform;
```

6. Run migrations
```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

7. Create uploads directory
```bash
mkdir -p writable/uploads/{products,pets,finders}
chmod 755 writable/uploads -R
```

8. Start development server
```bash
php spark serve
```

Access the application at `http://localhost:8080`

## Default Credentials

**Admin Panel**: `/admin/login`
- Email: `admin@pet-qr.local`
- Password: `Admin@123`

## Database Schema

See `/docs/database-schema.md`

## API Documentation

See `/docs/api-documentation.md`

## Directory Structure

```
app/
├── Models/
├── Controllers/
├── Services/
├── Repositories/
├── Validation/
├── Views/
├── Database/
│   ├── Migrations/
│   └── Seeds/
└── Config/

public/
├── index.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/

writable/
├── uploads/
├── logs/
└── cache/
```

## License

MIT
