@extends('layouts.app')

@section('title', 'Tarif')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1>Tarif
                        {{ $jenisPembayaran ? ' - ' . ($jenisPembayaran->nama_pembayaran ? $jenisPembayaran->nama_pembayaran : '') . ' - T.A ' . ($jenisPembayaran->tahunAjaran ? $jenisPembayaran->tahunAjaran->thn_ajaran : '') : '' }}
                    </h1>
                    <a href="{{ route('tarif.create', $jenisPembayaran->id_jenis_pembayaran) }}" class="btn btn-primary">Tambah Tarif</a>
                    <a href="{{ route('jenis-transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Tarif</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tarif as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kelas->tingkat . $item->kelas->nama_kelas }}</td>
                                    <td>
                                        {{ $item->tarif ? 'Rp. ' . number_format($item->tarif, 0, ',', '.') : '' }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit-tarif-{{ $item->id_tarif }}"><i class="fas fa-edit"></i></button>
                                    
                                        <form action="{{ route('tarif.destroy', [$jenisPembayaran->id_jenis_pembayaran, $item->id_tarif]) }}"
                                            method="post" class="d-inline show_confirm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                {{-- modal edit --}}
                                <div class="modal fade" id="modal-edit-tarif-{{ $item->id_tarif }}" tabindex="-1"
                                    aria-labelledby="modal-edit-tarif-{{ $item->id_tarif }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tarif</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="{{ route('tarif.update', [$jenisPembayaran->id_jenis_pembayaran, $item->id_tarif]) }}"
                                                    method="post" id="editTarifForm">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-3">
                                                        <label for="kelas">Kelas</label>
                                                        <select name="id_kelas" id="kelas" class="form-control">
                                                            <option value="">Pilih Kelas</option>
                                                            @foreach ($kelas as $kls)
                                                                <option value="{{ $kls->id_kelas }}"
                                                                    {{ /* $kls->id_kelas */ $kls->id_kelas == $item->kelas->id_kelas ? 'selected' : '' }}>
                                                                    {{ $kls->tingkat . $kls->nama_kelas }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="tarif">Tarif</label>
                                                        <input type="text" name="tarif" id="tarif" value="{{ $item->tarif }}" class="form-control">
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                </form>
                                            </div>
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
                </div>
            </div>
        </div>
    </div>
@endsection
