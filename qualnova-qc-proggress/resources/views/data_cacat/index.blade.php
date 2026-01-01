<x-app-layout>
    <div class="py-12 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div id="pageGuide" x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl shadow-sm relative">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-900 text-lg mb-1">Panduan Penggunaan Halaman</h3>
                        <ul class="list-disc list-inside text-sm leading-relaxed opacity-90">
                            <li>Halaman ini digunakan untuk mengelola dan memverifikasi data cacat produksi dalam Qual Nova.</li>
                            <li>Gunakan tombol <strong>+ TAMBAH DATA</strong> untuk menambahkan laporan baru.</li>
                            <li>Tombol <strong>Edit</strong> untuk memperbarui data sebelum diverifikasi.</li>
                            <li>Tombol <strong>Hapus</strong> hanya tersedia untuk Manager dan Super Admin.</li>
                        </ul>
                        <div class="mt-4 border-t border-blue-200 pt-3">
                            <h4 class="font-bold text-blue-900 mb-1 flex items-center gap-2"><span>🔑</span> Hak Akses Berdasarkan Role:</h4>
                            <ul class="list-disc list-inside text-sm leading-relaxed opacity-90">
                                @php $role = Auth::user()->role; @endphp
                                @if($role === 'operator_produksi')
                                    <li><strong>Operator:</strong> Tambah & Edit data sebelum diverifikasi.</li>
                                @elseif($role === 'petugas_qc')
                                    <li><strong>Petugas QC:</strong> Validasi dan update status verifikasi.</li>
                                @else
                                    <li><strong>Admin/Manager:</strong> Akses penuh untuk semua tindakan.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <button @click="show = false; localStorage.setItem('hideGuideData', true)" class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2rem] overflow-hidden border border-gray-100">
                
                <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase">
                            Manajemen <span class="text-indigo-600">Data Cacat</span>
                        </h2>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                            <span class="h-1 w-8 bg-indigo-600 rounded-full"></span>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Validasi & Database Kerusakan</p>
                        </div>
                    </div>

                    @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'operator_produksi']))
                    <button onclick="openModal('createModal')" class="group relative inline-flex items-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-2xl transition-all shadow-xl shadow-indigo-200 hover:-translate-y-1 active:translate-y-0 overflow-hidden">
                        <div class="absolute inset-0 w-3 bg-white/20 transition-all group-hover:w-full"></div>
                        <svg class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="relative z-10">TAMBAH DATA</span>
                    </button>
                    @endif
                </div>

                <div class="p-10">
                    @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 p-5 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-2xl flex items-center justify-between shadow-sm animate-pulse">
                        <div class="flex items-center">
                            <div class="bg-emerald-500 p-1 rounded-full mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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
                                    <th class="px-6 py-4 text-left">Tanggal</th>
                                    <th class="px-6 py-4 text-left">Shift & Mesin</th>
                                    <th class="px-6 py-4 text-left">Jenis Cacat</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-left">Waktu Verifikasi</th>
                                    <th class="px-6 py-4 text-right">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-transparent">
                                @forelse ($dataCacat as $item)
                                <tr class="bg-white hover:bg-indigo-50/50 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                    <td class="px-6 py-5 first:rounded-l-2xl">
                                        <div class="text-sm font-extrabold text-gray-700 tracking-tight">{{ $item->tanggal }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-gray-600">{{ $item->shift }}</div>
                                        <div class="text-[10px] text-indigo-400 font-black uppercase tracking-widest mt-0.5">{{ $item->lokasi_mesin }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-gray-700">
                                        {{ $item->jenisCacat->nama_jenis ?? '-' }}
                                        <div class="text-[10px] text-gray-400 font-medium">{{ $item->jenis_kain ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @php $status = $item->status_verifikasi; @endphp
                                        @if ($status == 1)
                                            <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider border border-emerald-100">Terverifikasi</span>
                                        @elseif ($status == 2)
                                            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider border border-blue-100">Revision</span>
                                        @elseif ($status == 3)
                                            <span class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-wider border border-rose-100">Rejected</span>
                                        @else
                                            <span class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-wider border border-amber-100">Belum Valid</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-gray-700 tracking-tight">
                                            @if($item->status_verifikasi && $item->verifikasi && $item->verifikasi->tanggal_verifikasi != '0000-00-00 00:00:00')
                                                {{ \Carbon\Carbon::parse($item->verifikasi->tanggal_verifikasi)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest italic">Belum Diverifikasi</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right last:rounded-r-2xl">
                                        <div class="flex justify-end gap-2">
                                            @if($item->status_verifikasi)
                                                <button onclick='openPreviewModal(@json($item))' class="p-2.5 text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                            @endif

                                            <button onclick='openEditModal(@json($item))' class="p-2.5 text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>

                                            @if(in_array(Auth::user()->role, ['manager_produksi', 'super_admin']))
                                            <form id="deleteForm-{{ $item->id_cacat }}" action="{{ route('data-cacat.destroy', $item->id_cacat) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $item->id_cacat }})" class="p-2.5 text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center bg-white rounded-3xl">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                            </div>
                                            <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada data cacat terdaftar</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/40 backdrop-blur-[8px] z-[100]">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden p-10 border border-gray-100">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-gray-900 uppercase italic">Tambah Data Cacat</h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Input laporan kerusakan baru</p>
            </div>
            <form id="createForm" method="POST" action="{{ route('data-cacat.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 transition-all px-6 py-3.5 font-bold text-gray-700" required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="shift" placeholder="Shift" class="bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" required>
                        <input type="text" name="lokasi_mesin" placeholder="Mesin" class="bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" required>
                    </div>
                    <input type="text" name="jenis_kain" placeholder="Jenis Kain" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm">
                    <select name="id_jenis" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" required>
                        <option value="">Pilih Jenis Cacat</option>
                        @foreach($jenisCacat as $j)
                            <option value="{{ $j->id_jenis }}">{{ $j->nama_jenis }}</option>
                        @endforeach
                    </select>
                    <div>
                        <input type="file" name="foto_bukti" class="w-full text-xs font-bold text-gray-400" onchange="previewImage(event, 'createPreview')">
                        <img id="createPreview" src="#" class="hidden mt-4 rounded-2xl border max-h-32 mx-auto cursor-pointer hover:opacity-80 transition-opacity" onclick="openImageLightbox(this.src)">
                    </div>
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeModal('createModal')" class="flex-1 px-6 py-4 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-[2] bg-indigo-600 px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all uppercase tracking-[0.2em]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="previewModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/40 backdrop-blur-[8px] z-[100]">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden p-10 border border-gray-100 text-center">
            <h2 class="text-2xl font-black text-gray-900 uppercase italic mb-6">Detail Laporan</h2>
            <div class="space-y-4 text-left bg-gray-50 p-6 rounded-[2rem] mb-6">
                <div class="flex justify-between border-b border-gray-100 pb-2"><span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</span><span class="font-bold text-gray-700" id="preview_tanggal"></span></div>
                <div class="flex justify-between border-b border-gray-100 pb-2"><span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Shift/Mesin</span><span class="font-bold text-gray-700"><span id="preview_shift"></span> / <span id="preview_lokasi_mesin"></span></span></div>
                <div class="flex justify-between border-b border-gray-100 pb-2"><span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cacat</span><span class="font-bold text-indigo-600" id="preview_jenis_cacat"></span></div>
                <img id="preview_foto" src="#" class="hidden mt-4 rounded-2xl border w-full object-cover">
            </div>
            <button onclick="closeModal('previewModal')" class="w-full px-6 py-4 bg-gray-900 text-white text-xs font-black rounded-2xl uppercase tracking-widest">Tutup</button>
        </div>
    </div>

    <div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/40 backdrop-blur-[8px] z-[100]">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden p-10 border border-gray-100">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-gray-900 uppercase italic" id="editModalTitle">Update Data</h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Sesuaikan informasi verifikasi</p>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="space-y-4 mb-8">
                    @php $role = Auth::user()->role; @endphp
                    <input type="date" id="edit_tanggal" name="tanggal" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" {{ $role === 'petugas_qc' ? 'readonly' : '' }}>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" id="edit_shift" name="shift" class="bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" {{ $role === 'petugas_qc' ? 'readonly' : '' }}>
                        <input type="text" id="edit_lokasi_mesin" name="lokasi_mesin" class="bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" {{ $role === 'petugas_qc' ? 'readonly' : '' }}>
                    </div>
                    
                    @if(in_array($role, ['petugas_qc', 'manager_produksi', 'super_admin']))
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-emerald-500 mb-2">Status Verifikasi</label>
                        <select name="status_verifikasi" id="edit_status" class="w-full bg-emerald-50 border-none rounded-2xl px-6 py-3.5 font-black text-xs text-emerald-700 uppercase tracking-widest">
                            <option value="0">Belum Terverifikasi</option>
                            <option value="1">Terverifikasi</option>
                            <option value="2">Revision</option>
                            <option value="3">Rejected</option>
                        </select>
                    </div>
                    @endif

                    <select id="edit_id_jenis" name="id_jenis" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-3.5 font-bold text-sm" {{ $role === 'petugas_qc' ? 'disabled' : '' }}>
                        @foreach($jenisCacat as $j)
                            <option value="{{ $j->id_jenis }}">{{ $j->nama_jenis }}</option>
                        @endforeach
                    </select>
                    <img id="editPreview" src="#" class="hidden mt-4 rounded-2xl border max-h-32 mx-auto cursor-pointer hover:opacity-80 transition-opacity" onclick="openImageLightbox(this.src)">
                </div>
                <div class="flex gap-4">
                    <button type="button" onclick="closeModal('editModal')" class="flex-1 px-6 py-4 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Batal</button>
                    <button type="submit" id="editSubmitBtn" class="flex-[2] bg-amber-500 px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all uppercase tracking-[0.2em]">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div id="imageLightbox" class="hidden fixed inset-0 bg-black/90 backdrop-blur-sm z-[200] flex items-center justify-center p-4" onclick="closeImageLightbox()">
        <div class="relative max-w-7xl max-h-[90vh] w-full h-full flex items-center justify-center">
            <button onclick="closeImageLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="lightboxImage" src="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" onclick="event.stopPropagation()">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openImageLightbox(src) {
            if (src && src !== '#') {
                document.getElementById('lightboxImage').src = src;
                document.getElementById('imageLightbox').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeImageLightbox() {
            document.getElementById('imageLightbox').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function previewImage(event, previewId) {
            const preview = document.getElementById(previewId);
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => { preview.src = reader.result; preview.classList.remove('hidden'); };
                reader.readAsDataURL(file);
            }
        }

        function openEditModal(data) {
            const form = document.getElementById('editForm');
            const role = "{{ Auth::user()->role }}";
            form.action = `/data-cacat/${data.id_cacat}`;
            document.getElementById('edit_tanggal').value = data.tanggal;
            document.getElementById('edit_shift').value = data.shift;
            document.getElementById('edit_lokasi_mesin').value = data.lokasi_mesin;
            document.getElementById('edit_id_jenis').value = data.id_jenis;
            
            if(document.getElementById('edit_status')) {
                document.getElementById('edit_status').value = data.status_verifikasi;
            }

            const preview = document.getElementById('editPreview');
            if (data.foto_bukti) {
                preview.src = `/storage/${data.foto_bukti}`;
                preview.classList.remove('hidden');
                preview.style.cursor = 'pointer';
            } else { preview.classList.add('hidden'); }

            const btn = document.getElementById('editSubmitBtn');
            if (role === 'petugas_qc') {
                btn.textContent = 'UPDATE STATUS';
                btn.className = "flex-[2] bg-emerald-600 px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg uppercase tracking-widest";
            } else {
                btn.textContent = 'UPDATE';
                btn.className = "flex-[2] bg-amber-500 px-6 py-4 text-white text-xs font-black rounded-2xl shadow-lg hover:bg-amber-600 transition-all uppercase tracking-[0.2em]";
            }

            openModal('editModal');
        }

        function openPreviewModal(data) {
            document.getElementById('preview_tanggal').textContent = data.tanggal;
            document.getElementById('preview_shift').textContent = data.shift;
            document.getElementById('preview_lokasi_mesin').textContent = data.lokasi_mesin;
            document.getElementById('preview_jenis_cacat').textContent = data.jenis_cacat?.nama_jenis || '-';
            const img = document.getElementById('preview_foto');
            if (data.foto_bukti) { 
                img.src = `/storage/${data.foto_bukti}`; 
                img.classList.remove('hidden');
                img.style.cursor = 'pointer';
                img.onclick = () => openImageLightbox(img.src);
            } else { 
                img.classList.add('hidden'); 
            }
            openModal('previewModal');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: '<span class="text-xl font-black uppercase italic">Hapus Data?</span>',
                html: '<p class="text-sm text-gray-500 font-bold uppercase tracking-tight">Data laporan ini akan dihilangkan permanen.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: { popup: 'rounded-[2rem] border-none shadow-2xl p-8', confirmButton: 'rounded-xl font-black px-8 py-3 tracking-widest text-xs', cancelButton: 'rounded-xl font-black px-8 py-3 tracking-widest text-xs' }
            }).then((result) => { if (result.isConfirmed) { document.getElementById('deleteForm-' + id).submit(); } })
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</x-app-layout>