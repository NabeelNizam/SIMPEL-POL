<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Halaman Profil',
            'list' => ['Home', 'Profil']
        ];

        $page = (object) [
            'title' => ''
        ];

        $activeMenu = '-';

        $profile = auth()->user();
        if ($profile->pegawai) {
            $identifier = $profile->pegawai->nip;
        } else {
            $identifier = $profile->mahasiswa->nim;
        }

        return view('profile.profil', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'profile' => $profile, 'identifier' => $identifier]);
    }
    public function edit_ajax()
    {
        $user = auth()->user();
        if ($user->pegawai) {
            $identifier = $user->pegawai->nip;
        } else {
            $identifier = $user->mahasiswa->nim;
        }


        $jurusan = Jurusan::all();

        return view('profile.edit', ['user' => $user, 'identifier' => $identifier, 'jurusan' => $jurusan]);
    }
    public function update_ajax(Request $request, string $id)
    {
        // Validasi input umum
        $rules = [
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email',
            'username' => 'required|string|max:50|unique:users,username,' . $id . ',id_user',
            'jurusan' => 'required|integer|exists:jurusan,id_jurusan',
            'fotoprofil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];

        // Cek apakah role Mahasiswa
        $isMahasiswa = strtolower(auth()->user()->role->nama_role) === 'mahasiswa';

        // Tambahkan validasi nim atau nip sesuai role
        if ($isMahasiswa) {
            $mahasiswa = Mahasiswa::where('id_user', $id)->first();
            $rules['identifier'] = 'required|string|min:10|max:50|unique:mahasiswa,nim,' . ($mahasiswa->id_mahasiswa ?? 'NULL') . ',id_mahasiswa';
        } else {
            $pegawai = Pegawai::where('id_user', $id)->first();
            $rules['identifier'] = 'required|string|min:10|max:50|unique:pegawai,nip,' . ($pegawai->id_pegawai ?? 'NULL') . ',id_pegawai';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Input Anda Gagal diValidasi.'
            ], 422);
        }

        try {
            // Ambil objek user
            $user = User::findOrFail($id); // biar aman kalau user tidak ditemukan

            // Update data user
            $user->nama = $request->nama;
            $user->no_hp = $request->telepon;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->id_jurusan = $request->jurusan;

            // Proses upload gambar
            if ($request->hasFile('fotoprofil')) {
                if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
                    @unlink(public_path($user->foto_profil));
                }
                $file = $request->file('fotoprofil');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/img/foto_profil', $filename, 'public');
                $user->foto_profil = 'storage/' . $path; // simpan path akses publik
            }

            // Simpan data user
            $user->save();

            // Update data mahasiswa atau pegawai
            if ($isMahasiswa) {
                Mahasiswa::where('id_user', $user->id_user)->update([
                    'nim' => $request->identifier
                ]);
            } else {
                Pegawai::where('id_user', $user->id_user)->update([
                    'nip' => $request->identifier
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function password()
    {
        return view('profile.password');
    }
    public function update_password(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'old_password' => ['required'],
                'confirm_password' => ['required'],
                'new_password' => ['required', 'min:6'],
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Input Anda Gagal divalidasi.'
                ], 422);
            }

            $user = User::findOrFail(auth()->user()->id_user);
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect()->back()->with('success', 'Password berhasil diperbarui.');
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password lama tidak sesuai.'
                ], 422);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
