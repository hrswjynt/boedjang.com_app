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
                    <h6><b>Tambah Katalog Aset</b></h6>
                    <a href="{{route('asset.index')}}" class="btn btn-primary btn-sm add">
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
                    <form method="POST" action="{{route('asset.store')}}" id="asset_form" enctype="multipart/form-data">
                        @csrf
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Brand<span class="red">*</span></label>
                                        <select class="select2" multiple="multiple" name="brand[]" id="brand" class="form-control" style="width: 100%" required>   
                                            @foreach($brand as $b)
                                            <option value="{{$b->id}}">{{$b->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Master Bahan<span class="red">*</span></label>
                                        <select class="select2" name="master_bahan" id="master_bahan" class="form-control" style="width: 100%" required>
                                            <option value="" data-harga="">Pilih Master Bahan</option>   
                                            @foreach($bahan as $b)
                                            <option value="{{$b->id}}" data-harga={{$b->harga_acuan}} data-gambar={{$b->gambar}}>{{$b->item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Harga Acuan</label>
                                        <input name="harga_acuan" type="text" class="form-control" value="" id="harga_acuan" readonly>
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Urutan</label>
                                        <input name="sequence" type="number" class="form-control" value="0" id="sequence">
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Gambar </label><br>
                                        {{-- <input name="gambar" type="file"
                                            class="form-control" value="" id="gambar" accept="image/*"> --}}
                                        <img id="img" src="{{asset('images/noimage.png')}}" alt="your image" style="margin-top: 10px;max-height: 300px;" />
                                    </div>
                                    <div class="form-group mb-4 bmd-form-group">
                                        <label>Deskripsi <span class="red">*</span></label>
                                        <textarea rows="2" name="description" id="description" class="form-control" required="">{!! old('description') !!}</textarea>
                                    </div>
                                </div>
                            </div>
                                            
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-12">
                                    <button class="btn btn-success save pull-right mb-3" type="button" id="btn-submit">
                                        <i class="fa fa-save"></i>
                                        <span>Simpan</span>
                                    </button>
                                    <button class="btn btn-success save pull-right mb-3" id="btn-submit-loading" disabled="">
                                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                                        <span> Simpan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('other-script')
<script type="text/javascript">
    var url_delete = "{{url('asset-delete')}}";
    var base_url = "{{ url('/') }}";
    var no_image = "{{asset('images/noimage.png')}}";
</script>
<script type="module">

    CKEDITOR.replace('description', {
      toolbar: [{
          name: 'document',
          items: ['Print']
        },
        {
          name: 'clipboard',
          items: ['Undo', 'Redo']
        },
        {
          name: 'styles',
          items: ['Format', 'Font', 'FontSize']
        },
        {
          name: 'colors',
          items: ['TextColor', 'BGColor']
        },
        {
          name: 'align',
          items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
        },
        '/',
        {
          name: 'basicstyles',
          items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting']
        },
        {
          name: 'links',
          items: ['Link', 'Unlink']
        },
        {
          name: 'paragraph',
          items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
        },
        {
          name: 'insert',
          items: ['Image', 'Table']
        },
        {
          name: 'tools',
          items: ['Maximize']
        },
        {
          name: 'editing',
          items: ['Scayt']
        }
      ],
      height: 200,
      extraAllowedContent: 'h3{clear};h2{line-height};h2 h3{margin-left,margin-top}',

      extraPlugins: 'print,format,font,colorbutton,justify,uploadimage',
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',

      removeDialogTabs: 'image:advanced;link:advanced'
    });

    $(document).ready(function () {
        $('#master_bahan').change(function(){
            var selected = $(this).find('option:selected');
            var harga = selected.data('harga');
            var gambar = selected.data('gambar');
            $('#harga_acuan').val(new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(harga));
            if(gambar === null || gambar === ''){
                $("#img").attr("src",no_image);
            }else{
                $("#img").attr("src",'https://finance.boedjang.com/assets'+gambar);
            }
            
        });

        $('#btn-submit').show();
        $('#btn-submit-loading').hide();
        // $('#img').hide();

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
        
                reader.onload = function(e) {
                  $('#img').attr('src', e.target.result);
                }
        
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#gambar").change(function() {
            var val = $(this).val();
            switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'gif': case 'jpg': case 'png': case 'jpeg': case 'JPG': case 'PNG':
                    $('#img').show();
                    readURL(this);
                    break;
                default:
                    $(this).val('');
                    swal("Error Upload", "Format file gambar tidak valid (png | jpg | jpeg)", "error");
                    $('#gambar').val('');
                    $('#img').hide();
                    break;
            }
        });

        // var uploadField = document.getElementById("gambar");

        // uploadField.onchange = function() {
        //     if(this.files[0].size > 2097152){
        //         swal("Data Gambar terlalu besar!", {
        //           icon: "error",
        //         });
        //        this.value = "";
        //        $('#img').hide();
        //     };
        // };

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan menambah Katalog Aset?",
                text: 'Data yang ditambahkan dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#asset_form").submit()
              } else {
                swal("Proses Penambahan Data Katalog Aset Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#asset_form").submit(function(){
            if($("#asset_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#asset_form").length > 0) {
            $("#asset_form").validate({
                rules: {
                    brand: {
                        required: true
                    },
                    master_bahan: {
                        required: true
                    },
                    sequence: {
                        required: true
                    },
                    description: {
                        required: true,
                    },
                },
                messages: {
                    brand: {
                        required : 'Data Brand tidak boleh kosong',
                    },
                    master_bahan: {
                        required : 'Data Master Bahan tidak boleh kosong',
                    },
                    sequence: {
                        required : 'Data Urutan tidak boleh kosong',
                    },
                    description: 'Data Deskripsi tidak boleh kosong',
                },
            })
        }

    });
</script>
@endpush
