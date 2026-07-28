<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi Grownesia</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0;">
        <h2 style="color: #333333; margin-top: 0;">Halo, {{ $name }}</h2>
        <p style="color: #555555; line-height: 1.5;">
            Terima kasih telah mendaftar di Grownesia. Kode verifikasi akun Anda adalah:
        </p>
        
        <div style="text-align: center; margin: 25px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #1a56db; background: #f0f4ff; padding: 12px 24px; border-radius: 6px; display: inline-block;">
                {{ $otpCode }}
            </span>
        </div>
        
        <p style="color: #666666; font-size: 14px;">
            Kode ini berlaku selama 15 menit. Silakan masukkan kode ini pada halaman verifikasi.
        </p>
        <p style="color: #888888; font-size: 13px; margin-bottom: 0;">
            Jika Anda tidak merasa mendaftar, abaikan email ini.
        </p>
    </div>
</body>
</html>
