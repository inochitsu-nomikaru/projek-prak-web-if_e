@echo off
chcp 65001 >nul
title Resto Icikiwir Gejesgejes - Server Tailscale
cls

echo     RESTO ICIKIWIR GEJESGEJES - WEB PEMESANAN SMARTPHONE
echo.

:: 1. Cek & Set PHP Executable
set "PHP_EXEC="
if exist "C:\xampp\php\php.exe" (
    set "PHP_EXEC=C:\xampp\php\php.exe"
) else (
    where php >nul 2>&1
    if not errorlevel 1 (
        set "PHP_EXEC=php"
    )
)

if "%PHP_EXEC%"=="" (
    echo [ERROR] PHP tidak ditemukan di C:\xampp\php\php.exe maupun sistem PATH!
    echo Silakan install atau jalankan XAMPP terlebih dahulu.
    pause
    exit /b 1
)

:: 2. Cek apakah MySQL berjalan
tasklist /FI "IMAGENAME eq mysqld.exe" 2>nul | find /I /N "mysqld.exe" >nul
if errorlevel 1 (
    echo [INFO] Menjalankan layanan MySQL...
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
        timeout /t 2 >nul
    ) else (
        echo [PERINGATAN] Pastikan MySQL di XAMPP Control Panel sudah AKTIF!
    )
) else (
    echo [OK] MySQL Service terdeteksi AKTIF.
)

:: 3. Deteksi Tailscale IP
set "TAILSCALE_IP="
for /f "tokens=*" %%i in ('tailscale ip -4 2^>nul') do (
    set "TAILSCALE_IP=%%i"
)

set "PORT=8080"

echo.
echo ================================================================
echo   SERVER BERHASIL DIAKTIFKAN!
echo ================================================================
echo.
echo   - Akses Komputer Lokal  : http://localhost:%PORT%
if not "%TAILSCALE_IP%"=="" (
    echo   - Akses HP via Tailscale: http://%TAILSCALE_IP%:%PORT%
    echo.
    echo   [TIPS] Hubungkan HP kamu ke Tailscale, lalu buka link di atas
    echo          di browser HP untuk langsung memesan!
) else (
    echo   - Tailscale IP tidak terdeteksi. Pastikan aplikasi Tailscale aktif!
)
echo.
echo   - Halaman Admin/Karyawan: http://localhost:%PORT%/login.php
echo     (Username: admin ^| Password: admin)
echo.
echo ================================================================
echo Tekan [Ctrl + C] untuk mematikan server.
echo ================================================================
echo.

:: Buka browser secara otomatis ke localhost
start http://localhost:%PORT%

:: Jalankan PHP built-in web server pada interface 0.0.0.0
"%PHP_EXEC%" -S 0.0.0.0:%PORT%
pause

