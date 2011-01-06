<?php
/*
Plugin Name: SuperButtons
Plugin URI: http://wpsupertheme.com/superbuttons-stylish-buttons-for-your-website/
Description: Create super stylish buttons with just a few clicks. Supports gradients and rounded corners. Cross-browser compatible.
Author: Illimar Tambek
Version: 0.6
Author URI: http://wpsupertheme.com/
*/

// DEFINE SOME CONSTANTS AND VARIABLES

	define ( 'SUPERBUTTON_URL' , WP_PLUGIN_URL . '/superbuttons' );
	define ( 'SUPERBUTTON_DIR' , WP_PLUGIN_DIR . '/superbuttons' );

// INCLUDE STUFF

	include ( SUPERBUTTON_DIR . '/cssparser.php' );

// SHORTCODE

	function create_superbutton($atts, $content = 'Click Me!') {
		
		extract(shortcode_atts(array(
			'image' => null,
			'symbol' => '&raquo;',
			'nosymbol' => false,
			'from' => '#000000',
			'to' => '#FFFFFF',
			'link' => 'javascript:void(0);',
			'class' => 'sprbtn_orange',
			'title' => '',
			'target' => '',
			'rel' => ''
		), $atts));

		if (!$title) $title = strip_tags($content);

		if ($nosymbol) {
			$symbol = '';
		} else {
			$symbol = ' ' . $symbol;
		}

		if ($image) { 
			$imageclass = 'hasimage';
			$link_style = 'background-image: url('.$image.');';
		}

		$classes = $class;

		$cssfiles = array( 'superbuttons.css', 'custom_styles.css');

		foreach ($cssfiles as $cssfile) {
		
			$cssfile = SUPERBUTTON_DIR . '/' . $cssfile;
			$cssfile = file_get_contents($cssfile);
			$cssp = new cssparser;
			$cssp -> ParseStr($cssfile);

			foreach ($cssp->css as $selector => $rules) {
				if ( strstr( $selector, $class ) ) {
					if ( !strstr( $selector, ' ' ) ) {
						if ( strstr( $selector, ':hover' ) ) {
							$state = 'hover';
							$bgHoverCSS = $rules['background-image'];
							$bgHoverStart = substr( $bgHoverCSS, strpos( $bgHoverCSS, '#'), 7);
							$bgHoverEnd = substr( $bgHoverCSS, strrpos( $bgHoverCSS, '#'), 7);
							$plain_selector = substr( $selector, 0, strrpos( $selector, ':') );
						} elseif ( strstr( $selector, ':active' ) ) { 
							$state = 'active';
							$bgActiveCSS = $rules['background-image'];
							$bgActiveStart = substr( $bgActiveCSS, strpos( $bgActiveCSS, '#'), 7);
							$bgActiveEnd = substr( $bgActiveCSS, strrpos( $bgActiveCSS, '#'), 7);
							$plain_selector = substr( $selector, 0, strrpos( $selector, ':') );
						} else {
							$state = 'normal';
							$bgNormalCSS = $rules['background-image'];
							$bgNormalStart = substr( $bgNormalCSS, strpos( $bgNormalCSS, '#'), 7);
							$bgNormalEnd = substr( $bgNormalCSS, strrpos( $bgNormalCSS, '#'), 7);
							$plain_selector = $selector;
						}
					}
					$returnRules[][$plain_selector][$state] = $rules;
				}
			}

		}


		$classes .= ' '.$bgNormalStart.' '.$bgNormalEnd.' '.$bgHoverStart.' '.$bgHoverEnd.' '.$bgActiveStart.' '.$bgActiveEnd;

		$button =	'<span class="superbutton '.$classes.'">
						<a href="'.$link.'" title="'.$title.'" class="'.$imageclass.'" style="'.$link_style.'" target="'.$target.'" rel="'.$rel.'">
							<span>'.$content.'</span>
						</a>
					</span>';

		return $button;
	}

	add_shortcode('superbutton', 'create_superbutton');

// REGISTER TINYMCE PLUGIN AND BUTTONS

	function superbuttons_addbuttons() {
	   // Don't bother doing this stuff if the current user lacks permissions
	   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		 return;
	 
	   // Add only in Rich Editor mode
	   if ( get_user_option('rich_editing') == 'true') {
		 add_filter("mce_external_plugins", "add_superbuttons_tinymce_plugin");
		 add_filter('mce_buttons', 'register_superbuttons_button');
	   }
	}
	 
	function register_superbuttons_button($buttons) {
	   array_push($buttons, "separator", "superbuttons");
	   return $buttons;
	}
	 
	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function add_superbuttons_tinymce_plugin($plugin_array) {
	   $plugin_array['superbuttons'] = SUPERBUTTON_URL . '/tinymce/editor_plugin.js';
	   return $plugin_array;
	}
	 
	// init process for button control
	add_action('init', 'superbuttons_addbuttons');

// ENQUEUE STYLES & SCRIPTS

	wp_enqueue_style( 'superbuttons' , SUPERBUTTON_URL . '/superbuttons.css');
	wp_enqueue_style( 'superbuttons' , SUPERBUTTON_URL . '/custom_styles.css');

	wp_enqueue_script( 'textshadow', SUPERBUTTON_URL . '/lib/jquery.dropShadow.js', array( 'jquery' ), false, true );
	add_action('wp_footer', 'superbuttons_ie_shadows', 12);

	function superbuttons_ie_shadows() {
		echo '<!--[if lte IE 8]><script type="text/javascript" src="'.SUPERBUTTON_URL.'/superbuttons.js"></script><![endif]-->';
	}

?>