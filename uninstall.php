<?php
/***
 * Trigger this file on Plugin uninstall
 *
 * @since 1.0.0
 *
 * @author Ratul Hasan <tanjilhasanratul@gmail.com>
 *
 * @package MyGitHub
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die();
}

// Delete All Core Data.
delete_option( '_custom_role_creator_installed' );
delete_option( '_custom_role_creator_version' );
