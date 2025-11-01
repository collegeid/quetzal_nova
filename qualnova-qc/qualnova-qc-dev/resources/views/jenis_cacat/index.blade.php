<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-800" 
                     x-data="{ openEdit: false, openCreate: false, selected: null, namaJenis: '' }">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            Manajemen Jenis Cacat
                        </h2>

                        @if(in_array(Auth::user()->role, ['super_admin', 'admin', 'qc']))
                            <button @click="openCreate = true"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                + Tambah Jenis
                            </button>
                        @endif
                    </div>

                    <!-- Flash Message -->
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

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-gray-200">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-gray-200">Nama Jenis</th>
                                    @if(in_array(Auth::user()->role, ['super_admin', 'admin', 'qc']))
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-gray-200">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($jenisCacat as $jenis)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800">{{ $jenis->nama_jenis }}</td>

                                        @if(in_array(Auth::user()->role, ['super_admin', 'admin', 'qc']))
                                            <td class="px-6 py-4 text-sm">
                                                <div class="flex space-x-2">
                                                    <button
                                                        @click="openEdit = true; selected = {{ $jenis->id_jenis }}; namaJenis = '{{ $jenis->nama_jenis }}'"
                                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-400 transition ease-in-out duration-150">
                                                        Edit
                                                    </button>

                                                    @if(in_array(Auth::user()->role, ['super_admin', 'admin']))
                                                        <form id="deleteForm-{{ $jenis->id_jenis }}" action="{{ route('jenis_cacat.destroy', $jenis->id_jenis) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" onclick="confirmDelete({{ $jenis->id_jenis }})"
                                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:ring-2 focus:ring-red-400 transition ease-in-out duration-150">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada data jenis cacat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal Tambah -->
                    <div x-show="openCreate"
                         x-cloak
                         class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 transition">
                        <div @click.away="openCreate = false"
                             class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Jenis Cacat</h2>

                            <form id="createForm" method="POST" action="{{ route('jenis_cacat.store') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="nama_jenis_create" class="block text-gray-700">Nama Jenis Cacat</label>
                                    <input type="text" name="nama_jenis" id="nama_jenis_create"
                                           class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           required>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" @click="openCreate = false"
                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal Edit -->
                    <div x-show="openEdit"
                         x-cloak
                         class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 transition">
                        <div @click.away="openEdit = false"
                             class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Jenis Cacat</h2>

                            <form id="editForm" method="POST"
                                  :action="`/jenis_cacat/${selected}`">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="nama_jenis" class="block text-gray-700">Nama Jenis Cacat</label>
                                    <input type="text" name="nama_jenis" id="nama_jenis"
                                           x-model="namaJenis"
                                           class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                           required>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" @click="openEdit = false"
                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
