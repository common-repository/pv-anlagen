<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


function sevz_delete_plugin() {
	global $wpdb;

	//delete_option( 'wpcf7' );

	$postGutschrift = get_page_by_title( 'Gutschrift', 'OBJECT', 'page' );
	wp_delete_post( $postGutschrift->ID, true );

	$postRechnung = get_page_by_title( 'Rechnung', 'OBJECT', 'page' );
	wp_delete_post( $postRechnung->ID, true );
	/*get_posts(
		array(
			'numberposts' => -1,
			'post_type' => 'page',
			'post_status' => 'any',
		)
	);

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
	}*/

	$wpdb->query( sprintf(
		"DROP TABLE IF EXISTS %s",
		$wpdb->prefix . 'consumer_data'
	) );
	$wpdb->query( sprintf(
		"DROP TABLE IF EXISTS %s",
		$wpdb->prefix . 'producer_data'
	) );
	$wpdb->query( sprintf(
		"DROP TABLE IF EXISTS %s",
		$wpdb->prefix . 'prosumer_data'
	) );

	wp_clear_scheduled_hook('sevz_add_user_roles');
	wp_clear_scheduled_hook('sevz_add_user_tables');
}

if ( ! defined( 'SOLAR_ENERGY_VISUALIZER_VERSION' ) ) {
	sevz_delete_plugin();
}
