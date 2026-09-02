@extends('layouts.app')

@section('title', 'Kelas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Kelas</h3>
                <div class="card-options">
                    <a href="{{ route('kelas.create') }}" class="btn btn-success">Tambah Kelas</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- --}}
                        @forelse ($kelas as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $item->tingkat }}{{ $item->nama_kelas }}
                            </td>
                            <td>
                                <a href="{{ route('kelas.edit', /* $item->id_kelas */ $item->id_kelas) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                {{-- <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modal-edit-kelas-{{ {{-- $item->id_kelas -- $item->id}} }}">
                                    <i class="fa fa-edit"></i>
                                </button> --}}
                                <form action="{{ route('kelas.destroy', /* $item->id_kelas */ $item->id_kelas) }}"
                                    method="post" class="d-inline">
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                        data-name="{{ /* $item->id_kelas */ $item->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- modal edit --}}
                        {{-- <div class="modal fade" id="modal-edit-kelas-{{ $item->id_kelas }}" tabindex="-1"
                            role="dialog" aria-labelledby="modal-edit-kelas-{{ $item->id_kelas }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('kelas.update', $item->id_kelas) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Kelas</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Pembayaran</label>
                                                <select class="form-control" id="tingkat" name="tingkat" required>
                                                    <option value="" disabled hidden selected>Pilih Tingkat</option>
                                                    <option value="VII" {{ $item->tingkat == 'VII' ? 'selected' : ''
                                                        }}>VII</option>
                                                    <option value="VIII" {{ $item->tingkat == 'VIII' ? 'selected' : ''
                                                        }}>VIII</option>
                                                    <option value="IX" {{ $item->tingkat == 'IX' ? 'selected' : '' }}>IX
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" id="nama_kelas" name="nama_kelas" required>
                                                    <option value="" disabled hidden selected>Pilih Nama Kelas</option>
                                                    <option value="A" {{ $item->nama_kelas == 'A' ? 'selected' : '' }}>A
                                                    </option>
                                                    <option value="B" {{ $item->nama_kelas == 'B' ? 'selected' : '' }}>B
                                                    </option>
                                                    <option value="C" {{ $item->nama_kelas == 'C' ? 'selected' : '' }}>C
                                                    </option>
                                                    <option value="D" {{ $item->nama_kelas == 'D' ? 'selected' : '' }}>D
                                                    </option>
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
                        </div> --}}
                        {{-- end modal edit --}}
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- {{ $kelas->links() }} --}}
            </div>
        </div>
    </div>
</div>

{{-- modal tambah --}}
{{-- <div class="modal fade" id="modal-tambah-kelas" tabindex="-1" role="dialog" aria-labelledby="modal-tambah-kelas"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('kelas.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select class="form-control" id="tingkat" name="tingkat" required>
                            <option value="" disabled hidden selected>Pilih Tingkat</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="nama_kelas" name="nama_kelas" required>
                            <option value="" disabled hidden selected>Pilih Nama Kelas</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
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
</div> --}}
{{-- end modal tambah --}}
@endsection
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

</script>
@endsection
