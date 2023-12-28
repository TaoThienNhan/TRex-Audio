<style>
    .footer {
        background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/bg-footer.png');
        background-size: cover;
        background-position: bottom;
    }
</style>

<div class="footer py-5 bg-dark">
    <div class="container">
        <div class="row mb-4 ">
            <div class="col-sm-4 d-flex align-items-center">
                <img src="<?= get_field('logo', 'option') ?>" alt="" class="img-fluid">
            </div>
            <div class="col-sm-8 d-flex justify-content-sm-end align-items-center">
                <?php
                if (have_rows('accuracy', 'option')) {
                    while (have_rows('accuracy', 'option')) {
                        the_row();
                        ?>
                        <a target="_blank" href="<?= get_sub_field('link', 'option') ?>">
                            <img src="<?= get_sub_field('icon', 'option') ?>" alt="" class="img-fluid my-3">
                        </a>
                        <?php
                    }
                }
                ?>
            </div>

            <div class="col-md-4 pt-1 my-3">
                <div class="location-container">
                    <?php
                    if (have_rows('address_base', 'option')) {
                        while (have_rows('address_base', 'option')) {
                            the_row();
                            ?>
                            <div class="location d-flex justify-content-start align-items-center">
                                <i class="fas fa-map-marker-alt fs-3 text-white"></i>
                                <div class="text-location ms-3">
                                    <p class="mb-0 text-uppercase"><?= get_sub_field('province') ?></p>
                                    <p class="text-white mb-0"><?= get_sub_field('address_detail') ?></p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-8 col-lg-7 offset-lg-1 my-3">
                <div class="d-block d-md-flex justify-content-between">
                    <div class="contact-footer pt-1 text-location">
                        <?php
                        if (have_rows('phone_list', 'option')) {
                            while (have_rows('phone_list', 'option')) {
                                the_row();
                                ?>
                                <p class="mb-0 me-3">
                            <span class="w-50 text-uppercase">
                                <?=get_sub_field('phone_title')?>:
                            </span>
                                    <span class="text-white w-50">
                                <?=get_sub_field('phone')?>
                            </span>
                                </p>
                                <?php
                            }
                        }
                        ?>
                        <p class="text-light mb-0">Email: <?=get_field('email_primary', 'option')?></p>
                    </div>

                    <div class="rules">
                        <strong class="pb-1">ĐIỀU KHOẢN SỬ DỤNG</strong>
                        <div>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'chinh-sach-menu',
                                'container' => 'nav',
                                'container_id' => 'chinh-sach',
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="nav-footer">
                        <strong class="pb-1">DANH MỤC</strong>
                        <div>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'danh-muc-footer-menu',
                                'container' => 'nav',
                                'container_id' => 'danh-muc',
                            ));
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row footer-bottom py-4">
            <div class="col-lg-6 d-flex justify-content-center justify-content-lg-start">
                <?php
                if (have_rows('social_media', 'option')) {
                    while (have_rows('social_media', 'option')) {
                        the_row();
                        ?>
                        <a target="_blank" href="<?=get_sub_field('social_media_link')?>"><img src="<?=get_sub_field('social_media_icon')?>" alt="" class="img-fluid me-2 me-lg-3"></a>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="col-lg-6 mt-3 mt-lg-0 text-uppercase text-light text-center text-lg-end text-design-by">
                <?php echo date('Y'); ?> © <a href="" class="text-light "><?=get_field('ten_trang_web', 'option')?></a> All Rights Reserved. Designed
                by <a href="" class="text-light ">Tay Nam Solution.</a>
            </div>
        </div>
    </div>
</div>