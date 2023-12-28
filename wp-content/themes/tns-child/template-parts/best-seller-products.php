<style>
    .product-single-container:hover .product-single-footer {
        background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png');
        background-size: 100% 100%;
    }
</style>

<secsion class="best-seller-products-wrapper">
    <div class="container-fluid py-3">
        <div class="container">
            <div class="title mb-3">
                <h2 class="text-center py-3 fw-bolder"
                    style="background: url(<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png);">
                    SẢN PHẨM BÁN CHẠY</h2>
            </div>
            <div class="row g-4">
                <?php
                $args_latest = array(
                    'post_type' => 'product',
                    'posts_per_page' => 9,
                    'orderby' => 'date',
                    'order' => 'desc',
                );

                $latest_products = new WP_Query($args_latest);

                if ($latest_products->have_posts()) :
                    while ($latest_products->have_posts()) :
                        $latest_products->the_post();
                        $product = wc_get_product(get_the_ID());
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product-single-container p-2 border border-secondary">
                                <div class="product-single">
                                    <div class="row header-product-single">
                                        <div class="col-lg-12">
                                            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/src/images/logo.png"
                                                 alt="" class="logo m-2 img-fluid">
                                            <strong class="bg-danger text-white py-2 px-4">HOT</strong>
                                            <a href="<?= $product->get_permalink() ?>"><?= $product->get_image() ?></a>
                                        </div>
                                    </div>
                                    <div class="row product-single-footer d-flex align-items-center p-3 mx-0">
                                        <div
                                            class="left-product-single-footer col-lg-7 d-flex flex-column align-items-center d-lg-block ">
                                            <a href="<?= $product->get_permalink() ?>" class="text-white mb-0 product-name"><strong><?=$product->get_name()?></strong></a>
                                            <div class="rate">
                                                <i class="fa-solid fa-star text-warning"></i>
                                                <i class="fa-solid fa-star text-warning"></i>
                                                <i class="fa-solid fa-star text-warning"></i>
                                                <i class="fa-solid fa-star text-warning"></i>
                                                <i class="fa-solid fa-star text-warning"></i>
                                            </div>
                                            <?php if ($product->is_on_sale()) : ?>
                                                <strong class="text-white me-3 price"><?php echo wc_price($product->get_sale_price()) ?></strong>
                                                <del class="text-secondary detail-text"><?php echo wc_price($product->get_regular_price()) ?></del>
                                            <?php else : ?>
                                                <?php echo wc_price($product->get_regular_price()) ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="right-product-single-footer col-lg-5 d-flex justify-content-center justify-content-lg-end ">
                                            <a href="<?=$product->get_permalink()?>"
                                               class="text-white text-center px-3 py-4 border-white border detail-text">
                                                <h6 class="m-0">CHI TIẾT</h6></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo 'Không có sản phẩm nào.';
                endif;
                ?>
            </div>
        </div>
    </div>
</secsion>