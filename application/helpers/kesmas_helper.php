<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_group_headers'))
{
    function get_group_headers($kategori_sampel)
    {
        $groups = [];
        switch (strtolower($kategori_sampel)) {
            case 'air minum':
                $groups = [
                    'A. Pemeriksaan Fisik',
                    'B. Pemeriksaan Kimia',
                    'C. Pemeriksaan Kimia Tambahan',
                    'D. Pemeriksaan Mikrobiologi Wajib',
                    'E. Pemeriksaan Mikrobiologi Tambahan'
                ];
                break;
            case 'air bersih':
                $groups = [
                    'A. Pemeriksaan Fisik',
                    'B. Pemeriksaan Kimia',
                    'C. Pemeriksaan Mikrobiologi'
                ];
                break;
            case 'makanan':
                $groups = [
                    'A. Pemeriksaan Kimia',
                    'B. Pemeriksaan Mikrobiologi',
                    'C. Pemeriksaan Parasitologi'
                ];
                break;
            case 'lingkungan':
                $groups = [
                    'A. Pemeriksaan Lingkungan/Pemeriksaan'
                ];
                break;
            default:
                $groups = [];
                break;
        }
        return $groups;
    }
}

if ( ! function_exists('kesmas_kelompok_title'))
{
    /**
     * Judul grup pemeriksaan untuk form. Nilai kelompok di database tidak diubah.
     */
    function kesmas_kelompok_title($kategori, $kelompok)
    {
        $prefixes = [
            'air_minum' => [
                'Fisik Wajib' => 'A',
                'Kimia Wajib' => 'B',
                'Kimia Khusus' => 'C',
                'Mikrobiologi Wajib' => 'D',
                'Mikrobiologi Tambahan' => 'E',
            ],
            'air_bersih' => [
                'Fisik' => 'A',
                'Kimia' => 'B',
                'Mikrobiologi' => 'C',
            ],
            'makanan' => [
                'Kimia' => 'A',
                'Mikrobiologi' => 'B',
                'Parasitologi' => 'C',
            ],
        ];

        $label = normalize_kelompok($kelompok, false);
        $prefix = $prefixes[$kategori][$kelompok] ?? '';
        return $prefix !== '' ? $prefix . '. ' . $label : $label;
    }
}

if ( ! function_exists('validation_errors_text'))
{
    function validation_errors_text($separator = "\n")
    {
        $html = validation_errors();
        if ($html === '')
        {
            return '';
        }

        $text = str_replace(['<p>', '</p>'], ['', $separator], $html);
        $lines = array_filter(array_map('trim', explode($separator, strip_tags($text))));

        return html_entity_decode(implode($separator, $lines), ENT_QUOTES, 'UTF-8');
    }
}
