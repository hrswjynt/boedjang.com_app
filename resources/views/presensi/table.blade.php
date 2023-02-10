@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pengajuan Form Cuti</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>List Data Presensi Online</b></h6>
                    <a href="{{ route('presensi.index') }}" class="btn btn-success btn-sm add">
                        <i class="fa fa-plus "></i>
                        <span>Presensi Online</span>
                    </a>
                </div>
                <div class="card-body">
                  <div id="success-delete">
                  </div>
                  @if ($message = Session::get('success'))
                  <div class="alert alert-success alert-dismissible" id="success-alert">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <p>{!! $message !!}</p>
                  </div>
                  @endif
                  @if ($message = Session::get('danger'))
                  <div class="alert alert-danger alert-dismissible" id="danger-alert">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <p>{!! $message !!}</p>
                  </div>
                  @endif
                  <div id="cuti-data">
                    <div class="table-responsive">
                        <table class="table" id="table-presensi-data" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">
                                        No
                                    </th>
                                    <th width="10%">
                                        Waktu
                                    </th>
                                    <th width="10%">
                                        Internet Protokol
                                    </th>
                                    <th width="5%">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = "{{ url('/') }}";
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-presensi-data').DataTable({
            processing: true,
            serverSide: true,
            "lengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            language: {
                'paginate': {
                  'previous': '<span class="fas fa-angle-left"></span>',
                  'next': '<span class="fas fa-angle-right"></span>'
                }
              },
            ajax: base_url+"/presensi-data",
            columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap' style='max-width:100px'>" + data + "</div>";
                    },
                    targets: 3
                }
             ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'date',
                    name: 'date',
                    'sClass': 'text-center',
                },
                {
                    data: 'ip',
                    name: 'ip',
                },
                {
                    data: 'status',
                    name: 'status',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        if(data == '0'){
                            return '<span class="badge badge-warning" style="zoom:120%">Menunggu</span>';
                        }else if(data == '1'){
                            return '<span class="badge badge-success" style="zoom:120%">Disetujui</span>';
                        }else if(data == '2'){
                            return '<span class="badge badge-danger" style="zoom:120%">Ditolak</span>';
                        }            
                    }
                }
            ]
        });
    });
</script>
@endpush