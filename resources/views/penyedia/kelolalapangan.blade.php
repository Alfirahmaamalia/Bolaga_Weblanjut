@extends('layouts.app')

@section('title', 'Kelola Lapangan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4">Kelola Lapangan</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">Tambah Lapangan</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Harga / jam</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $lapangan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $lapangan->nama_lapangan }}</td>
                        <td>{{ $lapangan->jenis_olahraga }}</td>
                        <td>Rp {{ number_format($lapangan->harga_perjam,0,',','.') }}</td>
                        <td>
                            @if($lapangan->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak aktif</span>
                            @endif
                        </td>
                        <td style="width:120px">
                            @if($lapangan->foto)
                                <img src="{{ asset($lapangan->foto) }}" class="img-fluid rounded" alt="foto">
                            @else
                                <span class="text-muted small">Belum ada</span>
                            @endif
                        </td>
                        <td style="width:180px">
                            <button
                                class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $lapangan->lapangan_id }}"
                                data-nama-lapangan="{{ $lapangan->nama_lapangan }}"
                                data-jenis-olahraga="{{ $lapangan->jenis_olahraga }}"
                                data-lokasi="{{ $lapangan->lokasi }}"
                                data-deskripsi="{{ $lapangan->deskripsi }}"
                                data-harga-perjam="{{ $lapangan->harga_perjam }}"
                                data-is-active="{{ $lapangan->aktif }}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                            >Edit</button>

                            <form action="{{ route('penyedia.lapangan.destroy', $lapangan->lapangan_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lapangan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data lapangan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $data->links() }}
    </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('penyedia.lapangan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Lapangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="nama_lapangan" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Jenis</label>
              <input type="text" name="jenis_olahraga" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Lokasi</label>
              <input type="text" name="lokasi" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Harga / jam (Rp)</label>
              <input type="number" name="harga_perjam" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto (opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="aktif" id="createActive" value="1" checked>
                <label class="form-check-label" for="createActive">Aktif</label>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Lapangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="nama_lapangan" id="edit_nama" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Jenis</label>
              <input type="text" name="jenis_olahraga" id="edit_jenis" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Lokasi</label>
              <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Harga / jam (Rp)</label>
              <input type="number" name="harga_perjam" id="edit_harga" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Foto (opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" required></textarea>
            </div>
          <div class="form-check">
              <input class="form-check-input" type="checkbox" name="aktif" id="editActive" value="1">
              <label class="form-check-label" for="editActive">Aktif</label>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Perbarui</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit').forEach(function(btn){
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const nama = this.dataset.namaLapangan || '';
            const jenis = this.dataset.jenisOlahraga || '';
            const lokasi = this.dataset.lokasi || '';
            const deskripsi = this.dataset.deskripsi || '';
            const harga = this.dataset.hargaPerjam || 0;
            const isActive = this.dataset.isActive == '1' || this.dataset.isActive === 'true';

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jenis').value = jenis;
            document.getElementById('edit_lokasi').value = lokasi;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('editActive').checked = isActive;

            const form = document.getElementById('formEdit');
            form.action = "{{ url('penyedia/lapangan') }}/" + id;
        });
    });
});
</script>
@endpush