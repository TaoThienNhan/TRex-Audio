<?php

global $assets;

return [
	'js' => [
		'bootstrap'  => get_theme_file_uri($assets . 'libs/bootstrap/js/bootstrap.js'),
		'mmenu'      => get_theme_file_uri($assets . 'libs/mmenu/mmenu.js'),
		'slick'      => get_theme_file_uri($assets . 'libs/slick/slick.js'),
		'fancybox'   => get_theme_file_uri($assets . 'libs/fancybox/jquery.fancybox.js'),
		'wow'        => get_theme_file_uri($assets . 'libs/wow/wow.js'),
		'tns-script' => get_theme_file_uri($assets . 'js/scripts.js'),
	],

	'css' => [
		'bootstrap'    => get_theme_file_uri($assets . 'libs/bootstrap/css/bootstrap.css'),
		'font-awesome' => get_theme_file_uri($assets . 'libs/font-awesome/css/all.min.css'),
		'mmenu'        => get_theme_file_uri($assets . 'libs/mmenu/mmenu.css'),
		'fancybox'     => get_theme_file_uri($assets . 'libs/fancybox/jquery.fancybox.css'),
		'slick'        => get_theme_file_uri($assets . 'libs/slick/slick.css'),
		'animate'      => get_theme_file_uri($assets . 'libs/wow/animate.css'),
		'tns-style'    => get_theme_file_uri($assets . 'css/style.css'),
	]
];
