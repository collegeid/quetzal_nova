<x-app-layout>
    <div class="py-12 min-h-screen" x-data="{ 
        openCreate: false, 
        openEdit: false, 
        formData: { id: '', name: '', username: '', email: '', whatsapp: '', role: 'petugas_qc', password: '', password_confirmation: '' } 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: '<span class="text-xl font-black uppercase italic text-rose-600">Gagal Memproses!</span>',
                            html: '<ul class="text-left text-sm font-bold text-gray-600 list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                            customClass: { popup: 'rounded-[2rem] shadow-2xl p-8 border-none' },
                            confirmButtonColor: '#f43f5e',
                            confirmButtonText: 'PERBAIKI DATA'
                        });
                    });
                </script>
            @endif

            <div id="pageGuide" x-data="{ show: localStorage.getItem('hideUserGuide') !== 'true' }" x-show="show" x-transition class="mb-8 bg-indigo-50/50 border border-indigo-100 text-indigo-900 px-8 py-6 rounded-custom shadow-sm relative backdrop-blur-sm" x-cloak>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-lg uppercase italic tracking-tight mb-2">Manajemen Personel</h3>
                        <p class="text-sm font-bold opacity-75">Kelola akun personel dan level akses sistem berdasarkan departemen.</p>
                    </div>
                </div>
                <button @click="show = false; localStorage.setItem('hideUserGuide', 'true')" class="absolute top-6 right-6 text-indigo-400 hover:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                <div class="bg-white shadow-premium rounded-custom overflow-hidden border border-gray-50">
                    
                    <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase leading-none">
                                Daftar <span class="text-indigo-600">Pengguna</span>
                            </h2>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-2">Otoritas & Akses Sistem</p>
                        </div>

                        <button @click="openCreate = true; openEdit = false; formData = { id: '', name: '', username: '', email: '', whatsapp: '', role: 'petugas_qc', password: '', password_confirmation: '' }" 
                                class="group relative inline-flex items-center px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl transition-all shadow-xl shadow-indigo-100 hover:-translate-y-1 active:translate-y-0 overflow-hidden uppercase tracking-widest">
                            <div class="absolute inset-0 w-2 bg-white/20 transition-all group-hover:w-full"></div>
                            <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            <span class="relative z-10">+ TAMBAH USER</span>
                        </button>
                    </div>

                    <div class="p-10">
                        <div class="overflow-x-auto rounded-3xl border border-gray-100 bg-gray-50/30 p-2">
                            <table class="min-w-full border-separate border-spacing-y-3">
                                <thead>
                                    <tr class="text-gray-400 uppercase text-[10px] font-black tracking-[0.25em]">
                                        <th class="px-8 py-4 text-left">Personel</th>
                                        <th class="px-8 py-4 text-center">Level Akses</th>
                                        <th class="px-8 py-4 text-right">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    @php
                                        // Pemetaan Warna Dinamis berdasarkan Level
                                        $roleStyle = match($user->role) {
                                            'super_admin' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'avatar' => 'bg-rose-100 text-rose-700'],
                                            'manager_produksi' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-100', 'avatar' => 'bg-indigo-100 text-indigo-700'],
                                            'petugas_qc' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'avatar' => 'bg-purple-100 text-purple-700'],
                                            'operator_produksi' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'avatar' => 'bg-emerald-100 text-emerald-700'],
                                            default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-100', 'avatar' => 'bg-gray-100 text-gray-700']
                                        };
                                    @endphp
                                    <tr class="bg-white hover:bg-gray-50/50 transition-all duration-300 group shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                        <td class="px-8 py-6 first:rounded-l-[1.5rem]">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl {{ $roleStyle['avatar'] }} flex items-center justify-center font-black text-sm shadow-sm">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-gray-900 uppercase italic tracking-tight">{{ $user->name }}</div>
                                                    <div class="text-[10px] font-bold text-gray-400 tracking-widest mt-1 italic">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="px-4 py-2 rounded-xl border {{ $roleStyle['bg'] }} {{ $roleStyle['text'] }} {{ $roleStyle['border'] }} text-[10px] font-black uppercase tracking-widest inline-block shadow-sm">
                                                {{ str_replace('_', ' ', $user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right last:rounded-r-[1.5rem]">
                                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="
                                                    formData = {
                                                        id: '{{ $user->id }}',
                                                        name: '{{ $user->name }}',
                                                        username: '{{ $user->username }}',
                                                        email: '{{ $user->email }}',
                                                        whatsapp: '{{ $user->whatsapp }}',
                                                        role: '{{ $user->role }}',
                                                        password: '',
                                                        password_confirmation: ''
                                                    };
                                                    openEdit = true;
                                                " class="p-3 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </button>

                                                @if(Auth::user()->role === 'super_admin' && Auth::id() !== $user->id)
                                                    <form id="deleteForm-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="button" onclick="confirmDelete({{ $user->id }})" class="p-3 text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-xl transition-all shadow-sm">
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
                </div>

                <div x-show="openCreate" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-md z-[100]" x-transition.opacity>
                    <div @click.away="openCreate = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all p-12 border border-white" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        <div class="mb-10 text-center">
                            <h2 class="text-3xl font-black text-gray-900 uppercase italic leading-none">Daftar User <span class="text-indigo-600">Baru</span></h2>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-4">Kredensial Otoritas Operasional</p>
                        </div>

                        <form id="createForm" method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 text-left">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">Nama Lengkap</label>
                                        <input type="text" name="name" x-model="formData.name" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">Username</label>
                                        <input type="text" name="username" x-model="formData.username" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">Email</label>
                                        <input type="email" name="email" x-model="formData.email" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700" required>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">WhatsApp</label>
                                        <input type="text" name="whatsapp" x-model="formData.whatsapp" placeholder="08xxxxxxx" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">Level Hak Akses</label>
                                        <select name="role" x-model="formData.role" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 appearance-none">
                                            <option value="petugas_qc">Petugas QC (Quality)</option>
                                            <option value="operator_produksi">Operator Produksi</option>
                                            <option value="manager_produksi">Manager Produksi</option>
                                            <option value="super_admin">Super Admin (IT)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">Password</label>
                                        <input type="password" name="password" x-model="formData.password" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700" required>
                                    </div>
                                </div>
                                <div class="col-span-full">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2 ml-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" x-model="formData.password_confirmation" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700" required>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <button type="submit" class="w-full py-5 bg-indigo-600 text-white text-xs font-black rounded-2xl shadow-xl shadow-indigo-100 uppercase tracking-[0.2em] hover:-translate-y-1 transition-all">SIMPAN AKSES BARU</button>
                                <button type="button" @click="openCreate = false" class="w-full py-4 text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest text-center">BATALKAN</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="openEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-md z-[100]" x-transition.opacity>
                    <div @click.away="openEdit = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all p-12 border border-white">
                        <div class="mb-10 text-center">
                            <h2 class="text-3xl font-black text-amber-500 uppercase italic leading-none">Update <span class="text-gray-900">User</span></h2>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-4">Sesuaikan kredensial otoritas personel</p>
                        </div>

                        <form id="editUserForm" :action="`/users/${formData.id}`" method="POST">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 text-left">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">Nama</label>
                                        <input type="text" name="name" x-model="formData.name" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">Username</label>
                                        <input type="text" name="username" x-model="formData.username" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">Email</label>
                                        <input type="email" name="email" x-model="formData.email" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner" required>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">WhatsApp</label>
                                        <input type="text" name="whatsapp" x-model="formData.whatsapp" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">Role</label>
                                        <select name="role" x-model="formData.role" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner">
                                            <option value="petugas_qc">QC Officer</option>
                                            <option value="operator_produksi">Operator Produksi</option>
                                            <option value="manager_produksi">Manager Produksi</option>
                                            <option value="super_admin">Super Admin</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">Password <span class="lowercase italic font-normal text-gray-400">(Isi jika ganti)</span></label>
                                        <input type="password" name="password" x-model="formData.password" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner">
                                    </div>
                                </div>
                                <div class="col-span-full">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-amber-600 mb-2 ml-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" x-model="formData.password_confirmation" class="w-full bg-gray-50 border-none focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-6 py-4 font-bold text-gray-700 shadow-inner">
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <button type="submit" class="w-full py-5 bg-amber-500 text-white text-xs font-black rounded-2xl shadow-xl shadow-amber-100 uppercase tracking-[0.2em] hover:-translate-y-1 transition-all">PERBARUI INFORMASI</button>
                                <button type="button" @click="openEdit = false" class="w-full py-4 text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest text-center">BATAL</button>
                            </div>
                        </form>
                    </div>
                </div>

            @else
                <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
                    <div class="w-32 h-32 bg-rose-50 rounded-custom flex items-center justify-center mb-8 shadow-inner animate-pulse">
                        <svg class="w-16 h-16 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h2 class="text-4xl font-black text-gray-900 uppercase italic tracking-tighter mb-4">Akses Terbatas</h2>
                    <a href="{{ route('dashboard') }}" class="px-10 py-4 bg-gray-900 text-white text-xs font-black rounded-2xl shadow-xl hover:-translate-y-1 transition-all uppercase tracking-widest">KEMBALI</a>
                </div>
            @endif

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const editUserForm = document.getElementById("editUserForm");
            if (editUserForm) {
                editUserForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        ...swalCustom,
                        title: '<span class="text-xl font-black italic uppercase text-amber-500">Update Akun?</span>',
                        text: "Informasi personel akan diperbarui di seluruh database.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#f59e0b",
                        cancelButtonColor: "#94a3b8",
                        confirmButtonText: "YA, UPDATE",
                        cancelButtonText: "BATAL",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            editUserForm.submit();
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>