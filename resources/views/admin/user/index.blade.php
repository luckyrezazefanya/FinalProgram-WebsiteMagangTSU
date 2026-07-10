@extends('layouts.app')

@section('title', 'Data User')
@section('header_title', 'Database User')

@section('content')
    <div class="space-y-6" x-data="{ 
                addModal: false, 
                editModal: false, 
                editData: { id: '', name: '', email: '', role: '' },
                openEdit(user) {
                    this.editData.id = user.id;
                    this.editData.name = user.name;
                    this.editData.email = user.email;
                    this.editData.role = user.role;
                    this.editModal = true;
                }
            }">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header & Toolbar --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 fade-up">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Manajemen Data User</h3>
                <p class="text-sm text-gray-500">Kelola akun dan role pengguna</p>
            </div>

            <div class="flex gap-3">
                <button @click="addModal = true"
                    class="flex items-center justify-center gap-2 bg-tsu-teal hover:bg-tsu-teal-dark text-white px-5 py-3 rounded-2xl shadow-sm transition font-bold text-sm">
                    <span>➕</span>
                    <span>Tambah User</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden fade-up delay-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase">Nama</th>
                            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase">Email</th>
                            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase">Role</th>
                            <th class="px-6 py-5 text-xs font-bold text-gray-400 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-bold text-sm text-gray-800">{{ $u->name }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-700">{{ $u->email }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-700">
                                    <span class="bg-gray-100 px-3 py-1 rounded-full">{{ $u->role }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openEdit({{ json_encode($u) }})"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition"
                                            title="Edit">✏️</button>
                                        <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                                                title="Hapus">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Modal --}}
        <div x-show="addModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm" x-cloak>
            <div @click.outside="addModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah User</h3>
                <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                        <select name="role" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                            <option value="admin_super">Super Admin</option>
                            <option value="admin_fakultas">Admin Fakultas</option>
                            <option value="admin_universitas">Admin Universitas</option>
                            <option value="admin_prodi">Admin Prodi</option>
                            <option value="dosen">Dosen</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div class="flex gap-4 mt-6">
                        <button type="button" @click="addModal = false"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-tsu-teal text-white font-bold rounded-2xl hover:bg-tsu-teal-dark transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="editModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm" x-cloak>
            <div @click.outside="editModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Edit User</h3>
                <form :action="'{{ url('admin/user') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" x-model="editData.name" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editData.email" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Password (Kosongkan jika tidak
                            diubah)</label>
                        <input type="password" name="password"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                        <select name="role" x-model="editData.role" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-tsu-teal outline-none">
                            <option value="admin">Admin Fakultas</option>
                            <option value="admin_universitas">Admin Universitas</option>
                            <option value="admin_prodi">Admin Prodi</option>
                            <option value="dosen">Dosen</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div class="flex gap-4 mt-6">
                        <button type="button" @click="editModal = false"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection