# UAS-2388010049 — Administrasi Server (Cloud Computing II)

> **Nama**: M. Syifaulqulub Hakim  
> **NIM**: 2388010049  
> **Dosen**: Mohamad Firdaus, M.Kom.  
> **Mata Kuliah**: Administrasi Server (Cloud Computing II)

---

## 🌐 Live URL

| Service | Akses |
|---|---|
| Web Statis (CV) | `http://13.251.225.181/` |
| Web Dinamis (PHP Login) | `http://13.251.225.181/app` |

---

## 🏗️ Arsitektur Sistem

```
Internet
    │
    ▼
┌─────────────────────────────────────────────┐
│            AWS EC2 (t3.micro)               │
│         IP: 13.251.225.181                  │
│                                             │
│  ┌──────────────────────────────────────┐   │
│  │     Docker Network: app_network      │   │
│  │                                      │   │
│  │  Port 80 ──▶ [nginx_proxy]          │   │
│  │                  │                   │   │
│  │         ┌────────┴────────┐          │   │
│  │         ▼                ▼          │   │
│  │  [web_statis]    [web_dinamis]      │   │
│  │  nginx:alpine    php:8.2-apache     │   │
│  │                       │             │   │
│  │                       ▼             │   │
│  │                   [mariadb]         │   │
│  │                   port 3306         │   │
│  │                   (internal only)   │   │
│  └──────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
```

**Flow CI/CD:**
```
Developer (Local)
      │
      │  git push
      ▼
[GitHub Repository]
      │
      │  Trigger GitHub Actions
      ▼
┌─────────────────────┐
│   GitHub Actions    │
│  1. Build Image     │
│  2. Push to Hub     │
│  3. SCP compose.yml │
│  4. SSH → EC2       │
│  5. docker pull     │
│  6. docker compose  │
│     up -d           │
└──────────┬──────────┘
           │
           ▼
    [Docker Hub]
    hakim0901/web_statis
    hakim0901/web_dinamis
           │
           ▼
    [AWS EC2 Production]
    Zero-Touch Deployment ✅
```

---

## 📁 Struktur Repository

```
UAS-2388010049/
├── .github/
│   └── workflows/
│       ├── web_statis.yml      # CI/CD pipeline web statis (paths filter)
│       └── web_dinamis.yml     # CI/CD pipeline web dinamis (paths filter)
├── web_statis/
│   ├── index.html              # CV HTML statis
│   └── Dockerfile              # Build nginx image
├── web_dinamis/
│   ├── src/
│   │   └── index.php           # Aplikasi PHP + Login + Dashboard
│   ├── db/
│   │   └── init.sql            # Seed database MariaDB (auto-load)
│   └── Dockerfile              # Build php:apache image
├── nginx/
│   └── default.conf            # Reverse proxy config
├── docker-compose.yml          # Orkestrasi semua service
├── .env.example                # Template environment variables
├── .gitignore
└── README.md
```

---

## ⚙️ GitHub Actions Secrets

Konfigurasi secrets berikut di **Settings → Secrets and variables → Actions**:

| Secret | Keterangan |
|---|---|
| `DOCKERHUB_USERNAME` | Username Docker Hub (`hakim0901`) |
| `DOCKERHUB_TOKEN` | Access Token Docker Hub |
| `AWS_HOST` | IP publik EC2 (`13.251.225.181`) |
| `AWS_USERNAME` | Username SSH EC2 (`ubuntu`) |
| `AWS_PRIVATE_KEY` | Private key `.pem` (isi full kontennya) |
| `DB_PASS` | Password database appuser |
| `MYSQL_ROOT_PASSWORD` | Password root MariaDB |

---

## 🚀 Cara Deploy Pertama Kali (Setup EC2)

```bash
# 1. SSH ke EC2
ssh -i your-key.pem ubuntu@13.251.225.181

# 2. Install Docker
sudo apt update && sudo apt install -y docker.io docker-compose-plugin
sudo usermod -aG docker ubuntu
newgrp docker

# 3. Buat folder app
mkdir -p ~/app/nginx ~/app/web_dinamis/db

# 4. Jalankan compose (pertama kali)
cd ~/app
docker compose up -d

# 5. Cek status container
docker compose ps
```

