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
<!-- Panduan Penggunaan Halaman -->
<div 
    id="pageGuide" 
    x-data="{ show: true }"
    x-show="show"
    x-transition
    class="mb-4 bg-blue-100 border border-blue-300 text-blue-800 px-4 py-3 rounded-lg shadow-sm relative"
>
    <div class="flex items-start gap-2">
        <svg class="w-5 h-5 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
        </svg>
        <div>
            <h3 class="font-semibold text-blue-900 mb-1">Panduan Penggunaan Halaman</h3>
            <ul class="list-disc list-inside text-sm leading-relaxed">
                <li>Halaman ini digunakan untuk mengelola dan memverifikasi data cacat produksi.</li>
                <li>Gunakan tombol <strong>+ Tambah Data</strong> untuk menambahkan laporan baru.</li>
                <li>Tombol <strong>Edit</strong> untuk memperbarui data sebelum diverifikasi.</li>
                <li>Tombol <strong>Update Status</strong> hanya untuk petugas_qc dalam memvalidasi data.</li>
                <li>Tombol <strong>Preview</strong> digunakan untuk melihat data yang sudah terverifikasi.</li>
                <li>Tombol <strong>Hapus</strong> hanya tersedia untuk manager_produksi dan Super manager_produksi.</li>
            </ul>
            <div class="mt-3 border-t border-blue-200 pt-2">
                <h4 class="font-semibold text-blue-900 mb-1">🔑 Hak Akses Berdasarkan Role:</h4>
                <ul class="list-disc list-inside text-sm leading-relaxed">
                    @php $role = Auth::user()->role; @endphp

                    @if($role === 'operator_produksi')
                        <li><strong>Operator Produksi:</strong> Dapat menambah dan mengedit data cacat sebelum diverifikasi.</li>
                        <li>Tidak dapat memverifikasi atau menghapus data.</li>
                    @elseif($role === 'petugas_qc')
                        <li><strong>Petugas QC:</strong> Hanya dapat mengubah status verifikasi data.</li>
                        <li>Tidak dapat menambah atau menghapus data.</li>
                    @elseif($role === 'manager_produksi')
                        <li><strong>Manager Produksi/Supervisor:</strong> Dapat menambah, mengedit, menghapus, dan memverifikasi data.</li>
                        <li>Tidak dapat mengubah data yang sudah terverifikasi.</li>
                    @elseif($role === 'super_admin')
                        <li><strong>Super Admin:</strong> Memiliki akses penuh untuk semua tindakan di halaman ini.</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <button 
        @click="show = false; localStorage.setItem('hideGuide', true)" 
        class="absolute top-2 right-3 text-blue-800 hover:text-blue-900 text-xl leading-none"
    >
        ×
    </button>
