;(function ($) {
    $( document ).on(
        "click",
        '.crc_role_edit_button',
        function (e) {
			var role_display_name = $( this ).data( 'edit_crc_role_display_name' );
			var role_name         = $( this ).data( 'edit_crc_role_name' );
			$( "#edit_crc_role_name" ).val( role_display_name );
			$( "#edit_crc_pre_role_name" ).val( role_name );
			$( "#edit_crc_copy_of" ).val( role_name );
		}
    );

    $( "#crc_all_roles_dropdown" ).change(
        function (e) {
			var role_name = $( this ).val();
            $( ".all" ).hide();
            $( "." + role_name ).show();
		}
    );

    /**
     * Success message
     */
    $( "#crc_settings_message" ).delay( 1500 ).addClass( "in" ).fadeOut( 1500 );
})( jQuery );
