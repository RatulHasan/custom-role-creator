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
        add_action( 'user_row_actions', array( $this, 'cb_user_row_actions' ), 10, 2 );
        add_filter( "plugin_action_links_{$plugin_file}", array( $this, 'add_settings_links' ) );
    }

    /**
     * Add edit actions for every users at the users list.
     *
     * @param  array  $actions  all actions.
     * @param  object $user  user object.
     *
     * @return mixed
     */
    public function cb_user_row_actions( $actions, $user ) {
        global $pagenow;
        if ( 'users.php' !== $pagenow ) {
            return $actions;
        }

        $nonce     = wp_create_nonce( 'crc_assign_user_cap_nonce' );
        $link      = '<a href="users.php?page=custom-role-creator&object=user&action=assign&user_id=' . $user->ID . '&_wpnonce=' . $nonce . '">' . esc_html__( 'Add Capabilities', 'custom-role-creator' ) . '</a>';
        $actions[] = wp_kses(
            $link,
            array(
				'a' => array(
					'href' => array(),
				),
            )
        );

        return $actions;
    }

    /**
     * Callback for add options page
     *
     * @return void
     */
    public function cb_add_settings_page() {
        add_options_page(
            __( 'Custom Role Creator', 'custom-role-creator' ),
            __( 'Custom Role Creator', 'custom-role-creator' ),
            'manage_options',
            'crc-settings',
            array( $this, 'cb_add_custom_role_creator_settings_page' ),
            7
        );

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
        if ( isset( $_GET['action'] ) && 'assign' === $_GET['action'] && 'role' === $_GET['object'] ) {
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

        } elseif ( isset( $_GET['action'] ) && 'assign' === $_GET['action'] && 'user' === $_GET['object'] ) {
            if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'crc_assign_user_cap_nonce' ) ) {
                wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
            }

            $user_id = sanitize_text_field( wp_unslash( $_GET['user_id'] ) );
            $user    = new \WP_User( $user_id );
            if ( ! empty( $user->data->user_login ) ) {
                $all_caps     = array();
                $checked_caps = array();
                $classes      = array();
                foreach ( $all_roles_obj->roles as $role_name_key => $role ) {
                    foreach ( $role['capabilities'] as $cap_key => $value ) {
                        if ( ! in_array( $cap_key, $all_caps, true ) ) {
                            $all_caps[] = $cap_key;
                            if ( array_key_exists( $cap_key, $user->allcaps ) ) {
                                $checked_caps[] = $cap_key;
                            }
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
                $role_name         = ! empty( $user ) ? $user->data->user_login : '';
                $current_role_name = '';
                include_once CRC_INCLUDE_PATH . '/templates/assign_roles.php';
            } else {
                include_once CRC_INCLUDE_PATH . '/templates/all_roles.php';
            }
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

    /**
     * Callback for Custom Role Creator settings menu
     *
     * @return void
     */
    public function cb_add_custom_role_creator_settings_page() {
        $title_text = __( 'Reset User Roles And Capabilities To Default!', 'custom-role-creator' );
        $text       = "<span class='crc-color-red'>" . __( 'WARNING!', 'custom-role-creator' ) . '</span> ' . __( 'Reset to default will set default user roles and capabilities from WordPress core.', 'custom-role-creator' ) . '
            <br>' . __( 'If any plugins (such as WooCommerce, Dokan and/or many others) have changed user roles and capabilities during installation, those changes will be', 'custom-role-creator' ) . " <span class='crc-color-red'>" . __( 'LOST FOREVER!', 'custom-role-creator' ) . '</span><br /><br />';

        include_once CRC_INCLUDE_PATH . '/templates/role_settings.php';
    }

}
