<?php
/**
* Plugin Name: YITH WooCommerce Bulk Product Editing
* Plugin URI: http://yithemes.com/
* Description: YITH WooCommerce Bulk Product Editing allows you to edit prices of more products in one time.
* Version: 1.0.1
* Author: YIThemes
* Author URI: http://yithemes.com/
* Text Domain: yith-wcbep
* Domain Path: /languages/
*
* @author yithemes
* @package YITH WooCommerce Bulk Edit Products
* @version 1.0.1
*/
/*  Copyright 2015  Your Inspiration Themes  (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* == COMMENT == */ 

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_wcbep_install_woocommerce_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'YITH WooCommerce Bulk Product Editing is enabled but not effective. It requires WooCommerce in order to work.', 'yit' ); ?></p>
    </div>
    <?php
}


function yith_wcbep_install_free_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Bulk Product Editing while you are using the premium one.', 'yit' ); ?></p>
    </div>
    <?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( ! defined( 'YITH_WCBEP_VERSION' ) ){
    define( 'YITH_WCBEP_VERSION', '1.0.1' );
}

if ( ! defined( 'YITH_WCBEP_FREE_INIT' ) ) {
    define( 'YITH_WCBEP_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WCBEP' ) ) {
    define( 'YITH_WCBEP', true );
}

if ( ! defined( 'YITH_WCBEP_FILE' ) ) {
    define( 'YITH_WCBEP_FILE', __FILE__ );
}

if ( ! defined( 'YITH_WCBEP_URL' ) ) {
    define( 'YITH_WCBEP_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_WCBEP_DIR' ) ) {
    define( 'YITH_WCBEP_DIR', plugin_dir_path( __FILE__ )  );
}

if ( ! defined( 'YITH_WCBEP_TEMPLATE_PATH' ) ) {
    define( 'YITH_WCBEP_TEMPLATE_PATH', YITH_WCBEP_DIR . 'templates' );
}

if ( ! defined( 'YITH_WCBEP_ASSETS_URL' ) ) {
    define( 'YITH_WCBEP_ASSETS_URL', YITH_WCBEP_URL . 'assets' );
}

if ( ! defined( 'YITH_WCBEP_ASSETS_PATH' ) ) {
    define( 'YITH_WCBEP_ASSETS_PATH', YITH_WCBEP_DIR . 'assets' );
}


function yith_wcbep_init() {

    load_plugin_textdomain( 'yith-wcbep', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

    // Load required classes and functions
    require_once('includes/class.yith-wcbep-list-table.php');
    require_once('functions.yith-wcbep.php');
    require_once('class.yith-wcbep-admin.php');
    require_once('class.yith-wcbep.php');

    // Let's start the game!
    YITH_WCBEP();
}
add_action( 'yith_wcbep_init', 'yith_wcbep_init' );


function yith_wcbep_install() {

    if ( ! function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'yith_wcbep_install_woocommerce_admin_notice' );
    }
    elseif ( defined( 'YITH_WCBEP_PREMIUM' ) ) {
        add_action( 'admin_notices', 'yith_wcbep_install_free_admin_notice' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
    else {
        do_action( 'yith_wcbep_init' );
    }
}
add_action( 'plugins_loaded', 'yith_wcbep_install', 11 );