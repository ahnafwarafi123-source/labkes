<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (! function_exists('get_kelompok_prefix')) {
    /**
     * Maps a given kelompok (group) name to an alphabetical prefix (A, B, C, D...).
     *
     * @param string $kategori The category of the sample (e.g., 'air_minum', 'makanan').
     * @param string $kelompok The group name (e.g., 'Fisika', 'Kimia Wajib').
     * @return string The alphabetical prefix (e.g., 'A. ', 'B. ') or an empty string if not found.
     */
    function get_kelompok_prefix(string $kategori, string $kelompok): string
    {
        $mapping = [];

        switch ($kategori) {
            case 'air_minum':
                $mapping = [
                    'Fisik' => 'A. ',
                    'Kimia' => 'B. ',
                    'Kimia Wajib' => 'B. ',
                    'Kimia Tambahan' => 'C. ',
                    'Kimia Khusus' => 'C. ',
                    'Mikrobiologi' => 'D. ',
                    'Mikrobiologi Wajib' => 'D',
                    'Mikrobiologi Tambahan' => 'E',
                ];
                break;
            case 'air_bersih':
                $mapping = [
                    'Fisik' => 'A. ',
                    'Kimia' => 'B. ',
                    'Mikrobiologi' => 'C. ',
                ];
                break;
            case 'makanan':
                $mapping = [
                    'Kimia' => 'A. ',
                    'Mikrobiologi' => 'B. ',
                    'Parasitologi' => 'C. ',
                ];
                break;
            case 'lingkungan':
                $mapping = [
                    'Pemeriksaan Lingkungan' => 'A. ', // Assuming this is the main group
                    // Add other specific groups if needed for lingkungan
                ];
                break;
            default:
                // Default mapping or handle unknown categories
                break;
        }

        return $mapping[$kelompok] ?? '';
    }
}

if (! function_exists('normaize_kelompok')) {
    /**
     * Normalisasi nama kelompok pemeriksaan agar seragam di semua tampilan (detail, input, print):
     * - Fisika / FISIKA => FISIK (atau Fisik jika non-uppercase)
     * - Bakteriologi / BAKTERIOLOGI => MIKROBIOLOGI (atau Mikrobiologi jika non-uppercase)
     * - Mikrobiologi Wajib / Tambahan
     *
     * @param string|null $kelompok
     * @param bool $uppercase Default true
     * @return string
     */
    function normalize_kelompok(?string $kelompok, bool $uppercase = true): string
    {
        $raw = trim((string)$kelompok);
        if ($raw === '') return '-';
        $map_upper = [
            'FISIKA'                 => 'FISIK',
            'FISIK'                  => 'FISIK',
            'BAKTERIOLOGI'           => 'MIKROBIOLOGI',
            'MIKROBIOLOGI'           => 'MIKROBIOLOGI',
            'BAKTERIOLOGI WAJIB'     => 'MIKROBIOLOGI WAJIB',
            'MIKROBIOLOGI WAJIB'     => 'MIKROBIOLOGI WAJIB',
            'BAKTERIOLOGI TAMBAHAN'  => 'MIKROBIOLOGI TAMBAHAN',
            'MIKROBIOLOGI TAMBAHAN'  => 'MIKROBIOLOGI TAMBAHAN',
            'KIMIA TAMBAHAN'         => 'KIMIA TAMBAHAN',
        ];
        $map_title = [
            'FISIKA'                 => 'Fisik',
            'FISIK'                  => 'Fisik',
            'BAKTERIOLOGI'           => 'Mikrobiologi',
            'MIKROBIOLOGI'           => 'Mikrobiologi',
            'BAKTERIOLOGI WAJIB'     => 'Mikrobiologi Wajib',
            'MIKROBIOLOGI WAJIB'     => 'Mikrobiologi Wajib',
            'BAKTERIOLOGI TAMBAHAN'  => 'Mikrobiologi Tambahan',
            'MIKROBIOLOGI TAMBAHAN'  => 'Mikrobiologi Tambahan',
            'KIMIA TAMBAHAN'         => 'Kimia Tambahan',
        ];
        $upper_key = strtoupper($raw);
        if ($uppercase) {
            return $map_upper[$upper_key] ?? $upper_key;
        } else {
            return $map_title[$upper_key] ?? $raw;
        }
    }
}

if (! function_exists('kelompok_tampil')) {
    /**
     * Nama kelompok untuk grouping di layar input & laporan.
     * Sampel non-Air-Minum (Air Bersih / Higiene-Sanitasi / Makanan / Lingkungan)
     * memakai label generik, sehingga parameter yang diambil dari master Air Minum
     * ("Kimia Wajib", "Fisik Wajib", dst.) ikut menyatu ke grup Fisik/Kimia/Mikrobiologi.
     * Laporan Air Minum tidak dinormalisasi agar tetap membedakan Wajib/Khusus/Tambahan.
     *
     * @param string|null $kelompok Nilai mentah dari kesmas_master_pemeriksaan.
     * @param string $jenis_sampel jenis_sampel pada permintaan.
     * @return string Kunci grouping.
     */
    function kelompok_tampil(?string $kelompok, string $jenis_sampel = ''): string
    {
        $k = trim((string) $kelompok);
        if ($k === '') return 'LAINNYA';
        if (stripos($jenis_sampel, 'air minum') !== false) return $k;
        return preg_replace('/\s+(Wajib|Khusus|Tambahan)$/i', '', $k) ?: $k;
    }
}

if (! function_exists('e_html')) {
    /**
     * Safely echoes a string by running it through htmlspecialchars,
     * with double encoding disabled to prevent issues with pre-escaped data.
     *
     * @param mixed $string The string to display.
     * @param string $default The default value to show if the string is null.
     * @return string The escaped HTML string.
     */
    function e_html($string, string $default = '-'): string
    {
        $string_to_display = $string ?? $default;
        return htmlspecialchars((string)$string_to_display, ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('terbilang')) {
    /**
     * Mengubah angka bulat menjadi kalimat Bahasa Indonesia.
     * Contoh: 35000 => "Tiga Puluh Lima Ribu".
     */
    function terbilang($angka): string
    {
        $angka = (int) floor(abs((float) $angka));
        if ($angka === 0) return 'Nol';

        $satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($angka < 12) return $satuan[$angka];
        if ($angka < 20) return terbilang($angka - 10) . ' Belas';
        if ($angka < 100) return trim(terbilang(intdiv($angka, 10)) . ' Puluh ' . ($angka % 10 ? terbilang($angka % 10) : ''));
        if ($angka < 200) return trim('Seratus ' . ($angka - 100 ? terbilang($angka - 100) : ''));
        if ($angka < 1000) return trim(terbilang(intdiv($angka, 100)) . ' Ratus ' . ($angka % 100 ? terbilang($angka % 100) : ''));
        if ($angka < 2000) return trim('Seribu ' . ($angka - 1000 ? terbilang($angka - 1000) : ''));

        foreach ([1000000000000 => 'Triliun', 1000000000 => 'Miliar', 1000000 => 'Juta', 1000 => 'Ribu'] as $nilai => $nama) {
            if ($angka >= $nilai) {
                $sisa = $angka % $nilai;
                return trim(terbilang(intdiv($angka, $nilai)) . ' ' . $nama . ' ' . ($sisa ? terbilang($sisa) : ''));
            }
        }
        return '';
    }
}
