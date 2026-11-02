@echo off
title Stock Kos - Week 6 OOP PHP Server
echo ========================================================
echo   STOK KOS - WEEK 6 (OBJECT-ORIENTED PHP)
echo   Pertemuan 6 Web Programming: Sprint 05
echo ========================================================
echo Membuka browser di http://localhost:8000 ...
start http://localhost:8000

if exist "C:\xampp\php\php.exe" (
    echo Menggunakan PHP dari C:\xampp\php\php.exe
    "C:\xampp\php\php.exe" -S localhost:8000
) else (
    echo Menggunakan PHP dari sistem PATH
    php -S localhost:8000
)

pause
