<div class="category-resolution-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block">
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
            <div class="col-lg-9">

            </div>
        </div>
    </div>
</div>