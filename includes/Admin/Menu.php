<?php
/***
 * Menu class file
 *
 * @since 1.0.0
 *
 * @author Ratul Hasan <tanjilhasanratul@gmail.com>
 *
 * @package CRC
 */

namespace CRC\Admin;

// To prevent direct access, if not define WordPress ABSOLUTE PATH then exit.
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * Class Menu
 *
 * @package My\GitHub
 */
class Menu {

    /**
     * Menu constructor.
     */
    public function __construct() {
        $plugin_file = CRC_BASE_NAME;
        add_action( 'admin_menu', array( $this, 'cb_add_settings_page' ) );
        add_filter( "plugin_action_links_{$plugin_file}", array( $this, 'add_settings_links' ) );
    }

    /**
     * Callback for add options page
     *
     * @return void
     */
    public function cb_add_settings_page() {
        add_submenu_page(
            'users.php',
            __( 'Custom Role Creator', 'custom-role-creator' ),
            __( 'Custom Role Creator', 'custom-role-creator' ),
            'manage_options',
            'custom-role-creator',
            array( $this, 'cb_add_custom_role_creator_page' ),
            3
        );
    }

    /**
     * Callback for add featured post
     *
     * @return void
     */
    public function cb_add_custom_role_creator_page() {
        $all_roles_obj  = new \WP_Roles();
        $all_role_names = $all_roles_obj->get_names();
        if ( isset( $_GET['action'] ) && 'assign' === $_GET['action'] ) {
            if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'crc_assign_role_nonce' ) ) {
                wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
            }
            $all_caps     = array();
            $checked_caps = array();
            $classes      = array();
            foreach ( $all_roles_obj->roles as $role_name_key => $role ) {
                if ( $role_name_key === $_GET['role'] ) {
                    $checked_caps = array_keys( $role['capabilities'] );
                }
                foreach ( $role['capabilities'] as $cap_key => $value ) {
                    if ( ! in_array( $cap_key, $all_caps, true ) ) {
                        $all_caps[] = $cap_key;
                    }
                    $pre_value = array();
                    if ( array_key_exists( $cap_key, $classes ) ) {
                        $pre_value[]         = $classes[ $cap_key ];
                        $pre_value[]         = $role_name_key;
                        $classes[ $cap_key ] = implode( ' ', $pre_value );
                    } else {
                        $classes[ $cap_key ] = $role_name_key;
                    }
                }
            }
            sort( $all_caps );
            $role_name         = isset( $_GET['role'] ) ? sanitize_text_field( $all_role_names[ $_GET['role'] ] ) : '';
            $current_role_name = isset( $_GET['role'] ) ? sanitize_text_field( $_GET['role'] ) : '';
            include_once CRC_INCLUDE_PATH . '/templates/assign_roles.php';
        } else {
            include_once CRC_INCLUDE_PATH . '/templates/all_roles.php';
        }
    }

    /**
     * Add settings links
     *
     * @param  array $links  all predefined links.
     *
     * @return mixed
     */
    public function add_settings_links( array $links ) {
        $settings_links = "<a href='users.php?page=custom-role-creator'>Settings</a>";
        $settings_links = wp_kses(
            $settings_links,
            array(
                'a' => array(
                    'href' => array(),
                ),
            )
        );
        array_push( $links, $settings_links );

        return $links;
    }

}
