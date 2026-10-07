# PLAN — User Management System (ข้อสอบ PHP Laravel Developer)

> เอกสารนี้คือ brief สำหรับ Claude Code ให้ทำงานทีละ Phase ตามลำดับ
> ทำแต่ละ Phase ให้ครบ → รันเกณฑ์ตรวจ (Done when) ให้ผ่าน → commit → ค่อยไป Phase ถัดไป

---

## 1. เป้าหมาย

สร้าง Web Application ระบบจัดการผู้ใช้ด้วย Laravel 13 ที่มี Login, Register, Dashboard, CRUD ผู้ใช้ (ค้นหา + แบ่งหน้า) และแก้ไขโปรไฟล์ตัวเอง
หน้าตาต้องสวยและดูเป็นมืออาชีพ แต่โค้ดต้องน้อยและอ่านง่าย เพราะผู้ตรวจจะอ่านโค้ดด้วย

## 2. Tech stack (ตัดสินใจแล้ว)

| ส่วน | เลือกใช้ |
|---|---|
| Framework | Laravel 13, PHP 8.4 |
| Database | SQLite |
| Starter kit | **Laravel Livewire Starter Kit (official)** — Blade + Livewire + Flux UI (free) + Tailwind v4 |
| Auth | ระบบ auth ที่มากับ starter kit (ไม่ใช้ Breeze เพราะ Laravel เลิกใช้แล้วตั้งแต่ v12) |
| Test | Pest |
| ส่งงาน | GitHub repo |

**กฎ:**
- ห้ามเพิ่ม package นอกเหนือจากที่ starter kit ให้มา ยกเว้นจำเป็นจริงและต้องแจ้งก่อน
- ทำตาม convention ของ starter kit ที่ติดตั้งมา (โครงสร้าง Livewire component, Flux component, layout) ให้ตรวจโค้ดที่ kit สร้างไว้ก่อนแล้วเขียนตามแบบเดียวกัน
- ห้าม commit `.env` และไฟล์ `database/database.sqlite`
- ไม่มีระบบ role ผู้ใช้ทุกคนที่ล็อกอินแล้วจัดการผู้ใช้ได้เท่ากัน

## 3. ข้อกำหนดตามโจทย์ + ที่ตกลงเพิ่ม

### 3.1 Login (`/login`)
- ฟอร์ม Email + Password
- สำเร็จ → redirect ไป `/dashboard`
- ไม่สำเร็จ → แสดงข้อความแจ้งเตือน
- ทุกหน้ายกเว้น login / register / เปลี่ยนภาษา ต้องอยู่ใต้ middleware `auth` ถ้ายังไม่ล็อกอินให้ redirect ไป `/login`

### 3.2 Register (`/register`)
- ฟิลด์: ชื่อ, Email, รหัสผ่าน, ยืนยันรหัสผ่าน
- Validation ฝั่ง server ทุกฟิลด์:
  - `name`: required, string, max:255
  - `email`: required, email, lowercase, max:255, unique:users
  - `password`: required, `confirmed`, `Password::defaults()` (อย่างน้อย 8 ตัวอักษร)
- ถ้ารหัสผ่านกับยืนยันรหัสผ่านไม่ตรงกัน ต้องแสดงข้อความเตือนใต้ช่องยืนยันรหัสผ่าน
- เพิ่มการเตือนแบบ real-time ขณะพิมพ์ช่องยืนยันรหัสผ่าน (เช่น `wire:model.live.blur` หรือ Alpine) เป็นส่วนเสริม แต่ server-side validation ต้องมีเสมอ
- สมัครสำเร็จ → login อัตโนมัติ → ไป `/dashboard`

### 3.3 Dashboard (`/dashboard`)
- Navigation (ใช้ sidebar ของ starter kit) มี:
  - ลิงก์ Dashboard
  - ลิงก์ "จัดการผู้ใช้งาน" (`/users`)
  - ลิงก์ "แก้ไขโปรไฟล์" (`/profile/edit`)
  - ปุ่ม Logout
  - ตัวสลับภาษา TH / EN
- เนื้อหา: การ์ดสรุปเล็ก ๆ เช่น จำนวนผู้ใช้ทั้งหมด, ผู้ใช้ที่สมัครวันนี้, ข้อความต้อนรับชื่อผู้ใช้ (ไม่ต้องเยอะ)

### 3.4 จัดการผู้ใช้ (`/users`) — CRUD
| Route | หน้าที่ |
|---|---|
| `GET /users` | ตารางรายชื่อผู้ใช้: ลำดับ, ชื่อ, Email, วันที่สร้าง, ปุ่มแก้ไข/ลบ |
| `GET /users/create` | ฟอร์มเพิ่มผู้ใช้ (ชื่อ, Email, รหัสผ่าน, ยืนยันรหัสผ่าน) |
| `GET /users/{user}/edit` | ฟอร์มแก้ไขผู้ใช้ — **เว้นรหัสผ่านว่าง = ใช้รหัสเดิม** |
| ลบ | ปุ่มลบ → Flux modal ยืนยันก่อนลบ |

