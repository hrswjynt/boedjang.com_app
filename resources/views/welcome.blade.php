@extends('layouts.app_landing')
@section('content')
<!-- Services-->
<section class="page-section" id="about">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">About Us</h2>
            <!-- <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3> -->
        </div>
        <div class="row text-center">
            <div class="col-md-12 text-muted">
                {!! DB::table('content')->where('type','about')->first()->content !!}
            </div>
        </div>
    </div>
</section>
<!-- Brands-->
<section class="page-section" id="brand" style="background-color: #EFEDED">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Our Brands</h2>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="team-member">
                    <img class="mx-auto rounded-circle" src="{{asset('landing/assets/img/brand/bebekboedjang.webp')}}" alt="" />
                    <h4>Bebek Boedjang</h4>
                    <p class="text-muted">Sambelnya Istimewa</p>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-member">
                    <img class="mx-auto rounded-circle" src="{{asset('landing/assets/img/brand/bts.webp')}}" alt="" />
                    <h4>Bakso Tyga Sapi</h4>
                    <p class="text-muted">Baksonya Kress</p>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-member">
                    <img class="mx-auto rounded-circle" src="{{asset('landing/assets/img/brand/muaraikansambal.webp')}}" alt="" />
                    <h4>Muara Ikan Sambal</h4>
                    <p class="text-muted">Ikannya seger</p>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-member">
                    <img class="mx-auto rounded-circle" src="{{asset('landing/assets/img/brand/bebekbejo.webp')}}" alt="" />
                    <h4>Bebek Bejo</h4>
                    <p class="text-muted">Dari Pontianak Untuk Indonesia</p>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-member">
                    <img class="mx-auto rounded-circle" src="{{asset('landing/assets/img/brand/bale.webp')}}" alt="" />
                    <h4>Nasi Bakar Iga Bale</h4>
                    <p class="text-muted">Jagonya Nasi dan Iga Bakar</p>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="team-member">
                    <img class="mx-auto rounded-circle" src="{{asset('landing/assets/img/brand/sotosemar.webp')}}" alt="" />
                    <h4>Soto Semar</h4>
                    <p class="text-muted">Kuahnya Segar</p>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Our Brands Grid-->
<section class="page-section" id="blog" style="background-color: #E5E5E5">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Blogs</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-link fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('landing/assets/img/portfolio/01-thumbnail.jpg')}}" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">Bebek Boedjang Menguasai Dunia</div>
                        <div class="portfolio-caption-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-link fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('landing/assets/img/portfolio/02-thumbnail.jpg')}}" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">Bakso Tyga Sapi menggunakan bakso sapi pilihan.</div>
                        <div class="portfolio-caption-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-link fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('landing/assets/img/portfolio/03-thumbnail.jpg')}}" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">Makanan Enak di Pontianak</div>
                        <div class="portfolio-caption-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-link fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('landing/assets/img/portfolio/04-thumbnail.jpg')}}" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">Makanan Bebek terbaik di Pontianak</div>
                        <div class="portfolio-caption-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 mb-4 mb-sm-0">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-link fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('landing/assets/img/portfolio/05-thumbnail.jpg')}}" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">Tips Menjaga Kesehatan</div>
                        <div class="portfolio-caption-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="portfolio-item">
                    <a class="portfolio-link" data-toggle="modal" href="#">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content"><i class="fas fa-link fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('landing/assets/img/portfolio/06-thumbnail.jpg')}}" alt="" />
                    </a>
                    <div class="portfolio-caption">
                        <div class="portfolio-caption-heading">Soto Semar Sudah Buka Gengs</div>
                        <div class="portfolio-caption-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact-->
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Contact</h2>
            <div style="color:white">
                {!! DB::table('content')->where('type','contact')->first()->content !!}
            </div>
        </div>
    </div>
</section>
@endsection