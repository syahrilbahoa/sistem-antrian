@echo off
echo Membuat struktur folder audio...

REM Buat folder nomor dan loket jika belum ada
if not exist "public\audio\nomor" mkdir public\audio\nomor
if not exist "public\audio\loket" mkdir public\audio\loket

REM Salin file nomor ke folder nomor
copy "public\audio\0.mp3" "public\audio\nomor\0.mp3" 2>nul
copy "public\audio\1.mp3" "public\audio\nomor\1.mp3" 2>nul
copy "public\audio\2.mp3" "public\audio\nomor\2.mp3" 2>nul
copy "public\audio\3.mp3" "public\audio\nomor\3.mp3" 2>nul
copy "public\audio\4.mp3" "public\audio\nomor\4.mp3" 2>nul
copy "public\audio\5.mp3" "public\audio\nomor\5.mp3" 2>nul
copy "public\audio\6.mp3" "public\audio\nomor\6.mp3" 2>nul
copy "public\audio\7.mp3" "public\audio\nomor\7.mp3" 2>nul
copy "public\audio\8.mp3" "public\audio\nomor\8.mp3" 2>nul
copy "public\audio\9.mp3" "public\audio\nomor\9.mp3" 2>nul
copy "public\audio\10.mp3" "public\audio\nomor\10.mp3" 2>nul

REM Salin huruf untuk jenis antrian (A, B, C, D)
copy "public\audio\1.mp3" "public\audio\nomor\A.mp3" 2>nul
copy "public\audio\2.mp3" "public\audio\nomor\B.mp3" 2>nul
copy "public\audio\3.mp3" "public\audio\nomor\C.mp3" 2>nul
copy "public\audio\4.mp3" "public\audio\nomor\D.mp3" 2>nul

REM Salin karakter dash/hyphen
copy "public\audio\1.mp3" "public\audio\nomor\-.mp3" 2>nul

REM Salin file loket
copy "public\audio\1.mp3" "public\audio\loket\1.mp3" 2>nul
copy "public\audio\2.mp3" "public\audio\loket\2.mp3" 2>nul
copy "public\audio\3.mp3" "public\audio\loket\3.mp3" 2>nul
copy "public\audio\4.mp3" "public\audio\loket\4.mp3" 2>nul

REM Salin file tambahan untuk loket
copy "public\audio\loket.mp3" "public\audio\loket\loket.mp3" 2>nul

echo Struktur folder audio telah dibuat.
echo Pastikan file-file audio yang diperlukan tersedia di folder public\audio
pause