- ใช้ named routes: `users.index`, `users.create`, `users.edit`
- **ค้นหา:** ช่องค้นหาเดียว ค้นจาก ชื่อ หรือ Email (`like`), ค้นหาแบบ live (debounce ~300ms), เก็บคำค้นไว้ใน query string (`?search=`)
- **แบ่งหน้า:** 5 รายการต่อหน้า, เรียงจากใหม่ไปเก่า, เปลี่ยนคำค้นแล้ว reset กลับหน้า 1
- แสดงข้อความ "ไม่พบข้อมูล" เมื่อไม่มีผลลัพธ์
- Validation เหมือน Register แต่ตอนแก้ไข email ต้อง `unique` โดยยกเว้น id ของตัวเอง และ password เป็น `nullable`
- **ห้ามลบบัญชีของตัวเอง:** ซ่อน/ปิดปุ่มลบในแถวของตัวเอง และต้องกันฝั่ง server ด้วย
- หลังบันทึก / แก้ไข / ลบ ให้แสดง toast หรือ flash message
- รหัสผ่านต้อง hash เสมอ (ใช้ `'password' => 'hashed'` cast ใน User model)
- เก็บ validation rules ไว้ที่เดียว (เช่น Livewire Form Object หรือ trait) ไม่ให้ซ้ำกันระหว่าง create/edit/register เท่าที่ทำได้โดยไม่ซับซ้อน

### 3.5 แก้ไขโปรไฟล์ (`/profile/edit`)
- route ต้องเป็น `/profile/edit` ตามโจทย์ (starter kit ใช้ `/settings/profile` ให้ redirect `/settings/...` มาที่ `/profile/edit` หรือรวมไว้ที่หน้าเดียว)
- หน้าเดียวแก้ได้ทั้ง ชื่อ, Email, รหัสผ่าน
- เปลี่ยนรหัสผ่านต้องกรอก รหัสผ่านปัจจุบัน + รหัสใหม่ + ยืนยันรหัสใหม่ (ถ้าไม่เปลี่ยนรหัสผ่าน ปล่อยว่างได้)

### 3.6 สองภาษา (TH / EN)
- ภาษาเริ่มต้น: ไทย (`APP_LOCALE=th`), fallback: `en`
- route `GET /locale/{locale}` (รับเฉพาะ `th`, `en`) → เก็บใน session → redirect back
- middleware `SetLocale` อ่านค่าจาก session แล้ว `App::setLocale()` ลงทะเบียนใน `bootstrap/app.php`
- ตัวสลับภาษาต้องอยู่ทั้งใน sidebar และหน้า login/register
- ข้อความ UI ใช้ `__('...')` กับไฟล์ `lang/th.json` และ `lang/en.json`
- ข้อความ validation ภาษาไทย: `php artisan lang:publish` แล้วสร้าง `lang/th/validation.php`, `lang/th/auth.php`, `lang/th/pagination.php`, `lang/th/passwords.php` (แปลเฉพาะ key ที่ใช้จริงก็พอ) และตั้งชื่อ attribute เป็นไทย (ชื่อ, อีเมล, รหัสผ่าน ...)
- วันที่สร้าง: แสดงผ่าน `translatedFormat()` ตาม locale เช่น `7 ต.ค. 2026 15:49` / `7 Oct 2026 15:49`
- คำศัพท์ (Claude Code เลือก wording เองได้) ให้สุภาพ กระชับ และใช้คำเดียวกันทั้งระบบ

### 3.7 ตัดออกจาก starter kit (ให้โค้ดตรงโจทย์และอ่านง่าย)
- Two-factor authentication
- Email verification (ลบ `verified` middleware ออกจาก route ด้วย)
- หน้า Appearance และหน้า Delete account (ถ้ามี) — เว้นแต่จะเก็บไว้แล้วไม่ทำให้ซับซ้อน
- Forgot password: เก็บไว้ได้ แต่ไม่ต้องทำให้ส่งอีเมลได้จริง (`MAIL_MAILER=log`)

## 4. Phases

### Phase 0 — เตรียมโปรเจกต์
- ถ้าโปรเจกต์ Laravel 13 ที่มีอยู่ **ยังไม่ได้ติดตั้ง Livewire starter kit** ให้สร้างใหม่ด้วย
  `laravel new user-management` → เลือก **Livewire**, auth แบบ Laravel built-in, test **Pest**, database **SQLite**
  (starter kit ติดตั้งเพิ่มภายหลังในโปรเจกต์เปล่าไม่ได้ง่าย การสร้างใหม่เร็วกว่า)
