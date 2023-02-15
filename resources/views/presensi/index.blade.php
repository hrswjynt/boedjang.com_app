@extends('layouts.app_admin')
@section('content')
    <style>
        label {
            font-weight: 700;
        }
    </style>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Presensi Online</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
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
                        <form method="POST" id="presensi_form" action="{{ route('presensi.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="dataip" class="col-sm-2 col-form-label">Internet Protokol</label>
                                <div class="col-sm-10">
                                    <input type="text" name="ip" require="required" id="dataip" for="dataip"
                                        placeholder="IP" readonly class="form-control">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-sm-2 col-form-label">NIP</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nip" require="required" class="form-control"
                                        id="nip" disabled value="{{ $karyawan->NIP }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nama" require="required" class="form-control"
                                        id="nama" placeholder="Nama" readonly value="{{ $karyawan->NAMA }}">
                                </div>
                            </div>
                            {{-- <label for="ceklokasi" class="col-sm-12 col-form-label">Lokasi</label> --}}
                            <div class="col-md-12">
                                <div id="lokasi" style="height:300px; width: 100%; position: relative; overflow: hidden;"
                                    class="img-thumbnail"></div>
                                <div class="col-sm-8">
                                    <p style="color:red" id="lokasides"></p>
                                    <input type="hidden" name="lokasi" require="required" id="ceklokasi" for="ceklokasi"
                                        placeholder="Latitude & Longitude" readonly>
                                </div>
                                <script type="text/javascript">
                                    let x = document.getElementById("lokasides");

                                    function ambil_lokasi() {
                                        if (navigator.geolocation) {
                                            navigator.geolocation.getCurrentPosition(showPosition, showError);
                                        } else {
                                            x.innerHTML = "Sistem Geolokasi tidak mendukung browser ini.";
                                            document.getElementById("lokasi").style.display = "none";
                                        }
                                    }

                                    function showPosition(position) {
                                        document.getElementById("ceklokasi").value = [position.coords.latitude, '&', position.coords.longitude];
                                        const myLatLng = {
                                            lat: position.coords.latitude,
                                            lng: position.coords.longitude
                                        };
                                        const map = new google.maps.Map(document.getElementById("lokasi"), {
                                            zoom: 18,
                                            center: myLatLng,
                                            disableDefaultUI: true,
                                            zoomControl: true,
                                        });

                                        new google.maps.Marker({
                                            position: myLatLng,
                                            map,
                                            title: "Hello",
                                        });
                                    }

                                    function showError(error) {
                                        switch (error.code) {
                                            case error.PERMISSION_DENIED:
                                                x.innerHTML =
                                                    "<p>Pengguna menolak mengizinkan akses geolokasi pada browser, segera izinkan ulang akses geolokasi.</p>"+
                                                    "<p>Dengan cara klik tombol gembok pada browser (di dekat url), lalu klik 'Permissions', dan centang permission Location sehingga menjadi allowed/diizinkan.</p>"
                                                document.getElementById("lokasi").style.display = "none";
                                                break;
                                            case error.POSITION_UNAVAILABLE:
                                                x.innerHTML = "Tidak terdapat informasi pada lokasi."
                                                document.getElementById("lokasi").style.display = "none";
                                                break;
                                            case error.TIMEOUT:
                                                x.innerHTML = "Permintaan akses mendapatkan lokasi pengguna sudah kadaluarsa."
                                                document.getElementById("lokasi").style.display = "none";
                                                break;
                                            case error.UNKNOWN_ERROR:
                                                x.innerHTML = "Terjadi Kesalahan yang tidak diketahui."
                                                document.getElementById("lokasi").style.display = "none";
                                                break;
                                        }
                                    }

                                    window.ambil_lokasi = ambil_lokasi;
                                </script>

                                <script type="text/javascript"
                                    src="https://maps.google.com/maps/api/js?key=AIzaSyAiI2CrDJpf0FtqYif4IfVgu8xXdjTb_mc&callback=ambil_lokasi">
                                </script>
                            </div>
                            {{-- <label for="pict" class="col-sm-12 col-form-label">Foto Presensi</label> --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="start-camera" type="button" class="btn btn-warning btn-block"
                                            style="display: block;margin: 0 auto">Aktifkan Camera <i
                                                class="fas fa-camera"></i></button>
                                        <video id="video" autoplay="" style="display: none;margin: 3px"
                                            class="img-thumbnail"></video>
                                        <button id="click-photo" style="display: none;margin: 0 auto" type="button"
                                            class="btn btn-success btn-block">Ambil Foto <i class="fas fa-camera"></i></button>
                                        <div id="dataurl-container" style="display: none;margin-top:10px">
                                            <canvas id="canvas" width="200" height="270"
                                                style="margin: 0 auto;display:block" class="img-thumbnail"></canvas>
                                            <textarea id="dataurl" readonly="" name="image" style="display: none"></textarea>
                                        </div>
                                    </div>
                                </div>

                            <div class="row" style="margin-top: 10px; display: none" id="buttonsubmit">
                                <div class="col-md-12">
                                    <button class="btn btn-primary save pull-right mb-3 btn-block" type="button"
                                        id="btn-submit">
                                        <i class="fas fa-file-upload"></i>
                                        <span>Presensi</span>
                                    </button>
                                    <button class="btn btn-primary save pull-right mb-3 btn-block" id="btn-submit-loading"
                                        disabled="">
                                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                                        <span> Presensi</span>
                                    </button>
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
        $.getJSON('https://ipapi.co/json/', function(data) {
            // console.log(JSON.stringify(data, null, 2));
            document.getElementById("dataip").value = data.ip;
        });

        const base_url = "{{ url('/') }}";
        const cek_spam = <?php echo json_encode($cek_spam); ?>;
        let camera_button = document.querySelector("#start-camera");
        let video = document.querySelector("#video");
        let click_button = document.querySelector("#click-photo");
        let canvas = document.querySelector("#canvas");
        let dataurl = document.querySelector("#dataurl");
        let buttonsbumit = document.querySelector("#buttonsubmit");
        let dataurl_container = document.querySelector("#dataurl-container");

        camera_button.addEventListener('click', async function() {
            let stream = null;
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: false
                });
            } catch (error) {
                swal({
                    title: "Pengguna menolak mengizinkan akses Camera pada browser, segera izinkan ulang akses Camera.",
                    text: "Dengan cara klik tombol gembok pada browser (di dekat url), lalu klik 'Permissions', dan centang permission Camera sehingga menjadi allowed/diizinkan.",
                    icon: "warning",
                });
                return;
            }
            video.srcObject = stream;
            video.style.display = 'block';
            camera_button.style.display = 'none';
            click_button.style.display = 'block';
        });

        click_button.addEventListener('click', function() {
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            let image_data_url = canvas.toDataURL('image/jpeg');

            dataurl.value = image_data_url;
            dataurl_container.style.display = 'block';
            buttonsbumit.style.display = 'block';
        });

        $(document).ready(function() {
            $('#btn-submit').show();
            $('#btn-submit-loading').hide();

            $("#btn-submit").click(function() {
                if (cek_spam) {
                    swal("Proses dibatalkan, data presensi online terdeteksi spam.", {
                        icon: "error",
                    });
                    return false;
                }
                $('#btn-submit').hide();
                $('#btn-submit-loading').show();
                if (document.getElementsByName("image")[0].value === '') {
                    $('#btn-submit').show();
                    $('#btn-submit-loading').hide();
                    swal("Proses Presensi online dibatalkan, gambar foto harus dimasukkan", {
                        icon: "error",
                    });
                } else {
                    swal({
                            title: "Apakah anda yakin akan melakukan presensi online?",
                            text: 'Data presensi akan dimasukkan ke dalam database.',
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                $("#presensi_form").submit();
                            } else {
                                $('#btn-submit').show();
                                $('#btn-submit-loading').hide();
                                swal("Proses Presensi online dibatalkan", {
                                    icon: "error",
                                });
                            }
                        });
                }

            });
        });
    </script>
@endpush
