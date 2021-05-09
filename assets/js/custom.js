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
            $( "#crc_select_all_caps" ).data( 'crc_cap_value', role_name );
		}
    );

    $( "#crc_select_all_caps" ).click(
        function () {
            var crc_cap_value = $( "#crc_select_all_caps" ).data( 'crc_cap_value' );
            if ( this.checked ) {
                $( '.' + crc_cap_value ).prop( 'checked', true );
            } else {
                $( '.' + crc_cap_value ).prop( 'checked', false );
            }
        }
    );
    $( "#crc_reset_to_default_submit" ).click(
        function (e) {
            var crc_form = $( this ).closest( 'form' );
            e.preventDefault();
            $( '#reset_warning_modal' ).modal(
                'show',
                {
					backdrop: 'static',
					keyboard: false
                }
            ).on(
                'click',
                '#crc_proceed',
                function(e) {
                    var reset_warning_modal = $( '#reset_warning_modal' );
                    var be_confirm          = confirm( 'Data will be lost forever! Are you sure?' );
                    if (be_confirm) {
                        var tempElement = $( "<input type='hidden'/>" );
                        tempElement
                            .attr( "name", 'crc_reset_to_default_submit' )
                            .appendTo( crc_form );
                        crc_form.submit();
                        reset_warning_modal.modal( 'hide' );
                        tempElement.remove();
                        return true;
                    }
                    e.preventDefault();
                    reset_warning_modal.modal( 'hide' );
                }
            );
            $( "#cancel" ).on(
                'click',
                function(e){
					e.preventDefault();
					$( '#reset_warning_modal' ).modal( 'hide' );
				}
            );
		}
    );
    /**
     * Success message
     */
    $( "#crc_settings_message" ).delay( 1500 ).addClass( "in" ).fadeOut( 2000 );
})( jQuery );
