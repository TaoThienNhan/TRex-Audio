<?php
/**
 * Twitter cards functionality.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\OpenGraph
 * @author     Rank Math <support@rankmath.com>
 *
 * @copyright Copyright (C) 2008-2019, Yoast BV
 * The following code is a derivative work of the code from the Yoast(https://github.com/Yoast/wordpress-seo/), which is licensed under GPL v3.
 */

namespace RankMath\OpenGraph;

use RankMath\Helper;
use RankMath\Post;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Arr;

defined( 'ABSPATH' ) || exit;

/**
 * Twitter class.
 */
class Twitter extends OpenGraph {

	/**
	 * Network slug.
	 *
	 * @var string
	 */
	public $network = 'twitter';

	/**
	 * Metakey prefix.
	 *
	 * @var string
	 */
	public $prefix = 'twitter';

	/**
	 * Hold site info for twitter card.
	 *
	 * @var string
	 */
	private $site = null;

	/**
	 * Will hold the Twitter card type being created
	 *
	 * @var string
	 */
	private $type;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		/**
		 * Allow changing the Twitter Card type as output in the Twitter card.
		 *
		 * @param string $type
		 */
		if ( false === $this->do_filter( 'opengraph/twitter_card', true ) ) {
			return;
		}

		$this->action( 'rank_math/opengraph/twitter', 'use_facebook', 1 );
		$this->action( 'rank_math/opengraph/twitter', 'type', 5 );
		$this->action( 'rank_math/opengraph/twitter', 'title', 10 );
		$this->action( 'rank_math/opengraph/twitter', 'description', 11 );
		$this->action( 'rank_math/opengraph/twitter', 'website', 14 );

		if ( ! post_password_required() ) {
			$this->action( 'rank_math/opengraph/twitter', 'image', 30 );
		}

		if ( is_singular() ) {
			$this->action( 'rank_math/opengraph/twitter', 'article_author', 15 );
		}

