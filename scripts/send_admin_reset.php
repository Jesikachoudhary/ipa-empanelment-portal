<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Password;
use App\Models\Admin;

$email = $argv[1] ?? 'admin@example.test';

$admin = Admin::where('email', $email)->first();
if (! $admin) {
    echo "Admin with email {$email} not found.\n";
    exit(1);
}

$token = Password::broker('admins')->createToken($admin);

$url = url(route('admin.password.reset', ['token' => $token, 'email' => $admin->email], false));

echo $url . "\n";
