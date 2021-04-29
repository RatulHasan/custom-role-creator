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

}