		parent::__construct();
	}

	/**
	 * Set `use_facebook` variable.
	 */
	public function use_facebook() {
		$use_facebook = ( is_category() || is_tag() || is_tax() ) ? Helper::get_term_meta( 'twitter_use_facebook' ) :
			Helper::get_post_meta( 'twitter_use_facebook', Post::is_shop_page() ? Post::get_shop_page_id() : 0, true );

		if ( $use_facebook ) {
			$this->prefix = 'facebook';
		}
	}

	/**
	 * Display the Twitter card type.
	 *
	 * This defaults to summary but can be filtered using the <code>rank_math_twitter_card_type</code> filter.
	 */
	public function type() {
		$this->determine_card_type();
		$this->sanitize_card_type();

		$this->tag( 'twitter:card', $this->type );

		$remove_tags = false;
		if ( is_singular() && ! is_front_page() && in_array( $this->type, [ 'app', 'player' ], true ) ) {
			$remove_tags = 'app' === $this->type;
			$this->action( 'rank_math/opengraph/twitter', $this->type, 15 );
		}

		$remove_tags = is_date() && in_array( $this->type, [ 'summary', 'summary_large_image' ], true );
		if ( $remove_tags ) {
			$this->remove_tags();
		}
	}

	/**
	 * Output App card.
	 */
	public function app() {

		$this->tag( 'twitter:description', Helper::get_post_meta( 'twitter_app_description' ) );
		$this->tag( 'twitter:app:country', Helper::get_post_meta( 'twitter_app_country' ) );

		// iPhone.
		$this->tag( 'twitter:app:name:iphone', Helper::get_post_meta( 'twitter_app_iphone_name' ) );
		$this->tag( 'twitter:app:id:iphone', Helper::get_post_meta( 'twitter_app_iphone_id' ) );
		$this->tag( 'twitter:app:url:iphone', Helper::get_post_meta( 'twitter_app_iphone_url' ) );

		// iPad.
		$this->tag( 'twitter:app:name:ipad', Helper::get_post_meta( 'twitter_app_ipad_name' ) );
		$this->tag( 'twitter:app:id:ipad', Helper::get_post_meta( 'twitter_app_ipad_id' ) );
		$this->tag( 'twitter:app:url:ipad', Helper::get_post_meta( 'twitter_app_ipad_url' ) );

		// Google Play.
		$this->tag( 'twitter:app:name:googleplay', Helper::get_post_meta( 'twitter_app_googleplay_name' ) );
		$this->tag( 'twitter:app:id:googleplay', Helper::get_post_meta( 'twitter_app_googleplay_id' ) );
		$this->tag( 'twitter:app:url:googleplay', Helper::get_post_meta( 'twitter_app_googleplay_url' ) );
	}

	/**
	 * Output player card.
	 */
	public function player() {
		$this->tag( 'twitter:player', Helper::get_post_meta( 'twitter_player_url' ) );

		$size = Helper::get_post_meta( 'twitter_player_size' );
		if ( $size ) {
			$size = Arr::from_string( $size, 'x' );
			if ( isset( $size[1] ) ) {
				$twitter_meta['twitter:player:width']  = (int) $size[0];
				$twitter_meta['twitter:player:height'] = (int) $size[1];
			}
		}
		$this->tag( 'twitter:player:stream', Helper::get_post_meta( 'twitter_player_stream' ) );
		$this->tag( 'twitter:player:stream:content_type', Helper::get_post_meta( 'twitter_player_stream_ctype' ) );
	}

	/**
	 * Output the title.
	 */
	public function title() {
		$this->tag( 'twitter:title', trim( $this->get_title() ) );
	}

	/**
	 * Output the description.
	 */
	public function description() {
		$this->tag( 'twitter:description', trim( $this->get_description() ) );
	}

	/**
	 * Output the Twitter account for the site.
	 */
	public function website() {
		$this->site = Helper::get_settings( 'titles.twitter_author_names' );
		if ( Str::is_non_empty( $this->site ) ) {
			$this->tag( 'twitter:site', '@' . $this->site );
		}
	}

	/**
	 * Output the image for Twitter.
	 *
	 * Only used when OpenGraph is inactive or Summary Large Image card is chosen.
	 */
	public function image() {
		$images = new Image( false, $this );
		foreach ( $images->get_images() as $image_url => $image_meta ) {
			$overlay = $this->get_overlay_image( $this->prefix );
			if ( $overlay && ! empty( $image_meta['id'] ) ) {
				$secret    = $images->generate_secret( $image_meta['id'], $overlay );
				$image_url = admin_url( "admin-ajax.php?action=rank_math_overlay_thumb&id={$image_meta['id']}&type={$overlay}&secret={$secret}" );
			}

			$this->tag( 'twitter:image', esc_url_raw( $image_url ) );
		}
	}

	/**
	 * Outputs the authors twitter handle.
	 */
	public function article_author() {
		$author = Helper::get_user_meta( 'twitter_author', $GLOBALS['post']->post_author );
		$author = $author ? $author : get_user_meta( $GLOBALS['post']->post_author, 'twitter', true );
		$author = $this->get_twitter_id( ltrim( trim( $author ), '@' ) );

		if ( Str::is_non_empty( $author ) ) {
			$this->tag( 'twitter:creator', '@' . $author );
		} elseif ( Str::is_non_empty( $this->site ) ) {
			$this->tag( 'twitter:creator', '@' . $this->site );
		}
	}

	/**
	 * Determines the twitter card type for the current page
	 */
	private function determine_card_type() {
		$this->type = Helper::get_post_meta( 'twitter_card_type' );
		$this->type = $this->type ? $this->type : Helper::get_settings( 'titles.twitter_card_type' );

		/**
		 * Allow changing the Twitter Card type as output in the Twitter card.
		 *
		 * @param string $this->type
		 */
		$this->type = $this->do_filter( 'opengraph/twitter/card_type', $this->type );
	}

	/**
	 * Determines whether the card type is of a type currently allowed by Twitter
	 *
	 * @link https://dev.twitter.com/cards/types
	 */
	private function sanitize_card_type() {
		if ( ! in_array( $this->type, [ 'summary', 'summary_large_image', 'app', 'player' ], true ) ) {
			$this->type = 'summary';
		}
	}

	/**
	 * Checks if the given ID is actually an ID or a URL and if URL, distills the ID from it.
	 *
	 * Solves issues with filters returning URLs and theme's/other plugins also adding a user meta
	 * twitter field which expects URL rather than an ID (which is what we expect).
	 *
	 * @param string $id Twitter ID or URL.
	 *
	 * @return string|bool Twitter ID or false if it failed to get a valid Twitter ID.
	 */
	private function get_twitter_id( $id ) {
		if ( preg_match( '`([A-Za-z0-9_]{1,25})$`', $id, $match ) ) {
			return $match[1];
		}

		return false;
	}

	/**
	 * Remove archive tags.
	 */
	private function remove_tags() {
		$this->remove_action( 'rank_math/opengraph/twitter', 'title', 10 );
		$this->remove_action( 'rank_math/opengraph/twitter', 'description', 11 );
		$this->remove_action( 'rank_math/opengraph/twitter', 'image', 30 );
	}
}
