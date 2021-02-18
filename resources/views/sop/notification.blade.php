@extends('layouts.app_admin')

@section('content')
<style type="text/css">
    th, td { white-space: nowrap; } div.dataTables_wrapper { width: 100%; margin: 0 auto; }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifikasi SOP Admin</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm add">
                        <i class="fa fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                </div>
                <div class="card-body">
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
                                        Tipe
                                    </th>
                                    <th>
                                        Keterangan
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
            columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div class='text-wrap width-500'>" + data + "</div>";
                    },
                    targets: 3
                },
            ],
            data: data,
            columns: [
                { 
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }  
                },
                {
                    data: 'date',
                    name: 'date',
                    render: function (data, type, row) {
                        return data;
                    }
                },
                
                {
                    data: 'type',
                    name: 'type',
                    render: function (data, type, row) {
                        if(data == 1){
                            return '<div class="icon-circle bg-success"><i class="fas fa-file-alt text-white"></i></div>';
                        }else if(data == 2){
                            return '<div class="icon-circle bg-warning"><i class="fas fa-pencil-alt text-white"></i></div>';
                        }else if(data == 3){
                            return '<div class="icon-circle bg-danger"><i class="fas fa-trash text-white"></i></div>';
                        }
                    }
                },
                {
                    data: 'keterangan',
                    name: 'keterangan',
                    render: function (data, type, row) {
                        if(row.slug != null){
                            return '<a target="__blank" href="'+base_url+'/sop-list/'+row.slug+'" >'+data+'</a>';
                        }else{
                            return '<a target="__blank" href="'+base_url+'/sop-list">'+data+'</a>';
                        }
                       
                    }
                },
            ]
        });
    });

</script>
@endpush