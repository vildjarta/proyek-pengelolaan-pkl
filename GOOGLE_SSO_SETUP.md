# Setup Google SSO (Single Sign-On)

## 📋 Panduan Konfigurasi

Ikuti langkah-langkah berikut untuk mengaktifkan fitur Login dengan Google:

---

## 1️⃣ Dapatkan Google OAuth Credentials

### Langkah 1: Buka Google Cloud Console
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Login dengan akun Google Anda

### Langkah 2: Buat Project Baru (atau pilih yang sudah ada)
1. Klik dropdown project di header atas
2. Klik **"New Project"**
3. Isi nama project: `Sistem Pengelolaan PKL`
4. Klik **"Create"**

### Langkah 3: Aktifkan Google+ API
1. Di sidebar kiri, pilih **"APIs & Services" > "Library"**
2. Cari **"Google+ API"** atau **"Google Identity"**
3. Klik dan pilih **"Enable"**

### Langkah 4: Buat OAuth Consent Screen
1. Pilih **"APIs & Services" > "OAuth consent screen"**
2. Pilih **"External"** (untuk testing)
3. Isi informasi:
   - **App name**: Sistem Pengelolaan PKL
   - **User support email**: Email Anda
   - **Developer contact**: Email Anda
4. Klik **"Save and Continue"**
5. **Scopes**: Klik **"Add or Remove Scopes"**
   - Pilih: `userinfo.email`, `userinfo.profile`, `openid`
6. Klik **"Save and Continue"**
7. **Test users**: Tambahkan email yang akan digunakan untuk testing
8. Klik **"Save and Continue"**

### Langkah 5: Buat OAuth 2.0 Credentials
1. Pilih **"APIs & Services" > "Credentials"**
2. Klik **"+ CREATE CREDENTIALS" > "OAuth client ID"**
3. Pilih **Application type**: `Web application`
4. Isi informasi:
   - **Name**: `PKL Web Client`
   - **Authorized JavaScript origins**:
     ```
     http://localhost
     http://localhost:8000
     http://127.0.0.1:8000
     ```
   - **Authorized redirect URIs**:
     ```
     http://localhost/auth/google/callback
     http://localhost:8000/auth/google/callback
     http://127.0.0.1:8000/auth/google/callback
     ```
5. Klik **"Create"**
6. **SIMPAN** Client ID dan Client Secret yang ditampilkan

---

## 2️⃣ Konfigurasi File .env

Buka file `.env` di root project dan tambahkan/update variabel berikut:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

**⚠️ PENTING**: 
- Ganti `your-client-id-here` dengan Client ID dari Google Cloud Console
- Ganti `your-client-secret-here` dengan Client Secret dari Google Cloud Console
- Pastikan `APP_URL` di `.env` sesuai dengan URL aplikasi Anda

**Contoh:**
```env
APP_URL=http://localhost:8000

GOOGLE_CLIENT_ID=123456789012-abcdefghijklmnopqrstuvwxyz123456.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-AbCdEfGhIjKlMnOpQrStUvWxYz
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

---

## 3️⃣ Testing

### 1. Jalankan Server
```bash
php artisan serve
```

### 2. Buka Browser
Akses: `http://localhost:8000`

### 3. Klik Tombol "Masuk dengan Google"
- Anda akan diarahkan ke halaman login Google
- Pilih akun Google yang sudah didaftarkan sebagai Test User
- Setujui permission yang diminta
- Anda akan kembali ke aplikasi dan otomatis login

---

## 4️⃣ Cara Kerja Sistem

### Skenario 1: User Baru
1. User klik "Masuk dengan Google"
2. Login via Google
3. **Sistem membuat akun baru** dengan:
   - Email dari Google
   - Nama dari Google
   - Avatar dari Google
   - Google ID tersimpan
4. User otomatis login

### Skenario 2: User Sudah Ada (Email sama)
1. User sudah pernah daftar manual dengan email yang sama
2. Klik "Masuk dengan Google"
3. **Sistem menghubungkan** Google ID ke akun yang sudah ada
4. User otomatis login

### Skenario 3: User Sudah Pernah Login dengan Google
1. User klik "Masuk dengan Google"
2. **Sistem mengenali** Google ID
3. User langsung login tanpa perlu buat akun baru

---

## 5️⃣ Troubleshooting

### Error: "redirect_uri_mismatch"
**Solusi**: 
- Pastikan URL callback di Google Cloud Console sama persis dengan URL aplikasi
- Format: `http://localhost:8000/auth/google/callback`
- Jangan lupa save setelah update

### Error: "Access blocked: This app's request is invalid"
**Solusi**:
- Pastikan OAuth Consent Screen sudah di-setup
- Tambahkan email Anda sebagai Test User

### Error: "Client ID not found"
**Solusi**:
- Periksa kembali GOOGLE_CLIENT_ID di file `.env`
- Pastikan tidak ada spasi atau karakter aneh
- Jalankan: `php artisan config:clear`

### Tidak redirect setelah login Google
**Solusi**:
- Cek `APP_URL` di `.env` sudah benar
- Cek `GOOGLE_REDIRECT_URI` sesuai dengan `APP_URL`
- Clear cache: `php artisan cache:clear`

---

## 6️⃣ Keamanan

### ⚠️ JANGAN PERNAH:
- ❌ Commit file `.env` ke Git/GitHub
- ❌ Share Client Secret ke orang lain
- ❌ Publish credentials di public repository

### ✅ HARUS:
- ✅ Simpan credentials di file `.env` saja
- ✅ Tambahkan `.env` ke `.gitignore`
- ✅ Gunakan `.env.example` sebagai template (tanpa isi value asli)

---

## 7️⃣ Production Deployment

Jika deploy ke production (domain asli), update:

### Google Cloud Console:
1. Tambah domain production ke **Authorized JavaScript origins**:
   ```
   https://yourdomain.com
   ```
2. Tambah callback URL production ke **Authorized redirect URIs**:
   ```
   https://yourdomain.com/auth/google/callback
   ```

### File .env (Production):
```env
APP_URL=https://yourdomain.com
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### OAuth Consent Screen:
- Ubah dari **"Testing"** menjadi **"In Production"** setelah siap

---

## 📚 Dokumentasi Tambahan

- [Laravel Socialite Docs](https://laravel.com/docs/socialite)
- [Google OAuth 2.0 Setup](https://developers.google.com/identity/protocols/oauth2)
- [Google Cloud Console](https://console.cloud.google.com/)

---

## ✅ Checklist Setup

- [ ] Project dibuat di Google Cloud Console
- [ ] OAuth Consent Screen sudah dikonfigurasi
- [ ] OAuth 2.0 Credentials sudah dibuat
- [ ] Client ID dan Secret sudah disimpan
- [ ] File `.env` sudah diupdate dengan credentials
- [ ] Test user sudah ditambahkan di Google Cloud Console
- [ ] Migration database sudah dijalankan (`php artisan migrate`)
- [ ] Testing login dengan Google berhasil

---

**✨ Selesai! Fitur Login dengan Google sudah aktif.**
