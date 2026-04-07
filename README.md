# 📄 README - Sistem Antrian Berbasis Suara (Laravel + Reverb)

## 📌 Deskripsi

Aplikasi ini adalah **sistem antrian berbasis web** yang dilengkapi dengan:

- 🔔 Panggilan nomor antrian secara **realtime**
- 🔊 Suara otomatis saat nomor dipanggil (Text-to-Speech)
- ⚡ Menggunakan **Laravel Reverb** untuk komunikasi WebSocket (tanpa Pusher)

Cocok digunakan untuk:

- Rumah sakit
- Klinik
- Loket pelayanan
- Anjungan mandiri

---

## ⚙️ Persyaratan Sistem

Pastikan sudah terinstall:

- PHP >= 8.1
- Composer
- Laravel >= 10
- Node.js & NPM
- Browser modern (Chrome / Edge)

---

## 🚀 Instalasi Project

### 1. Clone Project

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
```

---

### 2. Install Dependency Laravel

```bash
composer update
```

---

### 3. Install Laravel Reverb

```bash
composer require laravel/reverb
```

---

### 4. Setup Reverb

```bash
php artisan reverb:install
```

---

### 5. Konfigurasi Environment

Copy file `.env`:

```bash
cp .env.example .env
```

Lalu edit `.env`:

```env
APP_NAME="Sistem Antrian"
APP_URL=http://127.0.0.1:8000

BROADCAST_DRIVER=reverb

REVERB_APP_ID=local-app
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret

REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

---

### 6. Generate Key & Migrasi Database

```bash
php artisan key:generate
php artisan migrate
```

---

### 7. Install Frontend (Jika Ada)

```bash
npm install
npm run dev
```

---

## ▶️ Menjalankan Aplikasi

Jalankan 3 terminal:

### Terminal 1 - Laravel

```bash
php artisan serve
```

---

### Terminal 2 - Reverb (WebSocket)

```bash
php artisan reverb:start
```

---

### Terminal 3 - Frontend

```bash
npm run dev
```

---

### Terminal 4 Untuk Menjalankan Semua Terminal dari 1 sampai 3

```bash
npm run start
```

---

## 🔊 Fitur Panggilan Suara

Aplikasi ini menggunakan **Text-to-Speech (TTS)** di browser.

Contoh implementasi:

```javascript
function panggilAntrian(nomor) {
    const text = "Nomor antrian " + nomor + ", silakan menuju loket";
    const speech = new SpeechSynthesisUtterance(text);
    speech.lang = "id-ID";
    window.speechSynthesis.speak(speech);
}
```

---

## 📡 Realtime Antrian (Reverb)

Event Laravel akan dikirim ke frontend secara realtime.

Contoh Event:

```php
event(new PanggilAntrian($nomor));
```

Frontend akan menerima dan langsung:

- Update tampilan nomor
- Memutar suara otomatis

---

## ⚠️ Troubleshooting

### ❌ Reverb tidak jalan

```bash
php artisan config:clear
php artisan cache:clear
```

---

### ❌ Port 8080 sudah digunakan

Ganti di `.env`:

```env
REVERB_PORT=9090
```

---

### ❌ Suara tidak keluar

- Pastikan browser mengizinkan audio
- Gunakan Chrome
- Pastikan user sudah klik halaman (autoplay restriction)

---

### ❌ Realtime tidak jalan

- Pastikan:

```env
BROADCAST_DRIVER=reverb
```

- Cek console browser (F12)

---

## 💡 Catatan Penting

- Reverb harus selalu dijalankan
- Jangan tutup terminal Reverb
- Gunakan jaringan lokal jika dipakai di banyak komputer

---

## 👨‍💻 Penggunaan

Alur sistem:

1. User ambil nomor antrian
2. Nomor masuk ke database
3. Petugas klik "Panggil"
4. Sistem:
    - Broadcast via Reverb
    - Update UI
    - Memutar suara otomatis

---

## 🏁 Selesai

Aplikasi siap digunakan 🎉

---

## 📬 Kontak

Jika ada kendala, silakan hubungi developer.
