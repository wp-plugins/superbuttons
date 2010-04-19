<?php
require_once('../../../wp-blog-header.php');
global $wpdb;

	$cssfile = WP_PLUGIN_DIR . '/superbuttons/superbuttons.css';
	$cssfile = file_get_contents($cssfile);
	$cssp = new cssparser;
	$cssp -> ParseStr($cssfile);
	foreach ($cssp->css as $selector => $rules) {
		if ( strstr( $selector, $_GET['selector'] ) ) {
			
			if ( strstr( $selector, ':hover' ) ) {
				$state = 'hover';
				$plain_selector = substr( $selector, 0, strrpos( $selector, ':') );
			} elseif ( strstr( $selector, ':active' ) ) { 
				$state = 'active';
				$plain_selector = substr( $selector, 0, strrpos( $selector, ':') );
			} else {
				$state = 'normal';
				$plain_selector = $selector;
			}
			$returnRules[$plain_selector][$state] = $rules;
		}
	}
	echo json_encode($returnRules);
?>