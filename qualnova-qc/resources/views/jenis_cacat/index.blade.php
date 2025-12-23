<x-app-layout>
    <div class="py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div 
                id="pageGuide" 
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl shadow-sm relative"
            >
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" 
                                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 text-lg mb-1">Panduan Penggunaan Halaman</h3>
                        <ul class="list-disc list-inside text-sm leading-relaxed opacity-90">
                            <li>Halaman ini digunakan untuk mengelola Jenis kecacatan dalam Qual Nova.</li>
                            <li>Gunakan tombol <strong>+ Tambah Data</strong> untuk menambahkan Jenis Kerusakan baru.</li>
                            <li>Tombol <strong>Edit</strong> untuk memperbarui Daftar Kecacatan.</li>
                            <li>Tombol <strong>Hapus</strong> hanya tersedia untuk Super Admin.</li>
                        </ul>
                        
                        <div class="mt-4 border-t border-blue-200 pt-3">
                            <h4 class="font-bold text-blue-900 mb-1 flex items-center gap-2">
                                <span>🔑</span> Hak Akses Berdasarkan Role:
                            </h4>
                            <ul class="list-disc list-inside text-sm leading-relaxed opacity-90">
                                @php $role = Auth::user()->role; @endphp
                                @if($role === 'manager_produksi')
                                    <li><strong>Manager Produksi/Supervisor:</strong> Dapat menambah, mengedit Data.</li>
                                @elseif($role === 'super_admin')
                                    <li><strong>Super Admin:</strong> Memiliki akses penuh untuk semua tindakan di halaman ini.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <button 
                    @click="show = false; localStorage.setItem('hideGuide', true)" 
                    class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] overflow-hidden border border-gray-100" 
                 x-data="{ openEdit: false, openCreate: false, selected: null, namaJenis: '' }">
                
                <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase">
                            Manajemen <span class="text-indigo-600">Jenis Cacat</span>
                        </h2>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                            <span class="h-1 w-8 bg-indigo-600 rounded-full"></span>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Klasifikasi Database Produk</p>
                        </div>
                    </div>

                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc']))
                    <button @click="openCreate = true"
                            class="group relative inline-flex items-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl transition-all shadow-xl shadow-indigo-200 hover:-translate-y-1 active:translate-y-0 overflow-hidden">
                        <div class="absolute inset-0 w-3 bg-white/20 transition-all group-hover:w-full"></div>
                        <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="relative z-10">TAMBAH DATA</span>
                    </button>
                    @endif
                </div>

                <div class="p-10">
                    @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl flex items-center justify-between shadow-sm animate-pulse">
                        <div class="flex items-center">
                            <div class="bg-emerald-500 p-1 rounded-full mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
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
                                    <th class="px-6 py-4 text-left">#</th>
                                    <th class="px-6 py-4 text-left">Nama Jenis Kerusakan</th>
                                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc']))
                                    <th class="px-6 py-4 text-right">Opsi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-transparent">
                                @forelse ($jenisCacat as $jenis)
                                <tr class="bg-white hover:bg-indigo-50/50 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                    <td class="px-6 py-5 first:rounded-l-2xl">
                                        <span class="text-[11px] font-black text-indigo-500 bg-indigo-50/50 px-3 py-1.5 rounded-xl border border-indigo-100/50">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-extrabold text-gray-700 tracking-tight">
                                        {{ $jenis->nama_jenis }}
                                    </td>
                                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc']))
                                    <td class="px-6 py-5 text-right last:rounded-r-2xl">
                                        <div class="flex justify-end gap-2">
                                            <button @click="openEdit = true; selected = {{ $jenis->id_jenis }}; namaJenis = '{{ $jenis->nama_jenis }}'"
                                                    class="p-2.5 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl transition-all duration-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>

                                            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                                            <form id="deleteForm-{{ $jenis->id_jenis }}" action="{{ route('jenis_cacat.destroy', $jenis->id_jenis) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $jenis->id_jenis }})"
                                                        class="p-2.5 text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center bg-white rounded-3xl">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            </div>
                                            <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada data terdaftar</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <template x-if="openCreate || openEdit">
                    <div class="fixed inset-0 flex items-center justify-center bg-gray-900/40 backdrop-blur-[8px] z-[100]" x-transition.opacity>
                        <div @click.away="openCreate = false; openEdit = false" 
                             class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all p-10 border border-gray-100">
                            
                            <div class="mb-8">
                                <h2 class="text-2xl font-black text-gray-900 uppercase italic" x-text="openCreate ? 'Tambah Jenis Cacat' : 'Update Klasifikasi'"></h2>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1" x-text="openCreate ? 'Input data klasifikasi baru' : 'Sesuaikan nama jenis kerusakan'"></p>
                            </div>

                            <form :id="openCreate ? 'createForm' : 'editForm'" method="POST" :action="openCreate ? '{{ route('jenis_cacat.store') }}' : `/jenis_cacat/${selected}`">
                                @csrf
                                <template x-if="openEdit"><input type="hidden" name="_method" value="PUT"></template>
                                
                                <div class="mb-8">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-3">Nama Jenis Cacat</label>
                                    <input type="text" name="nama_jenis" x-model="namaJenis" 
                                           class="w-full bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all px-6 py-4 font-bold text-gray-700 placeholder-gray-300" 
                                           placeholder="Misal: Serat Kain Putus" required>
                                </div>

                                <div class="flex gap-4">
                                    <button type="button" @click="openCreate = false; openEdit = false" 
                                            class="flex-1 px-6 py-4 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">
                                        Batal
                                    </button>
                                    <button type="submit" 
                                            :class="openCreate ? 'bg-indigo-600 shadow-indigo-100 hover:bg-indigo-700' : 'bg-amber-500 shadow-amber-100 hover:bg-amber-600'"
                                            class="flex-[2] px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg transition-all uppercase tracking-[0.2em]">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '<span class="text-xl font-black uppercase italic">Hapus Data?</span>',
                html: '<p class="text-sm text-gray-500 font-bold uppercase tracking-tight">Data ini akan dihilangkan permanen dari cloud database.</p>',
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
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] { display: none !important; }

        /* Custom scrollbar untuk tabel jika dibutuhkan */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</x-app-layout>