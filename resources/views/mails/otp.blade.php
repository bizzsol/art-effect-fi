<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="padding: 40px 0;">
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background-color: #ffffff; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); padding: 40px;">
                <tr>
                    <td style="font-size: 18px; color: #333333;">
                        <p style="margin: 0 0 20px;">Hello,</p>
                        <p style="margin: 0 0 20px;">Your OTP code is:</p>
                        <p style="background-color: #f0f0f0; padding: 15px; font-size: 24px; font-weight: bold; text-align: center; border-radius: 4px; margin: 0 0 20px;">
                            {{ $otp }}
                        </p>
                        <p style="margin: 0 0 20px;">This code will expire in 5 minutes.</p>
                        <p style="color: #6c757d; font-size: 14px;">Thanks,<br>{{ config('app.name') }}</p>
                    </td>
                </tr>
            </table>
            <p style="color: #aaa; font-size: 12px; margin-top: 20px;">Please do not reply to this email.</p>
        </td>
    </tr>
</table>
</body>
</html>
