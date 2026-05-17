use App\Models\ProfilPerusahaan;

it('returns a successful response', function () {
    ProfilPerusahaan::create([
        'nama_perusahaan' => 'Dantie Sticker',
        'deskripsi' => 'Solusi stiker terbaik kendaraan Anda.',
        'alamat' => 'Jl. Raya Wrapping No. 77',
        'email' => 'info@dantiesticker.com',
        'nomor_telepon' => '081234567890',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
});
