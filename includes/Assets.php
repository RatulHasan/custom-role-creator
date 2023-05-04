<?php
/***
 * Project Custom Role Creator
 *
 * @since 1.0.0
 *
 * @author Ratul Hasan <tanjilhasanratul@gmail.com>
 *
 * @package CRC
 */

namespace CRC;

// To prevent direct access, if not define WordPress ABSOLUTE PATH then exit.
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}
/**
 * Class Assets
 *
 * @package CRC
 */
class Assets {

    /**
     * Assets constructor.
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
    }

    /**
     * Load Frontend assets
     *
     * @param  string $screen  current screen.
     *
     * @return void
     */
    public function register_admin_scripts( $screen ) {
        $styles = $this->get_admin_styles();
        foreach ( $styles as $handle => $style ) {
            wp_register_style( $handle, $style['src'], $style['deps'], $style['ver'] );
        }

        $scripts = $this->get_admin_scripts();
        foreach ( $scripts as $handle => $script ) {
            wp_register_script( $handle, $script['src'], $script['deps'], $script['ver'], true );
        }

        if ( 'users_page_custom-role-creator' === $screen || 'settings_page_crc-settings' === $screen ) {
            wp_enqueue_style( 'crc_bootstrap' );
            wp_enqueue_style( 'crc_custom_css' );
            wp_enqueue_style( 'crc_my-font-awesome-css' );
            wp_enqueue_script( 'crc_bootstrap-scripts' );
            wp_enqueue_script( 'crc_custom_js' );

            if ( isset( $_GET['role'] ) && 'assign' === $_GET['action'] ) {
                wp_dequeue_style( 'crc_bootstrap' );
            }
        }
    }

    /**
     * Register Styles
     *
     * @return array[]
     */
    public function get_admin_styles() {
        return array(
            'crc_custom_css'          => array(
                'src'  => CRC_ASSETS . '/css/custom.min.css',
                'deps' => array(),
                'ver'  => CRC_VERSION,
            ),
            'crc_bootstrap'           => array(
                'src'  => 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css',
                'deps' => array(),
                'ver'  => CRC_VERSION,
            ),
            'crc_my-font-awesome-css' => array(
                'src'  => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css',
                'deps' => array(),
                'ver'  => CRC_VERSION,
            ),
        );
    }


    /**
     * Register Styles
     *
     * @return array[]
     */
    public function get_admin_scripts() {
        return array(
            'crc_custom_js'         => array(
                'src'  => CRC_ASSETS . '/js/custom.min.js',
                'deps' => array( 'jquery' ),
                'ver'  => CRC_VERSION,
            ),
            'crc_bootstrap-scripts' => array(
                'src'  => 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
                'deps' => array( 'jquery' ),
                'ver'  => CRC_VERSION,
            ),
        );
    }

}
