@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Dashboard</h4>
            </div>
            <div class="card-body">
                @if (auth()->user()->id_role != 3)
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pembayaran <img
                                        src="../assets/img/icons/unicons/wallet-info.png"
                                        alt="Credit Card"
                                        class="rounded"
                                        style="width: 30px; height: 30px;"
                                    /></h5>
                                    
                                    <h4 class="card-text">
                                        <strong>
                                            Rp {{ number_format($total_pembayaran, 0, ',', '.') }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Pembayaran Bulanan  <img
                                        src="../assets/img/icons/unicons/chart-success.png"
                                        alt="chart success"
                                        class="rounded"
                                        style="width: 30px; height: 30px;"
                                        /></h5>
                                    <h4 class="card-text">
                                        <strong>
                                            Rp {{ number_format($total_bulanan, 0, ',', '.') }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Pembayaran Bebas <img src="../assets/img/icons/unicons/cc-primary.png" 
                                        alt="Credit Card" class="rounded"  style="width: 30px; height: 30px;"/></h5>
                                    <h4 class="card-text">
                                        <strong>
                                            Rp {{ number_format($total_lain_lain, 0, ',', '.') }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Total Siswa  <img src="../assets/img/icons/unicons/chart.png" alt="User" class="rounded" style="width: 30px; height: 30px;"/></h5>
                                    <h4 class="card-text">
                                        <strong>
                                            {{ $total_siswa }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pembayaran <img
                                        src="../assets/img/icons/unicons/wallet-info.png"
                                        alt="Credit Card"
                                        class="rounded"
                                        style="width: 30px; height: 30px;"
                                    /></h5>
                                    <h4 class="card-text">
                                        <strong>
                                            Rp {{ number_format($total_pembayaran, 0, ',', '.') }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Pembayaran Bulanan <img
                                        src="../assets/img/icons/unicons/chart-success.png"
                                        alt="chart success"
                                        class="rounded"
                                        style="width: 30px; height: 30px;"
                                        /></h5>
                                    <h4 class="card-text">
                                        <strong>
                                            Rp {{ number_format($total_bulanan, 0, ',', '.') }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Pembayaran Bebas <img src="../assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded"  style="width: 30px; height: 30px;"/></h5>
                                    <h4 class="card-text">
                                        <strong>
                                            Rp {{ number_format($total_lain_lain, 0, ',', '.') }}
                                        </strong>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body text-center">
                                    <img src="{{ asset('assets/img/1.jpg') }}" alt="SMP Pemda 2 Kesugihan" class="img-fluid mb-3"  >
                                    {{-- <img src="../assets/img/1.png" alt="SMP Pemda 2 Kesugihan" class="img-fluid mb-3"> --}}
                                    <p class="card-text">
                                        SMP Pemda 2 Kesugihan merupakan salah satu lembaga pendidikan yang beralamat di Jl. Belimbing No. 17, 
                                        Kabupaten Cilacap, Jawa Tengah yang
                                        berkomitmen untuk memberikan pelayanan pendidikan yang 
                                        berkualitas. SMP Pemda 2 Kesugihan sudah berdiri sejak 26 Mei 1984. Saat ini memiliki bermacam-macam kegiatan ekstrakurikuler,
                                        contohnya drumband, sepakbola, pencak silat, dan lain sebagainya.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (auth()->user()->id_role == 1 || auth()->user()->id_role == 2)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card" style="border: 1px solid #f3f2f5;">
                                <div class="card-body">
                                    <h5 class="card-title">Jumlah Siswa Yang Membayar Per Hari di Bulan {{ \Carbon\Carbon::now()->format('F Y') }}</h5>
                                    <canvas id="studentsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('studentsChart').getContext('2d');
        var studentsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($daily_payment_labels), // e.g., [1, 2, 3, ..., 31]
                datasets: [{
                    label: 'Jumlah Siswa Membayar',
                    data: @json($daily_payment_data), // e.g., [10, 20, 30, ..., 5]
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
