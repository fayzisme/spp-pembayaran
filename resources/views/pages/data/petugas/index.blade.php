@extends('layouts.app')

@section('title', 'Petugas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Petugas</h3>
                <div class="card-options ">
                    <a href="{{ route('petugas.create') }}" type="button" class="btn btn-success mr-2">Tambah
                        Petugas</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('petugas.index') }}" class="me-3">
                    {{-- <div class="row">
                        <div class="form-group col-sm-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari petugas..."
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
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($petugas as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- <td>{{$item->iteration}}</td> --}}
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>
                                <a href="{{ route('petugas.show', $item->id_petugas) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('petugas.edit', $item->id_petugas) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('petugas.destroy', $item->id_petugas) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-danger show_confirm" type="submit">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        {{-- @empty
                        <tr>
                            <td colspan="6" class="text-center">Data tidak ditemukan</td>
                        </tr> --}}
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

</script>
@endsection
