# User Management System

ระบบจัดการผู้ใช้ด้วย Laravel 13 + Livewire + Flux UI — สมัคร/เข้าสู่ระบบ, แดชบอร์ด, CRUD ผู้ใช้ (ค้นหา + แบ่งหน้า) และแก้ไขโปรไฟล์ รองรับภาษาไทย/อังกฤษ

A user management web app built with Laravel 13, Livewire and Flux UI: register / login, dashboard, user CRUD (search + pagination) and profile editing, in Thai and English.

## ความต้องการระบบ / Requirements

- PHP 8.4
- Composer
- Node 20+

## ติดตั้ง / Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # Windows: type nul > database\database.sqlite
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

ทางลัด / Shortcut: `composer run setup` (install, สร้าง `.env`, key, migrate + seed, build) แล้วรัน `php artisan serve`

ระหว่างพัฒนาใช้ `composer run dev` / For development use `composer run dev`.

เปิด http://localhost:8000 

## บัญชีทดสอบ / Demo account

| Email | Password |
|---|---|
| `admin@example.com` | `password` |

Seeder สร้างผู้ใช้ตัวอย่างเพิ่มอีก 12 คน (ผู้ใช้ทุกคนมีรหัสผ่าน `password`) ทำให้เห็นการแบ่งหน้า 3 หน้า

## ฟีเจอร์ / Features

- เข้าสู่ระบบ / สมัครสมาชิก (ตรวจสอบข้อมูลฝั่ง server, สมัครแล้วเข้าสู่ระบบอัตโนมัติ)
- ทุกหน้า (ยกเว้น login, register, สลับภาษา) ต้องล็อกอินก่อน
- แดชบอร์ด: จำนวนผู้ใช้ทั้งหมด และผู้ใช้ที่สมัครวันนี้
- จัดการผู้ใช้ (`/users`): เพิ่ม / แก้ไข (เว้นรหัสผ่านว่าง = ใช้รหัสเดิม) / ลบพร้อม modal ยืนยัน
- แก้ไขโปรไฟล์ (`/profile/edit`): ชื่อ อีเมล และรหัสผ่าน (ต้องกรอกรหัสผ่านปัจจุบันเมื่อเปลี่ยนรหัสผ่าน)

### ส่วนที่ทำเพิ่มจากโจทย์ / Beyond the brief

- ค้นหาจากชื่อหรืออีเมลแบบ live (เก็บคำค้นใน `?search=`)
- แบ่งหน้า 5 รายการต่อหน้า เรียงใหม่ไปเก่า
- สองภาษา TH / EN (ข้อความ UI, validation, pagination และวันที่)
- ป้องกันการลบบัญชีของตัวเอง (ทั้งใน UI และฝั่ง server)
- แจ้งเตือนรหัสผ่านไม่ตรงกันแบบ real-time ขณะพิมพ์
- Feature tests ด้วย Pest
- Light / dark mode ตามระบบของผู้ใช้

## รัน test / Running tests

```bash
php artisan test
```

## Tech stack

Laravel 13, PHP 8.4, SQLite, Livewire 4, Flux UI, Tailwind CSS v4, Laravel Fortify, Pest
