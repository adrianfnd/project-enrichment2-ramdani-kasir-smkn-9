@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
         
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total Transaksi Hari Ini</div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $totalTransactions }} Transaksi</h5>
                        <p>Jumlah transaksi yang dilakukan pada hari ini.</p>
                    </div>
                </div>
            </div>

           
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total Stok Barang</div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $totalStock }} Item</h5>
                        <p>Total barang yang tersedia di sistem saat ini.</p>
                    </div>
                </div>
            </div>

         
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Transaksi Terakhir</div>
                    </div>
                    <div class="card-body">
                        @if($lastTransaction && $lastTransaction->user)
                            <p><strong>{{ $lastTransaction->user->name }}</strong> melakukan pembelian pada <strong>{{ $lastTransaction->created_at->format('d/m/Y') }}</strong>.</p>
                            <p>Total Transaksi: Rp {{ number_format($lastTransaction->total, 0, ',', '.') }}</p>
                        @else
                            <p>Transaksi terakhir tidak tersedia atau tidak memiliki pengguna yang terkait.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Grafik Transaksi Terbaru</div>
                    </div>
                    <div class="card-body">
                        
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    var ctx = document.getElementById('transactionChart').getContext('2d');
    var transactionChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: [
                @foreach($recentTransactions as $transaction)
                    '{{ $transaction->created_at->format('d/m/Y') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Jumlah Transaksi',
                data: [
                    @foreach($recentTransactions as $transaction)
                        {{ $transaction->details->sum('quantity') }},
                    @endforeach
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                borderColor: 'rgba(54, 162, 235, 1)', 
                borderWidth: 1
            }, {
                label: 'Total Transaksi',
                data: [
                    @foreach($recentTransactions as $transaction)
                        {{ $transaction->total }},
                    @endforeach
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.2)', 
                borderColor: 'rgba(255, 99, 132, 1)', 
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
</script>
@endsection
