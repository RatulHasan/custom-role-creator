<?php
/***
 * Role settings file
 *
 * @since 1.1.0
 *
 * @author Ratul Hasan <tanjilhasanratul@gmail.com>
 *
 * @package CRC
 */

// To prevent direct access, if not define WordPress ABSOLUTE PATH then exit.
if ( ! defined( 'ABSPATH' ) ) {
    exit();
}
if ( isset( $_GET['saved'] ) ) {
    if ( 1 == $_GET[ 'saved' ] ) {
        echo '<div id="crc_settings_message" class="alert-success">
    <p><strong>' . esc_html__( 'Success! Reset User Roles and Capabilities To Default.', 'custom-role-creator' ) . '</strong></p>
  </div>';
    }
}
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Custom Role Creator', 'custom-role-creator' ); ?></h1>
<div id="ure_tabs-5" aria-labelledby="ui-id-4" role="tabpanel" class="ui-tabs-panel ui-corner-bottom ui-widget-content" aria-hidden="false" style="">
    <div class="crc-bordered-box-red mt-20" style="~">
        <form method="post" action="">
            <h3 class="crc-color-red">
            <?php
            echo wp_kses(
                $title_text,
                array()
            );
            ?>
            </h3>
            <div class="mt-20">
            <?php
            echo wp_kses(
                $text,
                array(
					'span' => array( 'class' => 'crc-color-red' ),
					'br'   => array(),
                )
            );
            ?>
            </div>
            <button name="crc_reset_to_default_submit" id="crc_reset_to_default_submit" type="submit" class="btn btn-danger btn-flat"><i class="fas fa-recycle"></i> <?php esc_html_e( 'Reset to default', 'custom-role-creator' ); ?></button>
            <?php
                wp_nonce_field( 'crc_reset_roles_to_default', 'crc_reset_roles_to_default_fields' );
            ?>
        </form>
    </div>
</div>
</div>
<div class="modal" id="reset_warning_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title crc-color-red">
                    <?php
                    echo wp_kses(
                        $title_text,
                        array()
                    );
                    ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="crc-bordered-box-red mt-20">
                    <?php
                    echo wp_kses(
                        $text,
                        array(
                            'span' => array( 'class' => 'crc-color-red' ),
                            'br'   => array(),
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="crc_cancel" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" id="crc_proceed" class="btn btn-danger btn-sm">Proceed</button>
            </div>
        </div>
    </div>
</div>
