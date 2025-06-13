<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PrometheeTes;

class PrometheeController extends Controller
{
    public function tesPerhitungan() {
        $breadcrumb = (object) [
            'title' => 'Laporan Penugasan',
            'list' => ['Home', 'Laporan Penugasan']
        ];

        $activeMenu = '';

        $criteria = [
            ['name' => 'User Count',        'type' => 'BENEFIT', 'weight' => 0.2],
            ['name' => 'Urgensi',           'type' => 'BENEFIT', 'weight' => 0.182],
            ['name' => 'Waktu',             'type' => 'BENEFIT', 'weight' => 0.122],
            ['name' => 'Biaya Anggaran',    'type' => 'COST',    'weight' => 0.11],
            ['name' => 'Tingkat Kerusakan', 'type' => 'BENEFIT', 'weight' => 0.146],
            ['name' => 'Bobot Pelapor',     'type' => 'BENEFIT', 'weight' => 0.24],
        ];

        $alternatives = [
            [
                'id' => 'Proyektor Epson',     
                'values' => [5, 2, 1, 750000, 1, 10],
                'lokasi' => 'Ruang Teori 1, Lantai 5, Gedung Teknik Sipil - Teknologi Informasi',
                'tingkat_kerusakan' => 'RINGAN',
                'urgensi' => 'PENTING',
                'kategori' => 'Peralatan Elektronik',
                'bobot_pelapor' => 10
            ],
            [
                'id' => 'Komputer HP',         
                'values' => [3, 2, 1, 800000, 2, 9],
                'lokasi' => 'Ruang Teknisi, Lantai 5, Gedung Teknik Sipil - Teknologi Informasi',
                'tingkat_kerusakan' => 'SEDANG',
                'urgensi' => 'PENTING',
                'kategori' => 'Peralatan Teknologi',
                'bobot_pelapor' => 9
            ],
            [
                'id' => 'AC Panasonic 2 PK',   
                'values' => [2, 3, 2, 650000, 1, 6],
                'lokasi' => ' Laboratorium Komputer dan Jaringan 1 , Lantai 7, Gedung Teknik Sipil - Teknologi Informasi',
                'tingkat_kerusakan' => 'RINGAN',
                'urgensi' => 'DARURAT',
                'kategori' => 'Peralatan Elektronik',
                'bobot_pelapor' => 6
            ],
            [
                'id' => 'Meja Dosen',       
                'values' => [4, 3, 2, 500000, 2, 6],
                'lokasi' => 'Ruang Teori 3, Lantai 5, Gedung Teknik Sipil - Teknologi Informasi',
                'tingkat_kerusakan' => 'SEDANG',
                'urgensi' => 'DARURAT',
                'kategori' => 'Peralatan Elektronik',
                'bobot_pelapor' => 6
            ],
            [
                'id' => 'Lemari Lab',    
                'values' => [6, 1, 2, 1000000, 3, 8],
                'lokasi' => 'Laboratorium Proyek 4, Lantai 7, Gedung Teknik Sipil - Teknologi Informasi',
                'tingkat_kerusakan' => 'PARAH',
                'urgensi' => 'BIASA',
                'kategori' => 'Furnitur',
                'bobot_pelapor' => 8
            ]
        ];

        $result = PrometheeTes::calculate($criteria, $alternatives);

        // dd($result);

        return view('sarpras.tes.index', ['result' => $result, 'activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb]);
    }
}
