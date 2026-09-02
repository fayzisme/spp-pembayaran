@extends('layouts.app')

@section('title', 'Kelulusan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran Lunas</h3>
                    <div id="warning-message" class="alert alert-danger" style="display: {{ $unpaidExists ? 'block;' : 'none;'}}">
                        <strong>Perhatian!</strong> Masih ada pembayaran yang belum lunas di kelas asal dan tahun ajaran asal.
                    </div>
                    <div id="success-message" class="alert alert-success" style="display: none;">
                        <strong>Informasi!</strong> Pembayaran siswa pada kelas tersebut telah lunas.
                    </div>
                    <div class="card-options">
                        <form id="naikKelasForm" action="{{ route('kelulusan.naikKelas') }}" class="row" method="POST">
                            @csrf
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="id_kelas_asal" id="id_kelas_asal" class="form-select">
                                        <option value="">Pilih Kelas Asal</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ $item->id_kelas }}">{{ $item->tingkat . ' ' . $item->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p></p>
                                <div class="form-group">
                                    <select name="id_thn_ajaran_asal" id="id_thn_ajaran_asal" class="form-select">
                                        <option value="">Pilih Tahun Ajaran Asal</option>
                                        @foreach ($tahunAjaran as $item)
                                            <option value="{{ $item->id_thn_ajaran }}">{{ $item->thn_ajaran . ' ' . $item->semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 align-items-center d-flex">
                                <button type="button" id="naikKelasButton" class="btn btn-success mt-4" @if($unpaidExists || count($siswa) < 1) disabled @endif>Kelulusan</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body overflow-auto">
                        <form id="siswaForm">
                            <table id="table-siswa" class="table table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="siswa-table-body">
                                    @forelse ($siswa as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}
                                                @if (count($item->transaksi) == 0)
                                                    <input type="hidden" name="selected_siswa[]" value="{{ $item->id_siswa }}">
                                                @endif
                                            </td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->kelas->tingkat . ' ' . $item->kelas->nama_kelas }}</td>
                                            <td>{{ $item->tahunAjaran->thn_ajaran . ' ' . $item->tahunAjaran->semester }}</td>
                                            <td>
                                                @if (count($item->transaksi) > 0)
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                @else
                                                    <span class="badge bg-success">Lunas</span>   
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </form>
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
        let table = new DataTable('.table', {"order": []});
        const naikKelasButton = document.getElementById('naikKelasButton');
        const naikKelasForm = document.getElementById('naikKelasForm');
        const siswaTableBody = document.getElementById('siswa-table-body');
        const successMessage = document.getElementById('success-message');

        naikKelasButton.addEventListener('click', function(event) {
            event.preventDefault();
            const kelasAsal = document.getElementById('id_kelas_asal').value;
            const tahunAjaranAsal = document.getElementById('id_thn_ajaran_asal').value;
            const selectedSiswa = document.querySelectorAll('input[name="selected_siswa[]"]');
            
            if (selectedSiswa.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih minimal satu siswa untuk diluluskan',
                });
            } else {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin meluluskan kelas siswa yang dipilih?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, luluskan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(naikKelasForm);
                        selectedSiswa.forEach((input) => {
                            formData.append('selected_siswa[]', input.value);
                        });

                        fetch("{{ route('kelulusan.naikKelas') }}", {
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
                                    window.location.href = "{{ route('alumni.index') }}";
                                }
                            });
                        })
                        .catch(error => console.error('Error:', error));
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
                fetch("{{ route('kelulusan.checkUnpaid') }}"+`?kelas=${kelasAsal}&tahunAjaran=${tahunAjaranAsal}`)
                    .then(response => response.json())
                    .then(data => {
                        const warningMessage = document.getElementById('warning-message');
                        if (data.unpaidExists) {
                            warningMessage.style.display = 'block';
                            naikKelasButton.disabled = true;
                            successMessage.style.display = 'none';  // Hide success message
                        } else {
                            warningMessage.style.display = 'none';
                            naikKelasButton.disabled = false;
                            successMessage.style.display = 'block'; // Show success message
                        }

                        // Load siswa based on selected kelas and tahun ajaran
                        loadSiswa(kelasAsal, tahunAjaranAsal);
                    })
                    .catch(error => {
                        console.error('Error:', error)
                    });
            }
        }

        function loadSiswa(kelasAsal, tahunAjaranAsal) {
            fetch("{{ route('kelulusan.getSiswa') }}"+`?kelas=${kelasAsal}&tahunAjaran=${tahunAjaranAsal}`)
                .then(response => response.json())
                .then(data => {
                    table.clear();
                    let newData = [];
                    if (data.siswa.length > 0) {
                        data.siswa.forEach((item, index) => {
                            let input = `<input type="hidden" name="selected_siswa[]" value="${item.id_siswa}">`
                            let arr = [`${index + 1}${item.transaksi.length == 0 ? input : ''}`, `${item.nama}`, `${item.kelas.tingkat} ${item.kelas.nama_kelas}`, `${item.tahun_ajaran.thn_ajaran} ${item.tahun_ajaran.semester}`, `${item.transaksi.length > 0 ? '<span class="badge bg-danger">Belum Lunas</span>' : '<span class="badge bg-success">Lunas</span>'}`];
                            newData.push(arr);
                        });
                    } else {
                        naikKelasButton.disabled = true;
                        successMessage.style.display = 'none'; // Hide success message if no siswa
                    }
                    table.rows.add(newData).draw();
                })
                .catch(error => {
                    console.error('Error:', error)
                });
        }
    </script>
@endsection
