<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Password;
use App\Models\Admin;
use App\Notifications\AdminResetPasswordNotification;

$email = $argv[1] ?? 'admin@example.test';

$admin = Admin::where('email', $email)->first();
if (! $admin) {
    echo "Admin with email {$email} not found.\n";
    exit(1);
}

$token = Password::broker('admins')->createToken($admin);

// Send the actual notification which will use configured mailer
$admin->notify(new AdminResetPasswordNotification($token));

echo "Reset email sent to {$email}\n";
