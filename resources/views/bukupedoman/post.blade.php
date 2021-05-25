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
    .zoom {
      padding: 10px;
      transition: transform .2s; /* Animation */
      margin: 0 auto;
    }

    .zoom:hover {
      -ms-transform: scale(1.1); /* IE 9 */
      -webkit-transform: scale(1.1); /* Safari 3-8 */
      transform: scale(1.1); 
    }

    .zoom2 {
      transition: transform .2s; /* Animation */
      margin: 0 auto;
    }

    .zoom2:hover {
      -ms-transform: scale(1.03); /* IE 9 */
      -webkit-transform: scale(1.03); /* Safari 3-8 */
      transform: scale(1.03); 
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
                        <h1 class="h3 mb-0 text-gray-800" style="font-weight: 700;margin-right: 10px">{{$bukupedoman->title}}</h1>
                        <a href="{{ route('bukupedoman_list.index') }}" class="btn btn-primary btn-sm add">
                            <i class="fa fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="row">

                        @if($bukupedoman->gambar == null)
                        <div class="col-md-12">
                            @foreach($division as $c)
                            <span class="badge badge-warning shadow"><i class="fas fa-user-tie"></i></i> {{$c->name}}</span>
                            @endforeach
                            <p class="text-muted"> <i class="far fa-clock"></i> {{date_format($bukupedoman->updated_at,'d-m-Y H:i:s')}}</p>
                            <!-- <img id="img" src="{{asset('images/noimage.png')}}" alt="bukupedoman" style="margin-left: auto;margin-right: auto;display: block;margin-bottom: 30px" /> -->
                        </div>
                        
                        @else
                        <div class="col-md-12" style="overflow-x:auto;">
                            @foreach($division as $c)
                            <span class="badge badge-warning shadow"><i class="fas fa-user-tie"></i></i> {{$c->name}}</span>
                            @endforeach
                            <p class="text-muted"><i class="far fa-clock"></i> {{date_format($bukupedoman->updated_at,'d-m-Y H:i:s')}}</p>
                            <img id="img" src="{{asset('images/bukupedoman/'.$bukupedoman->gambar)}}" alt="bukupedoman" style="margin-left: auto;margin-right: auto;display: block;margin-bottom: 30px"/>
                        </div>
                        @endif

                        <div class="col-md-12" style="overflow-x:auto;">
                            <hr>
                            {!! $bukupedoman->content !!}
                            <hr>
                        </div>
                        @if(count($sop) > 0)
                        <div class="col-md-12">
                            <br><br><br>
                            <p style="text-align: center;font-weight: 900">Rekomendasi SOP <i class="fas fa-laugh-wink"></i></p>
                        </div>
                        @endif

                        <div class="col-md-12 row" style="margin: auto">
                        
                        @foreach($sop as $s)
                        
                        <div class="col-xs-6 d-flex align-items-stretch" style="width: 45%;margin:0px auto;margin-bottom: 10px">
                            <div class="zoom2" style="width: 100%">
                            <div class="card col-xs-6 box-shadow">
                                
                                @if($s->gambar == null)
                                
                                    <a href="{{url('sop-list/'.$s->slug)}}" style="text-decoration: none;" target="__blank">
                                        <img src="{{asset('images/placeholder.png')}}" style="height: 200px !important;object-fit: cover;border-bottom: 1px solid #DFE4E5;border: none;width: 100%">
                                    </a>
                                
                                @else
                                
                                    <a href="{{url('sop-list/'.$s->slug)}}" style="text-decoration: none;" target="__blank">
                                        <img src="{{ asset('images/sop/'.$s->gambar) }}" style="height: 200px !important;object-fit: cover;border-bottom: 1px solid #DFE4E5;border: none;width: 100%">
                                    </a>
                                
                                @endif
                                <div class="card-body">
                                    <a href="{{url('sop-list/'.$s->slug)}}" style="text-decoration: none;"><h6 class="card-text text-gray-900" style="height: 100px;text-align: center;font-weight: 800;margin-bottom: 10px;zoom:90%" target="__blank">{{$s->title}}</h6></a>
                                    
                                </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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