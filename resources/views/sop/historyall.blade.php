@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">History Pembaca SOP</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <a href="{{ route('sop.index') }}" class="btn btn-primary btn-sm add">
                        <i class="fa fa-arrow-left"></i>
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
                  <div id="sop-data">
                    <div class="table-responsive table-striped">
                        <table class="table" id="table-sop-data" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Waktu
                                    </th>
                                    
                                    <th>
                                        Nama
                                    </th>
                                    <th>
                                        NIP
                                    </th>
                                    <th>
                                        SOP
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
    var url_delete = "{{url('sop-delete')}}";
    var base_url = "{{ url('/') }}";
    var data = <?php echo json_encode($data) ?>;
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-sop-data').DataTable({
            "lengthMenu": [
                [100, 200, 500, -1],
                [100, 200, 500, 'All']
            ],
            language: {
                'paginate': {
                  'previous': '<span class="fas fa-angle-left"></span>',
                  'next': '<span class="fas fa-angle-right"></span>'
                }
              },
            data: data,
            columns: [
                { 
                    // "sortable": false,
                    // orderable: false, 
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {
                    data: 'date',
                    name: 'date',
                    // "sortable": false,
                    // orderable: false,
                    render: function (data, type, row) {
                        return '<span class="badge badge-success shadow">'+data+'</span>';
                    }
                },
                
                {
                    data: 'nama',
                    name: 'nama',
                    render: function (data, type, row) {
                        return '<span class="badge badge-danger shadow" >'+data+'</span>';
                    }
                },
                {
                    data: 'nip',
                    name: 'nip',
                    render: function (data, type, row) {
                        return '<span class="badge badge-primary shadow">'+data+'</span>';
                    }
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function (data, type, row) {
                        return '<span class="badge badge-info shadow" style="zoom:105%">'+data+'</span>';
                    }
                },
            ]
        });
    });

</script>
@endpush