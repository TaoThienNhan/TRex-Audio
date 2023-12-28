<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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

if (!defined('ABSPATH')){
	exit; // Exit if accessed directly
}

get_header('shop');

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<?php while (have_posts()) : ?><?php the_post();
	global $product;
	?>

	<div class="single-product-wrapper">
		<div class="row">
			<div class="col-md-6">
				<div class="main-image">
					<?= wp_get_attachment_image($product->get_image_id(), 'woocommerce_single'); ?>
				</div>
				<div class="img-gallery row my-3">
					<?php
					$attachment_ids = $product->get_gallery_image_ids();
					foreach ($attachment_ids as $attachment_id){
						?>
						<div class="col-3">
							<a href="<?= wp_get_attachment_url($attachment_id) ?>" style="display: block; width: 100%">
								<img src="<?= wp_get_attachment_image_url($attachment_id,
									'thumbnail') ?>" width="100%">
							</a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="col-md-6 p-md-5">
				<div class="product-name">
					<?= $product->get_name() ?>
				</div>
				<div class="star-rate d-flex gap-2 my-4">
					<i class="fa-solid fa-star"></i>
					<i class="fa-solid fa-star"></i>
					<i class="fa-solid fa-star"></i>
					<i class="fa-solid fa-star"></i>
					<i class="fa-solid fa-star"></i>
				</div>
				<div class="price">
					<?php if ($product->is_on_sale()) : ?>
						<strong class="text-white me-3 price"><?php echo wc_price($product->get_sale_price()) ?></strong>
						<del class="text-secondary detail-text"><?php echo wc_price($product->get_regular_price()) ?></del>
					<?php else : ?>
						<?php echo wc_price($product->get_regular_price()) ?>
					<?php endif; ?>
				</div>
				<div class="short-description py-5">
					<?= $product->get_short_description() ?>
				</div>
				<div class="stock">
					<?php
					if ($product->is_in_stock()){
						?>
						<span class="text-success"><i class="fa-solid fa-circle-check"></i> Còn Hàng</span>
						<?php
					}else{
						?>
						<span class="text-danger"><i class="fa-solid fa-circle-xmark"></i> Hết Hàng</span>
						<?php
					}

					?>
				</div>
				<div class="other py-3 my-5">
					<div class="sku">Mã Sản Phẩm: <?= $product->get_sku() ?></div>
					<div class="category">Danh Mục: <?= wc_get_product_category_list($product->get_id()) ?></div>
				</div>
			</div>
		</div>
		<div class="content p-5 my-5">
			<ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
				<li class="nav-item" role="presentation">
					<a class="nav-link active" id="ex1-tab-1" data-bs-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">Mô Tả</a>
				</li>
				<li class="nav-item" role="presentation">
					<a class="nav-link" id="ex1-tab-2" data-bs-toggle="tab" href="#ex1-tabs-2" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">Bình Luận</a>
				</li>
			</ul>
			<div class="tab-content" id="ex1-content">
				<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
					<?= the_content() ?? '' ?>
				</div>
				<div class="tab-pane fade py-5" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
					<?php
					$comments = get_comments([
						'post_id' => get_the_ID(),
						'status'  => 'approve',
					]);

					foreach ($comments as $comment){
						$author         = $comment->comment_author;
						$date           = $comment->comment_date;
						$avatar         = get_avatar($comment->comment_author_email, 60);
						$content        = $comment->comment_content;
						$average_rating = $product->get_average_rating();

						?>
						<div class="comment d-flex mb-3">
							<div class="avatar">
								<?= $avatar ?>
							</div>
							<div class="info">
								<div class="d-md-flex justify-content-between align-items-center">
									<div class="name"><?= $author ?></div>
									<div class="date"><?= $date ?></div>
								</div>
								<div class="content mb-3"><?= $content ?></div>
							</div>
						</div>
						<?php
					}

					if (comments_open()){
						comment_form();
					}
					?>

				</div>
			</div>
		</div>
		<?php
		$current_product_id = get_the_ID();
		$product_categories = wp_get_post_terms($current_product_id, 'product_cat');

		if ($product_categories){
		$category_ids = [];

		foreach ($product_categories as $category){
			$category_ids[] = $category->term_id;
		}

		$args = [
			'post_type'      => 'product',
			'posts_per_page' => 4,
			'tax_query'      => [
				[
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $category_ids,
				],
			],
		];

		$related_query = new WP_Query($args);

		if ($related_query->have_posts()) :
		?>
		<div class="related-products pt-2 mb-5">
			<div class="title text-uppercase">
				CÓ THỂ BẠN THÍCH!
			</div>
			<div class="row">
				<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
				<div class="col-lg-3 py-3 text-center">
					<a href="<?= $product->get_permalink() ?>"><?= $product->get_image() ?></a>
					<div class="text-center">
						<a href="<?= $product->get_permalink() ?>" class="product-title text-white d-block pt-3 text-uppercase"><?php echo mb_substr($product->get_name(), 0, 40); ?></a>
						<div class="star-rate d-flex justify-content-center">
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
							<i class="fa-solid fa-star"></i>
						</div>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
			<?php
			endif;
			wp_reset_postdata();
			}
			?>
	</div>
	<?php do_action('woocommerce_after_single_product'); ?><?php endwhile; // end of the loop.


get_footer('shop');
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
?>
