@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Tahun Ajaran</h3>
                <div class="card-options">
                    {{-- <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-success">Tambah Tahun Ajaran</a> --}}
                    <button type="button" class="btn btn-outline-info generate-thn-ajaran">
                        <i class="fas fa-sync-alt"></i> Generate Tahun Ajaran
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tahunAjaran as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->thn_ajaran }}</td>
                            <td>{{ $item->semester }}</td>
                            <td>
                                <a href="{{ route('tahun-ajaran.edit', $item->id_thn_ajaran) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('tahun-ajaran.destroy', $item->id_thn_ajaran) }}" method="post"
                                    class=" d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                        data-name="{{ $item->tahun_ajaran }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <form id="form-req-thn-ajaran" action="{{ route('tahun-ajaran.store') }}" method="post">
            @csrf
        </form>
    </div>
</div>
@endsection
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

    $('.generate-thn-ajaran').click(function() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Generate'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-req-thn-ajaran').submit();
            }
        });
    });
</script>
@endsection
