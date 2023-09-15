@extends('layouts.app_admin')

@section('content')
    <style>
        input[type="checkbox"] {
            width: 1.5rem;
            height: 1.5rem;
        }
    </style>

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Readiness Matrix</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6><b>Tambah Readiness Matrix</b></h6>
                        <a href="{{ route('readinessmatrix.index') }}" class="btn btn-info btn-sm add">
                            <i class="fa fa-arrow-left "></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <div id="success-delete">
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible" id="success-alert">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        @if ($message = Session::get('danger'))
                            <div class="alert alert-danger alert-dismissible" id="danger-alert">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('readinessmatrix.store') }}" id="readinessmatrix_form">
                            @csrf
                            <div class="container-fluid mt-3">

                                <div class="row gy-4">
                                    <form method="POST" action="{{ route('readinessmatrix.update', $matrixHeader->id) }}"
                                        id="readinessmatrix_form">
                                        @csrf
                                        @method('PUT')
                                        <div class="container-fluid mt-3">
                                            <div class="row">
                                                <div class="col-12 p-1">
                                                    <span class="font-weight-bold text-secondary">Atasan</span>
                                                </div>

                                                <div class="col-12 p-2">
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $matrixHeader->dataAtasan->name }}" readonly>
                                                </div>

                                                <div class="col-12 p-1">
                                                    <span class="font-weight-bold text-secondary">Catatan</span>
                                                </div>

                                                <div class="col-12 p-2">
                                                    <input type="text" class="form-control mb-3"
                                                        value="{{ $matrixHeader->catatan }}" readonly>
                                                </div>

                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table
                                                            id="table-{{ str_replace(' ', '-', strtolower($matrixHeader->dataBagian->nama)) }}"
                                                            class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>
                                                                        {{ $matrixHeader->dataBagian->nama . ' (' . $matrixHeader->dataBagian->kode . ')' }}
                                                                    </th>
                                                                    <th>Tipe</th>
                                                                    <th>Checked</th>
                                                                    <th>Valid</th>
                                                                    <th>Tanggal Valid</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($matrixHeader->matrix as $i => $matrix)
                                                                    <tr>
                                                                        <td>{{ $i + 1 }}</td>
                                                                        <td>
                                                                            {{ $matrix->kompetensi }}
                                                                        </td>
                                                                        <td>
                                                                            @php
                                                                                switch ($matrix->tipe) {
                                                                                    case '1':
                                                                                        $kom = 'K';
                                                                                        break;
                                                                                
                                                                                    case '2':
                                                                                        $kom = 'K';
                                                                                        break;
                                                                                
                                                                                    case '3':
                                                                                        $kom = 'K';
                                                                                        break;
                                                                                
                                                                                    default:
                                                                                        $kom = '';
                                                                                        break;
                                                                                }
                                                                            @endphp
                                                                            {{ $kom }}
                                                                        </td>
                                                                        <td>
                                                                            <input type="checkbox" name="staff[]"
                                                                                value="{{ $matrix->id }}"
                                                                                @if ($matrix->staff_valid) checked @endif
                                                                                disabled>
                                                                        </td>
                                                                        <td>
                                                                            <input type="checkbox" name="atasan[]"
                                                                                value="{{ $matrix->id }}"
                                                                                @if ($matrix->atasan_valid) checked @endif
                                                                                disabled>
                                                                        </td>
                                                                        <td>{{ $matrix->atasan_valid_date }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
