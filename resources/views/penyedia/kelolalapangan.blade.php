@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')
<div 
    class="max-w-6xl mx-auto px-4 py-6"
    x-data="{
        openCreate:false,
        openEdit:false,
        editId:'',
        editNama:'',
        editJenis:'',
        editLokasi:'',
        editDeskripsi:'',
        editHarga:'',
        editActive:false
    }"
>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Kelola Lapangan</h1>

        <button 
            @click="openCreate = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Tambah Lapangan
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-3 rounded border border-green-600 text-green-700 bg-green-50 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-3 rounded border border-red-600 text-red-700 bg-red-50 mb-4">
            <ul class="space-y-1">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-green-600 text-white text-sm uppercase">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Jenis</th>
                    <th class="px-4 py-2 border">Harga / jam</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Foto</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $lapangan)
                    <tr class="hover:bg-green-50">
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $lapangan->nama_lapangan }}</td>
                        <td class="px-4 py-2 border">{{ $lapangan->jenis_olahraga }}</td>
                        <td class="px-4 py-2 border">Rp {{ number_format($lapangan->harga_perjam) }}</td>
                        <td class="px-4 py-2 border">
                            @if($lapangan->aktif)
                                <span class="px-2 py-1 text-xs bg-green-600 text-white rounded">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">Tidak aktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border w-32">
                            @if($lapangan->foto)
                                <img src="{{ asset($lapangan->foto) }}" class="w-full h-20 object-cover rounded">
                            @else
                                <span class="text-gray-500 text-sm">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border space-x-2">

                            {{-- Tombol Edit --}}
                            <button 
                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700"
                                @click="
                                    openEdit = true;
                                    editId = '{{ $lapangan->lapangan_id }}';
                                    editNama = '{{ $lapangan->nama_lapangan }}';
                                    editJenis = '{{ $lapangan->jenis_olahraga }}';
                                    editLokasi = '{{ $lapangan->lokasi }}';
                                    editDeskripsi = '{{ $lapangan->deskripsi }}';
                                    editHarga = '{{ $lapangan->harga_perjam }}';
                                    editActive = {{ $lapangan->aktif ? 'true' : 'false' }};
                                "
                            >
                                Edit
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('penyedia.lapangan.destroy', $lapangan->lapangan_id) }}" 
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Hapus lapangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">Belum ada data lapangan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>



    {{-- ================================ --}}
    {{-- Modal Create --}}
    {{-- ================================ --}}
    <div 
        x-show="openCreate"
        class="fixed inset-0 bg-black/20 flex items-center justify-center"
        x-transition.opacity>
        <div 
            class="bg-white rounded-2xl shadow p-6 w-full max-w-md transform"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-8"
        >

            <h2 class="text-lg font-semibold mb-4">Tambah Lapangan</h2>

            <form action="{{ route('penyedia.lapangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="block text-sm mb-1">Nama</label>
                    <input type="text" name="nama_lapangan" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Jenis</label>
                    <input type="text" name="jenis_olahraga" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Lokasi</label>
                    <input type="text" name="lokasi" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Harga / jam</label>
                    <input type="number" name="harga_perjam" class="w-full border rounded p-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Foto</label>
                    <input type="file" name="foto" class="w-full">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full border rounded p-2"></textarea>
                </div>

                <label class="flex items-center space-x-2 mb-4">
                    <input type="checkbox" name="aktif" value="1" checked class="rounded">
                    <span>Aktif</span>
                </label>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openCreate=false" class="px-3 py-1 border rounded">Batal</button>
                    <button class="px-4 py-1 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>




    {{-- ================================ --}}
    {{-- Modal Edit --}}
    {{-- ================================ --}}
    <div 
    x-show="openEdit"
    class="fixed inset-0 bg-black/20 flex items-center justify-center"
    x-transition.opacity>
    <div 
        class="bg-white rounded-t-2xl shadow p-6 w-full max-w-md transform"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-8"
    >

        <h2 class="text-lg font-semibold mb-4">Edit Lapangan</h2>

        <form method="POST" 
            :action="'/penyedia/lapangan/' + editId"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" :value="editId">

            <div class="mb-3">
                <label class="block text-sm mb-1">Nama</label>
                <input type="text" name="nama_lapangan" class="w-full border rounded p-2" x-model="editNama">
            </div>

            <div class="mb-3">
                <label class="block text-sm mb-1">Jenis</label>
                <input type="text" name="jenis_olahraga" class="w-full border rounded p-2" x-model="editJenis">
            </div>

            <div class="mb-3">
                <label class="block text-sm mb-1">Lokasi</label>
                <input type="text" name="lokasi" class="w-full border rounded p-2" x-model="editLokasi">
            </div>

            <div class="mb-3">
                <label class="block text-sm mb-1">Harga / jam</label>
                <input type="number" name="harga_perjam" class="w-full border rounded p-2" x-model="editHarga">
            </div>

            <div class="mb-3">
                <label class="block text-sm mb-1">Ganti Foto</label>
                <input type="file" name="foto" class="w-full">
            </div>

            <div class="mb-3">
                <label class="block text-sm mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border rounded p-2" x-model="editDeskripsi"></textarea>
            </div>

            <label class="flex items-center space-x-2 mb-4">
                <input type="checkbox" name="aktif" value="1" class="rounded" x-bind:checked="editActive">
                <span>Aktif</span>
            </label>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="openEdit=false" class="px-3 py-1 border rounded">Batal</button>
                <button class="px-4 py-1 bg-blue-600 text-white rounded">Perbarui</button>
            </div>

        </form>
    </div>
</div>

</div>
@endsection
