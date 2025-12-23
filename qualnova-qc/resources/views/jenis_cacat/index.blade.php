<x-app-layout>
    <div class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div id="pageGuide" 
                 x-data="{ show: localStorage.getItem('hideJenisCacatGuide') !== 'true' }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform -translate-y-4" 
                 x-transition:enter-end="opacity-100 transform translate-y-0" 
                 class="mb-8 bg-indigo-50/50 border border-indigo-100 text-indigo-900 px-8 py-6 rounded-custom shadow-sm relative backdrop-blur-sm" 
                 x-cloak>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-black text-lg uppercase italic tracking-tight mb-2">Pusat Bantuan Klasifikasi</h3>
                        <ul class="space-y-1 text-sm font-bold opacity-75 list-none">
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span> Gunakan tombol <strong class="text-indigo-700 underline">+ TAMBAH DATA</strong> untuk input baru.</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span> Perubahan nama jenis akan berdampak pada seluruh database laporan.</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span> Hanya Super Admin yang diizinkan melakukan penghapusan data.</li>
                        </ul>
                    </div>
                </div>
                <button @click="show = false; localStorage.setItem('hideJenisCacatGuide', 'true')" class="absolute top-6 right-6 text-indigo-400 hover:text-indigo-600 transition-colors bg-white p-1 rounded-full shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="bg-white shadow-premium rounded-custom overflow-hidden border border-gray-50" 
                 x-data="{ openEdit: false, openCreate: false, selected: null, namaJenis: '' }">

                <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase">
                            Manajemen <span class="text-indigo-600">Jenis Cacat</span>
                        </h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="h-1.5 w-10 bg-indigo-600 rounded-full"></span>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">Master Database Produk</p>
                        </div>
                    </div>

                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc']))
                    <button @click="openCreate = true; namaJenis = ''" class="group relative inline-flex items-center px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl transition-all shadow-xl shadow-indigo-100 hover:-translate-y-1 active:translate-y-0 overflow-hidden">
                        <div class="absolute inset-0 w-2 bg-white/20 transition-all group-hover:w-full"></div>
                        <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="relative z-10 uppercase tracking-widest">Tambah Data</span>
                    </button>
                    @endif
                </div>

                <div class="p-10">
                    <div class="overflow-hidden rounded-3xl border border-gray-100 bg-gray-50/30 p-2">
                        <table class="min-w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-gray-400 uppercase text-[10px] font-black tracking-[0.25em]">
                                    <th class="px-8 py-4 text-left">Kode</th>
                                    <th class="px-8 py-4 text-left">Deskripsi Jenis Kerusakan</th>
                                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc']))
                                    <th class="px-8 py-4 text-right">Tindakan</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jenisCacat as $jenis)
                                <tr class="bg-white hover:bg-indigo-50/30 transition-all duration-300 group shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                    <td class="px-8 py-6 first:rounded-l-[1.5rem]">
                                        <span class="text-xs font-black text-indigo-500 bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-extrabold text-gray-700 group-hover:text-indigo-700 transition-colors italic uppercase">
                                            {{ $jenis->nama_jenis }}
                                        </span>
                                    </td>
                                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'petugas_qc']))
                                    <td class="px-8 py-6 text-right last:rounded-r-[1.5rem]">
                                        <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="selected = {{ $jenis->id_jenis }}; namaJenis = '{{ $jenis->nama_jenis }}'; openEdit = true" class="p-3 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>

                                            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi']))
                                            <form id="deleteForm-{{ $jenis->id_jenis }}" action="{{ route('jenis_cacat.destroy', $jenis->id_jenis) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $jenis->id_jenis }})" class="p-3 text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 animate-bounce">
                                                <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-black text-gray-300 uppercase tracking-[0.4em]">Belum Ada Data</h4>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="openCreate || openEdit" x-cloak class="fixed inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-md z-[100]" x-transition.opacity>
                    <div @click.away="openCreate = false; openEdit = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all p-12 border border-white" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        <div class="mb-10 text-center">
                            <h2 class="text-3xl font-black text-gray-900 uppercase italic leading-none" x-text="openCreate ? 'Tambah Baru' : 'Perbarui Data'"></h2>
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-3" x-text="openCreate ? 'Definisikan kategori kecacatan baru' : 'Sinkronisasi klasifikasi database'"></p>
                        </div>

                        <form :action="openCreate ? '{{ route('jenis_cacat.store') }}' : `/jenis_cacat/${selected}`" method="POST">
                            @csrf
                            <template x-if="openEdit">
                                <input type="hidden" name="_method" value="PUT">
                            </template>
                            
                            <div class="mb-10">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 ml-2">Nama Jenis Cacat</label>
                                <input type="text" name="nama_jenis" x-model="namaJenis" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-indigo-500 focus:bg-white rounded-2xl transition-all px-8 py-5 font-bold text-gray-700 placeholder-gray-300 shadow-inner focus:ring-0" placeholder="Misal: Noda Oli / Benang Putus" required>
                            </div>

                            <div class="flex flex-col gap-3">
                                <button type="submit" :class="openCreate ? 'bg-indigo-600 shadow-indigo-200' : 'bg-amber-500 shadow-amber-200'" class="w-full py-5 text-white text-xs font-black rounded-2xl shadow-xl transition-all uppercase tracking-[0.2em] hover:-translate-y-1 active:scale-95">
                                    Simpan Perubahan
                                </button>
                                <button type="button" @click="openCreate = false; openEdit = false" class="w-full py-4 text-[10px] font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">
                                    Batal & Kembali
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div> 
        </div>
    </div>

   
</x-app-layout>