- `git init`, ตรวจ `.gitignore` ให้กัน `.env` และ `database/database.sqlite`
- commit แรก: `chore: scaffold Laravel 13 with Livewire starter kit`

**Done when:** `composer run dev` แล้วเปิดหน้า `/login` และ `/register` ได้, `php artisan test` ผ่าน

### Phase 1 — ปรับ starter kit
- ตัดฟีเจอร์ตาม 3.7
- ตรวจ Register ให้ตรง 3.2 (ฟิลด์ครบ, validation ครบ, เตือนรหัสผ่านไม่ตรงกัน)
- ตั้ง `/dashboard` + sidebar ตาม 3.3 (ลิงก์ users ยังเป็น placeholder ได้)
- Seeder: admin `admin@example.com` / `password` + ผู้ใช้ปลอม 12 คนด้วย factory (ให้เห็นการแบ่งหน้า 3 หน้า)

**Done when:** login/logout/register ใช้ได้, guest เข้า `/dashboard` แล้วถูกส่งไป `/login`, `migrate:fresh --seed` ใช้ได้

### Phase 2 — CRUD ผู้ใช้
- ทำตาม 3.4 ครบทุกข้อ

**Done when:** เพิ่ม/แก้ไข/ลบ/ค้นหา/แบ่งหน้า ใช้ได้จริงในเบราว์เซอร์, ลบตัวเองไม่ได้ (ทั้ง UI และ server)

### Phase 3 — แก้ไขโปรไฟล์
- ทำตาม 3.5

**Done when:** `/profile/edit` แก้ชื่อ/อีเมล/รหัสผ่านได้, กรอกรหัสผ่านปัจจุบันผิดแล้วมีข้อความเตือน

### Phase 4 — สองภาษา
- ทำตาม 3.6 แล้วไล่ทุกหน้าให้ไม่มีข้อความ hard-code หลงเหลือ

**Done when:** สลับ TH/EN แล้วทุกหน้า รวมถึงข้อความ validation, pagination และวันที่ เปลี่ยนภาษาครบ

### Phase 5 — Tests (Pest, Feature tests)
อย่างน้อยต้องมี:
- Login สำเร็จ / รหัสผิดแล้วมี error
- Guest เข้า `/dashboard`, `/users`, `/profile/edit` ไม่ได้ (redirect `/login`)
- Register: สำเร็จ / email ซ้ำ / รหัสผ่านยืนยันไม่ตรงกัน
- Users: แสดงรายการ, เพิ่ม, แก้ไข (เว้นรหัสผ่านว่างแล้วรหัสเดิมยังใช้ได้), ลบ, ลบตัวเองไม่ได้
- ค้นหาด้วยชื่อ และด้วย email
- หน้าแรกแสดง 5 รายการ
- Profile update
- เปลี่ยนภาษาแล้ว locale เปลี่ยน

**Done when:** `php artisan test` ผ่านทั้งหมด

### Phase 6 — ขัดเงาและส่งงาน
- ตรวจ UI ทั้ง light/dark mode และหน้าจอมือถือ
- `./vendor/bin/pint` จัดรูปแบบโค้ด
- เขียน `README.md` (ภาษาไทย + อังกฤษสั้น ๆ):
  - ความต้องการระบบ: PHP 8.4, Composer, Node 20+
  - ขั้นตอนติดตั้งหลัง clone:
    ```bash
    composer install
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite   # Windows: type nul > database\database.sqlite
    php artisan migrate --seed
    npm install && npm run build
    php artisan serve
    ```
    (ถ้า `composer.json` มี script `setup` อยู่แล้ว ให้ระบุ `composer run setup` เป็นทางลัดด้วย)
  - บัญชีทดสอบ: `admin@example.com` / `password`
  - รายการฟีเจอร์ที่ทำ + จุดที่ทำเกินโจทย์ (ค้นหา, แบ่งหน้า, 2 ภาษา, tests, ป้องกันลบตัวเอง)
  - วิธีรัน test
- ตรวจว่า `.env.example` มี `DB_CONNECTION=sqlite`, `APP_LOCALE=th`, `APP_FALLBACK_LOCALE=en`
- สร้าง GitHub repo แล้ว push

**Done when:** clone repo ใหม่ไปโฟลเดอร์อื่น ทำตาม README ทุกขั้นแล้วระบบรันได้และ login ด้วยบัญชีทดสอบได้

## 5. Commit convention
แยก commit ตาม Phase ใช้ข้อความแบบ conventional เช่น
`feat: add user CRUD with search and pagination`, `feat: add TH/EN localization`, `test: add feature tests for users`, `docs: add README`
