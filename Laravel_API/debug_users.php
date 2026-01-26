<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Models\User;

echo "--- DEBUG USERS START ---\n";
$users = User::whereIn('id', [132, 134])->get();
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Type: {$user->type}\n";
}
echo "--- DEBUG USERS END ---\n";
