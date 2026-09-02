@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Kenaikan Kelas</h3>
                    <div id="warning-message" class="alert alert-danger" style="display: none;">
                        <strong>Perhatian!</strong> Masih ada pembayaran yang belum lunas di kelas asal dan tahun ajaran asal.
                    </div>
                    <div id="success-message" class="alert alert-success" style="display: none;">
                        <strong>Informasi!</strong> Pembayaran siswa pada kelas tersebut telah lunas.
                    </div>
                    <div class="card-options">
                        <form id="naikKelasForm" action="{{ route('kenaikan-kelas.naikKelas') }}" class="row" method="POST">
                            @csrf
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_kelas_asal">Kelas Asal</label>
                                    <select name="id_kelas_asal" id="id_kelas_asal" class="form-select">
                                        <option value="">Pilih Kelas Asal</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id_kelas }}">{{ $item->tingkat . $item->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_thn_ajaran_asal">Tahun Ajaran Asal</label>
                                    <select name="id_thn_ajaran_asal" id="id_thn_ajaran_asal" class="form-select">
                                        <option value="">Pilih Tahun Ajaran Asal</option>
                                        @foreach ($tahunAjaran as $item)
                                            <option value="{{ $item->id_thn_ajaran }}">{{ $item->thn_ajaran . ' ' . $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 align-items-center d-flex">
                                <button type="button" id="naikKelasButton" class="btn btn-success mt-4" disabled>Naik Kelas</button>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_kelas_tujuan">Kelas Tujuan</label>
                                    <select name="id_kelas_tujuan" id="id_kelas_tujuan" class="form-select">
                                        <option value="">Pilih Kelas Tujuan</option>
                                        @foreach ($kelas_tujuan as $item)
                                            <option value="{{ $item->id_kelas }}">{{ $item->tingkat . $item->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_thn_ajaran_tujuan">Tahun Ajaran Tujuan</label>
                                    <select name="id_thn_ajaran_tujuan" id="id_thn_ajaran_tujuan" class="form-select">
                                        <option value="">Pilih Tahun Ajaran Tujuan</option>
                                        @foreach ($tahunAjaran as $item)
                                            <option value="{{ $item->id_thn_ajaran }}">{{ $item->thn_ajaran . ' ' . $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body overflow-auto">
                        <table id="table-siswa" class="table table-striped table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswa as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->kelas->tingkat . $item->kelas->nama_kelas }}</td>
                                        <td>{{ $item->tahunAjaran->thn_ajaran . ' ' . $item->tahunAjaran->semester }}</td>
                                        {{-- <th>Status</th> --}}
                                        <td>
                                            @php
                                                // Ambil data transaksi dari tabel detail_transaksi berdasarkan ID siswa
                                                $detailTransaksi = \App\Models\DetailPembayaran::where('id_siswa', $item->id_siswa)->get();
                                                // Ambil data transaksi dari tabel transaksi berdasarkan ID siswa
                                                $transaksi = \App\Models\Pembayaran::where('id_siswa', $item->id_siswa)->get();
                                        
                                                // Jika data transaksi kosong
                                                if ($detailTransaksi->isEmpty() && $transaksi->isEmpty()) {
                                                    $totalBayar = 0;
                                                    $jumlahTransaksi = 0;
                                                } else {
                                                    // Hitung total bayar dan jumlah transaksi dari detail_transaksi
                                                    $totalBayarDetail = $detailTransaksi->sum('total_bayar');
                                                    $jumlahTransaksiDetail = $detailTransaksi->sum('jumlah_transaksi');
                                        
                                                    // Hitung total bayar dari transaksi
                                                    $totalBayarTransaksi = $transaksi->sum('total_bayar');
                                        
                                                    // Total bayar gabungan dari detail_transaksi dan transaksi
                                                    $totalBayar = $totalBayarDetail + $totalBayarTransaksi;
                                                    $jumlahTransaksi = $jumlahTransaksiDetail; // Sesuaikan sesuai kebutuhan
                                                }
                                            @endphp
                                        
                                            {{-- Debugging values --}}
                                            {{-- <pre>
                                                Total Bayar (Detail): {{ $totalBayarDetail }}
                                                Total Bayar (Transaksi): {{ $totalBayarTransaksi }}
                                                Total Bayar: {{ $totalBayar }}
                                                Jumlah Transaksi: {{ $jumlahTransaksi }}
                                            </pre> --}}
                                        
                                            @if ($item->id_siswa == null || $item->status == 1)
                                                <span class="badge bg-secondary">Lulus</span>
                                            @elseif ($jumlahTransaksi == 0)
                                                <span class="badge bg-info">Belum Ada Tagihan</span>
                                            @elseif ($jumlahTransaksi < $totalBayar)
                                                <span class="badge bg-danger">Belum Lunas</span>
                                            @else
                                                <span class="badge bg-success">Lunas</span>
                                            @endif
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
                <div class="card-footer">
                    {{ $siswa->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-dashboard')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var table = new DataTable('.table');
        const naikKelasButton = document.getElementById('naikKelasButton');
        const naikKelasForm = document.getElementById('naikKelasForm');

        naikKelasButton.addEventListener('click', function() {
            const kelasAsal = document.getElementById('id_kelas_asal').value;
            const tahunAjaranAsal = document.getElementById('id_thn_ajaran_asal').value;
            const kelasTujuan = document.getElementById('id_kelas_tujuan').value;
            const tahunAjaranTujuan = document.getElementById('id_thn_ajaran_tujuan').value;

            if (!kelasAsal || !tahunAjaranAsal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Kelas asal dan tahun ajaran asal tidak boleh kosong',
                });
            } else if (!kelasTujuan || !tahunAjaranTujuan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Kelas tujuan dan tahun ajaran tujuan tidak boleh kosong',
                });
            } else {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda akan menaikkan kelas siswa yang dipilih.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, naikkan kelas!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(naikKelasForm);

                        fetch("{{ route('kenaikan-kelas.naikKelas') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: data.success ? 'success' : 'error',
                                title: data.success ? 'Berhasil' : 'Gagal',
                                text: data.message,
                            }).then(() => {
                                if (data.success) {
                                    location.reload();
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error)
                        });
                    }
                });
            }
        });

        document.getElementById('id_kelas_asal').addEventListener('change', checkUnpaidTransactions);
        document.getElementById('id_thn_ajaran_asal').addEventListener('change', checkUnpaidTransactions);

        function checkUnpaidTransactions() {
            const kelasAsal = document.getElementById('id_kelas_asal').value;
            const tahunAjaranAsal = document.getElementById('id_thn_ajaran_asal').value;

            if (kelasAsal && tahunAjaranAsal) {
                fetch("{{ route('kenaikan-kelas.checkUnpaid') }}"+`?kelas=${kelasAsal}&tahunAjaran=${tahunAjaranAsal}`)
                    .then(response => response.json())
                    .then(data => {
                        const warningMessage = document.getElementById('warning-message');
                        // const successMessage = document.getElementById('success-message');
                        if (data.unpaidExists) {
                            warningMessage.style.display = 'block';
                            // successMessage.style.display = 'none';
                            naikKelasButton.disabled = true;
                        } else {
                            warningMessage.style.display = 'none';
                            // successMessage.style.display = 'block';
                            naikKelasButton.disabled = false;
                        }

                        // Load siswa based on selected kelas and tahun ajaran
                        loadSiswa(kelasAsal, tahunAjaranAsal, data.unpaidExists);
                    })
                    .catch(error => {
                        console.error('Error:', error)
                    });
            }
        }

        function loadSiswa(kelasAsal, tahunAjaranAsal, unpaidExists = true) {
            fetch("{{ route('kenaikan-kelas.getSiswa') }}"+`?kelas=${kelasAsal}&tahunAjaran=${tahunAjaranAsal}`)
                .then(response => response.json())
                .then(data => {
                    const successMessage = document.getElementById('success-message');
                    table.clear();
                    let newData = [];
                    if (data.siswa.length > 0) {
                        data.siswa.forEach((item, index) => {
                            let arr = [`${index + 1}`, `${item.nama}`, `${item.kelas.tingkat} ${item.kelas.nama_kelas}`, `${item.tahun_ajaran.thn_ajaran} ${item.tahun_ajaran.semester}`, `${item.cek_transaksi.length > 0 ?  (item.cek_transaksi[0].status == "Belum Lunas" ? '<span class="badge bg-danger">Belum Lunas</span>' : '<span class="badge bg-success">Lunas</span>') : '<span class="badge bg-info">Belum Ada Tagihan</span>'}`];
                            newData.push(arr);
                        });
                    }
                    else {
                        naikKelasButton.disabled = true;
                    }

                    if (unpaidExists) {
                        successMessage.style.display = 'none';
                    }
                    else {
                        if (data.siswa.length > 0) {
                            successMessage.style.display = 'block';
                        }
                    }
                    // Menambahkan data baru
                    table.rows.add(newData).draw();
                })
                .catch(error => {
                    console.error('Error:', error)
                });
        }
    </script>
@endsection
