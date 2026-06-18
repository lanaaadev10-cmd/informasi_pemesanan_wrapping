 <?php

/**
 * Helper: companyCms()
 * 
 * Fungsi ini membaca CMS content dari config, dengan opsi fallback ke database.
 * 
 * Penggunaan:
 *  - companyCms('profile.hero_heading')                    // Baca dari config['profile']['hero_heading']
 *  - companyCms('profile.hero_heading', 'default value')   // Dengan default fallback
 *  - companyCmsFromDb('hero_heading')                      // Fallback ke DB
 * 
 * PENTING: Setelah semua code diupdate untuk membaca dari config,
 * Anda bisa menghapus fallback ke DB dan langsung hapus kolom dari tabel.
 */

if (!function_exists('companyCms')) {
    /**
     * Baca CMS content dari config dengan notation path dot.
     * 
     * @param string $key       Path dengan dot notation (misal: 'profile.hero_heading')
     * @param mixed  $default   Nilai default jika key tidak ditemukan
     * @param bool   $fallbackToDb  Coba fallback ke database jika key tidak ada di config
     * 
     * @return mixed
     */
    function companyCms($key, $default = null, $fallbackToDb = true)
    {
        // 1. Coba ambil dari config profil_perusahan
        $value = config("profil_perusahan.{$key}");
        
        if ($value !== null) {
            return $value;
        }
        
        // 2. Jika fallbackToDb aktif dan value kosong, coba dari DB
        if ($fallbackToDb) {
            // Konversi path dot ke column name (misal: 'profile.hero_heading' -> 'hero_heading')
            $parts = explode('.', $key);
            $columnName = end($parts);
            return companyCmsFromDb($columnName, $default);
        }
        
        return $default;
    }
}

if (!function_exists('companyCmsFromDb')) {
    /**
     * Fallback: Baca langsung dari database untuk transitional period.
     * 
     * Setelah semua code diupdate, Anda bisa menghapus fungsi ini
     * dan drop kolom dari tabel profil_perusahaans.
     * 
     * @param string $column   Nama kolom di profil_perusahaans
     * @param mixed  $default  Nilai default
     * 
     * @return mixed
     */
    function companyCmsFromDb($column, $default = null)
    {
        try {
            $profil = \App\Models\ProfilPerusahaan::first();
            
            if (!$profil || !isset($profil->$column)) {
                return $default;
            }
            
            return $profil->$column;
        } catch (\Exception $e) {
            // Jika query error (misal kolom tidak ada), return default
            \Log::warning("companyCmsFromDb error: {$e->getMessage()}", ['column' => $column]);
            return $default;
        }
    }
}

if (!function_exists('companyCmsForAPI')) {
    /**
     * Untuk API responses: return CMS data dalam format array.
     * Berguna saat Anda ingin mengirim CMS config ke frontend via API.
     * 
     * @param string $section   Section yang diinginkan (misal: 'profile', 'home')
     * @return array
     */
    function companyCmsForAPI($section = null)
    {
        if ($section) {
            return config("profil_perusahan.{$section}");
        }
        
        return config('profil_perusahan');
    }
}
