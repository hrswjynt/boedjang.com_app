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
                    <h6><b>List Data Pengajuan</b></h6>
                    <a href="{{ route('formcuti.pengajuan') }}" class="btn btn-success btn-sm add">
                        <i class="fa fa-plus "></i>
                        <span>Buat Pengajuan</span>
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
                        <table class="table" id="table-cuti-data" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">
                                        No
                                    </th>
                                    <th width="10%">
                                        Tgl Mulai
                                    </th>
                                    <th width="10%">
                                        Tgl Akhir
                                    </th>
                                    <th width="30%">
                                        Lokasi Cuti
                                    </th>
                                    <th width="5%">
                                        Jumlah Off
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
        $('#table-cuti-data').DataTable({
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
            ajax: base_url+"/formcuti-data",
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
                    data: 'tgl_mulai',
                    name: 'tgl_mulai',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        var start_date = new Date(data);
                        var sdate = String(start_date.getDate()).padStart(2, '0') + ' ';
                        sdate += monthName(String(start_date.getMonth() + 1).padStart(2, '0'));
                        sdate += ' ' + start_date.getFullYear();
                        return sdate;
                    }
                },
                {
                    data: 'tgl_akhir',
                    name: 'tgl_akhir',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        var start_date = new Date(data);
                        var sdate = String(start_date.getDate()).padStart(2, '0') + ' ';
                        sdate += monthName(String(start_date.getMonth() + 1).padStart(2, '0'));
                        sdate += ' ' + start_date.getFullYear();
                        return sdate;
                    }
                },
                {
                    data: 'lokasi_cuti',
                    name: 'lokasi_cuti',
                    render: function (data, type, row) {
                        if(data == null){
                            return '-';
                        }else{
                            return data;
                        }           
                    }
                },
                {
                    data: 'off',
                    name: 'off',
                    render: function (data, type, row) {
                        if(data == null){
                            return '0';
                        }else{
                            return data;
                        }           
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        if(data == 'Menunggu'){
                            return '<span class="badge badge-warning" style="zoom:120%">'+data+'</span>';
                        }else if(data == 'Disetujui'){
                            return '<span class="badge badge-success" style="zoom:120%">'+data+'</span>';
                        }else if(data == 'Ditolak'){
                            return '<span class="badge badge-danger" style="zoom:120%">'+data+'</span>';
                        }            
                    }
                }
            ]
        });
    });
</script>
@endpush