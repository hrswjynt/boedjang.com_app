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
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">SOP</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <!-- Search form -->
                    <form class="form-inline d-flex justify-content-center md-form form-sm active-cyan-2 mt-2" action="{{route('sop_search.index')}}" method="GET" style="margin-bottom: 30px">
                         @csrf
                        <input class="form-control" type="text" aria-label="Search"name="search" placeholder="Cari SOP" value="{{$search}}" style="margin:5px"/>
                        <select class="form-control" name="category" style="margin:5px">
                            <option value="all">Semua</option>
                            @foreach($category as $c)
                            @if($category_select != null)
                                @if($category_select->id == $c->id)
                                <option value="{{$c->id}}" selected="">{{$c->name}}</option>
                                @else
                                <option value="{{$c->id}}">{{$c->name}}</option>
                                @endif
                            @else
                            <option value="{{$c->id}}">{{$c->name}}</option>
                            @endif
                            @endforeach
                        </select>
                        <button style="margin:5px" type="submit" class="btn btn-sm btn-primary form-control">Cari <i class="fas fa-search" aria-hidden="true"></i></button>
                    </form>
                    <div class="row">
                        @if(count($sop) > 0)
                        @foreach($sop as $s)
                        <?php $kategori = explode(';',$s->category_display);?>
                        <div class="col-md-4 d-flex align-items-stretch">
                            <div class="card mb-4 box-shadow">
                                @if($s->gambar == null)
                                <img class="card-img-top" data-src="holder.js/100px225?theme=thumb&amp;bg=55595c&amp;fg=eceeef&amp;text=Gambar Tidak Ditemukan" alt="Gambar Tidak Ditemukan [100%x200]" style="height: 200px; width: 100%; display: block;border-bottom: 1px solid #DFE4E5;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22348%22%20height%3D%22225%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20348%20225%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_175df8c5709%20text%20%7B%20fill%3A%23eceeef%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A17pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_175df8c5709%22%3E%3Crect%20width%3D%22348%22%20height%3D%22225%22%20fill%3D%22%2355595c%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22116.71875%22%20y%3D%22120.3%22%3ENo Image%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                                @else
                                <img src="{{ asset('images/sop/'.$s->gambar) }}" style="max-height: 200px;border-bottom: 1px solid #DFE4E5;">
                                @endif
                                <div class="card-body">
                                    <div class="zoom"><a href="{{url('sop-list/'.$s->slug)}}" style="text-decoration: none;"><h4 class="card-text text-gray-900" style="text-align: center;font-weight: 800;margin-bottom: 30px">{{$s->title}}</h4></a></div>
                                    
                                    <!-- <hr class="sidebar-divider"> -->
                                    <div style="bottom: 10px;position: absolute;margin-top: 10px" class="justify-content-between">
                                        <div class="justify-content-between align-items-center">
                                            <small class="text-muted" style="background-color:#DFE4E5;margin-right: 10px">{{$s->updated_at->diffForHumans()}}</small>
                                            @if($s->updated_at > date('Y-m-d H:m:s', strtotime("-7 days")))
                                            <span class="badge badge-success">New</span>
                                            @endif
                                            @for($i=1;$i < count($kategori);$i++)
                                            <span class="badge badge-info">{{$kategori[$i]}}</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="col-md-12" style="height: 400px;">
                            <h3 style="text-align: center;margin-top: 30px">Tidak ada data SOP yang tersedia. <i class="fas fa-laugh-wink"></i></h3>
                        </div>
                        @endif
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

</script>
@endpush