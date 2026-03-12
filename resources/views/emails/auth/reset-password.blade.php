<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body style="margin:0; padding:0; background-color:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color:#f4f7fb; margin:0; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td
                            style="background:linear-gradient(135deg, #7c3aed, #2563eb); padding:32px 24px; text-align:center;">
                            <h1 style="margin:0; font-size:28px; line-height:1.3; color:#ffffff; font-weight:700;">
                                Reset Password
                            </h1>
                            <p style="margin:10px 0 0; font-size:14px; color:#e5e7eb;">
                                {{ $appName }}
                            </p>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding:32px 24px;">
                            <p style="margin:0 0 16px; font-size:16px; line-height:1.7;">
                                Halo{{ isset($user->name) && $user->name ? ' ' . $user->name : '' }},
                            </p>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#4b5563;">
                                Kami menerima permintaan untuk mereset password akun Anda.
                                Klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin:28px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}"
                                            style="display:inline-block; padding:14px 28px; background:#2563eb; color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; border-radius:10px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 12px; font-size:14px; line-height:1.8; color:#6b7280;">
                                Link ini akan kedaluwarsa dalam <strong>{{ $expire }} menit</strong>.
                            </p>

                            <p style="margin:0 0 12px; font-size:14px; line-height:1.8; color:#6b7280;">
                                Jika Anda tidak merasa meminta reset password, abaikan email ini. Tidak ada perubahan
                                yang akan dilakukan pada akun Anda.
                            </p>

                            <div
                                style="margin-top:24px; padding:16px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px;">
                                <p style="margin:0 0 8px; font-size:13px; color:#6b7280;">
                                    Jika tombol di atas tidak bekerja, copy link berikut ke browser:
                                </p>
                                <p style="margin:0; word-break:break-all; font-size:13px; color:#2563eb;">
                                    <a href="{{ $resetUrl }}" style="color:#2563eb; text-decoration:none;">
                                        {{ $resetUrl }}
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td
                            style="padding:20px 24px; background:#f9fafb; border-top:1px solid #e5e7eb; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#9ca3af;">
                                © {{ date('Y') }} {{ $appName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
