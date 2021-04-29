;(function ($) {
    $( document ).on(
        "click",
        '.crc_role_edit_button',
        function (e) {
			var role_name = $( this ).data( 'edit_crc_role_name' );
			$( "#edit_crc_role_name" ).val( role_name );
		}
    );

    /**
     * Success message
     */
    $( "#crc_settings_message" ).delay( 450 ).addClass( "in" ).fadeOut( 6000 );
})( jQuery );
