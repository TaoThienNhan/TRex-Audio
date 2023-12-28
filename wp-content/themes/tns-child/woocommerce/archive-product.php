<?php
/**
 * The Template for displaying all archive product
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');
?>
    <style>
        .product-single-container:hover .product-single-footer {
            background: url('<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png');
            background-size: 100% 100%;
        }
    </style>
    <div class="archive-product-wrapper">
        <div class="container py-5">
            <div class="row">
                <div
                    class="col-lg-8 text-white d-block justify-content-md-between d-md-flex justify-content-center align-items-center">
                    <p class="woocommerce-result-count d-flex align-items-center">
                        <?= __('SHOWING', 'woocommerce') . ' ' . wc_get_loop_prop('total') . ' ' . __('OF', 'woocommerce') . ' ' . wc_get_loop_prop('total') . ' ' . __('RESULTS', 'woocommerce'); ?>
                    </p>
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-lg-8">
                    <div class="row related-products">
                        <?php
                        if (wc_get_loop_prop('total')):
                            while (have_posts()):
                                the_post();

                                global $product;

                                do_action('woocommerce_shop_loop');
                                ?>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="product-single-container p-2 border border-secondary">
                                        <div class="product-single">
                                            <div class="row header-product-single">
                                                <div class="col-lg-12">
                                                    <strong class="bg-danger text-white py-2 px-4">HOT</strong>
                                                    <a href="<?= $product->get_permalink() ?>"><?= $product->get_image() ?></a>
                                                </div>
                                            </div>
                                            <div class="row product-single-footer d-flex align-items-center p-3 mx-0">
                                                <div
                                                    class="left-product-single-footer col-lg-7 d-flex flex-column align-items-center d-lg-block ">
                                                    <a href="<?= $product->get_permalink() ?>"
                                                       class="text-white mb-0 product-name"><strong><?= $product->get_name() ?></strong></a>
                                                    <div class="rate">
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                    </div>
                                                    <?php if ($product->is_on_sale()) : ?>
                                                        <strong
                                                            class="text-white me-3 price"><?php echo wc_price($product->get_sale_price()) ?></strong>
                                                        <del
                                                            class="text-secondary detail-text"><?php echo wc_price($product->get_regular_price()) ?></del>
                                                    <?php else : ?>
                                                        <?php echo wc_price($product->get_regular_price()) ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div
                                                    class="right-product-single-footer col-lg-5 d-flex justify-content-center justify-content-lg-end ">
                                                    <a href="<?= $product->get_permalink() ?>"
                                                       class="text-white text-center px-3 py-4 border-white border detail-text">
                                                        <h6 class="m-0">CHI TIẾT</h6></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?><?php
                        endif;

                        woocommerce_product_loop_end();
                        ?>
                        <div class="pagination d-flex justify-content-center align-items-center">
                            <?php tns_pagination([
                                'prev' => '<',
                                'next' => '>'
                            ]); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 p-3">
                        <div class="other-product">
                            <div class="item">
                                <div class="cate-title text-white">Danh Mục Sản Phẩm</div>
                                <ul class="product-categories p-2">
                                    <?php
                                    $args = [
                                        'taxonomy' => 'product_cat',
                                        'orderby' => 'name',
                                        'title_li' => '',
                                    ];

                                    wp_list_categories($args);
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="item my-3">
                            <div class="cate-title mb-3 text-white">Sản Phẩm Mới</div>
                            <?php
                            $args = [
                                'status' => 'publish',
                                'limit' => 10,
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ];

                            $products = wc_get_products($args);

                            foreach ($products as $product) {
                                ?>
                                <div class="products row mb-3">
                                    <div class="img col-lg-5">
                                        <a href="<?= $product->get_permalink() ?>">
                                            <?= $product->get_image(); ?>
                                        </a>
                                    </div>
                                    <div class="name col-lg-7">
                                        <a href="<?= $product->get_permalink() ?>">
                                            <?= $product->get_name(); ?>
                                        </a>
                                        <div class="star-rate d-flex">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer('shop');
?>