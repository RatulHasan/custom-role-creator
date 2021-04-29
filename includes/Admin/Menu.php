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
            array( $this, 'cb_add_custom_role_creator_page' )
        );
    }

    /**
     * Callback for add featured post
     *
     * @return void
     */
    public function cb_add_custom_role_creator_page() {
        global $wp_roles;
        $all_roles = new \WP_Roles();
        include_once CRC_INCLUDE_PATH . '/templates/all_roles.php';
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
