@extends('layouts.app')

@section('title', 'Tambah Tarif')

@section('content')
    <div class="row">
        {{-- show data jenis transaksi --}}
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h1>Data Jenis Pembayaran</h1>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="nama_pembayaran">Nama Pembayaran</label>
                        <input type="text" name="nama_pembayaran" id="nama_pembayaran" class="form-control"
                            value="{{ $jenisPembayaran->nama_pembayaran }}" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="thn_ajaran">Tahun Ajaran</label>
                        <input type="text" name="thn_ajaran" id="thn_ajaran" class="form-control"
                            value="{{ $jenisPembayaran->tahunAjaran->thn_ajaran }}" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="semester">Semester</label>
                        <input type="text" name="semester" id="semester" class="form-control"
                            value="{{ $jenisPembayaran->tahunAjaran->semester }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="tipe_bayar">Tipe Pembayaran</label>
                        <input type="text" name="tipe_bayar" id="tipe_bayar" class="form-control"
                            value="{{ $jenisPembayaran->tipe_bayar }}" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h1>Tambah Tarif</h1>
                </div>
                <div class="card-body">
                    <form id="tarifForm" action="{{ route('tarif.store', $jenisPembayaran->id_jenis_pembayaran) }}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <div class="dropdown">
                                <div class="form-control" id="kelasDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    data-bs-auto-close="outside">
                                    <div id="kelasSelected" class="">Pilih Kelas</div>
                                </div>
                                <div class="dropdown-menu w-100" aria-labelledby="kelasDropdown">
                                    <div class="dropdown-header">
                                        <input type="text" class="form-control" id="kelasSearch" placeholder="Cari Kelas">
                                    </div>
                                    <div class="dropdown-item">
                                        <span class="check-icon"></span> <input type="checkbox" class="btn-check"
                                            autocomplete="off" id="select-all" value="all"> <label for="select-all">Select All</label>
                                    </div>
                                    <div class="dropdown-item">
                                        <span class="check-icon"></span> <input type="checkbox" class="btn-check"
                                            autocomplete="off" id="deselect-all" value="deall">
                                        <label for="deselect-all">Deselect All</label>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <div class="scroll">
                                        @foreach ($kelasOptions as $kelas)
                                            <div class="dropdown-item d-flex align-items-center">
                                                <input type="checkbox" value="{{ $kelas->id_kelas }}" id="kelas-{{ $kelas->id_kelas }}" class="btn-check" autocomplete="off" name="kelas[]">
                                                <label for="kelas-{{ $kelas->id_kelas }}">
                                                    <i class="bx bx-check selected-icon"></i>
                                                    {{ $kelas->tingkat . ' ' . $kelas->nama_kelas }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tarif">Tarif</label>
                            <input type="text" name="tarif" id="tarif" class="form-control">
                        </div>
                        <div class="form-group-row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                                <a href="{{ route('jenis-transaksi.index') }}" class="btn btn-secondary ml-3">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const kelasDropdown = document.getElementById('kelasDropdown');
        const kelasSelected = document.getElementById('kelasSelected');
        const kelasOptions = document.querySelectorAll('.dropdown-menu .dropdown-item input[type="checkbox"]');
        const selectAll = document.getElementById('select-all');
        const deselectAll = document.getElementById('deselect-all');
        const kelasSearch = document.getElementById('kelasSearch');
    
        kelasOptions.forEach(option => {
            option.addEventListener('change', updateKelasSelected);
        });
    
        selectAll.addEventListener('change', () => {
            kelasOptions.forEach(option => {
                if (option.value !== 'deall' && option.value !== 'all') {
                    option.checked = selectAll.checked;
                }
            });
            deselectAll.checked = false;
            updateKelasSelected();
        });
    
        deselectAll.addEventListener('change', () => {
            kelasOptions.forEach(option => {
                if (option.value !== 'deall' && option.value !== 'all') {
                    option.checked = false;
                }
            });
            updateKelasSelected();
        });
    
        kelasSearch.addEventListener('input', () => {
            const searchValue = kelasSearch.value.toLowerCase();
            kelasOptions.forEach(option => {
                const label = option.nextElementSibling.textContent.toLowerCase();
                option.parentElement.style.display = label.includes(searchValue) ? 'block' : 'none';
            });
        });
    
        function updateKelasSelected() {
            const selectedOptions = Array.from(kelasOptions)
                .filter(option => option.checked)
                .filter(option => option.value !== 'deall')
                .filter(option => option.value !== 'all');
    
            const selectedLabels = selectedOptions.map(option => option.nextElementSibling.textContent);
            kelasSelected.textContent = selectedLabels.join(', ') || 'Pilih Kelas';
        }
    </script>

@endsection
