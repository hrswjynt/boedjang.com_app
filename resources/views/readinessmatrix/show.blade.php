@extends('layouts.app_admin')

@section('content')
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

                                <div class="row">
                                    @foreach ($bagian as $b)
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kompetensi</th>
                                                        <th>Staff</th>
                                                        <th>Atasan</th>
                                                        <th>Tanggal Atasan</th>
                                                    </tr>
                                                    @foreach ($b->kompetensi as $i => $k)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td class="text-left">{{ $k->kompetensi }}</td>
                                                            <td>
                                                                {{ $k->matrix ? $k->matrix->staff_name : '' }}
                                                            </td>
                                                            <td>
                                                                {{ $k->matrix ? $k->matrix->atasan_name : '' }}
                                                            </td>
                                                            <td>
                                                                {{ $k->matrix ? $k->matrix->atasan_valid_date : '' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
