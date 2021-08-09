<?php
/**
 * Plugin Name:     CEN Custom Blocks
 * Plugin URI:      https://www.mplmnt.io
 * Description:     These are custom blocks created for CEN.
 * Author:          Kris Khoury
 * Author URI:      https://www.mplmnt.io
 * Text Domain:     cen-custom-blocks
 * Domain Path:     /cen-custom-blocks
 * Version:         0.1.0
 *
 * @package         CEN_Custom_Blocks
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$plugin_path =  plugin_dir_path( __FILE__ );
$plugin_url =  plugin_dir_url( __FILE__ );

if( class_exists('ACF') ) {
  $folder = $plugin_path . '/blocks/';
  $blocks = array(
    'calendar',
    'call-to-action',
    'signed-document',
    'team-cards',
    'three-column-news',
    'us-map-list',
  );
  foreach ($blocks as $key => $value) {
    require $folder.$value.'/index.php';
  }
}

function plugin_blocks_scripts() {
  global $plugin_url;
  wp_enqueue_style( 'style',  $plugin_url . "blocks.css");
}

add_action( 'wp_enqueue_scripts', 'plugin_blocks_scripts' );
add_action( 'admin_enqueue_scripts', 'plugin_blocks_scripts' );
