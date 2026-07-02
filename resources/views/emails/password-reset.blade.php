@component('emails.layout', [
    'subject' => $subject ?? 'Password Reset - Piyari Family',
    'heading' => $heading ?? 'Password Reset',
    'logoUrl' => $logoUrl,
    'greeting' => 'Dear ' . ($userName ?? 'User') . ',',
])
    <p style="margin:0 0 20px;">{{ $messageLine ?? 'We received a request to reset your password. Click the button below to set a new password:' }}</p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding:10px 0 20px;">
                <a href="{{ $resetUrl }}" style="display:inline-block;background-color:#6E0016;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:8px;font-weight:bold;">Reset Password</a>
            </td>
        </tr>
    </table>
    <p style="margin:0;color:#666666;font-size:13px;">If you did not request a password reset, you can safely ignore this email.</p>
@endcomponent
