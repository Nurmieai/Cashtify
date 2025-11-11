<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Cashtify Landing Page" />

  <title>Cashtify | Landing Page</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/images/1.svg') }}" type="image/svg+xml" />

  <!-- Bootstrap -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Line Icons -->
  <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css"/>

  <!-- Tiny Slider -->
  <link rel="stylesheet" href="{{ asset('css/tiny-slider.css') }}" />

  <!-- gLightBox -->
  <link rel="stylesheet" href="{{ asset('css/glightbox.min.css') }}" />

  <!-- Main Style -->
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}" />
</head>

<body>
  <!--====== NAVBAR START ======-->

  <!--====== NAVBAR NINE PART ENDS ======-->


  <!-- Start header Area -->

    <!-- BANNER SECTION -->
    <section class="py-4" style="scroll-margin-top: 80px; margin-top: 130px;" id="hero-area">
        <div class="container">
            <h4 class="fw-bold mb-4 text-center">🔥 Produk Terpopuler</h4>
            <div class="product-slider">
            <div class="product-track d-flex">
                @for ($i = 1; $i <= 5; $i++)
                <div class="card mx-3 border-0 shadow-sm product-card">
                    <img src="{{ asset('assets/images/logo.svg') }}" class="card-img-top" alt="Produk {{ $i }}">
                    <div class="card-body text-center">
                    <h6 class="fw-semibold mb-1">Produk {{ $i }}</h6>
                    <p class="text-primary fw-bold mb-0">Rp {{ number_format(10000 * $i, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endfor
            </div>
            </div>
        </div>
    </section>


    <!-- CATEGORY BUTTONS -->
    <section class="bg-white py-4 border-top">
        <div class="container text-center">
            <div class="row row-cols-2 row-cols-md-6 g-3">
                @php
                    $categories = [
                        'Top Up Game', 'Akun', 'Voucher', 'Item', 'Koin Game', 'Pulsa & Utilitas'
                    ];
                @endphp

                @foreach ($categories as $category)
                <div class="col">
                    <a href="">
                        <div class="card border-0 shadow-sm h-100 p-3">
                            <div class="mb-2">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="{{ $category }}" style="height: 40px;">
                            </div>
                            <span class="fw-semibold">{{ $category }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

  <!-- End header Area -->


  <!-- Start Pricing  Area -->
  <section id="pricing" class="pricing-area pricing-fourteen">
    <!--======  Start Section Title Five ======-->
    <div class="section-title-five">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="content">
              <h6>Pricing</h6>
              <h2 class="fw-bold">Pricing & Plans</h2>
              <p>
                There are many variations of passages of Lorem Ipsum available,
                but the majority have suffered alteration in some form.
              </p>
            </div>
          </div>
        </div>
        <!-- row -->
      </div>
      <!-- container -->
    </div>
    <!--======  End Section Title Five ======-->
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
          <div class="pricing-style-fourteen">
            <div class="table-head">
              <h6 class="title">Starter</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and industry.</p>
                <div class="price">
                  <h2 class="amount">
                    <span class="currency">$</span>0<span class="duration">/mo </span>
                  </h2>
                </div>
            </div>

            <div class="light-rounded-buttons">
              <a href="javascript:void(0)" class="btn primary-btn-outline">
                Start free trial
              </a>
            </div>

            <div class="table-content">
              <ul class="table-list">
                <li> <i class="lni lni-checkmark-circle"></i> Cras justo odio.</li>
                <li> <i class="lni lni-checkmark-circle"></i> Dapibus ac facilisis in.</li>
                <li> <i class="lni lni-checkmark-circle deactive"></i> Morbi leo risus.</li>
                <li> <i class="lni lni-checkmark-circle deactive"></i> Excepteur sint occaecat velit.</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="pricing-style-fourteen middle">
            <div class="table-head">
              <h6 class="title">Exclusive</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and industry.</p>
                <div class="price">
                  <h2 class="amount">
                    <span class="currency">$</span>99<span class="duration">/mo </span>
                  </h2>
                </div>
            </div>

            <div class="light-rounded-buttons">
              <a href="javascript:void(0)" class="btn primary-btn">
                Start free trial
              </a>
            </div>

            <div class="table-content">
              <ul class="table-list">
                <li> <i class="lni lni-checkmark-circle"></i> Cras justo odio.</li>
                <li> <i class="lni lni-checkmark-circle"></i> Dapibus ac facilisis in.</li>
                <li> <i class="lni lni-checkmark-circle"></i> Morbi leo risus.</li>
                <li> <i class="lni lni-checkmark-circle deactive"></i> Excepteur sint occaecat velit.</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="pricing-style-fourteen">
            <div class="table-head">
              <h6 class="title">Premium</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and industry.</p>
                <div class="price">
                  <h2 class="amount">
                    <span class="currency">$</span>150<span class="duration">/mo </span>
                  </h2>
                </div>
            </div>

            <div class="light-rounded-buttons">
              <a href="javascript:void(0)" class="btn primary-btn-outline">
                Start free trial
              </a>
            </div>

            <div class="table-content">
              <ul class="table-list">
                <li> <i class="lni lni-checkmark-circle"></i> Cras justo odio.</li>
                <li> <i class="lni lni-checkmark-circle"></i> Dapibus ac facilisis in.</li>
                <li> <i class="lni lni-checkmark-circle"></i> Morbi leo risus.</li>
                <li> <i class="lni lni-checkmark-circle"></i> Excepteur sint occaecat velit.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ End Pricing  Area -->



  <!-- Start Cta Area -->
  <section id="call-action" class="call-action">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9">
          <div class="inner-content">
            <h2>We love to make perfect <br />solutions for your business</h2>
            <p>
              Why I say old chap that is, spiffing off his nut cor blimey
              guvnords geeza<br />
              bloke knees up bobby, sloshed arse William cack Richard. Bloke
              fanny around chesed of bum bag old lost the pilot say there
              spiffing off his nut.
            </p>
            <div class="light-rounded-buttons">
              <a href="javascript:void(0)" class="btn primary-btn-outline">Get Started</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Cta Area -->



  <!-- Start Latest News Area -->
  <div id="blog" class="latest-news-area section">
    <!--======  Start Section Title Five ======-->
    <div class="section-title-five">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="content">
              <h6>latest news</h6>
              <h2 class="fw-bold">Latest News & Blog</h2>
              <p>
                There are many variations of passages of Lorem Ipsum available,
                but the majority have suffered alteration in some form.
              </p>
            </div>
          </div>
        </div>
        <!-- row -->
      </div>
      <!-- container -->
    </div>
    <!--======  End Section Title Five ======-->
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
          <!-- Single News -->
          <div class="single-news">
            <div class="image">
              <a href="javascript:void(0)"><img class="thumb" src="assets/images/blog/1.jpg" alt="Blog" /></a>
              <div class="meta-details">
                <img class="thumb" src="assets/images/blog/b6.jpg" alt="Author" />
                <span>BY TIM NORTON</span>
              </div>
            </div>
            <div class="content-body">
              <h4 class="title">
                <a href="javascript:void(0)"> Make your team a Design driven company </a>
              </h4>
              <p>
                Lorem Ipsum is simply dummy text of the printing and
                typesetting industry. Lorem Ipsum has been the industry's
                standard.
              </p>
            </div>
          </div>
          <!-- End Single News -->
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <!-- Single News -->
          <div class="single-news">
            <div class="image">
              <a href="javascript:void(0)"><img class="thumb" src="assets/images/blog/2.jpg" alt="Blog" /></a>
              <div class="meta-details">
                <img class="thumb" src="assets/images/blog/b6.jpg" alt="Author" />
                <span>BY TIM NORTON</span>
              </div>
            </div>
            <div class="content-body">
              <h4 class="title">
                <a href="javascript:void(0)">
                  The newest web framework that changed the world
                </a>
              </h4>
              <p>
                Lorem Ipsum is simply dummy text of the printing and
                typesetting industry. Lorem Ipsum has been the industry's
                standard.
              </p>
            </div>
          </div>
          <!-- End Single News -->
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <!-- Single News -->
          <div class="single-news">
            <div class="image">
              <a href="javascript:void(0)"><img class="thumb" src="assets/images/blog/3.jpg" alt="Blog" /></a>
              <div class="meta-details">
                <img class="thumb" src="assets/images/blog/b6.jpg" alt="Author" />
                <span>BY TIM NORTON</span>
              </div>
            </div>
            <div class="content-body">
              <h4 class="title">
                <a href="javascript:void(0)">
                  5 ways to improve user retention for your startup
                </a>
              </h4>
              <p>
                Lorem Ipsum is simply dummy text of the printing and
                typesetting industry. Lorem Ipsum has been the industry's
                standard.
              </p>
            </div>
          </div>
          <!-- End Single News -->
        </div>
      </div>
    </div>
  </div>
  <!-- End Latest News Area -->

  <!-- Start Brand Area -->
  <div id="clients" class="brand-area section">
    <!--======  Start Section Title Five ======-->
    <div class="section-title-five">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="content">
              <h6>Meet our Clients</h6>
              <h2 class="fw-bold">Our Awesome Clients</h2>
              <p>
                There are many variations of passages of Lorem Ipsum available,
                but the majority have suffered alteration in some form.
              </p>
            </div>
          </div>
        </div>
        <!-- row -->
      </div>
      <!-- container -->
    </div>
  <!-- ========================= map-section end ========================= -->
  <section class="map-section map-style-9" style="margin-top: 10px;">
    <div class="map-container">
      <object style="border:0; height: 500px; width: 100%;"
        data="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3102.7887109309127!2d-77.44196278417968!3d38.95165507956235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzjCsDU3JzA2LjAiTiA3N8KwMjYnMjMuMiJX!5e0!3m2!1sen!2sbd!4v1545420879707"></object>
    </div>
    </div>
  </section>
  <!-- ========================= map-section end ========================= -->

  <!-- Start Footer Area -->
  <footer class="footer-area footer-eleven">
    <!-- Start Footer Top -->
    <div class="footer-top">
      <div class="container">
        <div class="inner-content">
          <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
              <!-- Single Widget -->
              <div class="footer-widget f-about">
                <div class="logo">
                  <a href="index.html">
                    <img src="{{ asset('landing/assets/images/logo.svg') }}" alt="#" class="img-fluid" />
                  </a>
                </div>
                <p>
                  Making the world a better place through constructing elegant
                  hierarchies.
                </p>
                <p class="copyright-text">
                  <span>© 2024 Ayro UI.</span>Designed and Developed by
                  <a href="javascript:void(0)" rel="nofollow"> Ayro UI </a>. <br> Distributed by <a href="http://themewagon.com" target="_blank">ThemeWagon</a>
                </p>
              </div>
              <!-- End Single Widget -->
            </div>
            <div class="col-lg-2 col-md-6 col-12">
              <!-- Single Widget -->
              <div class="footer-widget f-link">
                <h5>Solutions</h5>
                <ul>
                  <li><a href="javascript:void(0)">Marketing</a></li>
                  <li><a href="javascript:void(0)">Analytics</a></li>
                  <li><a href="javascript:void(0)">Commerce</a></li>
                  <li><a href="javascript:void(0)">Insights</a></li>
                </ul>
              </div>
              <!-- End Single Widget -->
            </div>
            <div class="col-lg-2 col-md-6 col-12">
              <!-- Single Widget -->
              <div class="footer-widget f-link">
                <h5>Support</h5>
                <ul>
                  <li><a href="javascript:void(0)">Pricing</a></li>
                  <li><a href="javascript:void(0)">Documentation</a></li>
                  <li><a href="javascript:void(0)">Guides</a></li>
                  <li><a href="javascript:void(0)">API Status</a></li>
                </ul>
              </div>
              <!-- End Single Widget -->
            </div>
            <div class="col-lg-4 col-md-6 col-12">
              <!-- Single Widget -->
              <div class="footer-widget newsletter">
                <h5>Subscribe</h5>
                <p>Subscribe to our newsletter for the latest updates</p>
                <form action="#" method="get" target="_blank" class="newsletter-form">
                  <input name="EMAIL" placeholder="Email address" required="required" type="email" />
                  <div class="button">
                    <button class="sub-btn">
                      <i class="lni lni-envelope"></i>
                    </button>
                  </div>
                </form>
              </div>
              <!-- End Single Widget -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ End Footer Top -->
  </footer>
  <!--/ End Footer Area -->



  <a href="#" class="scroll-top btn-hover">
    <i class="lni lni-chevron-up"></i>
  </a>

  <!--====== js ======-->
  <script src="{{ asset('js/glightbox.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="{{ asset('js/tiny-slider.js') }}"></script>

  <script>

    //===== close navbar-collapse when a  clicked
    let navbarTogglerNine = document.querySelector(
      ".navbar-nine .navbar-toggler"
    );
    navbarTogglerNine.addEventListener("click", function () {
      navbarTogglerNine.classList.toggle("active");
    });

    // ==== left sidebar toggle
    let sidebarLeft = document.querySelector(".sidebar-left");
    let overlayLeft = document.querySelector(".overlay-left");
    let sidebarClose = document.querySelector(".sidebar-close .close");

    overlayLeft.addEventListener("click", function () {
      sidebarLeft.classList.toggle("open");
      overlayLeft.classList.toggle("open");
    });
    sidebarClose.addEventListener("click", function () {
      sidebarLeft.classList.remove("open");
      overlayLeft.classList.remove("open");
    });

    // ===== navbar nine sideMenu
    let sideMenuLeftNine = document.querySelector(".navbar-nine .menu-bar");

    sideMenuLeftNine.addEventListener("click", function () {
      sidebarLeft.classList.add("open");
      overlayLeft.classList.add("open");
    });

    //========= glightbox
    GLightbox({
      'href': 'https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM',
      'type': 'video',
      'source': 'youtube', //vimeo, youtube or local
      'width': 900,
      'autoplayVideos': true,
    });

  </script>
</body>

</html>
