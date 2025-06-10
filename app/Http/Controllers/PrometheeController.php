<?php

namespace App\Http\Controllers;

use App\Http\Helpers\PrometheeTes;

class PrometheeController extends Controller
{
    public function tesPerhitungan() {
        $criteria = [
            ['name' => 'User Count',        'type' => 'BENEFIT', 'weight' => 0.2],
            ['name' => 'Urgensi',           'type' => 'BENEFIT', 'weight' => 0.238],
            ['name' => 'Waktu',             'type' => 'BENEFIT', 'weight' => 0.12],
            ['name' => 'Biaya Anggaran',    'type' => 'COST',    'weight' => 0.24],
            ['name' => 'Tingkat Kerusakan', 'type' => 'BENEFIT', 'weight' => 0.202],
        ];

        // user count, urgensi, waktu, biaya, tingkat kerusakan
        $alternatives = [
            ['id' => 'Proyektor-A', 'values' => [5, 2, 1, 750000, 1]],
            ['id' => 'Proyektor-B', 'values' => [3, 2, 4, 800000, 2]],
            ['id' => 'Proyektor-C', 'values' => [9, 2, 3, 650000, 1]],
            ['id' => 'AC',          'values' => [5, 3, 5, 500000, 2]],
            ['id' => 'Papan Tulis', 'values' => [1, 3, 2, 1000000, 3]]
        ];

        $result = PrometheeTes::calculate($criteria, $alternatives);

        // dd($result);
        $activeMenu = 'tesSPK   ';

        $breadcrumb = (object) [
            'title' => 'Dashboard Sarana Prasarana',
            'list' => ['Home', 'dashboard']
        ];

        return view('sarpras.tes.index', ['result' => $result, 'activeMenu' => $activeMenu, 'breadcrumb' => $breadcrumb]);
    }
}
