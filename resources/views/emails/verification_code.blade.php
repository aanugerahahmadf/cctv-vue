<!doctype html>
<html>
  <body style="font-family: Inter, Arial, sans-serif; background:#f6f7fb; padding:24px;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;padding:24px;border:1px solid #e5e7eb;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
        <img src="{{ url('/images/pertamina-logo.png') }}" alt="Pertamina" style="height:32px;"/>
        <div>
          <div style="font-size:12px;color:#6b7280;font-weight:600;">KILANG PERTAMINA INTERNASIONAL</div>
          <div style="font-size:10px;color:#9ca3af;">REFINERY UNIT VI BALONGAN</div>
        </div>
      </div>
      <h2 style="margin:0 0 12px 0;color:#111827;">Kode Verifikasi Anda</h2>
      <p style="margin:0 0 16px 0;color:#374151;">Gunakan kode berikut untuk menyelesaikan proses:</p>
      <div style="font-size:32px;letter-spacing:8px;font-weight:800;color:#4f46e5;text-align:center;margin:16px 0;">{{ $code }}</div>
      <p style="margin:0;color:#6b7280;font-size:12px;">Kode berlaku selama 10 menit. Jika Anda tidak meminta kode ini, abaikan email ini.</p>
    </div>
    <p style="text-align:center;color:#9ca3af;font-size:12px;margin-top:16px;">© 2024 Kilang Pertamina Internasional</p>
  </body>
</html>