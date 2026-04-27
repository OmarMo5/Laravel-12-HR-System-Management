<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'mostafakotb359@gmail.com';
$manager = User::where('email', $email)->first();

if (!$manager) {
    echo "Manager not found with email: $email\n";
    exit;
}

echo "\n| Name | User ID | Department | Role | Status |\n";
echo "|------|---------|------------|------|--------|\n";
foreach (User::where('status', 'Active')->orderBy('department')->get() as $u) {
    echo "| " . str_pad($u->name, 20) . " | " . str_pad($u->user_id, 8) . " | " . str_pad($u->department, 15) . " | " . str_pad($u->role_name, 10) . " | " . $u->status . " |\n";
}
