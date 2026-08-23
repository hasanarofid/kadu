<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  body { margin:0; padding:0; background:#f5f0e8; font-family: Arial, sans-serif; }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(92,44,36,.12); }
  .header { background:linear-gradient(135deg,#5c2c24,#e98318); padding:32px 24px; text-align:center; }
  .header h1 { color:#fff; margin:0; font-size:22px; font-weight:900; letter-spacing:.5px; }
  .header p { color:rgba(255,255,255,.8); margin:6px 0 0; font-size:13px; }
  .body { padding:28px 28px 20px; }
  .body h2 { color:#5c2c24; font-size:18px; margin:0 0 12px; }
  .body p { color:#555; font-size:14px; line-height:1.7; margin:0 0 12px; }
  .info-box { background:#fffaf2; border-left:4px solid #e98318; border-radius:0 10px 10px 0; padding:14px 18px; margin:18px 0; }
  .info-box strong { color:#5c2c24; display:block; margin-bottom:4px; font-size:13px; }
  .info-box span { color:#666; font-size:13px; }
  .btn { display:inline-block; margin:14px 0 4px; padding:12px 28px; background:linear-gradient(90deg,#e98318,#5c2c24); color:#fff!important; border-radius:50px; font-weight:700; font-size:14px; text-decoration:none; }
  .footer { background:#f5f0e8; padding:16px 24px; text-align:center; font-size:11px; color:#9d7c64; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🕌 Mitra Syiar Baitullah</h1>
    <p>Program Syiar Umroh & Haji Terpercaya</p>
  </div>
  <div class="body">
    <h2>Ahlan Wa Sahlan, {{ $user->name }}! 🎉</h2>
    <p>Selamat bergabung sebagai <strong>Mitra Syiar Baitullah</strong>. Akun Anda telah berhasil dibuat dan siap digunakan.</p>
    <div class="info-box">
      <strong>📋 Data Akun Anda</strong>
      <span><b>Nama:</b> {{ $user->name }}</span><br>
      <span><b>Email:</b> {{ $user->email }}</span><br>
      @if($user->username)
      <span><b>Username:</b> @{{ $user->username }}</span>
      @endif
    </div>
    <p>Selamat bersyiar bersama kami. Semoga langkah Anda membawa keberkahan untuk diri sendiri dan sesama. Mulailah perjalanan Anda menuju Baitullah! 🤲</p>
    <a href="{{ url('/') }}" class="btn">Masuk ke Dashboard</a>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} Mitra Syiar Baitullah &mdash; {{ url('/') }}<br>
    Email ini dikirim otomatis, mohon jangan dibalas.
  </div>
</div>
</body>
</html>
