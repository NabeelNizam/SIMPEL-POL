<?php

namespace App\Http\Controllers;

use App\Http\Sheet\Sheet;
use App\Models\Aduan;
use App\Models\Biaya;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Periode;
use App\Models\Role;
use App\Models\UmpanBalik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Dashboard Sarana Prasarana',
            'list' => ['Home', 'dashboard']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'home';

        $periode = Periode::all();

        $totalLaporan = Aduan::count();
        $tertunda = Aduan::where('status', 'menunggu_diproses')->count();
        $dalamProses = Aduan::where('status', 'sedang_inspeksi')
        ->orWhere('status', 'sedang_diperbaiki')
        ->count();
        $selesai = Aduan::where('status', 'selesai')->count();

        // Data untuk grafik
        $umpanBalik = UmpanBalik::selectRaw('COUNT(*) as total, rating')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        $periodeId = $request->input('id_periode');
        $statusPerbaikan = Aduan::selectRaw('COUNT(*) as total, status')->groupBy('status')->get();
        $kategoriKerusakan = Aduan::with('fasilitas.kategori') // Ambil relasi kategori dari fasilitas
            ->get()
            ->groupBy(fn($item) => $item->fasilitas->kategori->nama_kategori ?? 'Tidak ada kategori') // Kelompokkan berdasarkan kategori
            ->map(function ($items, $kategori) {
                return [
                    'kategori' => $kategori,
                    'total' => $items->count(), // Hitung jumlah aduan dalam kategori
                ];
            })
            ->values();
        $trenKerusakanRaw = Aduan::selectRaw('COUNT(*) as total, MONTH(tanggal_aduan) as bulan')
        ->groupBy('bulan')
        ->orderBy('bulan', 'asc')
        ->get();

        // Pastikan semua bulan (1 hingga 12) ada dalam data
        $trenKerusakan = collect(range(1, 12))->map(function ($bulan) use ($trenKerusakanRaw) {
            $data = $trenKerusakanRaw->firstWhere('bulan', $bulan);
            return [
                'bulan' => $bulan,
                'total' => $data ? $data->total : 0, // Jika tidak ada data, set total ke 0
            ];
        });
        $trenAnggaranRaw = Biaya::selectRaw('SUM(besaran) as total, MONTH(inspeksi.tanggal_mulai) as bulan')
            ->join('inspeksi', 'biaya.id_inspeksi', '=', 'inspeksi.id_inspeksi') // Hubungkan tabel biaya dengan perbaikan
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // Pastikan semua bulan (1 hingga 12) ada dalam data
        $trenAnggaran = collect(range(1, 12))->map(function ($bulan) use ($trenAnggaranRaw) {
            $data = $trenAnggaranRaw->firstWhere('bulan', $bulan);
            return [
                'bulan' => $bulan,
                'total' => $data ? $data->total : 0, // Jika tidak ada data, set total ke 0
            ];
        });

        return view('admin.dashboard', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'periode',
            'totalLaporan',
            'tertunda',
            'dalamProses',
            'selesai',
            'umpanBalik',
            'statusPerbaikan',
            'kategoriKerusakan',
            'trenKerusakan',
            'trenAnggaran'
        ));
    }
    public function SOPDownload($filename)
    {
        $role = 'admin'; // Bisa juga ambil dari auth jika dinamis
        $filePath = "documents/{$role}/{$filename}";

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File SOP tidak ditemukan.');
        }

        // Cegah akses ke file berbahaya
        if (
            str_contains($filename, '..') ||
            str_contains($filename, '/') ||
            str_contains($filename, '\\')
        ) {
            abort(403, 'Akses tidak sah.');
        }

        // Jalankan download menggunakan response()->download()
        return response()->download(storage_path("app/public/{$filePath}"));
    }


    public function pengguna(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Manajemen Pengguna',
            'list' => ['Home', 'Manajemen Data Pengguna']
        ];

        $page = (object) [
            'title' => 'Daftar pengguna yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pengguna';

        $role = Role::all();
        $query = User::with('role');

        // Filter berdasarkan role
        if ($request->id_role) {
            $query->where('id_role', $request->id_role);
        }

        // Filter berdasarkan pencarian
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Sorting
        $sortColumn = $request->sort_column ?? 'nama'; // Default sorting by nama
        $sortDirection = $request->sort_direction ?? 'asc'; // Default sorting direction
        $query->orderBy($sortColumn, $sortDirection);

        $perPage = $request->input('per_page', 10); // default 10
        $users = $query->paginate($perPage);

        $users->appends(request()->query());


        if ($request->ajax()) {
            $html = view('admin.pengguna.user_table', compact('users'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.pengguna.user', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'role' => $role,
            'users' => $users,
        ]);
    }
    public function create_ajax()
    {
        $role = Role::all();
        $jurusan = Jurusan::all();
        return view('admin.pengguna.create', [
            'role' => $role,
            'jurusan' => $jurusan
        ]);
    }
    public function edit_ajax(User $user)
    {
        $role = Role::all();
        $jurusan = Jurusan::all();
        return view('admin.pengguna.edit', [
            'role' => $role,
            'jurusan' => $jurusan,
            'user' => $user
        ]);
    }
    public function update_ajax(Request $request, User $user)
    {
        try {

            $validator = Validator::make($request->all(), [
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'telepon' => [
                    'required',
                    'string',
                    'max:15',
                ],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id_user, 'id_user'),
                ],
                'username' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('users', 'username')->ignore($user->id_user, 'id_user'),
                ],
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                ],
                'jurusan' => [
                    'nullable',
                    'integer',
                    'exists:jurusan,id_jurusan',
                ],
                'role' => [
                    'nullable',
                    'integer',
                    'exists:roles,id_role',
                ],
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => 'weladalah ' . $validator->errors()
                ]);
                // return redirect()->route('admin.pengguna', $user)->withErrors($validator)->withInput();
            }

            $new_data = [
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'username' => $request->username,
                'id_jurusan' => $request->id_jurusan,
                'id_role' => $request->id_role
            ];
            if ($request->password) {
                $new_data['password'] = Hash::make($request->password);
            }
            $user->update($new_data);

            return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            // return redirect()->route('admin.pengguna')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    public function show_ajax(User $user)
    {
        $role = Role::all();
        $jurusan = Jurusan::all();
        return view('admin.pengguna.detail', [
            'role' => $role,
            'jurusan' => $jurusan,
            'user' => $user
        ]);
    }
    public function import_ajax()
    {
        return view('admin.pengguna.import');
    }
    public function import_file(Request $request)
    {
        try {
            // Validasi file yang diupload
            $rules = [
                'file_input' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->route('admin.pengguna')->with(
                    'errors', $validator->errors()
                );
            }
            $file = $request->file('file_input');

            $reader = IOFactory::createReader('Xlsx')->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            $data = $sheet->toArray(null, true, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'email' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'no_hp' => $value['D'],
                            'id_role' => Role::where('kode_role', $value['E'])->first()->id_role,
                            'id_jurusan' => Jurusan::where('kode_jurusan', $value['F'])->first()->id_jurusan,
                            'password' => 'password',
                            'foto_profil' => fake()->image(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                if (count($insert) > 0) {
                    User::insertOrIgnore($insert);
                }

                return redirect()->route('admin.pengguna')->with('success', 'Data berhasil diimport.');
            } else {
                return redirect()->route('admin.pengguna')->withErrors(['general' => 'Data gagal diimport.']);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.pengguna')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function store_ajax(Request $request)
    {
        // Validasi input umum
        $rules = [
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:50|unique:users,username',
            'jurusan' => 'required|integer|exists:jurusan,id_jurusan',
            'id_role' => 'required|integer|exists:roles,id_role',
            'password' => 'required|string|min:6',
        ];

        // Cek apakah role Mahasiswa
        // $isMahasiswa = strtolower(Role::find($request->id_role)->nama_role) === 'mahasiswa';
        $isMahasiswa = Role::find($request->id_role)->kode_role === 'MHS';

        // Tambahkan validasi nim atau nip sesuai role
        if ($isMahasiswa) {
            $rules['identifier'] = 'required|string|max:50|unique:mahasiswa,nim';
        } else {
            $rules['identifier'] = 'required|string|max:50|unique:pegawai,nip';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // return response()->json([
            //     'success' => false,
            //     'errors' => $validator->errors()
            // ], 422);
            return redirect()->route('admin.pengguna')->withErrors($validator)->withInput();
        }

        try {
            // Simpan user
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->telepon,
                'id_jurusan' => $request->jurusan,
                'id_role' => $request->id_role,
                'foto_profil' => fake()->image()
            ]);

            if ($isMahasiswa) {
                Mahasiswa::create([
                    'id_user' => $user->id_user,
                    'nim' => $request->identifier
                ]);
            } else {
                Pegawai::create([
                    'nip' => $request->identifier,
                    'id_user' => $user->id_user,
                ]);
            }

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Pengguna berhasil ditambahkan.'
            // ]);
            return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            // ], 500);
            return redirect()->route('admin.pengguna')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirm_ajax(User $user)
    {
        return view('admin.pengguna.confirm')->with([
            'user' => $user
        ]);
    }

    public function remove_ajax(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil dihapus.');
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Pengguna berhasil dihapus.'
            // ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    private function set_sheet()
    {
        $users = User::with('role')->get();
        $data = $users->map(function ($user) {
            return [
                'Nama' => $user->nama,
                'Email' => $user->email,
                'Username' => $user->username,
                'Telepon' => $user->no_hp,
                'Role' => $user->role ? $user->role->nama_role : 'Tidak ada',
                'Jurusan' => $user->jurusan ? $user->jurusan->nama_jurusan : 'Tidak ada',
            ];
        })->toArray();
        $headers = [
            'Nama',
            'Email',
            'Username',
            'Telepon',
            'Role',
            'Jurusan'
        ];
        $sheet = Sheet::make(
            [
                'data' => $data,
                'headers' => $headers,
                'title' => 'Daftar Pengguna',
                'filename' => 'daftar_pengguna_' . now()->format('Ymd_His') . '.xlsx',
            ]
        );
        return $sheet;
    }
    public function export_excel()
    {
        $sheet = $this->set_sheet();
        return $sheet->toXls();
    }
    public function export_pdf()
    {
        $sheet = $this->set_sheet();
        return $sheet->toPdf();
    }
}
