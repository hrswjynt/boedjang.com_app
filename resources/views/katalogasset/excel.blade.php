@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Katalog Aset</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6><b>Daftar Katalog Aset</b></h6>
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
                  <div id="asset-data">
                    <div class="table-responsive">
                        <table class="table" id="table-asset-data" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                    </th>
                                    @foreach($brand as $b)
                                    <th>
                                        {{$b->name}}
                                    </th>
                                    @endforeach
                                    <th>
                                        Nama
                                    </th>
                                    <th>
                                        Harga Acuan
                                    </th>
                                    <th>
                                        Urutan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($data as $d)
                                <?php $i++; ?>
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    @foreach($brand as $b)
                                        @if (strpos($d->brand_display, $b->name) !== false) 
                                            <td>
                                                YA
                                            </td>
                                        @else
                                            <td>
                                                -
                                            </td>
                                        @endif
                                    @endforeach
                                    

                                    <td>
                                        {{$d->name}}
                                    </td>
                                    <td>
                                        {{$d->harga_acuan}}
                                    </td>
                                    <td>
                                        {{$d->sequence}}
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
<script type="text/javascript">
    var url_delete = "{{url('asset-delete')}}";
    var base_url = "{{ url('/') }}";
</script>

@endsection

@push('other-script')
<script type="text/javascript">
    $(function () {
        $('#table-asset-data').DataTable({
            dom: "lBfrtip",
            buttons: [
            {
                extend: "excelHtml5",
                filename: "katalog-asset",
                exportOptions: {
                columns: ":visible",
                },
            },
            "colvis",
            ],
            "lengthMenu": [
                [-1],
                ['All']
            ],
            language: {
                'paginate': {
                    'previous': '<span class="fas fa-angle-left"></span>',
                    'next': '<span class="fas fa-angle-right"></span>'
                }
            },
        });
    });

    $("#success-alert").fadeTo(3000, 500).slideUp(500, function () {
        $("#success-alert").slideUp(500);
    });

</script>
@endpush