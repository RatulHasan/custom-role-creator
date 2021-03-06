<?php
/***
 * Initial class file
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

use CRC\Admin\Menu;
use CRC\Admin\FormHandle;

/**
 * Class Init
 *
 * @package CRC
 */
class Init {

    /**
     * Getaway for all classes.
     *
     * @return void
     */
    public static function register() {
        if ( is_admin() ) {
            if ( current_user_can( 'manage_options' ) ) {
                new FormHandle();
                new Assets();
                new Menu();
            }
        }
    }
}
