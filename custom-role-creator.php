<?php
/**
 * Plugin Name:         Custom Role Creator
 * Plugin URI:          https://github.com/RatulHasan/custom-role-creator
 * Description:         Custom Role Creator plugin allows you to add or change user roles and capabilities easily.
 * Version:             1.0.0
 * Requires PHP:        5.6
 * Requires at least:   5.2
 * Author:              Ratul Hasan
 * Author URI:          https://ratuljh.wordpress.com/
 * License:             GPL-2.0-or-later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         custom-role-creator
 * Domain Path:         /languages
 *
 * @package WordPress
 */

// To prevent direct access, if not define WordPress ABSOLUTE PATH then exit.
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * Class CRC
 */
class CRC {

    // Plugin version.
    const CRC_VERSION = '1.0.0';

    /**
     * CRC constructor.
     */
    public function __construct() {
        require_once __DIR__ . '/vendor/autoload.php';
        $this->localization_setup();
        $this->define_constant();

        add_action( 'activate_plugin', array( $this, 'cb_activate_plugin' ) );
        add_action( 'plugins_loaded', array( $this, 'initiate_plugin' ) );
    }

    /**
     * Define main plugin constant here for future use.
     *
     * @return void
     */
    public function define_constant() {
        define( 'CRC_VERSION', self::CRC_VERSION );
        define( 'CRC_BASE_NAME', plugin_basename( __FILE__ ) );
        define( 'CRC_BASE_PATH', __DIR__ );
        define( 'CRC_INCLUDE_PATH', __DIR__ . '/includes' );
        define( 'CRC_URL', plugins_url( '', __FILE__ ) );
        define( 'CRC_ASSETS', CRC_URL . '/assets' );
    }

    /**
     * Activating the plugin
     *
     * @return void
     */
    public function cb_activate_plugin() {
        if ( ! get_option( '_custom_role_creator_installed' ) ) {
            update_option( '_custom_role_creator_installed', time() );
        }
        update_option( '_custom_role_creator_version', CRC_VERSION );
    }
    /**
     * Initialize plugin for localization
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'custom-role-creator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Initiate the plugin
     *
     * @return void
     */
    public function initiate_plugin() {
//        global $wp_roles;
//        $all_roles = new \WP_Roles();
//        echo '<pre>';
//        print_r( $all_roles );
//        exit();

    }
    /**
     * Init method for CRC
     *
     * @return \CRC|false
     */
    public static function init() {
        $instance = false;
        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }
}

/**
 * Initialize the Github
 *
 * @return void
 */
function custom_role_creator() {
    CRC::init();
}

/**
 * Hit start
 */
custom_role_creator();
