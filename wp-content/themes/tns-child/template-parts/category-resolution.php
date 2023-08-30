<style>
    .row .resolution-item:hover {
        background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png');
        background-size: 100% 100%;
    }
</style>
<div class="category-resolution-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block m-0 p-0">
                <div class="title d-flex justify-content-center align-items-center" style="background: url( <?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png)">
                    <i class="fa-solid fa-bars me-2"></i>DANH MỤC SẢN PHẨM
                </div>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'danh-muc-menu',
                    'container' => 'nav',
                ));
                ?>
            </div>
            <div class="col-lg-9 m-0 p-0">
                <div class="banner">
                    <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/banner.jpg" alt="">
                </div>
                <div class="row m-0 p-0 resolution">
                    <div class="col resolution-item d-flex justify-content-center align-items-center">
                        <div class="resolution-item-border gap-1 d-flex justify-content-center align-items-center">
                            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/money-bag.png" alt="">
                            <div class="d-none d-md-block">Hỗ Trợ trả góp</div>
                        </div>
                    </div>
                    <div class="col resolution-item d-flex justify-content-center align-items-center">
                        <div class="resolution-item-border gap-1 d-flex justify-content-center align-items-center">
                            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/maintenance.png" alt="">
                            <div class="d-none d-md-block">Thi công lắp đặt & Hướng dẫn sử dụng</div>
                        </div>
                    </div>
                    <div class="col resolution-item  d-flex justify-content-center align-items-center">
                        <div class="resolution-item-border gap-1 d-flex justify-content-center align-items-center">
                            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/shield.png" alt="">
                            <div class="d-none d-md-block">Bảo hành 2 năm tất cả sản phẩm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>