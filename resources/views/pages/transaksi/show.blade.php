@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header ">
                    <h3>Riwayat Pembayaran SPP</h3>
                </div>
                <div class="card-body p-0 overflow-auto">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tahun</th>
                                <th>Semester</th> <!-- Kolom Semester ditambahkan -->
                                <th>Bulan</th>
                                <th>Total Pembayaran</th>
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
                                    <td>
                                        {{ $item->tahunAjaran->semester }} <!-- Data Semester ditampilkan -->
                                    </td>
                                    <td>
                                        {{ $item->bulan }}
                                    </td>
                                    <td>
                                        {{ $item->tarif->tarif ? 'Rp. ' . number_format($item->tarif->tarif, 0, ',', '.') : '' }}
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
                                    
                                    {{-- <td>
                                        <a href="{{ route('transaksi.invoice', $item->id_detail_transaksi) }}"
                                            class="btn btn-danger">PDF</a>
                                    </td> --}}
                                    <td>
                                        <a href="{{ route('transaksi.invoice', $item->id_detail_transaksi) }}" target="blank"
                                            class="btn btn-danger">PDF</a>
                                    </td>
                                    
                                    <td>
                                        {{ $item->created_at }}
                                    </td>
                                    <td>
                                        @if ($item->status_transaksi == 'Pending' || $item->status_transaksi == 'Gagal')
                                            <form onsubmit="return submitFormRepayMonthly({{ $item->id_detail_transaksi }})">
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
                            <form id="transaksiForm" onsubmit="return submitForm();">
                                <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <div class="dropdown">
                                        <div class="form-control" id="bulanDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false" data-bs-auto-close="outside">
                                            <div id="bulanSelected" class="">Pilih Bulan</div>
                                        </div>
                                        <div class="dropdown-menu w-100" aria-labelledby="bulanDropdown">
                                            <div class="dropdown-header">
                                                <input type="text" class="form-control" id="bulanSearch"
                                                    placeholder="Cari Bulan">
                                            </div>
                                            <div class="dropdown-item">
                                                <span class="check-icon"></span> <input type="checkbox" class="btn-check"
                                                    autocomplete="off" id="select-all" value="all"> <label
                                                    for="select-all">Select All</label>
                                            </div>
                                            <div class="dropdown-item">
                                                <span class="check-icon"></span> <input type="checkbox" class="btn-check"
                                                    autocomplete="off" id="deselect-all">
                                                <label for="deselect-all">Deselect All</label>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <div class="scroll">
                                                @foreach ($bulanOptions as $bulan)
                                                    @if (!in_array($bulan, $alreadyPaidMonths))
                                                        <div class="dropdown-item d-flex align-items-center">
                                                            <input type="checkbox" value="{{ $bulan }}"
                                                                id="{{ $bulan }}" class="btn-check"
                                                                autocomplete="off" name="bulan[]">
                                                            <label for="{{ $bulan }}" class="">
                                                                <i class="bx bx-check selected-icon"></i>
                                                                {{ $bulan }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_transaksi" class="form-label">Jumlah Total</label>
                                    <input type="text" class="form-control" id="jumlah_transaksi" value="Rp. 0"
                                        readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="metode_transaksi" class="form-label">Pembayaran</label>
                                    <select class="form-select" id="metode_transaksi" name="metode_transaksi" required>
                                        <option selected hidden disabled value="">Pilih Metode Pembayaran</option>
                                        @if (Auth::user()->id_role == 3)
                                            <option value="Online">Online</option>
                                        @else
                                            <option value="Tunai">Tunai</option>
                                            {{-- <option value="tunai">Tunai</option> --}}
                                        @endif
                                    </select>
                                </div>
                                @if (Auth::user()->id_role == 3)
                                    <button type="submit" class="btn btn-primary" id="pay-button">Bayar</button>
                                @else
                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                @endif
                                <a href="{{ route('transaksi.index') }}" type="button"
                                    class="btn btn-info">Kembali</a>
                            </form>
                        @else
                            <form id="transaksiForm"
                                action="{{ route('transaksi.createPaymentMonthly', $transaksi->id_transaksi) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <div class="dropdown">
                                        <div class="form-control" id="bulanDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false" data-bs-auto-close="outside">
                                            <div id="bulanSelected" class="">Pilih Bulan</div>
                                        </div>
                                        <div class="dropdown-menu w-100" aria-labelledby="bulanDropdown">
                                            <div class="dropdown-header">
                                                <input type="text" class="form-control" id="bulanSearch"
                                                    placeholder="Cari Bulan">
                                            </div>
                                            <div class="dropdown-item">
                                                <span class="check-icon"></span> <input type="checkbox" class="btn-check"
                                                    autocomplete="off" id="select-all" value="all"> <label
                                                    for="select-all">Select All</label>
                                            </div>
                                            <div class="dropdown-item">
                                                <span class="check-icon"></span> <input type="checkbox" class="btn-check"
                                                    autocomplete="off" id="deselect-all" value="deall">
                                                <label for="deselect-all">Deselect All</label>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <div class="scroll">
                                                @foreach ($bulanOptions as $bulan)
                                                    @if (!in_array($bulan, $alreadyPaidMonths))
                                                        <div class="dropdown-item d-flex align-items-center">
                                                            <input type="checkbox" value="{{ $bulan }}"
                                                                id="{{ $bulan }}" class="btn-check"
                                                                autocomplete="off" name="bulan[]">
                                                            <label for="{{ $bulan }}" class="">
                                                                <i class="bx bx-check selected-icon"></i>
                                                                {{ $bulan }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_transaksi" class="form-label">Jumlah Total</label>
                                    <input type="text" class="form-control" id="jumlah_transaksi" value="Rp. 0"
                                        readonly>
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
                                        {{-- <option value="manual">Manual</option> --}}
                                        <option value="Tunai">Tunai</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Bayar</button>
                                <a href="{{ route('transaksi.index', Session::get('last_query')) }}" type="button"
                                    class="btn btn-info">Kembali</a>
                            </form>
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
        function submitFormRepayMonthly(id_detail_transaksi) {
            $.ajax({
                url: "{{ route('transaksi.repayMonthly', $transaksi->id_transaksi) }}",
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
                                        swal("Success", "Pembayaran berhasil",
                                                "success")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.show', $transaksi->id_transaksi) }}";
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
                                        swal("Warning", "Pembayaran pending", "warning")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.show', $transaksi->id_transaksi) }}";
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
                                        swal("Error", "Pembayaran gagal", "error")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.show', $transaksi->id_transaksi) }}";
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
                url: "{{ route('transaksi.createPaymentMonthly', $transaksi->id_transaksi) }}",
                type: "POST",
                data: {
                    _method: 'POST',
                    _token: '{{ csrf_token() }}',
                    bulan: $('input[name="bulan[]"]:checked').map(function() {
                        return $(this).val();
                    }).get(),
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
                                        swal("Success", "Pembayaran berhasil",
                                                "success")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.show', $transaksi->id_transaksi) }}";
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
                                        swal("Warning", "Pembayaran pending", "warning")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.show', $transaksi->id_transaksi) }}";
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
                                        swal("Error", "Pembayaran gagal", "error")
                                            .then((value) => {
                                                window.location.href =
                                                    "{{ route('transaksi.show', $transaksi->id_transaksi) }}";
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

<script>
    const bulanDropdown = document.getElementById('bulanDropdown');
    const bulanSelected = document.getElementById('bulanSelected');
    const bulanOptions = document.querySelectorAll('.dropdown-menu .dropdown-item input[type="checkbox"]');
    const selectAll = document.getElementById('select-all');
    const deselectAll = document.getElementById('deselect-all');
    const bulanSearch = document.getElementById('bulanSearch');

    bulanOptions.forEach(option => {
        option.addEventListener('change', updateBulanSelected);
    });

    selectAll.addEventListener('change', () => {
        bulanOptions.forEach(option => {
            if (option.value !== 'deall' && option.value !== 'all') {
                option.checked = selectAll.checked;
            }
        });
        deselectAll.checked = false;
        updateBulanSelected();
    });

    deselectAll.addEventListener('change', () => {
        bulanOptions.forEach(option => {
            if (option.value !== 'deall' && option.value !== 'all') {
                option.checked = false;
            }
        });
        updateBulanSelected();
    });

    bulanSearch.addEventListener('input', () => {
        const searchValue = bulanSearch.value.toLowerCase();
        bulanOptions.forEach(option => {
            const label = option.nextElementSibling.textContent.toLowerCase();
            option.parentElement.style.display = label.includes(searchValue) ? 'block' : 'none';
        });
    });

    function updateBulanSelected() {
        const selectedOptions = Array.from(bulanOptions)
            .filter(option => option.checked)
            .filter(option => option.value !== 'deall')
            .filter(option => option.value !== 'all');

        const selectedLabels = selectedOptions.map(option => option.nextElementSibling.textContent);
        bulanSelected.textContent = selectedLabels.join(', ') || 'Pilih Bulan';

        updateJumlahPembayaran();
    }

    function updateJumlahPembayaran() {
        const selectedOptions = Array.from(bulanOptions)
            .filter(option => option.checked)
            .filter(option => option.value !== 'deall')
            .filter(option => option.value !== 'all');

        let total = 0;
        const tarif = {{ $transaksi->tarif->tarif }};
        selectedOptions.forEach(option => {
            total += tarif;
        });

        document.getElementById('jumlah_transaksi').value = 'Rp. ' + new Intl.NumberFormat('id-ID').format(total);
    }
</script>

@endsection
