@extends('layouts.app')

@section('title', 'Broadcast')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title
                    ">Broadcast Page</h4>
                </div>
                <div class="card-body">
                    <p>Ini Broadcast</p>

                    <p>{{ $target }}</p>

                    <a href="{{ route('broadcast') }}" class="btn btn-sm btn-primary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection