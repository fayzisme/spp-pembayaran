@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header ">
                    <h3>Riwayat Pembayaran</h3>
                </div>
                <div class="card-body p-0 overflow-auto">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tahun</th>
                                <th>Semester</th> <!-- Kolom Semester -->
                                <th>Nilai</th>
                                <th>Status</th>
                                <th>Metode Transaksi</th>
                                <th>Nama Petugas</th>
                                <th>Invoice</th>
                                <th>Created</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailPembayaran as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>
                                        @php
                                            $tahun = explode('/', $item->tahunAjaran->thn_ajaran);
                                            $tahun_ajaran = $item->tahunAjaran->semester == 'Ganjil' ? $tahun[0] : $tahun[1];
                                            echo $tahun_ajaran;
                                        @endphp
                                    </td>
                                    <td>{{ $item->tahunAjaran->semester }}</td> <!-- Data Semester -->
                                    <td>
                                        {{ $item->jumlah_transaksi ? 'Rp. ' . number_format($item->jumlah_transaksi, 0, ',', '.') : '' }}
                                    </td>
                                    <td>
                                        @if ($item->status_transaksi == 'Sukses')
                                            <span class="badge bg-success">Sukses</span>
                                        @elseif ($item->status_transaksi == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($item->status_transaksi == 'Gagal')
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->metode_transaksi }}</td>
                                    <td>
                                        @if ($item->metode_transaksi == 'Tunai' && $item->petugas)
                                            {{ $item->petugas->nama }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaksi.invoice', $item->id_detail_transaksi) }}"
                                            class="btn btn-danger">PDF</a>
                                    </td>
                                    <td>
                                        {{ $item->created_at }}
                                    </td>
                                    {{-- <td>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
                                    </td> --}}
                                    <td>
                                        @if ($item->status_transaksi == 'Pending' || $item->status_transaksi == 'Gagal')
                                            <form onsubmit="return submitFormRepay({{ $item->id_detail_transaksi }})">
                                                <input type="hidden" name="id_detail_transaksi" value="{{ $item->id_detail_transaksi }}">
                                                <button type="submit" class="btn btn-primary">Bayar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Pembayaran</h3>
                </div>
                <div class="card-body">
                    @if ($transaksi->status == 'Belum Lunas')
                        @if (Auth::user()->id_role == 3)
                            <form id="transaksiLainForm">
                                <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">
                                <div class="mb-3">
                                    <label for="tarif" class="form-label">Kekurangan yang Harus Dibayar</label>
                                    <input type="text" class="form-control" id="tarif"
                                        value="{{ 'Rp. ' . number_format($transaksi->tarif->tarif - $transaksi->detailPembayaran->sum('jumlah_transaksi'), 0, ',', '.') }}"
                                        readonly>
                                </div>
                                <input type="hidden" class="form-control" id="jumlah_transaksi" name="jumlah_transaksi">
                                <div class="mb-3">
                                    <label for="jumlah_transaksi2" class="form-label">Jumlah Bayar</label>
                                    <input type="text" class="form-control" id="jumlah_transaksi2" value="" required>
                                </div>
                                <div class="mb-3">
                                    <label for="metode_transaksi" class="form-label">Pembayaran</label>
                                    <select class="form-select" id="metode_transaksi" name="metode_transaksi" required>
                                        <option selected hidden disabled value="">Pilih Metode Pembayaran</option>
                                        <option value="Online">Online</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" id="pay-button">Bayar</button>
                                <a href="{{ route('transaksi.index') }}" type="button"
                                    class="btn btn-info">Kembali</a>
                            </form>
                            <script>
                                document.getElementById('transaksiLainForm').addEventListener('submit', function(event) {
                                    var kekurangan = parseInt("{{ $transaksi->tarif->tarif }}") - parseInt("{{ $transaksi->detailPembayaran->sum('jumlah_transaksi') }}");
                                    var jumlahBayar = parseInt(document.getElementById('jumlah_transaksi2').value.replace(/\D/g, ''));
        
                                    if (jumlahBayar > kekurangan) {
                                        event.preventDefault();
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Jumlah bayar tidak boleh melebihi kekurangan yang harus dibayar.',
                                        });
                                    } else if (jumlahBayar <= 0) {
                                        event.preventDefault();
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Jumlah bayar harus lebih dari 0.',
                                        });
                                    } else if (jumlahBayar <= kekurangan) {
                                        event.preventDefault();
                                        // Allow form submission if the amount is valid
                                        console.log("Form is valid. Submitting...");
                                        return submitForm()
                                    }
                                });
                            </script>
                            
                        @else
                        <form id="transaksiLainForm"
                        action="{{ route('transaksi.createPayment', $transaksi->id_transaksi) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">
                        <div class="mb-3">
                            <label for="tarif" class="form-label">Kekurangan yang Harus Dibayar</label>
                            <input type="text" class="form-control" id="tarif"
                                value="{{ 'Rp. ' . number_format($transaksi->tarif->tarif - $transaksi->detailPembayaran->sum('jumlah_transaksi'), 0, ',', '.') }}"
                                readonly>
                        </div>
                        <input type="hidden" class="form-control" id="jumlah_transaksi" name="jumlah_transaksi">
                        <div class="mb-3">
                            <label for="jumlah_transaksi2" class="form-label">Jumlah Bayar</label>
                            <input type="text" class="form-control" id="jumlah_transaksi2" value="">
                        </div>
                        <div class="mb-3">
                            <label for="id_petugas" class="form-label">Petugas</label>
                            <select class="form-control @error('id_petugas') is-invalid @enderror" id="id_petugas" name="id_petugas" required>
                                <option value="" disabled hidden selected>Pilih Petugas</option>
                                @foreach ($petugas as $item)
                                <option value="{{ $item->id_petugas }}" {{ $item->id_petugas == old('id_petugas') ? 'selected' : '' }}>{{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_petugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="metode_transaksi" class="form-label">Pembayaran</label>
                            <select class="form-select" id="metode_transaksi" name="metode_transaksi" required>
                                <option selected hidden disabled value="">Pilih Metode Pembayaran</option>
                                <option value="Tunai" >Tunai</option>
                                {{-- <option value="tunai" >Tunai</option> --}}
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Bayar</button>
                        <a href="{{ route('transaksi.index', Session::get('last_query')) }}" type="button"
                            class="btn btn-info">Kembali</a>
                    </form>

                      <!-- Skrip JavaScript untuk validasi jumlah bayar -->
                    <script>                        
                        document.getElementById('transaksiLainForm').addEventListener('submit', function(event) {
                            var kekurangan = parseInt("{{ $transaksi->tarif->tarif }}") - parseInt("{{ $transaksi->detailPembayaran->sum('jumlah_transaksi') }}");
                            var jumlahBayar = parseInt(document.getElementById('jumlah_transaksi2').value.replace(/\D/g, ''));
                            console.log('masuk');
                            if (jumlahBayar > kekurangan) {
                                event.preventDefault();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Jumlah bayar tidak boleh melebihi kekurangan yang harus dibayar.',
                                });
                            } else if (jumlahBayar <= 0) {
                                event.preventDefault();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Jumlah bayar harus lebih dari 0.',
                                });
                            } else if (jumlahBayar <= kekurangan) {
                                // Allow form submission if the amount is valid
                                console.log("Form is valid. Submitting...");
                            }
                        });
                    </script>

                            
                        @endif
                    @else
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Pembayaran Lunas</h4>
                            <p>Pembayaran sudah lunas, tidak ada tagihan yang harus dibayar.</p>
                        </div>
                        <a href="{{ route('transaksi.index', Session::get('last_query')) }}" type="button" class="btn btn-info">Kembali</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script type="text/javascript">
        // bayar ulang
        function submitFormRepay(id_detail_transaksi) {
            $.ajax({
                url: "{{ route('transaksi.repay', $transaksi->id_transaksi) }}",
                type: "POST",
                data: {
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                    id_detail_transaksi: id_detail_transaksi
                },
                success: function(data) {
                    if (data.status == 'error') {
                        alert(data.message);
                    } else {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                console.log(result);
                                $.ajax({
                                    url: "{{ route('notification.handler') }}",
                                    type: "POST",
                                    data: {
                                        _method: 'POST',
                                        _token: '{{ csrf_token() }}',
                                        order_id: result.order_id,
                                        transaction_status: result.transaction_status,
                                        fraud_status: result.fraud_status
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        swal("Success", "Pembayaran Berhasil",
                                                "success")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.showLain', $transaksi->id_transaksi) }}";
                                            });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred. Please try again.');
                                    }
                                })
                            },
                            onPending: function(result) {
                                console.log(result);
                                $.ajax({
                                    url: "{{ route('notification.handler') }}",
                                    type: "POST",
                                    data: {
                                        _method: 'POST',
                                        _token: '{{ csrf_token() }}',
                                        order_id: result.order_id,
                                        transaction_status: result.transaction_status,
                                        fraud_status: result.fraud_status
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        swal("Warning", "Pembayaran Pending", "warning")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.showLain', $transaksi->id_transaksi) }}";
                                            });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred. Please try again.');
                                    }
                                });
                            },
                            onError: function(result) {
                                console.log(result);
                                $.ajax({
                                    url: "{{ route('notification.handler') }}",
                                    type: "POST",
                                    data: {
                                        _method: 'POST',
                                        _token: '{{ csrf_token() }}',
                                        order_id: result.order_id,
                                        transaction_status: result.transaction_status,
                                        fraud_status: result.fraud_status
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        swal("Error", "Pembayaran Gagal", "error")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.showLain', $transaksi->id_transaksi) }}";
                                            });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred. Please try again.');
                                    }
                                });
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
            return false;
        }

        function submitForm() {
            $.ajax({
                url: "{{ route('transaksi.createPayment', $transaksi->id_transaksi) }}",
                type: "POST",
                data: {
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                    jumlah_transaksi: $('#jumlah_transaksi').val(),
                    metode_transaksi: $('#metode_transaksi').val(),
                    id_transaksi: $('input[name="id_transaksi"]').val()
                },
                success: function(data) {
                    if (data.status == 'error') {
                        alert(data.message);
                    } else {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                console.log(result);
                                $.ajax({
                                    url: "{{ route('notification.handler') }}",
                                    type: "POST",
                                    data: {
                                        _method: 'POST',
                                        _token: '{{ csrf_token() }}',
                                        order_id: result.order_id,
                                        transaction_status: result.transaction_status,
                                        fraud_status: result.fraud_status
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        swal("Success", "Pembayaran Berhasil",
                                                "success")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.showLain', $transaksi->id_transaksi) }}";
                                            });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred. Please try again.');
                                    }

                                })
                            },
                            onPending: function(result) {
                                console.log(result);
                                $.ajax({
                                    url: "{{ route('notification.handler') }}",
                                    type: "POST",
                                    data: {
                                        _method: 'POST',
                                        _token: '{{ csrf_token() }}',
                                        order_id: result.order_id,
                                        transaction_status: result.transaction_status,
                                        fraud_status: result.fraud_status
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        swal("Warning", "Pembayaran Pending", "warning")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.showLain', $transaksi->id_transaksi) }}";
                                            });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred. Please try again.');
                                    }
                                });
                            },
                            onError: function(result) {
                                console.log(result);
                                $.ajax({
                                    url: "{{ route('notification.handler') }}",
                                    type: "POST",
                                    data: {
                                        _method: 'POST',
                                        _token: '{{ csrf_token() }}',
                                        order_id: result.order_id,
                                        transaction_status: result.transaction_status,
                                        fraud_status: result.fraud_status
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        swal("Error", "Pembayaran Gagal", "error")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.showLain', $transaksi->id_transaksi) }}";
                                            });
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                        alert('An error occurred. Please try again.');
                                    }
                                });
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
            return false;
        }
    </script>
@endsection
