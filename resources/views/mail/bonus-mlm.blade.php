<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  body { margin:0; padding:0; background:#f5f0e8; font-family: Arial, sans-serif; }
  .wrap { max-width:560px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(92,44,36,.12); }
  .header { padding:28px 24px; text-align:center; }
  .header.sponsor  { background:linear-gradient(135deg,#1a6b3e,#27ae60); }
  .header.team     { background:linear-gradient(135deg,#1a4b6e,#2980b9); }
  .header.reward   { background:linear-gradient(135deg,#7d6008,#e98318); }
  .header.payment  { background:linear-gradient(135deg,#5c2c24,#e98318); }
  .header.withdrawal { background:linear-gradient(135deg,#2c3e50,#7f8c8d); }
  .header h1 { color:#fff; margin:0; font-size:20px; font-weight:900; }
  .header p  { color:rgba(255,255,255,.8); margin:6px 0 0; font-size:13px; }
  .body { padding:28px 28px 20px; }
  .body h2 { color:#5c2c24; font-size:17px; margin:0 0 12px; }
  .body p  { color:#555; font-size:14px; line-height:1.7; margin:0 0 12px; }
  .amount-box { text-align:center; background:#fffaf2; border:2px solid #e98318; border-radius:12px; padding:16px; margin:18px 0; }
  .amount-box .label { font-size:12px; color:#9d7c64; font-weight:600; text-transform:uppercase; margin-bottom:4px; }
  .amount-box .amount { font-size:28px; font-weight:900; color:#5c2c24; }
  .info-box { background:#fffaf2; border-left:4px solid #e98318; border-radius:0 10px 10px 0; padding:14px 18px; margin:16px 0; font-size:13px; color:#555; line-height:1.7; }
  .btn { display:inline-block; margin:14px 0 4px; padding:12px 28px; background:linear-gradient(90deg,#e98318,#5c2c24); color:#fff!important; border-radius:50px; font-weight:700; font-size:14px; text-decoration:none; }
  .footer { background:#f5f0e8; padding:16px 24px; text-align:center; font-size:11px; color:#9d7c64; }
</style>
</head>
<body>
<div class="wrap">

  @if($type === 'sponsor')
  <div class="header sponsor">
    <h1>💰 Bonus Ujroh Sponsor Masuk!</h1>
    <p>Mitra Syiar Baitullah</p>
  </div>
  <div class="body">
    <h2>Selamat, {{ $user->name }}!</h2>
    <p>Anda baru saja mendapatkan <strong>Bonus Ujroh Sponsor</strong> karena berhasil mensponsori Mitra baru dalam jaringan Anda.</p>
    <div class="amount-box">
      <div class="label">Bonus Diterima</div>
      <div class="amount">Rp {{ number_format($data['amount'] ?? 250000, 0, ',', '.') }}</div>
    </div>
    @if(!empty($data['new_member']))
    <div class="info-box"><b>Member Baru:</b> {{ $data['new_member'] }}</div>
    @endif
    <p>Bonus telah dikreditkan ke e-Wallet Syiar Anda. Sponsori 2 mitra = balik modal! 🚀</p>
    <a href="{{ url('/admin/keuangan') }}" class="btn">Lihat E-Wallet Saya</a>
  </div>

  @elseif($type === 'team')
  <div class="header team">
    <h1>🌐 Bonus Komisi Team!</h1>
    <p>Mitra Syiar Baitullah</p>
  </div>
  <div class="body">
    <h2>Komisi Team Masuk, {{ $user->name }}!</h2>
    <p>Jaringan Anda terus berkembang. Anda mendapat <strong>Bonus Komisi Team</strong> dari pertumbuhan mitra di level Team {{ $data['level'] ?? '' }}.</p>
    <div class="amount-box">
      <div class="label">Bonus Team Level {{ $data['level'] ?? '-' }}</div>
      <div class="amount">Rp {{ number_format($data['amount'] ?? 5000, 0, ',', '.') }}</div>
    </div>
    <a href="{{ url('/admin/aktivitas') }}" class="btn">Lihat Riwayat Bonus</a>
  </div>

  @elseif($type === 'reward')
  <div class="header reward">
    <h1>🏆 Reward Prestasi!</h1>
    <p>Mitra Syiar Baitullah</p>
  </div>
  <div class="body">
    <h2>Luar Biasa, {{ $user->name }}!</h2>
    <p>Anda telah mencapai milestone jaringan <strong>{{ number_format($data['mitra_count'] ?? 0, 0, ',', '.') }} Mitra</strong> dan berhak atas Reward Prestasi!</p>
    <div class="amount-box">
      <div class="label">Reward Prestasi</div>
      <div class="amount">Rp {{ number_format($data['amount'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <a href="{{ url('/admin/keuangan') }}" class="btn">Lihat Detail Reward</a>
  </div>

  @elseif($type === 'payment_verified')
  <div class="header payment">
    <h1>✅ Pembayaran Terverifikasi!</h1>
    <p>Mitra Syiar Baitullah</p>
  </div>
  <div class="body">
    <h2>Halo, {{ $user->name }}!</h2>
    <p>Pembayaran Voucher Anda telah <strong>diverifikasi oleh Admin</strong>. Voucher aktivasi siap digunakan untuk mendaftarkan Mitra baru.</p>
    <div class="info-box">
      <b>📋 Detail Order:</b><br>
      Jumlah Voucher: <b>{{ $data['voucher_qty'] ?? 1 }} Voucher</b><br>
      Total Bayar: <b>Rp {{ number_format($data['amount'] ?? 0, 0, ',', '.') }}</b><br>
      @if(!empty($data['voucher_codes']))
      Kode Voucher: <b>{{ implode(', ', $data['voucher_codes']) }}</b>
      @endif
    </div>
    <a href="{{ url('/admin/voucher-wallet') }}" class="btn">Lihat Voucher Saya</a>
  </div>

  @elseif($type === 'withdrawal')
  <div class="header withdrawal">
    <h1>💳 Update Penarikan Saldo</h1>
    <p>Mitra Syiar Baitullah</p>
  </div>
  <div class="body">
    <h2>Halo, {{ $user->name }}!</h2>
    <p>Status penarikan saldo Anda telah diperbarui.</p>
    <div class="info-box">
      <b>Status:</b> {{ $data['status_label'] ?? '-' }}<br>
      <b>Jumlah:</b> Rp {{ number_format($data['amount'] ?? 0, 0, ',', '.') }}<br>
      @if(!empty($data['notes']))
      <b>Catatan Admin:</b> {{ $data['notes'] }}
      @endif
    </div>
    <a href="{{ url('/admin/penarikan-saldo') }}" class="btn">Lihat Detail</a>
  </div>
  @endif

  <div class="footer">
    &copy; {{ date('Y') }} Mitra Syiar Baitullah &mdash; {{ url('/') }}<br>
    Email ini dikirim otomatis, mohon jangan dibalas.
  </div>
</div>
</body>
</html>