</div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
            <div class="flex justify-between items-center mb-6">
                
            <h2 class="text-2xl font-semibold text-gray-800">Manajemen Data Cacat</h2>
            @if(in_array(Auth::user()->role, ['super_admin', 'manager_produksi', 'operator_produksi']))
                <button onclick="openModal('createModal')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    + Tambah Data
                </button>
            @endif

        </div>

        
        <div class="overflow-x-auto">
            
         <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden bg-white">
           <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Shift</th>
                        <th class="px-4 py-2">Lokasi Mesin</th>
                        <th class="px-4 py-2">Jenis Kain</th>
                        <th class="px-4 py-2">Jenis Cacat</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Waktu Verifikasi</th> 
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach ($dataCacat as $item)
                <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-2">{{ $item->tanggal }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->shift }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->lokasi_mesin }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->jenis_kain ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ $item->jenisCacat->nama_jenis ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @if($item->status_verifikasi)
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Terverifikasi</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">Belum Valid</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                            @if($item->status_verifikasi)
                                    @if($item->verifikasi && $item->verifikasi->tanggal_verifikasi != '0000-00-00 00:00:00')
                                        {{ \Carbon\Carbon::parse($item->verifikasi->tanggal_verifikasi)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-gray-400 italic">Belum diverifikasi</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Belum diverifikasi</span>
                                @endif

                            </td>

                        <td class="px-4 py-2 flex gap-2">
    @php
        $role = Auth::user()->role;
        $verified = $item->status_verifikasi;
    @endphp

    @if($verified)
    <button type="button"
        onclick='openPreviewModal(@json($item))'
        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
    Preview
</button>

    @else
        @if(in_array($role, ['operator_produksi', 'manager_produksi', 'super_admin']))
            <button type="button" onclick='openEditModal(@json($item))'
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                Edit
            </button>
        @elseif($role === 'petugas_qc')
            <button type="button" onclick='openEditModal(@json($item))'
                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                Update Status
            </button>
        @endif
    @endif

    @if(in_array($role, ['manager_produksi', 'super_admin']))
        <form id="deleteForm-{{ $item->id_cacat }}" action="{{ route('data-cacat.destroy', $item->id_cacat) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete({{ $item->id_cacat }})"
                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Hapus</button>
        </form>
    @endif
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   <!-- Modal Tambah -->
<div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Tambah Data Cacat</h3>
        <form id="createForm" method="POST" action="{{ route('data-cacat.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-3">
            <input 
                    type="date" 
                    name="tanggal" 
                    class="w-full border rounded p-2" 
                    required 
                    max="{{ date('Y-m-d') }}"
                    value="{{ date('Y-m-d') }}"

             >          
                <input type="text" name="shift" placeholder="Shift" class="w-full border rounded p-2" required>
                <input type="text" name="lokasi_mesin" placeholder="Lokasi Mesin" class="w-full border rounded p-2" required>
                <input type="text" name="jenis_kain" placeholder="Jenis Kain" class="w-full border rounded p-2">

                <select name="id_jenis" class="w-full border rounded p-2" required>
                    <option value="">Pilih Jenis Cacat</option>
                    @foreach($jenisCacat as $j)
                        <option value="{{ $j->id_jenis }}">{{ $j->nama_jenis }}</option>
                    @endforeach
                </select>

                <div>
                    <input type="file" name="foto_bukti" id="create_foto_bukti" class="w-full border rounded p-2" accept="image/*" onchange="previewImage(event, 'createPreview')">
                    <img id="createPreview" src="#" alt="Preview" class="hidden mt-2 rounded border max-h-40 mx-auto">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('createModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview -->
<div id="previewModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Detail Data Cacat</h3>
        <div class="space-y-2">
            <p><strong>Tanggal:</strong> <span id="preview_tanggal"></span></p>
            <p><strong>Shift:</strong> <span id="preview_shift"></span></p>
            <p><strong>Lokasi Mesin:</strong> <span id="preview_lokasi_mesin"></span></p>
            <p><strong>Jenis Kain:</strong> <span id="preview_jenis_kain"></span></p>
            <p><strong>Jenis Cacat:</strong> <span id="preview_jenis_cacat"></span></p>
            <div>
                <strong>Foto Bukti:</strong>
                <img id="preview_foto" src="#" class="hidden mt-2 rounded border max-h-40 mx-auto">
            </div>
        </div>
        <div class="flex justify-end pt-4">
            <button type="button" onclick="closeModal('previewModal')" class="px-3 py-1 border rounded hover:bg-gray-100">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Edit Data Cacat</h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @php $role = Auth::user()->role; @endphp
            <div class="space-y-3">
                <input type="date" id="edit_tanggal" name="tanggal" class="w-full border rounded p-2" required max="{{ date('Y-m-d') }}"
                  
                       {{ $role === 'petugas_qc' ? 'readonly' : '' }}>
                
                <input type="text" id="edit_shift" name="shift" class="w-full border rounded p-2" required
                       {{ $role === 'petugas_qc' ? 'readonly' : '' }}>
                
                <input type="text" id="edit_lokasi_mesin" name="lokasi_mesin" class="w-full border rounded p-2" required
                       {{ $role === 'petugas_qc' ? 'readonly' : '' }}>
                
                <input type="text" id="edit_jenis_kain" name="jenis_kain" class="w-full border rounded p-2"
                       {{ $role === 'petugas_qc' ? 'readonly' : '' }}>

                <select id="edit_id_jenis" name="id_jenis" class="w-full border rounded p-2" required
                        {{ $role === 'petugas_qc' ? 'disabled' : '' }}>
                    <option value="">Pilih Jenis Cacat</option>
                    @foreach($jenisCacat as $j)
                        <option value="{{ $j->id_jenis }}">{{ $j->nama_jenis }}</option>
                    @endforeach
                </select>

                <div>
                    <input type="file" name="foto_bukti" id="edit_foto_bukti" class="w-full border rounded p-2" accept="image/*"
                           onchange="previewImage(event, 'editPreview')" {{ $role === 'petugas_qc' ? 'disabled' : '' }}>
                    <img id="editPreview" src="#" alt="Preview" class="hidden mt-2 rounded border max-h-40 mx-auto">
                </div>

                {{-- Untuk petugas_qc, manager_produksi, dan Super manager_produksi, tampilkan dropdown status --}}
                            @if(in_array($role, ['petugas_qc', 'manager_produksi', 'super_admin']))
                                <select name="status_verifikasi" class="w-full border rounded p-2" required>
                                    <option value="0" {{ isset($data) && $data->status_verifikasi == 0 ? 'selected' : '' }}>Belum Terverifikasi</option>
                                    <option value="1" {{ isset($data) && $data->status_verifikasi == 1 ? 'selected' : '' }}>Terverifikasi</option>
                                </select>
                            @endif


                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit"
                            class="{{ $role === 'petugas_qc' ? 'bg-green-600' : 'bg-yellow-600' }} text-white px-3 py-1 rounded">
                        {{ $role === 'petugas_qc' ? 'Update Status' : 'Update' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


</div>
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = () => {
            preview.src = reader.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "#";
        preview.classList.add('hidden');
    }
}

/**
 * 🧠 Open Edit Modal — versi fix:
 * - gunakan id_cacat sebagai primary key
 * - pastikan action URL benar
 * - tampilkan foto lama jika ada
 */
function openEditModal(data) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');

    const role = "{{ Auth::user()->role }}";
    const verified = data.status_verifikasi == 1;

    // Set action URL
    form.action = `/data-cacat/${data.id_cacat}`;

    // Set form values
    document.getElementById('edit_tanggal').value = data.tanggal;
    document.getElementById('edit_shift').value = data.shift;
    document.getElementById('edit_lokasi_mesin').value = data.lokasi_mesin;
    document.getElementById('edit_jenis_kain').value = data.jenis_kain || '';
    document.getElementById('edit_id_jenis').value = data.id_jenis;

    // Preview foto
    const preview = document.getElementById('editPreview');
    if (data.foto_bukti) {
        preview.src = `/storage/${data.foto_bukti}`;
        preview.classList.remove('hidden');
    } else {
        preview.src = "#";
        preview.classList.add('hidden');
    }

    // Field readonly / disabled logic
    const inputs = ['edit_tanggal', 'edit_shift', 'edit_lokasi_mesin', 'edit_jenis_kain', 'edit_id_jenis', 'edit_foto_bukti'];
    inputs.forEach(id => {
        const el = document.getElementById(id);
        if (role === 'operator_produksi' && verified) el.disabled = true;
        if (role === 'petugas_qc') el.disabled = true; // petugas_qc hanya update status
        if (['manager_produksi','super_admin'].includes(role) && verified) el.disabled = true;
    });

    // Update tombol submit
    const submitBtn = form.querySelector('button[type="submit"]');
    if (role === 'petugas_qc') {
        submitBtn.textContent = 'Update Status';
        submitBtn.className = 'bg-green-500 text-white px-3 py-1 rounded';
    } else if (verified) {
        submitBtn.textContent = 'Preview';
        submitBtn.className = 'bg-blue-500 text-white px-3 py-1 rounded cursor-not-allowed';
        submitBtn.disabled = true;
    } else {
        submitBtn.textContent = 'Update';
        submitBtn.className = 'bg-yellow-500 text-white px-3 py-1 rounded';
        submitBtn.disabled = false;
    }

    modal.classList.remove('hidden');
}



</script>

<script>
function openPreviewModal(data) {

    // Set text
    document.getElementById('preview_tanggal').textContent = data.tanggal;
    document.getElementById('preview_shift').textContent = data.shift;
    document.getElementById('preview_lokasi_mesin').textContent = data.lokasi_mesin;
    document.getElementById('preview_jenis_kain').textContent = data.jenis_kain || '-';
    
    // Debug jenisCacat
    document.getElementById('preview_jenis_cacat').textContent = data.jenis_cacat?.nama_jenis || '-';

    // Set foto
    const img = document.getElementById('preview_foto');
    if (data.foto_bukti) {
        img.src = `/storage/${data.foto_bukti}`;
        img.classList.remove('hidden');
    } else {
        img.src = '#';
        img.classList.add('hidden');
    }

    // Tampilkan modal
    document.getElementById('previewModal').classList.remove('hidden');
}

</script>


</x-app-layout>
