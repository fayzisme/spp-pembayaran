@extends('layouts.app')

@section('title', 'Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Siswa</h3>
                <div class="card-options ">
                    <a href="{{ route('siswa.create') }}" type="button" class="btn btn-success mr-2">Tambah Siswa</a>
                    {{-- <button type="button" class="btn btn-warning mr-2" data-bs-toggle="modal"
                        data-bs-target="#importExcel">
                        Import Excel
                    </button> --}}
                    <button type="button" class="btn btn-warning mr-2" data-bs-toggle="modal" data-bs-target="#importExcel">
                        <i class="fas fa-file-excel"></i> Import
                    </button>                    
                    <a href="{{ route('siswa.export') }}" class="btn btn-success mr-2">
                        <i class="fas fa-file-excel"></i> Ekspor
                    </a>                    
                    <a href="{{ route('siswa.exportPdf') }}" target="_blank" class="btn btn-danger mr-2">
                        <i class="fas fa-file-pdf"></i> Ekspor
                    </a>                                     
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('siswa.index') }}" class="me-3">
                    {{-- <div class="row">
                        <div class="form-group col-sm-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari siswa..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </div> --}}
                </form>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            {{-- <th>Jenis Kelamin</th> --}}
                            {{-- <th>Foto</th> --}}
                            {{-- <th>Status</th> --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswa as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nis }}</td>
                            <td>{{ $item->kelas->tingkat . $item->kelas->nama_kelas }}</td>
                            <td>{{ $item->tahunAjaran->thn_ajaran . ' - ' . $item->tahunAjaran->semester }}</td>
                            {{-- <td>{{ $item->jenis_kelamin == 'L' ? 'Laki - Laki' : 'Perempuan' }}</td> --}}
                            {{-- <td>
                                <img src="{{ $item->user->image ? asset('images/' . $item->user->image) : asset('assets/img/icons/user_default.jpg') }}"
                                    alt="{{ $item->nama }}" width="50">
                            </td> --}}
                            {{-- <td> --}}
                                {{-- @if ($item->status == 'Aktif')
                                <span class="badge bg-label-success">Aktif</span>
                                @else
                                <span class="badge bg-label-danger">Non-Aktif</span>
                                @endif --}}
                            <td>
                                {{-- show siswa --}}
                                <a href="{{ route('siswa.show', $item->id_siswa) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('siswa.edit', $item->id_siswa) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('siswa.destroy', $item->id_siswa) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger show_confirm" type="submit"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2">
                    {{-- {{ $siswa->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal import excel --}}
<div class="modal fade" id="importExcel" tabindex="-1" aria-labelledby="importExcelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('siswa.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelLabel">Import Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

</script>
@endsection
