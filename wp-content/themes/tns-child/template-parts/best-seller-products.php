<style>
    .product-single {
        transition: top all .3s;
        background: #4c4c4c;
        opacity: 1;
    }

    .product-single-container:hover .product-single {
        background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/bg-single-product.png');
        background-size: 100% 100%;
        background-position: top;
        background-repeat: no-repeat;
    }

    .product-single-container:hover {
        border: 1px solid #ffcc00 !important;
    }

    .product-single-container:hover .rate i {
        color: white !important;
    }

    .product-single-container:hover .price {
        color: black !important;
    }

    .product-single-container:hover .product-name {
        color: black !important;
    }

    .product-single-container:hover .product-name {
        color: black !important;
    }

    .detail-text:hover {
        background-color: rgba(173, 173, 173, 0.353);
    }
</style>

<secsion class="best-seller-products-wrapper ">
    <div class="container-fluid py-3 bg-dark">
        <div class="container">
            <div class="title">
                <h1 class="text-center py-3" style="background: url(<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png);">SẢN PHẨM BÁN CHẠY</h1>
            </div>
            <div class="row g-4">

                <?php
                for ($i = 1; $i < 10; $i++) {
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product-single-container p-2 border border-secondary">
                            <div class="product-single">
                                <div class="row header-product-single">
                                    <div class="col-lg-12">
                                        <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/logo.png" alt="" class="logo m-2 img-fluid">
                                        <strong class="bg-danger text-white py-2 px-4 ">HOT</strong>
                                        <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/loa<?= $i ?>.png" alt="" class="w-100 img-fluid">
                                    </div>
                                </div>
                                <div class="row product-single-footer d-flex align-items-center p-3">
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
    </div>
</secsion>