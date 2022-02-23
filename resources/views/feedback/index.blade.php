@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Feedback {{Auth::user()->name}}</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>List Data Feedback</b></h6>
                    <a href="{{ route('feedback.pengajuan') }}" class="btn btn-success btn-sm add">
                        <i class="fa fa-plus "></i>
                        <span>Buat Feedback</span>
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
                  <div id="feedback-data">
                    <div class="table-responsive">
                        <table class="table" id="table-feedback-data" width="100%">
                            <thead>
                                <tr>
                                    <th width="1%">
                                        No
                                    </th>
                                    <th width="10%">
                                        Tgl
                                    </th>
                                    <th width="10%">
                                        Outlet
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
        $('#table-feedback-data').DataTable({
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
            ajax: base_url+"/feedback-data",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tgl',
                    name: 'tgl',
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
                    data: 'outlet_name',
                    name: 'outlet_name',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        return data;
                    }
                },
                {
                    data: 'outlet',
                    name: 'outlet',
                    'sClass': 'text-center',
                    render: function (data, type, row) {
                        return '<span class="badge badge-success" style="zoom:120%">Diterima</span>';          
                    }
                }
            ]
        });
    });
</script>
@endpush