<x-app-layout>
    <div class="py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
            
            <div id="pageGuide" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl shadow-sm relative">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 text-lg mb-1">Panduan Penggunaan Halaman</h3>
                        <ul class="list-disc list-inside text-sm leading-relaxed opacity-90">
                            <li>Halaman ini digunakan untuk mengelola Role dan Pengguna dalam Qual Nova.</li>
                            <li>Gunakan tombol <strong>+ TAMBAH USER</strong> untuk mendaftarkan akun baru ke sistem.</li>
                            <li>Tombol <strong>Edit</strong> digunakan untuk memperbarui informasi data pengguna.</li>
                            <li>Tombol <strong>Hapus</strong> hanya tersedia untuk Super Admin.</li>
                        </ul>
                        <div class="mt-4 border-t border-blue-200 pt-3">
                            <h4 class="font-bold text-blue-900 mb-1 flex items-center gap-2"><span>🔑</span> Hak Akses Berdasarkan Role:</h4>
                            <ul class="list-disc list-inside text-sm leading-relaxed opacity-90">
                                @php $role = Auth::user()->role; @endphp
                                @if($role === 'manager_produksi')
                                    <li><strong>Manager Produksi:</strong> Dapat menambah dan mengedit Data User.</li>
                                @elseif($role === 'super_admin')
                                    <li><strong>Super Admin:</strong> Memiliki akses penuh (Tambah, Edit, Hapus).</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <button @click="show = false" class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] overflow-hidden border border-gray-100" 
                 x-data="{ openCreate: false, openEdit: false, selected: null, formData: { name: '', username: '', email: '', role: '', whatsapp: '', password: '', password_confirmation: '' } }">

                <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase">
                            Daftar <span class="text-indigo-600">Pengguna</span>
                        </h2>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                            <span class="h-1 w-8 bg-indigo-600 rounded-full"></span>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Manajemen Akses Karyawan</p>
                        </div>
                    </div>

                    <button @click="openCreate = true" class="group relative inline-flex items-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl transition-all shadow-xl shadow-indigo-200 hover:-translate-y-1 active:translate-y-0 overflow-hidden">
                        <div class="absolute inset-0 w-3 bg-white/20 transition-all group-hover:w-full"></div>
                        <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                        <span class="relative z-10">TAMBAH USER</span>
                    </button>
                </div>

                <div class="p-10">
                    @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl flex items-center justify-between shadow-sm animate-pulse">
                        <div class="flex items-center">
                            <div class="bg-emerald-500 p-1 rounded-full mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="font-bold tracking-tight">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 font-bold">✕</button>
                    </div>
                    @endif

                    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-gray-50/30 p-2">
                        <table class="min-w-full divide-y divide-gray-200 border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                                    <th class="px-6 py-4 text-left">Info Akun</th>
                                    <th class="px-6 py-4 text-left">Username</th>
                                    <th class="px-6 py-4 text-left">Kontak (WA)</th>
                                    <th class="px-6 py-4 text-left">Role</th>
                                    <th class="px-6 py-4 text-right">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-transparent">
                                @foreach($users as $user)
                                <tr class="bg-white hover:bg-indigo-50/50 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                    <td class="px-6 py-5 first:rounded-l-2xl">
                                        <div class="text-sm font-extrabold text-gray-700 tracking-tight">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-gray-600 italic">
                                        {{ $user->username }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="text-[11px] font-black text-indigo-500 bg-indigo-50/50 px-3 py-1.5 rounded-xl border border-indigo-100/50">
                                            {{ $user->whatsapp }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($user->role === 'super_admin')
                                            <span class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-wider border border-rose-100 text-center block w-fit">Super Admin</span>
                                        @elseif($user->role === 'manager_produksi')
                                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider border border-blue-100 text-center block w-fit">Manager</span>
                                        @else
                                            <span class="px-3 py-1.5 rounded-xl bg-purple-50 text-purple-600 text-[10px] font-black uppercase tracking-wider border border-purple-100 text-center block w-fit">{{ str_replace('_', ' ', $user->role) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right last:rounded-r-2xl">
                                        <div class="flex justify-end gap-2">
                                            <button @click="openEdit = true; selected = {{ $user->id }}; formData = { name: '{{ $user->name }}', username: '{{ $user->username }}', email: '{{ $user->email }}', whatsapp: '{{ $user->whatsapp }}', role: '{{ $user->role }}', password: '', password_confirmation: '' }" 
                                                    class="p-2.5 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl transition-all duration-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>

                                            @if(Auth::user()->role === 'super_admin' && Auth::id() !== $user->id)
                                            <form id="deleteForm-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $user->id }})" class="p-2.5 text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="openCreate" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900/40 backdrop-blur-[8px] z-[100]" x-transition.opacity>
                    <div @click.away="openCreate = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden p-10 border border-gray-100 transform transition-all">
                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-gray-900 uppercase italic">Tambah User Baru</h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Daftarkan akses sistem baru</p>
                        </div>
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Nama</label>
                                    <input type="text" name="name" x-model="formData.name" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Username</label>
                                    <input type="text" name="username" x-model="formData.username" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Email</label>
                                    <input type="email" name="email" x-model="formData.email" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Whatsapp</label>
                                    <input type="text" name="whatsapp" x-model="formData.whatsapp" placeholder="08xxxx" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Role</label>
                                    <select name="role" x-model="formData.role" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                        <option value="petugas_qc">Petugas QC</option>
                                        <option value="operator_produksi">Operator Produksi</option>
                                        <option value="manager_produksi">Manager Produksi</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Password</label>
                                    <input type="password" name="password" x-model="formData.password" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Konfirmasi</label>
                                    <input type="password" name="password_confirmation" x-model="formData.password_confirmation" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" @click="openCreate = false" class="flex-1 px-6 py-4 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] bg-indigo-600 px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all uppercase tracking-[0.2em]">Simpan User</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="openEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900/40 backdrop-blur-[8px] z-[100]" x-transition.opacity>
                    <div @click.away="openEdit = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden p-10 border border-gray-100 transform transition-all">
                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-gray-900 uppercase italic">Edit Pengguna</h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Perbarui informasi akun</p>
                        </div>
                        <form :action="`/users/${selected}`" method="POST">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" x-model="formData.name" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Username</label>
                                    <input type="text" name="username" x-model="formData.username" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Whatsapp</label>
                                    <input type="text" name="whatsapp" x-model="formData.whatsapp" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Email</label>
                                    <input type="email" name="email" x-model="formData.email" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Role</label>
                                    <select name="role" x-model="formData.role" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700">
                                        <option value="petugas_qc">Petugas QC</option>
                                        <option value="operator_produksi">Operator Produksi</option>
                                        <option value="manager_produksi">Manager Produksi</option>
                                        <option value="super_admin">Super Admin</option>
                                    </select>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Sandi Baru</label>
                                    <input type="password" name="password" placeholder="Kosongkan jika tetap" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Konfirmasi</label>
                                    <input type="password" name="password_confirmation" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" @click="openEdit = false" class="flex-1 px-6 py-4 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] bg-amber-500 px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all uppercase tracking-[0.2em]">Update Akun</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            @else
            <div class="flex flex-col items-center justify-center min-h-[60vh] bg-white rounded-[2rem] shadow-xl p-10 text-center">
                <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h2 class="text-3xl font-black text-gray-900 uppercase italic">Akses Ditolak</h2>
                <p class="text-gray-500 font-bold mt-2">Anda tidak memiliki izin untuk mengelola pengguna.</p>
                <a href="{{ route('dashboard') }}" class="mt-8 px-8 py-3 bg-indigo-600 text-white rounded-2xl font-black text-xs tracking-widest uppercase hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">Kembali ke Dashboard</a>
            </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '<span class="text-xl font-black uppercase italic">Hapus User?</span>',
                html: '<p class="text-sm text-gray-500 font-bold uppercase tracking-tight">Akun ini akan dinonaktifkan permanen dari sistem.</p>',
                icon: 'warning',
                iconColor: '#f43f5e',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl p-8',
                    confirmButton: 'rounded-xl font-black px-8 py-3 tracking-widest text-xs',
                    cancelButton: 'rounded-xl font-black px-8 py-3 tracking-widest text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + id).submit();
                }
            })
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        input:focus, select:focus { outline: none !important; }
    </style>
</x-app-layout>