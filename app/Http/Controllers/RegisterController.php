<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register.register');
    }

    public function actionregister(Request $request)
    {
        $result = [];

        // Start database transaction
        DB::beginTransaction();

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users',
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8',
                'role' => 'required|string|max:255',
                'kode_user' => 'required|string|max:255',
            ]);

            $roleMapping = [
                'AD' => 'admin',
                'ST' => 'staff',
                'CS' => 'customer',
            ];

            $role = $request->role;

            // Konversi nilai role menggunakan mapping, apabila tidak ada ubah nilai jadi guest
            $roleName = $roleMapping[$role] ?? 'customer';

            // Buat request untuk generate_user_id
            $generateRequest = new Request(['role' => $role]);

            // Panggil metode generateUserId untuk mendapatkan user_id
            $userIdResponse = $this->generate_user_id($generateRequest);
            $userId = $userIdResponse->getData()->user_id; // Mengambil user_id dari response

            $user = User::create([
                'user_id' => $userId,
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'roles' => $roleName,
                'rcabang' => $request->cabang_list_reg,
                'user_kode' => $request->kode_user,
                'gudang' => $request->kode_gudang,
            ]);

            DB::commit();
            $result['pesan'] = 'Register Berhasil. Akun Anda sudah Aktif.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback the transaction in case of validation error
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            // Rollback the transaction in case of general error
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        return response()->json($result);
    }

    public function editregister(){
            // Fetch user data
            $user = User::find(session('id')); // Assuming session has user id
            return view('register.editregister', compact('user'));
    }

    public function updateregister(Request $request)
    {
        $result = [];
        DB::beginTransaction();
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8',
                'roles' => 'required|string|max:255',
            ]);

            $user = User::find(session('id'));

            // Update user details
            $user->email = $request->email;
            $user->name = $request->name;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->roles = $request->roles;
            $user->save();

            DB::commit();
            $result['pesan'] = 'Update Berhasil.';
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        return response()->json($result);
    }

    public function listregister()
    {
        return view('register.listregister');
    }

    public function filter_register(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();

        $allowedRoles = ['admin'];
        if (!in_array($user->roles, $allowedRoles)) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        // Jika pengguna memiliki peran yang sesuai, lanjutkan dengan query
        // Jika tidak ada penambahan spesifik perti CAST untuk date pakai ini -> $query = DB::table('users');
        $query = DB::table('users')
        ->select([
            'users.id',
            'users.user_id',
            'users.email',
            'users.name',
            'users.roles',
            'users.rcabang',
            'users.user_kode',
            'cabangs.nama as cabang_name',
            DB::raw('DATE(users.created_at) as created_at')
        ])
        ->leftJoin('cabangs', 'users.rcabang', '=', 'cabangs.cabang_id');

        if ($request->has('startDate') && $request->startDate) {
            $query->where('users.created_at', '>=', $request->startDate);
        }

        if ($request->has('endDate') && $request->endDate) {
            $query->where('users.created_at', '<=', $request->endDate);
        }

        if ($request->has('searchText') && $request->searchText) {
            $query->where(function($q) use ($request) {
                $q->where('users.email', 'like', '%' . $request->searchText . '%')
                ->orWhere('users.name', 'like', '%' . $request->searchText . '%')
                ->orWhere('users.user_id', 'like', '%' . $request->searchText . '%')
                ->orWhere('users.user_kode', 'like', '%' . $request->searchText . '%')
                ->orWhere('users.roles', 'like', '%' . $request->searchText . '%');
            });
        }

        $users = $query->get();

        return response()->json([
            'data' => $users
        ]);
    }


    public function edit_list_register($id){
        // Fetch user data
        $user = User::find($id);// Assuming session has user id
        return response()->json($user);
    }

    public function select_list_register_staff($id){
        // Cari berdasarkan kolom user_id
        $user = User::where('user_id', $id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update_list_register(Request $request){
        $result = [];
        DB::beginTransaction();
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8',
                'roles_list_reg' => 'required|string|max:255',
                'kode_user_list' => 'required|string|max:255',
            ]);

            $user = User::where('user_id', $request->input('id'))->first();

            if (!$user) {
                return response()->json(['pesan' => 'User not found.'], 404);
            }

            // Update user details
            $user->email = $request->email;
            $user->name = $request->name;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            // Update roles
            $roleMapping = [
                'AD' => 'admin',
                'ST' => 'staff',
                'CS' => 'customer',
            ];

            $role = $request->roles_list_reg;
            $roleName = $roleMapping[$role] ?? 'customer';

            // Cek apakah roles berbeda dari sebelumnya
            if($user->roles != $roleName){
                // Buat request untuk generate_user_id dengan role baru
                $generateRequest = new Request(['role' => $role]);

                // Panggil metode generateUserId untuk mendapatkan user_id baru
                $userIdResponse = $this->generate_user_id($generateRequest);
                $userId = $userIdResponse->getData()->user_id;

                // Update user_id dan roles
                $user->user_id = $userId;
                $user->roles = $roleName;
            }

            $user->rcabang = $request->cabang_list_reg;
            $user->user_kode = $request->kode_user_list;
            $user->gudang = $request->gudang_list_reg;
            $user->save();

            DB::commit();
            $result['pesan'] = 'Update Berhasil.';
            $result['user_id_baru'] = $user->user_id; // Tambahkan user_id baru jika berubah (untuk keperluan debugu, kalo tidak perlu bisa dihapus)
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            $result['pesan'] = 'Validation Error: ' . implode(', ', Arr::flatten($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            $result['pesan'] = 'Error: ' . $e->getMessage();
        }
        return response()->json($result);
    }

    public function delete_list_register($id){
        $user = User::find($id);

        if ($user){
            $user->delete();
            return response()->json(['success' => 'User berhasil dihapus']);
        }else{
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
    }

    // Fungsi Calback id_user
    public function generate_user_id(Request $request){
        $role = $request->input('role');

        $lastUser = DB::table('users')
            ->where('user_id', 'LIKE', $role . '%')
            ->orderBy('user_id', 'desc')
            ->first();

        if ($lastUser) {
            // 1. Ambil Data user_id dari Objek, 2.strlen menghitung panjang string, substr memotong string berarti disini dipotong 2 karena nilai strlen role =2 _> substr('AD0005', 2) maka didapat nilai 0005. lalu int mendapat nilai integer disini berarti bernilai 5
            $lastNumber = (int) substr($lastUser->user_id, strlen($role));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newUserId = $role . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return response()->json(['user_id' => $newUserId]);
    }

}
