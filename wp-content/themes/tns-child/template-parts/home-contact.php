<style>
    .home-contact {
        background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/bg-contact.png');
        background-size: cover;
        background-position: center;
    }
</style>

<div class="brand-logo py-3">
    <div class="container">
        <div class="row gy-4 brand-banner py-3 py-md-4 d-flex justify-content-between align-items-center">
            <?php
            if (have_rows('cac_nhan_hieu_lien_ket', 'option')) {
                while (have_rows('cac_nhan_hieu_lien_ket', 'option')) {
                    the_row();
                    ?>
                    <img src="<?= get_sub_field('hinh') ?>" alt="" class="img-fluid col-6 col-md-4 col-lg-2">
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<div class="home-contact py-5 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <!-- Video lớn ở trên -->
                    <div class="col-12 border border-1 border-secondary p-2 d-flex justify-content-center align-items-lg-center big-video">
                        <div class="responsive-iframe">
                            <?=get_field('video_san_pham_chinh', 'option')?>
                        </div>
                    </div>
                </div>
                <div class="row border border-1 border-secondary small-video">
                    <!-- Video nhỏ ở dưới -->
                    <div class="col-4 border border-1 border-secondary p-2 d-flex justify-content-center align-items-lg-center">
                        <div class="responsive-iframe">
                            <?=get_field('video_san_pham_phu_1', 'option')?>
                        </div>
                    </div>
                    <div class="col-4 border border-1 border-secondary p-2 d-flex justify-content-center align-items-lg-center">
                        <div class="responsive-iframe">
                            <?=get_field('video_san_pham_phu_2', 'option')?>
                        </div>
                    </div>
                    <div class="col-4 border border-1 border-secondary p-2 d-flex justify-content-center align-items-lg-center">
                        <div class="responsive-iframe">
                            <?=get_field('video_san_pham_phu_3', 'option')?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-3 mt-lg-0 px-5">
                <h2 class="text-center py-3 fw-bolder" style="background: url(<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png);background-position: center;">YÊU CẦU TƯ VẤN </h2>
                <?=do_shortcode('[forminator_form id="98"]')?>
            </div>
        </div>
    </div>
</div>