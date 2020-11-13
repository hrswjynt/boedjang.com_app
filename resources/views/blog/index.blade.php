@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Blog</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="text-primary">Daftar Blog</h6>
                    <a href="{{ route('blog.create') }}" class="btn btn-success btn-sm add">
                        <i class="fa fa-plus"></i>
                        <span>Tambah Blog</span>
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
                  <div id="blog-data">
                    <div class="table-responsive">
                        <table class="table" id="table-blog-data" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    <th>
                                        Slug
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                    <th>
                                        Description
                                    </th>
                                    <th class="text-right">
                                        Actions
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
    var url_delete = "{{url('blog-delete')}}";
    var base_url = "{{ url('/') }}";
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-blog-data').DataTable({
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
            ajax: base_url+"/blog-data",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'slug',
                    name: 'slug'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description',
                },
                {
                    data: 'action',
                    name: 'action',
                    'sClass': 'td-actions text-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });


    $(document).ready(function () {
        $("body").on("click", ".blogDelete", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            var url = e.target;
            swal({
                title: 'Apakah Anda Yakin?',
                text: 'Blog yang telah dihapus tidak dapat dikembalikan lagi!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function (value) {
                if (value) {
                    $.ajax({
                        url: url_delete + "/" + id,
                        type: 'POST',
                        data: {
                            _token: token,
                            id: id
                        },
                        success: function (response) {
                            $("#success-delete").html('<div class="alert alert-'+response.type+' alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><p>' +
                                response.message + '</p></div>');
                            $('.dataTable').each(function () {
                                dt = $(this).dataTable();
                                dt.fnDraw();
                            })
                            $("#success-delete").fadeTo(3000, 500).slideUp(500, function () {
                                $("#success-delete").slideUp(500);
                            });
                        }
                    });
                    return false;
                }
            });
        });

    });

    $("#success-alert").fadeTo(3000, 500).slideUp(500, function () {
        $("#success-alert").slideUp(500);
    });

</script>
@endpush