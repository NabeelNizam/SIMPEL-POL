<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{
public function index(Request $request)
{
    $breadcrumb = (object) [
        'title' => 'Manajemen Role',
        'list' => ['Home', 'Manajemen Data Role']
    ];

    $page = (object) [
        'title' => 'Daftar Role yang terdaftar dalam sistem'
    ];

    $activeMenu = 'role';

    // Query untuk model Role
    $query = Role::query();

    // Filter berdasarkan pencarian
    if ($request->search) {
        $query->where('nama_role', 'like', "%{$request->search}%");
    }

    // Sorting
    $sortColumn = $request->sort_column ?? 'nama_role'; // Default sorting by nama_role
    $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
    $query->orderBy($sortColumn, $sortDirection);

    // Pagination
    $perPage = $request->input('per_page', 10); // default 10
    $roles = $query->paginate($perPage);

    $roles->appends(request()->query());

    // Jika permintaan adalah AJAX, kembalikan hanya tabel
    if ($request->ajax()) {
        $html = view('admin.role.role_table', compact('roles'))->render();
        return response()->json(['html' => $html]);
    }

    // Kembalikan view utama
    return view('admin.role.index', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'activeMenu' => $activeMenu,
        'roles' => $roles,
    ]);
}
    public function create_ajax()
    {
        return view('admin.role.create');
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'kode_role' => 'required|string|unique:roles,kode_role',
            'nama_role' => 'required|string|unique:roles,nama_role'
        ]);

        try {
            Role::create([
                'kode_role' => $request->kode_role,
                'nama_role' => $request->nama_role,
            ]);
            return redirect()->back()->with('success', 'Data Role berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal simpan Role: ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Gagal menyimpan data.']);
        }
    }

    public function confirm_ajax(Role $role)
    {
        return view('admin.role.confirm')->with([
            'role' => $role
        ]);
    }

    public function destroy_ajax(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('admin.role')->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['general' => 'Tindakan terlarang: Ada user yang terhubung dengan role ini.']);
        }
    }

    public function show_ajax(Role $role)
    {
        $role = Role::findOrFail($role->id_role);

        return view('admin.role.detail', ['role' => $role]);
    }


    public function edit_ajax(Role $role)
    {
        $role = Role::findOrFail($role->id_role);

        return view('admin.role.edit', ['role' => $role]);
    }

    public function update_ajax(Request $request, Role $role)
    {
            $rules = [
                'kode_role' => 'required|unique:roles,kode_role,' . $role->id_role . ',id_role',
                'nama_role' => 'required|unique:roles,nama_role,' . $role->id_role . ',id_role',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $role = Role::find($role->id_role);
            if (!$role) {
                return response()->json([
                    'status' => false,
                    'message' => 'Role tidak ditemukan',
                ]);
            }

            $role->update([
                'kode_role' => $request->kode_role,
                'nama_role' => $request->nama_role
            ]);

            // return response()->json([
            //     'status' => true,
            //     'message' => 'Data Role berhasil diperbarui',
            // ]);
            return redirect()->route('admin.role')->with('success', 'Data Role berhasil diperbarui');
    }

    public function import_ajax()
    {
        return view('admin.role.import');
    }

    public function import_file(Request $request)
    {
        //if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_input' => ['required', 'mimes:xlsx,xls,csv', 'max:2048']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_input');

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $data = $sheet->toArray(null, false, true, true);

        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'kode_role' => $value['A'],
                        'nama_role' => $value['B'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                Role::insertOrIgnore($insert);
            }

            return redirect()->back()->with('success', 'Data berhasil diimport.');
        } else {
            return redirect()->back()->withErrors(['general' => 'Data gagal diimport.']);
        }
    }

    public function set_sheet(){
         $role = Role::get();

        $filename = 'data_role_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $sheet = new Sheet(); // Pass the data and filename to the Sheet
        $sheet->title = 'Data Role User';
        $sheet->text = 'Berikut adalah daftar role yang ada di sistem.';
        $sheet->footer = 'Dibuat oleh Admin';
        $sheet->header = ['Kode Role', 'Nama Role'];

        $sheet->data = $role->map(function ($item) {
            return [
                'kode_role' => $item->kode_role,
                'nama_role' => $item->nama_role,
            ];
        })->toArray();
        $sheet->filename = $filename;

        return $sheet;
    }

     public function export_excel()
    {

        return $this->set_sheet()->toXls();
    }

    public function export_pdf()
    {
        return $this->set_sheet()->toPdf();
    }

    // public function export_pdf()
    // {

    //     $role = Role::get();

    //     $headers = ['Kode Role', 'Nama Role'];
    //     $data = $role->map(function ($item) {
    //         return [
    //             'kode_role' => $item->kode_role,
    //             'nama_role' => $item->nama_role
    //         ];
    //     })->toArray();
    //     $sheet = Sheet::make(
    //         [
    //             'title' => 'Data Role User',
    //             'text' => 'Berikut adalah Daftar Role User yang terdaftar di sistem.',
    //             'footer' => 'Dibuat oleh Nabeela',
    //             'header' => $headers,
    //             'data' => $data,
    //             'filename' => 'data_role' . date('Y-m-d_H-i-s'),
    //             'is_landscape' => false, // Mengatur orientasi kertas menjadi landscape
    //         ]
    //     );
    //     return $sheet->toPdf();
    // }

    // public function export_excel()
    // {

    //     $role = Role::get();

    //     $headers = ['Kode Role', 'Nama Role'];
    //     $data = $role->map(function ($item) {
    //         return [
    //             'kode_role' => $item->kode_role,
    //             'nama_role' => $item->nama_role,
    //         ];
    //     })->toArray();
    //     $sheet = Sheet::make(
    //         [
    //             'header' => $headers,
    //             'data' => $data,
    //             'filename' => 'data_role' . date('Y-m-d_H-i-s'),
    //         ]
    //     );
    //     return $sheet->toXls();
    // }

}
