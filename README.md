# Club Panahan Management System

Sistem manajemen berbasis web untuk Club Panahan yang mencakup
manajemen member, absensi latihan, booking sesi, dan dashboard,
dibangun menggunakan Laravel sebagai backend API.

## Background

Sebelum sistem ini dibangun, pencatatan kehadiran member dilakukan
secara manual menggunakan kertas, yang sering menimbulkan konflik
terkait sisa jatah latihan antara pihak klub dan orang tua member.

Sistem ini dikembangkan untuk menyediakan data absensi yang valid,
transparansi jatah latihan bulanan, serta mempermudah pengelolaan
member oleh admin dan pelatih.

## Objectives

- Menyediakan data absensi yang akurat dan tervalidasi
- Menampilkan sisa jatah latihan bulanan secara transparan
- Mempermudah admin dan pelatih dalam manajemen member dan sesi latihan
- Mampu mempromosikan club melalui website resmi

## Tech Stack

### Backend
- PHP (bahasa utama)
- Laravel (framework PHP)
- Composer (package manager PHP)
- Laravel Sanctum (authentication)
- Laravel Feature Test (PHPUnit)

### Database
- MySQL

### Frontend
- Blade (templating engine Laravel)
- HTML5, CSS3
- JavaScript (bisa vanilla atau library)
- Bootstrap (CSS framework, biasanya default Laravel UI)
- jQuery (sering dipakai untuk interaksi sederhana)
- Vue.js (kadang dipakai, tergantung kebutuhan; Laravel mendukung ini)
- npm (package manager JS, jika ada frontend build)

## User Roles

### Admin
- CRUD member, pelatih, paket latihan
- Validasi member baru
- Manajemen berita & prestasi

### Coach
- Membuat dan mengelola sesi latihan
- Validasi kehadiran member

### Member (Orang Tua / Member)
- Registrasi akun
- Mendaftarkan diri sendiri atau anak
- Booking sesi latihan
- Melihat dashboard absensi dan sisa kuota

## Features

- Authentication & Role-Based Access
- Parent–Child Member Management
- Training Session Booking
- Attendance Validation & Quota Management
- Member Dashboard
- Company Profile (News & Achievements)

## Use Case Diagram

UCD dapat dilihat pada:

- [Use Case Diagram](docs/ucd.png)

Deskripsi UCD tersedia pada folder berikut:

- [Use Case Description](docs/ucd-desc.md)

## Arsitektur Sistem

Arsitektur Sistem dapat dilihat pada:

- [Arsitektur Sistem](docs/arsitektur.png)

## API Documentation

Dokumentasi API tersedia pada folder berikut:

- [API Contract](docs/api-contract.md)

## Database Design

ERD dan desain database dapat dilihat pada:

- [Entity Relationship Diagram](docs/erd.png)

## Testing Documentation

Dokumentasi test tersedia pada folder berikut:

- [Testing](docs/testing.md)

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Auth](https://img.shields.io/badge/Auth-Laravel%20Sanctum-blueviolet)
![OAuth](https://img.shields.io/badge/OAuth-Google-red)
![Tests](https://img.shields.io/badge/tests-172%20passed-brightgreen)
![Assertions](https://img.shields.io/badge/assertions-664-green)
![Project](https://img.shields.io/badge/Project-Kerja%20Praktek-blue)
![Status](https://img.shields.io/badge/Status-Production%70Ready-success)