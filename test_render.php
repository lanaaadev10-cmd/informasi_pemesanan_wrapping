<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

try {
    $pesanan = \App\Models\Pesanan::with(['details', 'form', 'pembayaran'])->first();
    if (!$pesanan) {
        echo "No pesanan found in DB, creating mock...\n";
        $pesanan = new \App\Models\Pesanan();
        $pesanan->kode_pesanan = 'TEST-001';
        $pesanan->total_harga = 500000;
    }
    
    $view = view('dashboard.customer.pesanan.show', ['pesanan' => $pesanan]);
    $html = $view->render();
    
    // Check for common error patterns
    $errors = [];
    if (preg_match('/Call to a member function|Trying to get property .* of non-object|Undefined variable|Undefined index/', $html, $m)) {
        $errors[] = $m[0];
    }
    
    if (empty($errors)) {
        echo "RENDER OK - no errors detected\n";
    } else {
        echo "ERRORS FOUND: " . implode(', ', $errors) . "\n";
    }
} catch (Throwable $e) {
    echo "RENDER ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
