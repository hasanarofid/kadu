<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  body { margin:0; padding:0; background:#f5f0e8; font-family: Arial, sans-serif; }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(92,44,36,.12); }
  .header { background:linear-gradient(135deg,#c0392b,#e98318); padding:28px 24px; text-align:center; }
  .header h1 { color:#fff; margin:0; font-size:20px; font-weight:900; }
  .header p { color:rgba(255,255,255,.8); margin:6px 0 0; font-size:13px; }
  .body { padding:28px 28px 20px; }
  .body h2 { color:#5c2c24; font-size:17px; margin:0 0 12px; }
  .body p { color:#555; font-size:14px; line-height:1.7; margin:0 0 12px; }
  .warn-box { background:#fff5f5; border-left:4px solid #c0392b; border-radius:0 10px 10px 0; padding:14px 18px; margin:18px 0; }
  .warn-box p { color:#8b0000; margin:0; font-size:13px; font-weight:600; }
  .btn { display:inline-block; margin:14px 0 4px; padding:12px 28px; background:linear-gradient(90deg,#e98318,#5c2c24); color:#fff!important; border-radius:50px; font-weight:700; font-size:14px; text-decoration:none; }
  .footer { background:#f5f0e8; padding:16px 24px; text-align:center; font-size:11px; color:#9d7c64; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🔐 Keamanan Akun</h1>
    <p>Mitra Syiar Baitullah</p>
  </div>
  <div class="body">
    <h2>Password Anda Telah Diubah</h2>
    <p>Halo <strong>{{ $user->name }}</strong>, kami menginformasikan bahwa password akun Mitra Syiar Baitullah Anda baru saja diperbarui pada <strong>{{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</strong>.</p>
    <div class="warn-box">
      <p>⚠️ Jika Anda TIDAK melakukan perubahan ini, segera hubungi admin kami dan amankan akun Anda secepatnya!</p>
    </div>
    <p>Jika ini adalah tindakan Anda sendiri, abaikan email ini. Keamanan akun Anda adalah prioritas kami.</p>
    <a href="{{ url('/') }}" class="btn">Masuk ke Dashboard</a>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} Mitra Syiar Baitullah &mdash; {{ url('/') }}<br>
    Email ini dikirim otomatis, mohon jangan dibalas.
  </div>
</div>
</body>
</html>
