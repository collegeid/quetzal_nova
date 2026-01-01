<x-app-layout>
    <div class="py-12 min-h-screen bg-[#f8fafc]" x-data="{ 
        openCreate: false, 
        openEdit: false, 
        selected: null, 
        formData: { name: '', username: '', email: '', role: '', password: '', password_confirmation: '' } 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 p-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl flex items-center justify-between shadow-sm animate-pulse">
                    <div class="flex items-center">
                        <div class="bg-emerald-500 p-1.5 rounded-full mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <span class="font-bold tracking-tight">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 font-black">✕</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-5 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-2xl shadow-sm">
                    <ul class="list-disc list-inside text-sm font-bold opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="pageGuide" x-data="{ show: localStorage.getItem('hideDataCacatGuide') !== 'true' }" x-show="show" 
                 x-transition class="mb-8 bg-blue-50/50 border border-blue-100 text-blue-900 px-8 py-6 rounded-custom shadow-sm relative backdrop-blur-sm" x-cloak>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" /></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-black text-lg uppercase italic tracking-tight mb-2 underline decoration-blue-300 decoration-2">Pusat Data Produksi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[11px] font-bold opacity-80 leading-relaxed uppercase tracking-wider">
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>+ Tambah Data:</strong> Input laporan cacat baru.</li>
                                <li><strong>Edit:</strong> Perubahan diizinkan pada status UNVALIDATED/REVISION.</li>
                                <li><strong>Preview:</strong> Detail laporan & bukti foto.</li>
                            </ul>
                            <div class="bg-blue-100/50 p-3 rounded-xl border border-blue-200 text-[10px]">
                                <h4 class="font-black mb-1 flex items-center gap-2"><span>🔑</span> INFO AKSES:</h4>
                                @php $role = Auth::user()->role; @endphp
                                {{ strtoupper(str_replace('_', ' ', $role)) }} - DAPAT MENGELOLA DATA SESUAI WEWENANG.
                            </div>
                        </div>
                    </div>
                </div>
                <button @click="show = false; localStorage.setItem('hideDataCacatGuide', 'true')" class="absolute top-6 right-6 text-blue-400 hover:text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="bg-white shadow-premium rounded-custom overflow-hidden border border-gray-50">
                <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase leading-none">
                            Data <span class="text-indigo-600">Kualitas Produksi</span>
                        </h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-2">Log Kerusakan Kain Harian</p>
                    </div>

                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'operator_produksi']))
                        <button onclick="openModal('createModal')" class="group relative inline-flex items-center px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl transition-all shadow-xl shadow-indigo-100 hover:-translate-y-1 active:translate-y-0 overflow-hidden uppercase tracking-widest">
                            <div class="absolute inset-0 w-2 bg-white/20 transition-all group-hover:w-full"></div>
                            <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            <span class="relative z-10">+ TAMBAH LAPORAN</span>
                        </button>
                    @endif
                </div>

                <div class="p-10">
                    <div class="overflow-x-auto rounded-3xl border border-gray-100 bg-gray-50/30 p-2">
                        <table class="min-w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-gray-400 uppercase text-[10px] font-black tracking-[0.25em]">
                                    <th class="px-6 py-4 text-left">Produk / Waktu</th>
                                    <th class="px-6 py-4 text-center">Identitas Unit</th>
                                    <th class="px-6 py-4 text-left">Klasifikasi</th>
                                    <th class="px-6 py-4 text-center">Status Verifikasi</th>
                                    <th class="px-6 py-4 text-right">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataCacat as $item)
                                <tr class="bg-white hover:bg-indigo-50/30 transition-all duration-300 group shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                    <td class="px-6 py-5 first:rounded-l-[1.5rem]">
                                        <div class="flex flex-col text-left">
                                            <span class="text-sm font-black text-gray-800 italic uppercase tracking-tighter">{{ $item->jenis_kain ?? 'N/A' }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 mt-1 italic tracking-widest">{{ $item->tanggal }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center text-left">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100 inline-block mx-auto mb-1 uppercase tracking-tighter">{{ $item->lokasi_mesin }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic text-left">Shift {{ $item->shift }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="text-sm font-extrabold text-gray-700 italic uppercase leading-none">{{ $item->jenisCacat->nama_jenis ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @php $status = $item->status_verifikasi; @endphp
                                        @if ($status == 1)
                                            <span class="px-4 py-1.5 rounded-xl border bg-emerald-50 text-emerald-600 border-emerald-100 text-[10px] font-black uppercase tracking-widest shadow-sm">VERIFIED</span>
                                        @elseif ($status == 2)
                                            <span class="px-4 py-1.5 rounded-xl border bg-blue-50 text-blue-600 border-blue-100 text-[10px] font-black uppercase tracking-widest shadow-sm">REVISION</span>
                                        @elseif ($status == 3)
                                            <span class="px-4 py-1.5 rounded-xl border bg-rose-50 text-rose-600 border-rose-100 text-[10px] font-black uppercase tracking-widest shadow-sm">REJECTED</span>
                                        @else
                                            <span class="px-4 py-1.5 rounded-xl border bg-amber-50 text-amber-600 border-amber-100 text-[10px] font-black uppercase tracking-widest shadow-sm">UNVALIDATED</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right last:rounded-r-[1.5rem]">
                                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @php
                                                $role = Auth::user()->role;
                                                $verified = ($item->status_verifikasi == 1);
                                            @endphp

                                            @if($status == 0 || $status == 2 || $status == 3)
                                                @if(in_array($role, ['operator_produksi', 'manager_produksi', 'super_admin']))
                                                    <button type="button" onclick='openEditModal(@json($item))' class="p-2.5 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                @elseif($role === 'petugas_qc')
                                                    <button type="button" onclick='openEditModal(@json($item))' class="p-2.5 text-emerald-500 bg-emerald-50 hover:bg-emerald-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </button>
                                                @endif
                                            @else
                                                <button type="button" onclick='openPreviewModal(@json($item))' class="p-2.5 text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white rounded-xl transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </button>
                                            @endif

                                            @if(in_array($role, ['manager_produksi', 'super_admin']))
                                                <form id="deleteForm-{{ $item->id_cacat }}" action="{{ route('data-cacat.destroy', $item->id_cacat) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete({{ $item->id_cacat }})" class="p-2.5 text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-xl transition-all shadow-sm">
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

            <div id="createModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-md z-[100]" x-transition.opacity>
                <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all p-10 border border-white">
                    <div class="mb-8 text-center">
                        <h3 class="text-2xl font-black text-gray-900 uppercase italic leading-none">Input <span class="text-indigo-600">Cacat Baru</span></h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-3">Laporan unit harian</p>
                    </div>
                    <form id="createForm" method="POST" action="{{ route('data-cacat.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest text-left">Tanggal</label>
                                    <input type="date" name="tanggal" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3.5 font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 transition-all" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest text-left">Shift</label>
                                    <input type="text" name="shift" placeholder="A/B/C" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3.5 font-bold text-gray-700" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest text-left">Lokasi Mesin</label>
                                    <input type="text" name="lokasi_mesin" placeholder="ID Mesin" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3.5 font-bold text-gray-700" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest text-left">Jenis Kain</label>
                                    <input type="text" name="jenis_kain" placeholder="Produk" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3.5 font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="text-left">
                                <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest text-left">Klasifikasi Cacat</label>
                                <select name="id_jenis" class="w-full bg-gray-50 border-none rounded-2xl px-5 py-3.5 font-bold text-gray-700" required>
                                    <option value="">Pilih Jenis Kerusakan</option>
                                    @foreach($jenisCacat as $j) <option value="{{ $j->id_jenis }}">{{ $j->nama_jenis }}</option> @endforeach
                                </select>
                            </div>
                            <div class="text-left">
                                <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest text-left">Unggah Bukti (Foto)</label>
                                <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 transition-all hover:border-indigo-400 group">
                                    <input type="file" name="foto_bukti" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" onchange="previewImage(event, 'createPreview')">
                                    <div class="text-center py-4">
                                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Klik atau seret file gambar kesini</p>
                                    </div>
                                    <img id="createPreview" src="#" class="hidden mt-4 rounded-xl border-4 border-white shadow-lg max-h-40 mx-auto">
                                </div>
                            </div>
                            <div class="flex gap-4 pt-4">
                                <button type="button" onclick="closeModal('createModal')" class="flex-1 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest transition-colors">Batal</button>
                                <button type="submit" class="flex-[2] py-4 bg-indigo-600 text-white text-[10px] font-black rounded-2xl shadow-xl uppercase tracking-widest hover:-translate-y-1 transition-all">Simpan Laporan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="previewModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-md z-[100]" x-transition.opacity>
                <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all p-10 border border-white text-center">
                    <h3 class="text-2xl font-black text-indigo-600 uppercase italic leading-none mb-1">Detail <span class="text-gray-900">Validasi Report</span></h3>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.4em] mb-8">Informasi Produk Cacat</p>
                    <div class="grid grid-cols-2 gap-4 text-left mb-8">
                        <div class="bg-gray-50 p-4 rounded-2xl"><p class="text-[9px] font-black text-indigo-400 uppercase mb-1">Tanggal</p><p class="text-sm font-black text-gray-700 uppercase italic" id="preview_tanggal"></p></div>
                        <div class="bg-gray-50 p-4 rounded-2xl"><p class="text-[9px] font-black text-indigo-400 uppercase mb-1">Shift</p><p class="text-sm font-black text-gray-700 uppercase italic" id="preview_shift"></p></div>
                        <div class="bg-gray-50 p-4 rounded-2xl"><p class="text-[9px] font-black text-indigo-400 uppercase mb-1">Mesin</p><p class="text-sm font-black text-gray-700 uppercase italic" id="preview_lokasi_mesin"></p></div>
                        <div class="bg-gray-50 p-4 rounded-2xl"><p class="text-[9px] font-black text-indigo-400 uppercase mb-1">Jenis Kain</p><p class="text-sm font-black text-gray-700 uppercase italic" id="preview_jenis_kain"></p></div>
                        <div class="col-span-2 bg-indigo-600 p-4 rounded-2xl shadow-lg shadow-indigo-100"><p class="text-[9px] font-black text-indigo-200 uppercase mb-1 tracking-widest">Jenis Kerusakan</p><p class="text-sm font-black text-white uppercase italic" id="preview_jenis_cacat"></p></div>
                    </div>
                    <div class="mb-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Foto Bukti Kerusakan:</p>
                        <img id="preview_foto" src="#" class="rounded-[1.5rem] border-4 border-gray-100 shadow-xl max-h-56 mx-auto object-cover transition-transform hover:scale-105 duration-500">
                        <span id="preview_foto_error" class="hidden text-[10px] font-black text-rose-500 italic mt-2">GAMBAR TIDAK DAPAT DITAMPILKAN (403/404)</span>
                    </div>
                    <button type="button" onclick="closeModal('previewModal')" class="w-full py-4 bg-gray-900 text-white text-[10px] font-black rounded-2xl uppercase tracking-[0.2em] shadow-xl hover:-translate-y-1 transition-all">Tutup Pratinjau</button>
                </div>
            </div>

            <div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/60 backdrop-blur-md z-[100]" x-transition.opacity>
                <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all p-10 border border-white">
                    <div class="mb-8 text-center">
                        <h3 class="text-2xl font-black text-gray-900 uppercase italic leading-none text-amber-500">Perbarui <span class="text-gray-900">Laporan Cacat</span></h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-3">Sesuaikan data operasional unit</p>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="space-y-4 text-left">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-left">
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest">Tanggal</label>
                                    <input type="date" id="edit_tanggal" name="tanggal" class="w-full bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700" required max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="text-left">
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest">Shift</label>
                                    <input type="text" id="edit_shift" name="shift" class="w-full bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-left">
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest">Lokasi Mesin</label>
                                    <input type="text" id="edit_lokasi_mesin" name="lokasi_mesin" class="w-full bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700" required>
                                </div>
                                <div class="text-left">
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest">Jenis Kain</label>
                                    <input type="text" id="edit_jenis_kain" name="jenis_kain" class="w-full bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="text-left">
                                <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest">Klasifikasi Cacat</label>
                                <select id="edit_id_jenis" name="id_jenis" class="w-full bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700" required>
                                    @foreach($jenisCacat as $j) <option value="{{ $j->id_jenis }}">{{ $j->nama_jenis }}</option> @endforeach
                                </select>
                            </div>
                            
                            <div id="image_edit_section" class="text-left">
                                <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-2 tracking-widest">Ganti Foto Bukti (Opsional)</label>
                                <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-4 transition-all hover:border-amber-400 group overflow-hidden bg-gray-50/50">
                                    <input type="file" name="foto_bukti" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" onchange="previewImage(event, 'editPreview')">
                                    
                                    <div class="flex items-center gap-4 relative z-10">
                                        <div class="w-16 h-16 rounded-xl bg-white border border-gray-100 flex items-center justify-center shadow-sm overflow-hidden flex-shrink-0">
                                            <svg id="edit_placeholder_icon" class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <img id="editPreview" src="#" class="hidden w-full h-full object-cover">
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[9px] font-black text-gray-800 uppercase tracking-widest mb-0.5">Pilih Gambar Baru</p>
                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-tight italic">Drag file kesini atau klik untuk menelusuri</p>
                                        </div>
                                    </div>
                                    
                                    <span id="editPreview_error" class="hidden text-[10px] font-black text-rose-500 italic mt-2 text-center block uppercase tracking-widest">Gagal memuat gambar (403/404)</span>
                                </div>
                            </div>

                            @if(in_array($role, ['petugas_qc', 'manager_produksi', 'super_admin']))
                                <div class="bg-indigo-50 p-5 rounded-[1.5rem] border border-indigo-100 mt-4">
                                    <label class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-1 italic tracking-widest">Otoritas Validasi QC</label>
                                    <select name="status_verifikasi" id="edit_status_verifikasi" class="w-full bg-white border-none rounded-xl p-3 font-black text-indigo-700 uppercase italic text-xs tracking-tighter shadow-sm" required>
                                        <option value="0">UNVALIDATED (ANTRIAN)</option>
                                        <option value="1">VERIFIED (SAH)</option>
                                        <option value="2">REVISION (REVISI)</option>
                                        <option value="3">REJECTED (TOLAK)</option>
                                    </select>
                                </div>
                            @endif

                            <div class="flex gap-4 pt-4">
                                <button type="button" onclick="closeModal('editModal')" class="flex-1 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest transition-colors">Batal</button>
                                <button type="submit" id="edit_submit_btn" class="flex-[2] py-4 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        // Fungsi Reset & Error Handler Gambar
        function resetImageState(imgId) {
            const img = document.getElementById(imgId);
            const errorMsg = document.getElementById(imgId + '_error');
            const placeholder = document.getElementById(imgId.includes('edit') ? 'edit_placeholder_icon' : '');
            
            if (img) img.classList.add('hidden');
            if (errorMsg) errorMsg.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');

            if (img) {
                img.onerror = function() {
                    this.classList.add('hidden');
                    if (errorMsg) errorMsg.classList.remove('hidden');
                    if (placeholder) placeholder.classList.remove('hidden');
                };
            }
        }

        function previewImage(event, previewId) {
            const preview = document.getElementById(previewId);
            const file = event.target.files[0];
            const placeholder = document.getElementById('edit_placeholder_icon');

            resetImageState(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = () => { 
                    preview.src = reader.result; 
                    preview.classList.remove('hidden'); 
                    if(placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else { preview.src = "#"; }
        }

        function openEditModal(data) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const role = "{{ Auth::user()->role }}";
            const verified = (data.status_verifikasi == 1);

            form.action = `/data-cacat/${data.id_cacat}`;
            document.getElementById('edit_tanggal').value = data.tanggal;
            document.getElementById('edit_shift').value = data.shift;
            document.getElementById('edit_lokasi_mesin').value = data.lokasi_mesin;
            document.getElementById('edit_jenis_kain').value = data.jenis_kain || '';
            document.getElementById('edit_id_jenis').value = data.id_jenis;

            if (document.getElementById('edit_status_verifikasi')) {
                document.getElementById('edit_status_verifikasi').value = data.status_verifikasi;
            }

            // Reset preview untuk edit
            resetImageState('editPreview');
            const placeholder = document.getElementById('edit_placeholder_icon');
            
            if (data.foto_bukti) {
                const preview = document.getElementById('editPreview');
                preview.src = `/storage/${data.foto_bukti}`;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }

            // Logic wewenang form fields
            const inputs = ['edit_tanggal', 'edit_shift', 'edit_lokasi_mesin', 'edit_jenis_kain', 'edit_id_jenis'];
            const editSection = document.getElementById('image_edit_section');
            
            inputs.forEach(id => {
                const el = document.getElementById(id);
                if (role === 'petugas_qc' || (verified && role !== 'super_admin')) {
                    el.disabled = true;
                    if(el.tagName === 'INPUT') el.readOnly = true;
                } else { el.disabled = false; el.readOnly = false; }
            });
            
            // Kontrol seksi gambar untuk QC
            if (role === 'petugas_qc') {
                if(editSection) editSection.classList.add('hidden');
            } else {
                if(editSection) editSection.classList.remove('hidden');
            }

            const submitBtn = document.getElementById('edit_submit_btn');
            if (role === 'petugas_qc') {
                submitBtn.textContent = 'KONFIRMASI STATUS QC';
                submitBtn.className = 'flex-[2] py-4 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 bg-emerald-500 shadow-emerald-100';
            } else {
                submitBtn.textContent = 'PERBARUI DATA LAPORAN';
                submitBtn.className = 'flex-[2] py-4 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 bg-amber-500 shadow-amber-100';
            }

            modal.classList.remove('hidden');
        }

        function openPreviewModal(data) {
            document.getElementById('preview_tanggal').textContent = data.tanggal;
            document.getElementById('preview_shift').textContent = data.shift;
            document.getElementById('preview_lokasi_mesin').textContent = data.lokasi_mesin;
            document.getElementById('preview_jenis_kain').textContent = data.jenis_kain || '-';
            document.getElementById('preview_jenis_cacat').textContent = data.jenis_cacat?.nama_jenis || '-';
            
            resetImageState('preview_foto');
            if (data.foto_bukti) {
                const img = document.getElementById('preview_foto');
                img.src = `/storage/${data.foto_bukti}`;
                img.classList.remove('hidden');
            }
            document.getElementById('previewModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>
