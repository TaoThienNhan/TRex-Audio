<?php
/**
 * Functions and definitions
 */

/**
 * Display custom logo
 */
function tns_logo(){ ?>
	<div class="title-area">
		<?php
		$wrap = is_front_page() ? 'h1' : 'p';
		if (has_custom_logo()):
			$custom_logo_id = get_theme_mod('custom_logo'); ?>

			<a href="<?= esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
				<?= wp_get_attachment_image($custom_logo_id, 'full'); ?>
			</a>

			<?= sprintf("<{$wrap} %s>%s</{$wrap}>", 'class="site-title"',
			esc_attr(get_bloginfo('name'))) ?>

		<?php else: ?>
			<?= sprintf("<{$wrap} %s>", 'class="site-title"') ?>
			<a href="<?= esc_url(home_url('/')); ?>" title="<?= esc_attr(get_bloginfo('name')); ?>" rel="home"><?= esc_attr(get_bloginfo('name')); ?></a>
			<?= "</{$wrap}>" ?><?php endif; ?>
		<p class="site-description"><?= esc_attr(get_bloginfo('description')); ?></p>
	</div>
<?php }

/**
 * pagination
 */
function tns_pagination($args = []){

	$defaults = [
		'items_wrap_class'  => '',
		'items_class'       => '',
		'item_active_class' => 'active',
		'links_class'       => '',
		'prev'              => 'Prev',
		'next'              => 'Next',
		'prev_label'        => 'Previous',
		'next_label'        => 'Next',
		'echo'              => TRUE
	];

	$args        = wp_parse_args($args, $defaults);
	$args        = (object) $args;
	$query       = $GLOBALS['wp_query'];
	$paged       = max(1, absint($query->get('paged')));
	$total_pages = max(1, absint($query->max_num_pages));

	if (1 == $total_pages):
		return;
	endif;

	$pages_to_show         = absint(5);
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start       = floor($pages_to_show_minus_1 / 2);
	$half_page_end         = ceil($pages_to_show_minus_1 / 2);
	$start_page            = $paged - $half_page_start;
	$pagination            = '';

	if ($start_page <= 0):
		$start_page = 1;
	endif;

	$end_page = $paged + $half_page_end;

	if (($end_page - $start_page) != $pages_to_show_minus_1):
		$end_page = $start_page + $pages_to_show_minus_1;
	endif;

	if ($end_page > $total_pages):
		$start_page = $total_pages - $pages_to_show_minus_1;
		$end_page   = $total_pages;
	endif;

	if ($start_page < 1):
		$start_page = 1;
	endif;

	$pagination .= '<ul class="' . $args->items_wrap_class . '">';

	// First
	if ($start_page >= 2 && $pages_to_show < $total_pages){
		//$pagination .= get_pagenum_link( 1 );
	}

	// Previous
	if ($paged > 1){
		$pagination .= '<li class="' . $args->items_class . '"><a class="' . $args->links_class . '" href="' . get_pagenum_link($paged - 1) . '" aria-label="' . $args->prev_label . '">' . $args->prev . '</a></li>';
	}

	foreach (range($start_page, $end_page) as $i){
		if ($i == $paged):
			$pagination .= '<li class="' . $args->items_class . ' ' . $args->item_active_class . '"><a class="' . $args->links_class . '" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
		else:
			$pagination .= '<li class="' . $args->items_class . '"><a class="' . $args->links_class . '" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
		endif;
	}

	// Next
	if ($paged < $total_pages){
		$pagination .= '<li class="' . $args->items_class . '"><a class="' . $args->links_class . '" href="' . get_pagenum_link($paged + 1) . '" aria-label="' . $args->next_label . '">' . $args->next . '</a></li>';
	}

	// Last
	if ($end_page < $total_pages){
		//$pagination .= get_pagenum_link( $total_pages );
	}

	$pagination .= '</ul>';

	if ($args->echo){
		echo $pagination;

		return;
	}

	return $pagination;
}

/**
 * Locate and require a config file.
 */
function tns_get_config($config){

	$parent_file = sprintf('%s/config/%s.php', get_template_directory(), $config);
	$child_file  = sprintf('%s/config/%s.php', get_stylesheet_directory(), $config);

	$data = [];

	if (is_readable($child_file)){
		$data = require $child_file;
	}

	if (empty($data) && is_readable($parent_file)){
		$data = require $parent_file;
	}

	return (array) $data;

}

/**
 * Open tag with classes
 */
function tns_open_tag($tag, $classes){
	echo '<' . esc_attr($tag) . ' class="' . esc_attr(apply_filters('tns_open_div_tag_class', $classes)) . '">';
}

/**
 * Close tag
 */
function tns_close_tag($tag){
	echo '</' . esc_attr($tag) . '>';
}
