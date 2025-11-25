<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$count = DB::table('penilaian_penguji')->count();
echo "Total rows in penilaian_penguji: $count\n";

if ($count > 0) {
    echo "\nData in penilaian_penguji:\n";
    $data = DB::table('penilaian_penguji')->get();
    foreach ($data as $row) {
        echo json_encode($row) . "\n";
    }
}
