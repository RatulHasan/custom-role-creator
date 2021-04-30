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

        if ( 'users_page_custom-role-creator' === $screen ) {
            wp_enqueue_style( 'bootstrap' );
            wp_enqueue_style( 'AdminLTE' );
            wp_enqueue_style( 'crc_custom' );
            wp_enqueue_style( 'my-font-awesome-css' );
            wp_enqueue_script( 'bootstrap-scripts' );
            wp_enqueue_script( 'custom-js' );

            if ( isset( $_GET['role'] ) && 'assign' === $_GET['action'] ) {
                wp_dequeue_style( 'bootstrap' );
                wp_dequeue_style( 'AdminLTE' );
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
            'AdminLTE'            => array(
                'src'  => CRC_ASSETS . '/css/AdminLTE.min.css',
                'deps' => array( 'bootstrap' ),
                'ver'  => filemtime( CRC_BASE_PATH . '/assets/css/AdminLTE.min.css' ),
            ),
            'crc_custom'          => array(
                'src'  => CRC_ASSETS . '/css/custom.css',
                'deps' => array(),
                'ver'  => filemtime( CRC_BASE_PATH . '/assets/css/custom.css' ),
            ),
            'bootstrap'           => array(
                'src'  => CRC_ASSETS . '/css/bootstrap/dist/css/bootstrap.min.css',
                'deps' => array(),
                'ver'  => filemtime( CRC_BASE_PATH . '/assets/css/bootstrap/dist/css/bootstrap.min.css' ),
            ),
            'my-font-awesome-css' => array(
                'src'  => MY_GITHUB_ASSETS . '/fontawesome-free-5.15.3/css/all.min.css',
                'deps' => array(),
                'ver'  => filemtime( MY_GITHUB_BASE_PATH . '/assets/fontawesome-free-5.15.3/css/all.min.css' ),
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
            'custom-js'         => array(
                'src'  => CRC_ASSETS . '/js/custom.js',
                'deps' => array( 'jquery' ),
                'ver'  => filemtime( CRC_BASE_PATH . '/assets/js/custom.js' ),
            ),
            'bootstrap-scripts' => array(
                'src'  => CRC_ASSETS . '/css/bootstrap/dist/js/bootstrap.min.js',
                'deps' => array( 'jquery' ),
                'ver'  => filemtime( CRC_BASE_PATH . '/assets/css/bootstrap/dist/js/bootstrap.min.js' ),
            ),
        );
    }

}
