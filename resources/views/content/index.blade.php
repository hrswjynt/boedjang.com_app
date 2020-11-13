@extends('layouts.app_admin')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Content</h1>
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
    </div>
    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Content</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible" id="success-alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form method="POST" action="{{route('content.store')}}" id="content_form">
                        @csrf
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="container-fluid mt-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Title<span class="red">*</span></label>
                                                            <input name="title" type="text"
                                                                class="form-control" value="{!! DB::table('content')->where('type','title')->first()->content !!}" required="">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Header Menu<span class="red">*</span></label>
                                                            <input name="header_menu" type="text"
                                                                class="form-control" value="{!! DB::table('content')->where('type','header_menu')->first()->content !!}" required="">
                                                        </div>
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">Header<span class="red">*</span></label>
                                                            <input name="header" type="text"
                                                                class="form-control" value="{!! DB::table('content')->where('type','header')->first()->content !!}" required="">
                                                        </div>

                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label class="bmd-label-floating">SubHeader<span class="red">*</span></label>
                                                            <input name="subheader" type="text"
                                                                class="form-control" value="{!! DB::table('content')->where('type','subheader')->first()->content !!}" required="">
                                                        </div>
                                                        
                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>About <span class="red">*</span></label>
                                                            <textarea rows="10" name="about" class="form-control" required="">{!! DB::table('content')->where('type','about')->first()->content !!}</textarea>
                                                        </div>

                                                        <div class="form-group mb-4 bmd-form-group">
                                                            <label>Contact <span class="red">*</span></label>
                                                            <textarea rows="10" name="contact" class="form-control" required="">{!! DB::table('content')->where('type','contact')->first()->content !!}</textarea>
                                                        </div>

                                                        <div class="form-group mb-4">
                                                            <label>Footer<span class="red">*</span></label>
                                                            <input name="footer" type="text"
                                                                class="form-control" value="{!! DB::table('content')->where('type','footer')->first()->content !!}" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
<!-- /.container-fluid -->
@endsection

@push('other-script')
<script>
    //HUBUNGKAN CKEDITOR DENGAN TEXTAREA YANG BERNAMA CONTENT
    //ADAPUN KONFIGURASI UPLOAD URLNYA MENGARAH KE ROUTE POST.IMAGE
    // CKEDITOR.replace('contact', {
    //     filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
    //     filebrowserUploadMethod: 'form'
    // });

    // CKEDITOR.addCss('.cke_editable { font-size: 15px; padding: 2em; }');

    CKEDITOR.replace('contact', {
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
        // {
        //   name: 'colors',
        //   items: ['TextColor', 'BGColor']
        // },
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

      extraAllowedContent: 'h3{clear};h2{line-height};h2 h3{margin-left,margin-top}',

      extraPlugins: 'print,format,font,colorbutton,justify,uploadimage',
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',

      removeDialogTabs: 'image:advanced;link:advanced'
    });

    CKEDITOR.replace('about', {
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
        // {
        //   name: 'colors',
        //   items: ['TextColor', 'BGColor']
        // },
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

      extraAllowedContent: 'h3{clear};h2{line-height};h2 h3{margin-left,margin-top}',

      extraPlugins: 'print,format,font,colorbutton,justify,uploadimage',

      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',

      removeDialogTabs: 'image:advanced;link:advanced'
    });
</script>
<script type="text/javascript">
    var base_url = "{{ url('') }}";
    $(document).ready(function(){
        $('#btn-submit').show();
        $('#btn-submit-loading').hide();

        $("#btn-submit").click(function(){
            swal({
                title: "Apakah anda yakin akan mengedit data content?",
                text: 'Data yang ditambahkan dapat merubah data pada database.', 
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#content_form").submit()
              } else {
                swal("Proses Pengeditan Data Content Dibatalkan!", {
                  icon: "error",
                });
              }
            });
        });

        $("#content_form").submit(function(){
            if($("#content_form").valid()){
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
            }else{
                return false;
            }
        });

        if($("#content_form").length > 0) {
            $("#content_form").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    header_menu: {
                        required: true,
                    },
                    header: {
                        required: true,
                    },
                    subheader: {
                        required: true,
                    },
                    about: {
                        required: true
                    },
                    contact: {
                        required: true
                    },
                    footer: {
                        required: true
                    },
                },
                messages: {
                    title: {
                        required : 'Data Title harus diisi',
                    },
                    header_menu: {
                        required : 'Data Header Menu harus diisi',
                    },
                    header: {
                        required : 'Data Header harus diisi',
                    },
                    subheader: {
                        required : 'Data SubHeader harus diisi',
                    },
                    about: {
                        required : 'Data About harus diisi',
                    },
                    contact: {
                        required : 'Data Contact harus diisi',
                    },
                    footer: {
                        required : 'Data Footer harus diisi',
                    },
                },
            })
        }
    });
</script>
@endpush