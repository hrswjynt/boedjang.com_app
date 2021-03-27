@extends('layouts.app_admin')
@section('content')
<style type="text/css">
    img {
        max-width: 100%;
        height: auto !important;
        border: 3px solid #3BBEEC;
        margin:5px;
        margin-left: auto;
        margin-right: auto;
    }
    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100% !important;
      border: 2px solid #3BBEEC;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    th{
        background-color: #92D3DF;
        font-weight: 700;
        text-align: center
    }

    tr:nth-child(even){background-color: #f2f2f2}

    blockquote {
      background: #f9f9f9;
      border-left: 10px solid #ccc;
      margin: 1.5em 10px;
      padding: 0.5em 10px;
      quotes: "\201C""\201D""\2018""\2019";
    }
    blockquote:before {
      color: #ccc;
      /*content: open-quote;*/
      font-size: 4em;
      line-height: 0.1em;
      margin-right: 0.25em;
      vertical-align: -0.4em;
    }
    blockquote p {
      display: inline;
    }
    .text-muted{
        zoom: 80%;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700;margin-right: 10px">{{$item->name}}</h1>
                        <a href="{{ route('item_list.index') }}" class="btn btn-primary btn-sm add">
                            <i class="fa fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="row">

                        @if($item->gambar == null)
                        <div class="col-md-12">
                            <p class="text-muted"> <i class="far fa-clock"></i> {{date_format($item->updated_at,'d-m-Y H:i:s')}}</p>
                        </div>
                        
                        @else
                        <div class="col-md-12">
                            <p class="text-muted"><i class="far fa-clock"></i> {{date_format($item->updated_at,'d-m-Y H:i:s')}}</p>
                            <img id="img" src="{{asset('images/item/'.$item->gambar)}}" alt="item" style="margin-left: auto;margin-right: auto;display: block;margin-bottom: 30px;border: none"/>
                        </div>
                        @endif

                        <div class="col-md-12" style="overflow-x:auto;">
                            <hr>
                            {!! $item->content !!}
                            <hr>
                        </div>

                        <div class="col-md-12">
                            <br><br><br><br>
                            <br><br><br><br>
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

@endpush