Perbaikan Error kemarin

1. Buat ulang .env yang ternyata hilang Note: gunakan database sebelumnya tanpa menggunakan perintah php artisan migrate, cuku gunakan php artisan key:generate. terakhir jalankan php artisan optimize:clear.
2. silahkan cek kembali apakah sudah aman? jika belum lanjut baca lagi bro

3. Hapus kode pada file bootstrap/app.php - Hapus middleware ShareSettingToViews pada baris 25-27, berikut kodenya: $middleware->web(append: [
    //\App\Http\Middleware\ShareSettingsToViews::class,
]);

cukup komen saja apabila nantinya diperlukan

4. Hapus atau komen saja pada file routes/web.php - hapus atau komen importnya use App\Http\Controllers\Admin\OfflineOrderController;

lanjut hapus atau komen saja pada baris 76-83, berikut kelengkapan kodenya: Route::middleware('role:admin')->prefix('admin-offline')->name('admin.offline.')->group(function () {
            Route::get('/orders', [OfflineOrderController::class, 'index'])->name('orders.index');
                 Route::get('/orders/create', [OfflineOrderController::class, 'create'])->name('orders.create');
                 Route::post('/orders', [OfflineOrderController::class, 'store'])->name('orders.store');
                 Route::get('/orders/{id}/edit', [OfflineOrderController::class, 'edit'])->name('orders.edit');
                 Route::put('/orders/{id}', [OfflineOrderController::class, 'update'])->name('orders.update');
                 Route::delete('/orders/{id}', [OfflineOrderController::class, 'destroy'])->name('orders.destroy');
             });

Note: Saran saya untuk code yang berubah cukup komen saja dan tidak perlu menghapusnya, dikarenakan jika di kemudian hari akan dipakai tinggal hapus komennya saja, itu aja sih dari gua cmiwiwww