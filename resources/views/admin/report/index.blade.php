@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="mt-4">Laporan Keuangan</h1>
                            <form action="{{ route('laporan.generate-monthly') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Buat Laporan (Bulanan)
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Laporan</th>
                                                <th>Dibuat Oleh</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reports as $key => $report)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $report->report_date->format('d/m/Y') }}</td>
                                                    <td>{{ $report->user->name }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ Storage::url($report->report_link) }}" class="btn btn-success btn-sm" target="_blank">
                                                            Download
                                                        </a>
                                                        <form action="{{ route('laporan.destroy', $report->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
@endsection