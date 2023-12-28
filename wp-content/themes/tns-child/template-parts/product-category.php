<?php
$selected_category_ids = get_field('san_pham_theo_danh_muc', 'option');

if ($selected_category_ids) {
    foreach ($selected_category_ids as $category_item) {
        $category_id = $category_item['danh_muc'];
        $category = get_term($category_id, 'product_cat');

        if (!is_wp_error($category)) {
            $category_name = $category->name;
            $category_link = get_term_link($category);

            $quantity = $category_item['so_luong_san_pham_hien_thi'];

            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $quantity,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $category_id,
                        'operator' => 'IN',
                    ),
                ),
            );

            $products = wc_get_products($args);

            if ($products) :
                $brands = array();

                // Lặp qua các sản phẩm trong danh mục hiện tại và lấy thương hiệu của chúng
                foreach ($products as $product) {
                    $product_brands = wp_get_post_terms($product->get_id(), 'pa_thuong-hieu');

                    if (!empty($product_brands)) {
                        foreach ($product_brands as $product_brand) {
                            $brands[$product_brand->term_id] = $product_brand->name;
                        }
                    }
                }

                if (!empty($brands)) {
                    ?>
                    <section class="best-seller-products-wrapper">
                        <div class="container-fluid py-3">
                            <div class="container">
                                <div class="row py-3">
                                    <div class="col-lg-4 category_name">
                                        <h2 class="text-center py-3 m-0" style="background: url(<?= get_stylesheet_directory_uri(); ?>/assets/src/images/background-gold.png);background-position: center;font-size: 16px;font-weight: 800;"><?=$category_name?></h2>
                                    </div>
                                    <div class="text-white row col-lg-6 offset-lg-2 d-flex justify-content-between align-items-center my-2 my-lg-0">
                                        <?php
/*                                        foreach ($brands as $brand_id => $brand_name) {
                                            $brand_term = get_term($brand_id, 'pa_thuong-hieu');
                                            if (!is_wp_error($brand_term)) {
                                                $brand_link = get_term_link($brand_term);
                                                echo '<a href="' . $brand_link . '" class="col text-white border-s brand-name">' . $brand_name . '</a>';
                                            }
                                        }
                                        */?>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <?php
                                    foreach ($products as $product) {
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
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
                } else {
                    echo 'Không có sản phẩm nào trong danh mục ' . $category_name;
                }
            endif;
        }
    }
}
