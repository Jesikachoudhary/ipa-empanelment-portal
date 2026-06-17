<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use App\Notifications\AdminRegistrationNotification;
use Illuminate\Support\Facades\Hash;

$timestamp = time();
$email = "test+{$timestamp}@example.test";

// Create admin
$admin = Admin::firstOrCreate(
    ['email' => $email],
    [
        'name' => 'Test Admin',
        'password' => Hash::make('secret'),
    ]
);

// generate and save code
$code = random_int(100000, 999999);
$admin->registration_code = (string) $code;
$admin->registration_code_sent_at = now();
$admin->save();

// send notification and capture errors
try {
    $admin->notify(new AdminRegistrationNotification($code));
    echo "Notification triggered for {$email}\n";
    echo "Code: {$code}\n";
    echo "Login URL: " . url(route('admin.login', [], false)) . "\n";
} catch (\Throwable $e) {
    echo "Notification failed: " . $e->getMessage() . "\n";
}
