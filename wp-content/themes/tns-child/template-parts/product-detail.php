<style>
    .product-single {
        transition: top all .3s;
    }

    .product-single-container:hover .product-single {
        background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/bg-single-product.png');
        background-size: 100% 100%;
    }
</style>
<!-- Product section-->
<section class="py-5 bg-dark text-white">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/loa1.png" alt="..." /></div>
            <div class="col-md-6">
                <div class="small mb-1">Mô tả ngắn</div>
                <h1 class="display-5 fw-bolder">Loa Bluetooth Soundmax B-60/5.1</h1>
                <div class="rate">
                    <i class="fa-solid fa-star text-warning fs-6"></i>
                    <i class="fa-solid fa-star text-warning fs-6"></i>
                    <i class="fa-solid fa-star text-warning fs-6"></i>
                    <i class="fa-solid fa-star text-warning fs-6"></i>
                    <i class="fa-regular fa-star text-warning fs-6"></i>
                </div>
                <div class="fs-2 mb-5">
                    <span class="text-decoration-line-through">5.300.000</span>
                    <span>3.000.000</span>
                </div>
                <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium at dolorem quidem modi. Nam sequi consequatur obcaecati excepturi alias magni, accusamus eius blanditiis delectus ipsam minima ea iste laborum vero?</p>
                <div class="d-flex">
                    <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1" style="max-width: 3rem" />
                    <button class="btn detail-text flex-shrink-0" type="button">
                        <i class="bi-cart-fill me-1"></i>
                        Add to cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Related items section-->


<secsion class="best-seller-products-wrapper ">
    <div class="container-fluid py-5 bg-dark">
        <div class="container">
            <h2 class="fw-bolder mb-4 text-white">SẢN PHẨM TƯƠNG TỰ</h2>
            <div id="carouselExample" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">

                            <?php
                            for ($i = 1; $i < 4; $i++) {
                            ?>

                                <div class="col-lg-4 col-md-6">
                                    <div class="product-single-container p-2 border border-secondary">
                                        <div class="product-single">
                                            <div class="row header-product-single">
                                                <div class="col-lg-12">
                                                    <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/logo.png" alt="" class="logo m-2 img-fluid">
                                                    <strong class="bg-danger text-white py-2 px-4 ">HOT</strong>
                                                    <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/loa<?= $i ?>.png" alt="" class="w-100 img-fluid product-img">
                                                </div>
                                            </div>
                                            <div class="row product-single-footer mx-0 d-flex align-items-center p-3">
                                                <div class="left-product-single-footer py-3 col-lg-7 d-flex flex-column  align-items-center d-lg-block ">
                                                    <a href="#" class="text-white mb-0 product-name">Loa Bluetooth Soundmax B-60/5.1</a>
                                                    <div class="rate">
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-regular fa-star text-warning fs-6"></i>
                                                    </div>
                                                    <strong class="text-white me-3 price">3.300.000</strong> <del class="text-secondary detail-text">5.300.000</del>
                                                </div>
                                                <div class="right-product-single-footer col-lg-5 d-flex justify-content-center justify-content-lg-end ">
                                                    <a href=" " class="text-white text-center fw-bold px-2 py-3 border-white border detail-text">CHI TIẾT</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>

                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row">
                            
                            <?php
                            for ($i = 4; $i < 7; $i++) {
                            ?>

                                <div class="col-lg-4 col-md-6">
                                    <div class="product-single-container p-2 border border-secondary">
                                        <div class="product-single">
                                            <div class="row header-product-single">
                                                <div class="col-lg-12">
                                                    <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/logo.png" alt="" class="logo m-2 img-fluid">
                                                    <strong class="bg-danger text-white py-2 px-4 ">HOT</strong>
                                                    <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/loa<?= $i ?>.png" alt="" class="w-100 img-fluid product-img">
                                                </div>
                                            </div>
                                            <div class="row product-single-footer mx-0 d-flex align-items-center p-3">
                                                <div class="left-product-single-footer py-3 col-lg-7 d-flex flex-column  align-items-center d-lg-block ">
                                                    <a href="#" class="text-white mb-0 product-name">Loa Bluetooth Soundmax B-60/5.1</a>
                                                    <div class="rate">
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-solid fa-star text-warning fs-6"></i>
                                                        <i class="fa-regular fa-star text-warning fs-6"></i>
                                                    </div>
                                                    <strong class="text-white me-3 price">3.300.000</strong> <del class="text-secondary detail-text">5.300.000</del>
                                                </div>
                                                <div class="right-product-single-footer col-lg-5 d-flex justify-content-center justify-content-lg-end ">
                                                    <a href=" " class="text-white text-center fw-bold px-2 py-3 border-white border detail-text">CHI TIẾT</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>

                        </div>
                    </div>
                    <!-- <div class="carousel-item">
                        <img src="..." class="d-block w-100" alt="...">
                    </div> -->
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</secsion>