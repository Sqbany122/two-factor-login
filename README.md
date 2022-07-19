Project based on LARAVEL

Default hardcoded users:
- Anya (anya@test.com:secret) -> OTP enabled
- Brian (brian@test.com:secret) -> OTP disabled

OTP verification code (111111)


Login:
- app/Http/Controllers/Auth/AuthenticatedSessionController.php (@checkLoginCredentials, @twoFactorCheck)

Middleware for OTP:
- app/Http/Middleware/OTPVerify.php
