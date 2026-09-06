<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isApproved ? 'Verifikasi Akun Owner Disetujui' : 'Pemberitahuan Status Verifikasi Akun Owner' }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; background-color: #F4F3FB; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #F4F3FB; padding: 32px 16px; }
        .main-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(69, 12, 63, 0.08); border: 1px solid #EAE6FA; }
        .header { background: linear-gradient(135deg, #450C3F 0%, #4D3EA3 100%); padding: 36px 32px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 26px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; }
        .header p { margin: 6px 0 0 0; font-size: 13px; color: #FFD2F4; letter-spacing: 0.3px; }
        .content { padding: 36px 32px; color: #2D3748; line-height: 1.6; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
        .badge-success { background-color: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
        .badge-danger { background-color: #FEF2F2; color: #B91C1C; border: 1px solid #FECACA; }
        .greeting { font-size: 18px; font-weight: 700; color: #1F2937; margin: 0 0 12px 0; }
        .info-card { background-color: #F9FAFB; border-radius: 12px; padding: 18px 20px; margin: 24px 0; border: 1px solid #F3F4F6; }
        .info-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; border-bottom: 1px dashed #E5E7EB; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6B7280; }
        .info-value { color: #111827; font-weight: 600; text-align: right; }
        .reason-box { background-color: #FFF5F5; border-left: 4px solid #EF4444; border-radius: 0 12px 12px 0; padding: 18px 20px; margin: 24px 0; }
        .reason-title { font-size: 14px; font-weight: 700; color: #991B1B; margin-bottom: 6px; }
        .reason-text { font-size: 14px; color: #7F1D1D; margin: 0; line-height: 1.5; white-space: pre-line; }
        .steps-box { background-color: #F8FAFC; border-radius: 12px; padding: 20px; margin: 24px 0; border: 1px solid #E2E8F0; }
        .steps-title { font-size: 14px; font-weight: 700; color: #334155; margin-bottom: 10px; }
        .steps-list { margin: 0; padding-left: 20px; font-size: 13.5px; color: #475569; }
        .steps-list li { margin-bottom: 8px; }
        .steps-list li:last-child { margin-bottom: 0; }
        .btn-wrapper { text-align: center; margin: 32px 0 16px 0; }
        .btn { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #4D3EA3 0%, #450C3F 100%); color: #ffffff !important; text-decoration: none; border-radius: 50px; font-size: 15px; font-weight: 700; box-shadow: 0 4px 14px rgba(77, 62, 163, 0.35); text-align: center; }
        .btn-secondary { background: #4B5563; box-shadow: 0 4px 14px rgba(75, 85, 99, 0.25); }
        .footer { background-color: #F9FAFB; padding: 24px 32px; text-align: center; border-top: 1px solid #F3F4F6; font-size: 12px; color: #9CA3AF; }
        .footer a { color: #4D3EA3; text-decoration: none; }
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 12px 8px; }
            .header, .content, .footer { padding: 24px 20px; }
            .btn { width: 100%; box-sizing: border-box; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-container">
            <!-- Header Brand -->
            <div class="header">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            <h1>LOKAVINO</h1>
                            <p>Platform Eksplorasi Wisata, Penginapan & Tempat Nongkrong</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Content -->
            <div class="content">
                @if($isApproved)
                    <!-- Badge Disetujui -->
                    <div style="text-align: center;">
                        <span class="badge badge-success">&#10003; Akun Terverifikasi</span>
                    </div>

                    <h2 class="greeting">Halo, {{ $owner->user->name ?? 'Mitra Lokavino' }}!</h2>
                    <p style="margin-top: 0; color: #4B5563; font-size: 15px;">
                        Kabar gembira! Tim Admin Lokavino telah selesai memeriksa dokumen dan data pendaftaran Anda. Akun Owner Anda telah <strong>berhasil disetujui dan diverifikasi</strong>.
                    </p>

                    <!-- Info Ringkasan -->
                    <div class="info-card">
                        <table width="100%" border="0" cellspacing="0" cellpadding="6">
                            <tr>
                                <td style="color: #6B7280; font-size: 13.5px;">Nama Pemilik:</td>
                                <td align="right" style="color: #111827; font-weight: 600; font-size: 13.5px;">{{ $owner->user->name }}</td>
                            </tr>
                            <tr>
                                <td style="color: #6B7280; font-size: 13.5px;">Nama Usaha:</td>
                                <td align="right" style="color: #111827; font-weight: 600; font-size: 13.5px;">{{ $owner->company_name ?: 'Perorangan' }}</td>
                            </tr>
                            <tr>
                                <td style="color: #6B7280; font-size: 13.5px;">Email Login:</td>
                                <td align="right" style="color: #111827; font-weight: 600; font-size: 13.5px;">{{ $owner->user->email }}</td>
                            </tr>
                            <tr>
                                <td style="color: #6B7280; font-size: 13.5px;">Status Akun:</td>
                                <td align="right" style="color: #059669; font-weight: 700; font-size: 13.5px;">Aktif & Terverifikasi</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Panduan Langkah Selanjutnya -->
                    <div class="steps-box">
                        <div class="steps-title">Langkah Selanjutnya untuk Memulai:</div>
                        <ul class="steps-list">
                            <li><strong>Masuk ke Dashboard:</strong> Buka tautan di bawah menggunakan email dan password yang Anda daftarkan.</li>
                            <li><strong>Daftarkan Listing Tempat Usaha:</strong> Tambahkan detail penginapan, tempat wisata, atau kafe/tempat nongkrong Anda lengkap dengan foto dan fasilitas.</li>
                            <li><strong>Publikasi & Promosi:</strong> Setelah listing disetujui, tempat usaha Anda akan dapat ditemukan oleh wisatawan di seluruh Indonesia.</li>
                        </ul>
                    </div>

                    <!-- Tombol Call to Action -->
                    <div class="btn-wrapper">
                        <a href="{{ $loginUrl }}" target="_blank" class="btn">
                            Masuk ke Dashboard Owner &rarr;
                        </a>
                    </div>
                @else
                    <!-- Badge Ditolak -->
                    <div style="text-align: center;">
                        <span class="badge badge-danger">&#10005; Pendaftaran Perlu Diperbaiki</span>
                    </div>

                    <h2 class="greeting">Halo, {{ $owner->user->name ?? 'Mitra Lokavino' }}!</h2>
                    <p style="margin-top: 0; color: #4B5563; font-size: 15px;">
                        Terima kasih atas minat Anda untuk bermitra dengan Lokavino. Setelah melalui proses peninjauan dokumen, dengan berat hati kami informasikan bahwa pengajuan verifikasi akun Owner Anda <strong>belum dapat disetujui saat ini</strong>.
                    </p>

                    <!-- Kotak Alasan Penolakan -->
                    <div class="reason-box">
                        <div class="reason-title">&#9888; Alasan Penolakan dari Admin:</div>
                        <p class="reason-text">{{ $reason ?? ($owner->account_rejection_reason ?? 'Data identitas atau dokumen yang diunggah belum memenuhi ketentuan verifikasi Lokavino.') }}</p>
                    </div>

                    <!-- Saran Perbaikan -->
                    <div class="steps-box">
                        <div class="steps-title">Saran Tindak Lanjut & Perbaikan:</div>
                        <ul class="steps-list">
                            <li>Pastikan foto KTP yang diunggah jelas, tidak terpotong, tidak silau/buram, dan NIK terbaca dengan baik.</li>
                            <li>Pastikan nomor WhatsApp yang dicantumkan aktif agar admin kami dapat melakukan konfirmasi langsung.</li>
                            <li>Silakan masuk ke halaman login Lokavino untuk memperbarui profil dan dokumen persyaratan Anda.</li>
                        </ul>
                    </div>

                    <!-- Tombol Call to Action -->
                    <div class="btn-wrapper">
                        <a href="{{ $loginUrl }}" target="_blank" class="btn btn-secondary">
                            Buka Halaman Login Owner &rarr;
                        </a>
                    </div>
                @endif

                <!-- Bantuan -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #F3F4F6; font-size: 13px; color: #6B7280; text-align: center;">
                    Jika ada pertanyaan lebih lanjut, Anda dapat membalas email ini atau menghubungi tim dukungan kami.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px 0;">Email ini dikirimkan otomatis oleh sistem verifikasi <strong>Lokavino</strong>.</p>
                <p style="margin: 0;">&copy; {{ date('Y') }} Lokavino. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
