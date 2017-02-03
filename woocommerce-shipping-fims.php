<?php
/**
 * Plugin Name: FIMS Shipping for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/fims-shipping-for-woocommerce
 * Description: Add Fedex International Mail shipping to WooCommerce.
 * Author: Plexus, LLC
 * Text Domain: woocommerce-shipping-fims
 * Domain Path: /lang
 * Version: 1.0.0
 * Author URI: http://plexusllc.com
 * Copyright: © 2017 Plexus, LLC (email : plugins@plexusllc.com)
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( !defined( 'ABSPATH' ) ) exit;

class WC_Shipping_FIMS_Init {
  public $version = '1.0.0';

  /** @var object Class Instance */
  private static $instance;

  /**
   * Get the class instance
   */
  public static function get_instance() {
    return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
  }

  /**
   * Initialize the plugin's public actions
   */
  public function __construct() {
    if ( class_exists( 'WC_Shipping_Method' ) ) {
      add_action( 'init', array( $this, 'load_textdomain' ) );
      add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );
      add_action( 'woocommerce_shipping_init', array( $this, 'includes' ) );
      add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );
      add_action( 'admin_notices', array( $this, 'environment_check' ) );      
    } else {
      add_action( 'admin_notices', array( $this, 'wc_deactivated' ) );
    }
  }

  /**
   * environment_check function.
   */
  public function environment_check() {
    if ( version_compare( WC_VERSION, '2.6.0', '<' ) ) {
      return;
    }
  }

  // include the main plugin file
  public function includes() {
    include_once( dirname( __FILE__ ) . '/includes/class-wc-shipping-fims.php' );
  }

  /**
   * Add FIMS shipping method to WC
   *
   * @access public
   * @param mixed $methods
   * @return void
   */
  public function add_method( $methods ) {
    $methods['fims'] = 'WC_Shipping_FIMS';
    return $methods;
  }

  /**
   * Localisation
   */
  public function load_textdomain() {
    load_plugin_textdomain( 'woocommerce-shipping-fims', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
  }

  /**
   * Plugin page links
   */
  public function plugin_links( $links ) {
    $plugin_links = array(
      '<a href="#">' . __( 'Support', 'woocommerce-shipping-fims' ) . '</a>',
      '<a href="#">' . __( 'Docs', 'woocommerce-shipping-fims' ) . '</a>',
    );

    return array_merge( $plugin_links, $links );
  }

  /**
   * WooCommerce not installed notice
   */
  public function wc_deactivated() {
    echo '<div class="error"><p>' . sprintf( __( 'WooCommerce FIMS Shipping requires %s to be installed and active.', 'woocommerce-shipping-fims' ), '<a href="https://woocommerce.com" target="_blank">WooCommerce</a>' ) . '</p></div>';
  }
}

add_action( 'plugins_loaded' , array( 'WC_Shipping_FIMS_Init', 'get_instance' ), 0 );