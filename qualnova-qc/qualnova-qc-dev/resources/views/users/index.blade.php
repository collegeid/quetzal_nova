<x-app-layout>
    <div class="py-12" x-data="{ openCreate: false, openEdit: false, selected: null, formData: { name: '', username: '', email: '', role: '', password: '', password_confirmation: '' } }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Flash Message -->
            @if (session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show"
                    x-transition 
                    class="mb-4 flex items-center justify-between bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg shadow-sm"
                >
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2l4 -4m0 6a9 9 0 1 1 -9 -9a9 9 0 0 1 9 9z" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">✕</button>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Daftar User</h2>
                        @if(Auth::user()->role === 'super_admin')
                            <button @click="openCreate = true" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                + Tambah User
                            </button>
                        @endif
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b">Role</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-800">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800">{{ $user->username }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold">
                                            @if($user->role === 'super_admin')
                                                <span class="text-red-600">Super Admin</span>
                                            @elseif($user->role === 'admin')
                                                <span class="text-blue-600">Admin</span>
                                            @elseif($user->role === 'qc')
                                                <span class="text-purple-600">QC</span>
                                            @else
                                                <span class="text-gray-600">{{ ucfirst($user->role) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(Auth::user()->role === 'super_admin')
                                                <div class="flex justify-center space-x-2">
                                                    <button 
                                                        @click="
                                                            openEdit = true;
                                                            selected = {{ $user->id }};
                                                            formData = {
                                                                name: '{{ $user->name }}',
                                                                username: '{{ $user->username }}',
                                                                email: '{{ $user->email }}',
                                                                role: '{{ $user->role }}',
                                                                password: '',
                                                                password_confirmation: ''
                                                            };
                                                        "
                                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600">
                                                        Edit
                                                    </button>

                                                    @if(Auth::id() !== $user->id)
                                                        <form id="deleteForm-{{ $user->id }}" 
                                                            action="{{ route('users.destroy', $user->id) }}" 
                                                            method="POST" 
                                                            class="delete-user-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                    onclick="confirmDelete({{ $user->id }})"
                                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs rounded-md hover:bg-red-700">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif

                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

               <!-- Modal Create -->
<div x-show="openCreate" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div @click.away="openCreate = false" class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah User Baru</h2>
        <form id="createForm" x-ref="createForm"  method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Nama</label>
                    <input type="text" name="name" x-model="formData.name" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-gray-700">Username</label>
                    <input type="text" name="username" x-model="formData.username" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" x-model="formData.email" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-gray-700">Role</label>
                    <select name="role" x-model="formData.role" class="w-full border-gray-300 rounded-lg" required>
                        <option value="qc">QC</option>
                        <option value="Verifikator">Verifikator</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" x-model="formData.password" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" x-model="formData.password_confirmation" class="w-full border-gray-300 rounded-lg" required>
                </div>
            </div>

            <div class="flex justify-end mt-4 space-x-2">
                <button type="button" @click="openCreate = false; formData={name:'',username:'',email:'',role:'qc',password:'',password_confirmation:''}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

                <!-- Modal Edit -->
<div x-show="openEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div @click.away="openEdit = false" class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit User</h2>
        <form x-ref="editForm" @submit.prevent="
            if (formData.password !== formData.password_confirmation) {
                 Swal.fire({
            title: 'Periksa Data Kembali',
            text: 'Password dan Konfirmasi Password tidak sama!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tutup'
        });
                return;
            }
             Swal.fire({
            title: 'Update Data?',
            text: 'Pastikan data sudah benar sebelum disimpan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const userId = formData.id || selected;
                if (!userId) {
                    Swal.fire('Error', 'User ID tidak ditemukan.', 'error');
                    return;
                }

                // ✅ Set action ke /users/{id}
                $refs.editForm.action = `/users/${userId}`;
                $refs.editForm.submit();
            }
        });
        " method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Nama</label>
                    <input type="text" name="name" x-model="formData.name" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-gray-700">Username</label>
                    <input type="text" name="username" x-model="formData.username" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" x-model="formData.email" class="w-full border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-gray-700">Role</label>
                    <select name="role" x-model="formData.role" class="w-full border-gray-300 rounded-lg" required>
                        <option value="qc">QC</option>
                        <option value="Verifikator">Verifikator</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Password (opsional)</label>
                    <input type="password" name="password" x-model="formData.password" class="w-full border-gray-300 rounded-lg">
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" x-model="formData.password_confirmation" class="w-full border-gray-300 rounded-lg">
                </div>
            </div>

            <div class="flex justify-end mt-4 space-x-2">
                <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>

                </div>
            @else
                <div class="flex flex-col items-center justify-center min-h-[70vh]">
                    <h2 class="text-3xl font-bold text-red-600 mb-3">🚫 Akses Ditolak</h2>
                    <p class="text-gray-600 text-lg mb-6">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                        Kembali ke Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
