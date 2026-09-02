@extends('layouts.app')

@section('title', 'Jenis Pembayaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Jenis Pembayaran</h3>
                    <div class="card-options">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modal-tambah-jenis-pembayaran">Tambah Jenis Pembayaran</button>
                    </div>
                    <p></p>
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-primary">
                          <h6 class="alert-heading fw-bold mb-1">Jangan Lupa Atur Tarif</h6>
                          
                        </div>
                      </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                {{-- <th>Nama Pembayaran</th> --}}
                                <th>Nama Pembayaran</th>
                                <th>Tahun</th>
                                <th>Tipe Pembayaran</th>
                                <th>Semester</th>
                                <th>Tarif Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisPembayaran as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $item->nama_pembayaran }}</td> --}}
                                    <td>{{ $item->nama_pembayaran }} - T.A {{ $item->tahunAjaran->thn_ajaran }}</td>
                                    <td>{{ $item->tahunAjaran->thn_ajaran }}</td>
                                    <td>{{ $item->tipe_bayar }}</td>
                                    {{-- <td>{{ $item->semester }}</td> --}}
                                    <td>{{ $item->tahunAjaran->semester }}</td>
                                    <td>
                                        <a href="{{ route('tarif.index', $item->id_jenis_pembayaran) }}" class="btn btn-sm btn-outline-info">Atur Tarif</a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit-jenis-pembayaran-{{ $item->id_jenis_pembayaran }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('jenis-transaksi.destroy', $item->id_jenis_pembayaran) }}" method="post"
                                            class="d-inline show_confirm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                        
                                    </td>
                                </tr>

                                
                                {{-- modal edit --}}
                                <div class="modal fade modal-edit-jenis-pembayaran" id="modal-edit-jenis-pembayaran-{{ $item->id_jenis_pembayaran }}" tabindex="-1"
                                    role="dialog" aria-labelledby="modal-edit-jenis-pembayaran-{{ $item->id_jenis_pembayaran }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('jenis-transaksi.update', $item->id_jenis_pembayaran) }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Jenis Pembayaran</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Nama Pembayaran</label>
                                                        <input type="text" value="{{ $item->nama_pembayaran }}" name="nama_pembayaran" class="form-control" required>
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                        <label>Tahun Ajaran</label>
                                                        <select name="id_thn_ajaran" class="form-control" required>
                                                            {{-- <option value="" hidden disabled selected>Pilih Tahun Ajaran</option> --}}
                                                            <option selected disabled value>Pilih Tahun Ajaran</option>
                                                            @foreach ($tahunAjaran as $tahun)
                                                                <option value="{{ /* $tahun->id_thn_ajaran */ $tahun->id_thn_ajaran }}" {{ /* $tahun->id_thn_ajaran */ $tahun->id_thn_ajaran == $item->id_thn_ajaran ? 'selected' : '' }}>
                                                                    {{ $tahun->thn_ajaran . ' - ' . $tahun->semester }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                        <label>Tipe Pembayaran</label>
                                                        <select name="tipe_bayar" class="form-control" required>
                                                            {{-- <option value="" hidden disabled selected>Pilih Tipe Pembayaran</option> --}}
                                                            <option selected disabled value>Pilih Tipe Pembayaran</option>
                                                            <option value="Bulanan" {{ $item->tipe_bayar == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                            <option value="Bebas" {{ $item->tipe_bayar == 'Bebas' ? 'selected' : '' }}>Bebas</option>
                                                        </select>
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
                                {{-- modal tarif --}}
                                {{-- end modal tarif --}}
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $jenisPembayaran->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- modal tambah --}}
    <div class="modal fade modal-tambah-jenis-pembayaran" id="modal-tambah-jenis-pembayaran" tabindex="-1" role="dialog"
    aria-labelledby="modal-tambah-jenis-pembayaran" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('jenis-transaksi.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Pembayaran</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pembayaran</label>
                        <input type="text" name="nama_pembayaran" class="form-control" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <select name="id_thn_ajaran" class="form-control" required>
                            {{-- <option value="" hidden disabled selected>Pilih Tahun Ajaran</option> --}}
                            <option selected disabled value>Pilih Tahun Ajaran</option>
                            @foreach ($tahunAjaran as $tahun)
                                <option value="{{ /* $tahun->id_thn_ajaran */ $tahun->id_thn_ajaran }}">{{ $tahun->thn_ajaran . ' - ' . $tahun->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Tipe Pembayaran</label>
                        <select name="tipe_bayar" class="form-control" required>
                            {{-- <option value="" hidden disabled selected>Pilih Tipe Pembayaran</option> --}}
                            <option selected disabled value>Pilih Tipe Pembayaran</option>
                            <option value="Bulanan">Bulanan</option>
                            <option value="Bebas">Bebas</option>
                        </select>
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
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

</script>
@endsection
