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

        if ( isset( $_GET['action'] ) && 'assign' === $_GET['action'] && 'user' === $_GET['object'] ) {
            $this->cb_crc_user_role_update();
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
        if ( isset( $_POST['crc_reset_to_default_submit'] ) ) {
            $this->crc_reset_to_default();
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
        } else {
            $return = add_role( $role, $display_name );
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

        if ( $return ) {
            $crc_copy_of = isset( $_POST['crc_copy_of'] ) ? sanitize_text_field( $_POST['crc_copy_of'] ) : '';
            if ( ! empty( $crc_copy_of ) ) {
                $wp_roles         = new \WP_Roles();
                $copy_role_object = get_role( $crc_copy_of );
                if ( ! empty( $copy_role_object ) ) {
                    foreach ( $copy_role_object->capabilities as $cap => $value ) {
                        $wp_roles->add_cap( $role, $cap );
                    }
                }
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
                wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=1' );
                exit();
            } else {
                wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=0' );
                exit();
            }
        }
        wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&saved=0' );
        exit();
    }

    /**
     * Callback for crc user role capabilities update.
     *
     * @return bool|void
     */
    public function cb_crc_user_role_update() {
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

        $crc_add_cap = array_map( 'sanitize_text_field', wp_unslash( $_POST['crc_add_cap'] ) );
        $nonce       = wp_create_nonce( 'crc_assign_user_cap_nonce' );

        $user_id = sanitize_text_field( wp_unslash( $_GET['user_id'] ) );
        $user    = new \WP_User( $user_id );
        if ( ! empty( $crc_add_cap ) ) {
            if ( ! empty( $user->data->user_login ) ) {
                if ( is_array( $crc_add_cap ) ) {
                    foreach ( $user->roles as $role ) {
                        $crc_add_cap[] = $role;
                    }
                    $user->remove_all_caps();
                    foreach ( $crc_add_cap as $value ) {
                        $user->add_cap( $value );
                    }

                    wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&object=user&action=assign&user_id=' . $user->ID . '&_wpnonce=' . $nonce . '&saved=1' );
                    exit();
                } else {
                    wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&object=user&action=assign&user_id=' . $user->ID . '&_wpnonce=' . $nonce . '&saved=0' );
                    exit();
                }
            }

            wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&object=user&action=assign&user_id=' . $user->ID . '&_wpnonce=' . $nonce . '&saved=0' );
            exit();
        } else {
            $user->remove_all_caps();
            wp_safe_redirect( admin_url() . 'users.php?page=custom-role-creator&object=user&action=assign&user_id=' . $user->ID . '&_wpnonce=' . $nonce . '&saved=1' );
            exit();
        }
    }

    /**
     * Reset to default, Here we go!
     *
     * @return void
     */
    public function crc_reset_to_default() {
        if ( ! isset( $_POST['crc_reset_to_default_submit'] ) ) {
            return;
        }

        $nonce = isset( $_POST['crc_reset_roles_to_default_fields'] ) ? sanitize_text_field( wp_unslash( $_POST['crc_reset_roles_to_default_fields'] ) ) : '';
        if ( ! wp_verify_nonce( $nonce, 'crc_reset_roles_to_default' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Are you cheating?', 'custom-role-creator' ) );
        }

        global $wp_roles;
        if ( ! isset( $wp_roles ) ) {
            $wp_roles = new \WP_Roles();
        }

        if ( ! function_exists( 'populate_roles' ) ) {
            require_once ABSPATH . 'wp-admin/includes/schema.php';
        }

        foreach ( $wp_roles->roles as $role_name => $role_info ) {
            $role = get_role( $role_name );
            foreach ( $role_info['capabilities'] as $capability => $capability_value ) {
                $role->remove_cap( $capability );
            }
            remove_role( $role_name );
        }

		// Reset role to default.
        populate_roles();

        $all_users = get_users();
        if ( ! empty( $all_users ) ) {
            foreach ( $all_users as $user ) {
                $user_wp = new \WP_User( $user->data->ID );
                foreach ( $user_wp->roles as $role_info ) {
                    $user_wp->remove_all_caps();
                    $user_wp->add_role( $role_info );
                }
            }
        }

        wp_safe_redirect( admin_url() . 'options-general.php?page=crc-settings&saved=1' );
        exit();
    }
}
