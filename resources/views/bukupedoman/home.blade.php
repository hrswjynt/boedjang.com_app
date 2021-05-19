@extends('layouts.app_admin')
@section('content')
<style type="text/css">
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
    .kecil{
        zoom: 85%;
    }
    @media screen and ( max-width: 400px ){

        li.page-item {

            display: none;
        }

        .page-item:first-child,
        .page-item:nth-child( 2 ),
        .page-item:nth-last-child( 2 ),
        .page-item:last-child,
        .page-item.active,
        .page-item.disabled {

            display: block;
        }
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buku Pedoman</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Search form -->
                    <form class="form-inline d-flex justify-content-center md-form form-sm active-cyan-2 mt-2" action="{{route('bukupedoman_search.index')}}" method="GET" style="margin-bottom: 30px">
                         @csrf
                        <input class="form-control" type="text" aria-label="Search"name="search" placeholder="Cari Buku Pedoman" value="{{$search}}" style="margin:5px"/>

                        <select class="form-control" name="division" style="margin:5px">
                            <option value="all">Semua Divisi</option>
                            @foreach($division as $j)
                            @if($division_select != null)
                                @if($division_select->id == $j->id)
                                <option value="{{$j->id}}" selected="">{{$j->name}}</option>
                                @else
                                <option value="{{$j->id}}">{{$j->name}}</option>
                                @endif
                            @else
                            <option value="{{$j->id}}">{{$j->name}}</option>
                            @endif
                            @endforeach
                        </select>
                        <button style="margin:5px" type="submit" class="btn btn-sm btn-primary form-control">Cari <i class="fas fa-search" aria-hidden="true"></i></button>
                    </form>
                    <div class="row">
                        @if(count($bukupedoman) > 0)
                        @foreach($bukupedoman as $s)
                        <?php $division = explode(';',$s->division_display);?>
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="card mb-6 box-shadow" style="width: 100%">
                                @if($s->gambar == null)
                                <div class="zoom2" style="width: 100%">
                                    <a href="{{url('bukupedoman-list/'.$s->slug)}}" style="text-decoration: none;">
                                        <img src="{{asset('images/placeholder.png')}}" style="height: 200px;width: 100%;object-fit: cover;border-bottom: 1px solid #DFE4E5;">
                                    </a>
                                </div>
                                @else
                                <div class="zoom2" style="width: 100%">
                                    <a href="{{url('bukupedoman-list/'.$s->slug)}}" style="text-decoration: none;">
                                        <img src="{{ asset('images/bukupedoman/'.$s->gambar) }}" style="height: 200px;width: 100%;object-fit: cover;border-bottom: 1px solid #DFE4E5;">
                                    </a>
                                </div>
                                @endif
                                <div class="card-body">
                                    <div class="zoom"><a href="{{url('bukupedoman-list/'.$s->slug)}}" style="text-decoration: none;"><h4 class="card-text text-gray-900" style="text-align: center;font-weight: 800;margin-bottom: 50px">{{$s->title}}</h4></a></div>
                                    
                                    <!-- <hr class="sidebar-divider"> -->
                                    <div style="bottom: 10px;position: absolute;" class="justify-content-between">
                                        <div class="justify-content-between align-items-center">
                                            <small class="text-muted kecil" style="background-color:#DFE4E5;margin-right: 10px">{{$s->updated_at->diffForHumans()}}</small>
                                            @if($s->updated_at > date('Y-m-d H:m:s', strtotime("-10 days")))
                                            <span class="badge badge-success kecil"><i class="far fa-calendar-check"></i> Terbaru</span>
                                            @endif

                                            @for($i=1;$i < count($division);$i++)
                                            <span class="badge badge-warning kecil"><i class="fas fa-user-tie"></i></i> {{$division[$i]}}</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="col-md-12" style="height: 400px;">
                            <h3 style="text-align: center;margin-top: 30px">Tidak ada data Buku Pedoman yang tersedia. <i class="fas fa-laugh-wink"></i></h3>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex col-md-12">
    <div class="mx-auto">
        {{$bukupedoman->appends(request()->input())->onEachSide(1)->links()}}
    </div>
</div>
<script type="text/javascript">
    var base_url = "{{ url('/') }}";
</script>
@endsection
@push('other-script')
<script type="text/javascript">

</script>
@endpush