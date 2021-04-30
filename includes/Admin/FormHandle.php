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

        $display_name = ucfirst( sanitize_text_field( $_POST['crc_role_name'] ) );
        $role         = strtolower( str_replace( ' ', '_', $display_name ) );
        $return       = add_role( $role, $display_name );
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

        if ( $return ) {
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
        $crc_add_cap           = $_POST['crc_add_cap'];

        $role_object = get_role( $crc_current_role_name );
        foreach ( $role_object->capabilities as $capabilities => $value ) {
            $role_object->remove_cap( $capabilities );
        }

        if ( ! empty( $crc_add_cap ) ) {
            if ( is_array( $crc_add_cap ) ) {
                foreach ( $crc_add_cap as $value ) {
                    $role_object->add_cap( $value );
                }
                wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
                exit();
            } else {
                $role_object->add_cap( $crc_add_cap );
                wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
                exit();
            }
        }
        wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
        exit();
    }

}