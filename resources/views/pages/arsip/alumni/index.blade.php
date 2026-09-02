@extends('layouts.app')

@section('title', 'Alumni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Alumni</h3>
                
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('alumni.index') }}" class="me-3">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            {{-- <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari siswa..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div> --}}
                        </div>
                    </div>
                </form>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            {{-- <th>Aksi</th> --}}
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
                           
                            {{-- <td> --}}
                               
                                {{-- <a href="{{ route('alumni.show', $item->id_siswa) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('alumni.edit', $item->id_siswa) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('alumni.destroy', $item->id_siswa) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger show_confirm" type="submit"><i
                                            class="fa fa-trash"></i></button>
                                </form> --}}
                            {{-- </td> --}}
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


@endsection
@section('js-dashboard')
<script>
    let table = new DataTable('.table');

</script>
@endsection
