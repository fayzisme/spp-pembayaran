@extends('layouts.app')

@section('title', 'Nama Pembayaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Nama Pembayaran</h3>
                    <div class="card-options">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modal-tambah-nama-transaksi">Tambah Nama Pembayaran</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pembayaran</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($namaPembayaran as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_transaksi }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit-nama-transaksi-{{ $item->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('nama-transaksi.destroy', $item->id) }}"
                                            method="post" class=" d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                data-name="{{ $item->nama_transaksi }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                {{-- modal edit --}}
                                <div class="modal fade" id="modal-edit-nama-transaksi-{{ $item->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="modal-edit-nama-transaksi-{{ $item->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('nama-transaksi.update', $item->id) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Nama Pembayaran</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nama Pembayaran</label>
                                                        <input type="text" name="nama_transaksi" class="form-control"
                                                            value="{{ $item->nama_transaksi }}" required>
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                        <label>Keterangan</label>
                                                        <textarea name="keterangan" class="form-control" required>{{ $item->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- end modal edit --}}
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $namaPembayaran->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- modal tambah --}}
    <div class="modal fade" id="modal-tambah-nama-transaksi" tabindex="-1" role="dialog"
        aria-labelledby="modal-tambah-nama-transaksi" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('nama-transaksi.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Nama Pembayaran</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Pembayaran</label>
                            <input type="text" name="nama_transaksi" class="form-control" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" required></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end modal tambah --}}
@endsection
