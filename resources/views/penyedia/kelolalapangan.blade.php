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
                @forelse($lapangans as $lapangan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $lapangan->nama }}</td>
                        <td>{{ $lapangan->jenis }}</td>
                        <td>Rp {{ number_format($lapangan->harga,0,',','.') }}</td>
                        <td>
                            @if($lapangan->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak aktif</span>
                            @endif
                        </td>
                        <td style="width:120px">
                            @if($lapangan->foto)
                                <img src="{{ asset('storage/'.$lapangan->foto) }}" class="img-fluid rounded" alt="foto">
                            @else
                                <span class="text-muted small">Belum ada</span>
                            @endif
                        </td>
                        <td style="width:180px">
                            <button
                                class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $lapangan->id }}"
                                data-nama="{{ $lapangan->nama }}"
                                data-jenis="{{ $lapangan->jenis }}"
                                data-harga="{{ $lapangan->harga }}"
                                data-is_active="{{ $lapangan->is_active }}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit"
                            >Edit</button>

                            <form action="{{ route('penyedia.lapangan.destroy', $lapangan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lapangan ini?')">
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
        {{ $lapangans->links() }}
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
              <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Jenis</label>
              <input type="text" name="jenis" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Harga / jam (Rp)</label>
              <input type="number" name="harga" class="form-control" min="0" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Foto (opsional)</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
          </div>
          <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" id="createActive" value="1" checked>
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
              <input type="text" name="nama" id="edit_nama" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Jenis</label>
              <input type="text" name="jenis" id="edit_jenis" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Harga / jam (Rp)</label>
              <input type="number" name="harga" id="edit_harga" class="form-control" min="0" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Ganti Foto (opsional)</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
          </div>
          <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" id="editActive" value="1">
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
    // isi form edit ketika tombol edit diklik
    document.querySelectorAll('.btn-edit').forEach(function(btn){
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const nama = this.dataset.nama || '';
            const jenis = this.dataset.jenis || '';
            const harga = this.dataset.harga || 0;
            const isActive = this.dataset.is_active == '1' || this.dataset.is_active === 'true';

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jenis').value = jenis;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('editActive').checked = isActive;

            // set action url (sesuaikan route name jika perlu)
            const form = document.getElementById('formEdit');
            form.action = "{{ url('penyedia/lapangan') }}/" + id;
        });
    });
});
</script>
@endpush