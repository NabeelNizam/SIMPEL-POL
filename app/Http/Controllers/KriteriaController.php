<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\KriteriaModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KriteriaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Bobot Prioritas Perbaikan',
            'list' => ['Home']
        ];

        $page = (object) [
            'title' => 'Daftar kriteria yang terdaftar dalam sistem'
        ];

        $activeMenu = 'bobot';

        $kriteria = Kriteria::all();

        return view('sarpras.bobot.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kriteria' => $kriteria]);
    }

    public function edit()
    {
        $kriteria = Kriteria::all();

        return view('sarpras.bobot.edit')->with('kriteria', $kriteria);
    }

    public function update(Request $request)
    {
        $kriteria = Kriteria::all();

        $rules = [];
        foreach ($kriteria as $k) {
            $rules["bobot_{$k->id_kriteria}"] = 'required|numeric|min:0|max:1';
        }

        $validated = $request->validate($rules);

        $total = collect($validated)->sum();

        if (round($total, 3) != 1.000) {
            return back()->withErrors(['total' => 'Jumlah total bobot harus sama dengan 1.'])->withInput();
        }

        foreach ($validated as $key => $value) {
            $id = str_replace('bobot_', '', $key);
            Kriteria::where('id_kriteria', $id)->update([
                'bobot' => $value
            ]);
        }

        return redirect()->back()->with('success', 'Data bobot berhasil diperbarui.');
    }
}
