@extends('layouts.app')

@section('title', 'Broadcast')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Notifikasi</h4>
                </div>
                <div class="card-body">
                    <p>Dapat mengirimkan notifikasi Whatsapp secara langsung ke seluruh siswa atau dapat memilih siswa secara perorangan.</p>

                    <p class="mb-0 text-center"><strong>Pilih metode di bawah ini :</strong></p>
                    <hr class="my-1">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-1 text-center">Kirim Serentak</h4>
                            <form action="{{ route('doBroadcast') }}" method="post" id="formBroadcastSerentak">
                                @csrf
                                <div class="pilih-jenis">
                                    <label>Jenis Pembayaran</label>
                                    <select class="form-select mb-1 @error('jnspembayaran') is-invalid @enderror" name="jnspembayaran" id="jnspembayaran">
                                        <option value="" hidden selected disabled>--- Pilih Jenis Pembayaran ---</option>
                                        @foreach ($datajnsPembayaran as $itemListPembayaran)
                                            @php
                                                // Ambil tahun ajaran dan semester yang sesuai
                                                $tahunAjaran = $dataTahunAjaran->firstWhere('id_thn_ajaran', $itemListPembayaran->id_thn_ajaran);
                                            @endphp
                                            <option value="{{ $itemListPembayaran->id_jenis_pembayaran }}">
                                                {{ $itemListPembayaran->nama_pembayaran }} - T.A {{ $tahunAjaran->thn_ajaran }} Semester {{ $tahunAjaran->semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    @error('jnspembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-sm btn-success w-100"><i class="menu-icon tf-icons bx bxl-whatsapp"></i>Kirim Serentak</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-1 text-center">Kirim Perorangan</h4>
                            <form action="{{ route('doBroadcastTarget') }}" method="post" id="formBroadcastTarget">
                                @csrf
                                <div class="pilih-target">
                                    <label>Nama Siswa</label>
                                    <select class="form-select mb-1 @error('target') is-invalid @enderror" name="target" id="target">
                                        <option value="" hidden selected disabled>--- Pilih Siswa ---</option>
                                        @foreach ($datasiswaAll as $itemList)
                                            <option value="{{ $itemList->id_siswa }}">{{ $itemList->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('target')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="pilih-jenis">
                                    <label>Jenis Pembayaran</label>
                                    <select class="form-select mb-1 @error('jnspembayaran') is-invalid @enderror" name="jnspembayaran" id="jnspembayaran">
                                        <option value="" hidden selected disabled>--- Pilih Jenis Pembayaran ---</option>
                                        @foreach ($datajnsPembayaran as $itemListPembayaran)
                                            @php
                                                // Ambil tahun ajaran dan semester yang sesuai
                                                $tahunAjaran = $dataTahunAjaran->firstWhere('id_thn_ajaran', $itemListPembayaran->id_thn_ajaran);
                                            @endphp
                                            <option value="{{ $itemListPembayaran->id_jenis_pembayaran }}">
                                                {{ $itemListPembayaran->nama_pembayaran }} - T.A {{ $tahunAjaran->thn_ajaran }} Semester {{ $tahunAjaran->semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    @error('jnspembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="menu-icon tf-icons bx bxl-whatsapp"></i>Kirim Pesan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formBroadcastSerentak').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman form secara default
            Swal.fire({
                title: 'Berhasil!',
                text: 'Notifikasi berhasil dikirim secara serentak.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Melanjutkan pengiriman form setelah notifikasi
                }
            });
        });

        document.getElementById('formBroadcastTarget').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman form secara default
            Swal.fire({
                title: 'Berhasil!',
                text: 'Notifikasi berhasil dikirim ke siswa yang dipilih.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Melanjutkan pengiriman form setelah notifikasi
                }
            });
        });
    </script>
@endsection
