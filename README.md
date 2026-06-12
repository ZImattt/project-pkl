<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

---

# 📊 SISTEM INFORMASI PKL
### Praktik Kerja Lapangan — Laravel 10.x

---

## 📑 DAFTAR ISI

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Spesifikasi Teknis](#-spesifikasi-teknis)
- [Tata Cara Penggunaan](#-tata-cara-penggunaan)
  - [Persiapan Awal](#1-persiapan-awal)
  - [Instalasi](#2-instalasi)
  - [Konfigurasi](#3-konfigurasi)
  - [Menjalankan Aplikasi](#4-menjalankan-aplikasi)
- [Role & Hak Akses](#-role--hak-akses)
- [Struktur Folder](#-struktur-folder)
- [Screenshot](#-screenshot)
- [Lisensi](#-lisensi)

---

## 📖 TENTANG PROJECT

Sistem Informasi PKL adalah aplikasi web untuk mengelola kegiatan Praktik Kerja Lapangan secara terintegrasi. Dibangun menggunakan Laravel Framework yang ekspresif dan elegan.

### Keunggulan Laravel:

| Fitur | Deskripsi |
|-------|-----------|
| ⚡ **Routing Engine** | Simple dan cepat |
| 🗄️ **ORM Eloquent** | Database yang ekspresif dan intuitif |
| 🔐 **Authentication** | Sistem login built-in |
| 📦 **Migration** | Database agnostic schema |
| 🔄 **Queue System** | Robust background job processing |
| 📡 **Broadcasting** | Real-time event broadcasting |

---

## 🎯 FITUR UTAMA

- [x] **Multi-role Authentication** (Admin, Pembimbing, Siswa)
- [x] **Dashboard Analytics** — Statistik real-time
- [x] **Manajemen Data Siswa** — CRUD lengkap
- [x] **Manajemen Pembimbing** — Assign pembimbing ke siswa
- [x] **Logbook Kegiatan** — Pencatatan kegiatan harian
- [x] **Upload Dokumen** — Laporan, sertifikat, foto kegiatan
- [x] **Monitoring Progress** — Tracking status PKL
- [x] **Export Report** — PDF & Excel
- [x] **Notifikasi Sistem** — Email & in-app notification
- [x] **Responsive Design** — Mobile-friendly UI

---

## ⚙️ SPESIFIKASI TEKNIS

```json
{
  "framework": "Laravel 10.x",
  "php": "^8.1",
  "database": "MySQL 5.7+ / MariaDB 10.3+",
  "frontend": "Font Awesome",
  "javascript": "Alpine.js + Chart.js",
  "package_manager": "Composer + NPM",
  "server": "Apache 2.4+ / Nginx 1.18+"
}
