<?php
/***
 * Menu class file
 *
 * @since 1.0.0
 *
 * @author Ratul Hasan <tanjilhasanratul@gmail.com>
 *
 * @package CRC\Admin
 */

namespace CRC\Admin;

// To prevent direct access, if not define WordPress ABSOLUTE PATH then exit.
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * Class FormHandle
 *
 * @package CRC\Admin
 */
class FormHandle {

    /**
     * FormHandle constructor.
     */
    public function __construct() {
        if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] ) {
            $this->cb_crc_role_delete();
        }

        if ( ! isset( $_POST ) ) {
            return;
        }

        if ( isset( $_POST['crc_new_role_submit'] ) ) {
            $this->cb_crc_role_submit();
        }
        if ( isset( $_POST['crc_role_update'] ) ) {
            $this->cb_crc_role_update();
        }
        if ( isset( $_POST['crc_cap_submit'] ) ) {
            $this->cb_crc_cap_submit();
        }
    }

    /**
     * Callback for crc role delete.
     *
     * @return void
     */
    public function cb_crc_role_delete() {
        if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'crc_delete_role_nonce' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }
        $role        = sanitize_text_field( $_GET['role'] );
        $role_object = get_role( $role );
        if ( ! empty( $role_object ) ) {
            remove_role( $role );
            wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&deleted=3' );
            exit();
        }
        wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&deleted=4' );
        exit();
    }

    /**
     * Callback for crc role submit.
     *
     * @return void
     */
    public function cb_crc_role_submit() {
        if ( ! isset( $_POST['crc_new_role_submit'] ) ) {
            return;
        }

        $nonce = isset( $_POST['crc_new_role_fields'] ) ? sanitize_text_field( wp_unslash( $_POST['crc_new_role_fields'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'crc_new_role' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        $crc_copy_of = isset( $_POST['crc_copy_of'] ) ? sanitize_text_field( $_POST['crc_copy_of'] ) : '';
        if ( ! empty( $crc_copy_of ) ) {
            $role_object = get_role( $crc_copy_of );
        }

        $display_name = isset( $_POST['crc_role_name'] ) ? ucfirst( sanitize_text_field( $_POST['crc_role_name'] ) ) : '';
        if ( empty( $display_name ) ) {
            wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=2' );
            exit();
        }

        $role = strtolower( str_replace( ' ', '_', $display_name ) );
        if ( ! empty( $role_object ) ) {
            $return = add_role( $role, $display_name, $role_object->capabilities );
            flush_rewrite_rules( true );
        } else {
            $return = add_role( $role, $display_name );
            flush_rewrite_rules( true );
        }
        if ( ! empty( $return ) ) {
            wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
            exit();
        }
        wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=0' );
        exit();
    }

    /**
     * Callback for crc role update.
     *
     * @return void
     */
    public function cb_crc_role_update() {
        if ( ! isset( $_POST['crc_role_update'] ) ) {
            return;
        }

        $nonce = isset( $_POST['crc_update_role_fields'] ) ? sanitize_text_field( wp_unslash( $_POST['crc_update_role_fields'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'crc_update_role' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        $crc_pre_role_name = sanitize_text_field( $_POST['crc_pre_role_name'] );
        $display_name      = ucfirst( sanitize_text_field( $_POST['crc_role_name'] ) );
        $role              = strtolower( str_replace( ' ', '_', $display_name ) );

        $val = get_option( 'wp_user_roles' );

	    $val[ $crc_pre_role_name ]['name'] = $display_name;
        $val[ $role ]                      = $val[ $crc_pre_role_name ];

        unset( $val[ $crc_pre_role_name ] );
        $return = update_option( 'wp_user_roles', $val );
        flush_rewrite_rules( true );

        if ( $return ) {
            $crc_copy_of = isset( $_POST['crc_copy_of'] ) ? sanitize_text_field( $_POST['crc_copy_of'] ) : '';
            if ( ! empty( $crc_copy_of ) ) {
                $copy_role_object = get_role( $crc_copy_of );
                $new_role_object  = get_role( $role );
                foreach ( $new_role_object->capabilities as $capabilities => $value ) {
                    $new_role_object->remove_cap( $capabilities );
                }
                if ( ! empty( $copy_role_object ) ) {
                    foreach ( $copy_role_object->capabilities as $cap => $value ) {
                        $new_role_object->add_cap( $cap );
                    }
                }
                flush_rewrite_rules( true );
            }
            wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
            exit();
        }
        wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=0' );
        exit();
    }

    /**
     * Callback for crc role cap submit.
     *
     * @return bool|void
     */
    public function cb_crc_cap_submit() {
        if ( ! isset( $_POST['crc_cap_submit'] ) ) {
            return;
        }

        $nonce = isset( $_POST['crc_assign_cap_form_field'] ) ? sanitize_text_field( wp_unslash( $_POST['crc_assign_cap_form_field'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'crc_assign_cap_form' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        $crc_current_role_name = strtolower( str_replace( ' ', '_', sanitize_text_field( wp_unslash( $_POST['crc_current_role_name'] ) ) ) );
        $crc_add_cap           = array_map( 'sanitize_text_field', wp_unslash( $_POST['crc_add_cap'] ) );

        $role_object = get_role( $crc_current_role_name );
        foreach ( $role_object->capabilities as $capabilities => $value ) {
            $role_object->remove_cap( $capabilities );
        }

        if ( ! empty( $crc_add_cap ) ) {
            if ( is_array( $crc_add_cap ) ) {
                foreach ( $crc_add_cap as $value ) {
                    $role_object->add_cap( $value );
                }
                flush_rewrite_rules( true );
                wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
                exit();
            } else {
                flush_rewrite_rules( true );
                $role_object->add_cap( $crc_add_cap );
                wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
                exit();
            }
        }
        wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
        exit();
    }

    /**
     * Reset all roles.
     *
     * @return void
     */
	public function cb_crc_reset_role() {
        if ( ! function_exists( 'populate_roles' ) ) {
            require_once ABSPATH . 'wp-admin/includes/schema.php';
        }

        populate_roles();
    }
}