---

## 🔄 Flow Zero-Touch Deployment (Live Test)

```bash
# Di komputer lokal — ubah kode, misalnya di web_dinamis/src/index.php
nano web_dinamis/src/index.php

# Commit & push
git add .
git commit -m "feat: update tampilan dashboard"
git push origin main

# GitHub Actions otomatis:
# ✅ Build image baru
# ✅ Push ke Docker Hub
# ✅ SSH ke EC2
# ✅ Pull image terbaru
# ✅ Restart container (zero downtime)
# ✅ Perubahan langsung terlihat di browser!
```

---

## 🐳 Penjelasan docker-compose.yml

| Service | Image | Port | Keterangan |
|---|---|---|---|
| `nginx` | `nginx:alpine` | `80:80` | Reverse proxy utama |
| `web_statis` | `hakim0901/web_statis:latest` | expose 80 | CV HTML |
| `web_dinamis` | `hakim0901/web_dinamis:latest` | expose 80 | PHP App + Login |
| `db` | `mariadb:10.11` | expose 3306 | Database (internal) |

**Fitur Docker Compose:**
- ✅ `depends_on` dengan `service_healthy` → urutan startup benar
- ✅ `healthcheck` pada MariaDB → PHP menunggu DB siap
- ✅ Volume persisten `db_data` → data tidak hilang saat restart
- ✅ Port 3306 **tidak** di-expose ke publik → aman
- ✅ Environment variables dari `.env` → credentials aman

---

## 🗄️ Auto-Seeding Database

File `web_dinamis/db/init.sql` di-mount ke `/docker-entrypoint-initdb.d/` pada container MariaDB. MariaDB secara otomatis menjalankan semua `.sql` di folder tersebut saat container **pertama kali** dibuat.

**Akun default yang ter-seed:**

| Username | Password | Role |
|---|---|---|
| `admin` | `admin123` | admin |
| `user1` | `user123` | user |
| `user2` | `user123` | user |

---

## 🔍 Penjelasan CI/CD Pipeline (Paths Filter)

Pipeline menggunakan **paths filter** sehingga:

- Mengubah file di `web_statis/` → hanya pipeline `web_statis.yml` yang jalan
- Mengubah file di `web_dinamis/` → hanya pipeline `web_dinamis.yml` yang jalan
- **Tidak memboroskan** GitHub Actions runner untuk service yang tidak berubah

```yaml
on:
  push:
    branches: [main]
    paths:
      - "web_statis/**"           # Hanya trigger jika file web statis berubah
```

---

## 📸 Bukti Screenshot

> *(Tambahkan screenshot setelah deployment)*

- [ ] Screenshot GitHub Actions — pipeline hijau ✅
- [ ] Screenshot Docker Hub — image ter-push ✅
- [ ] Screenshot browser — `http://13.251.225.181` (web statis) ✅
- [ ] Screenshot browser — `http://13.251.225.181/app` (login PHP) ✅
- [ ] Screenshot `docker compose ps` di EC2 ✅
- [ ] Screenshot Live Test — commit → pipeline → perubahan di browser ✅

---

## 🧪 Perintah Debugging di EC2

```bash
# Cek semua container
docker compose ps

# Lihat log real-time
docker compose logs -f

# Log spesifik service
docker compose logs web_dinamis
docker compose logs db

# Masuk ke container DB
docker exec -it mariadb mariadb -u appuser -p app_db

# Cek port mapping
docker compose port nginx 80

# Restart semua service
docker compose restart
```

---

## 🔒 Security Notes

- Database port `3306` **hanya** exposed di internal Docker network
- Credentials disimpan di GitHub Secrets & `.env` file (tidak di-commit)
- SSH key EC2 hanya ada di GitHub Secrets
- Password di-hash menggunakan MD5 (sesuai implementasi database seed dan autentikasi PHP)

---

*Dibuat untuk UAS Administrasi Server — UIN Sunan Gunung Djati Bandung, 2025*
