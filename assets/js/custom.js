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
    $( "#crc_delete_custom_roles_to_default_submit" ).click(
        function (e) {
            var crc_custom_form            = $( this ).closest( 'form' );
            var reset_custom_warning_modal = $( '#reset_custom_warning_modal' );
            e.preventDefault();
            $( reset_custom_warning_modal ).modal(
                'show',
                {
					backdrop: 'static',
					keyboard: false
                }
            ).on(
                'click',
                '#crc_custom_proceed',
                function(e) {
                    var be_custom_confirm = confirm( 'Data will be lost forever! Are you sure?' );
                    if (be_custom_confirm) {
                        var tempElement = $( "<input type='hidden'/>" );
                        tempElement
                            .attr( "name", 'crc_delete_custom_roles_to_default_submit' )
                            .appendTo( crc_custom_form );
                        crc_custom_form.submit();
                        reset_custom_warning_modal.modal( 'hide' );
                        tempElement.remove();
                    } else {
                        reset_custom_warning_modal.modal( 'hide' );
                        e.preventDefault();
                    }
                }
            );
            $( "#crc_custom_cancel" ).on(
                'click',
                function(e){
                    $( '#reset_custom_warning_modal' ).modal( 'hide' );
                    e.preventDefault();
				}
            );
		}
    );
    $( "#crc_reset_to_default_submit" ).click(
        function (e) {
            var crc_form            = $( this ).closest( 'form' );
            var reset_warning_modal = $( '#reset_warning_modal' );
            e.preventDefault();
            $( reset_warning_modal ).modal(
                'show',
                {
					backdrop: 'static',
					keyboard: false
                }
            ).on(
                'click',
                '#crc_proceed',
                function(e) {
                    var be_confirm = confirm( 'Data will be lost forever! Are you sure?' );
                    if (be_confirm) {
                        var tempElement = $( "<input type='hidden'/>" );
                        tempElement
                            .attr( "name", 'crc_reset_to_default_submit' )
                            .appendTo( crc_form );
                        crc_form.submit();
                        reset_warning_modal.modal( 'hide' );
                        tempElement.remove();
                    } else {
                        reset_warning_modal.modal( 'hide' );
                        e.preventDefault();
                    }
                }
            );
            $( "#crc_cancel" ).on(
                'click',
                function(e){
                    $( '#reset_warning_modal' ).modal( 'hide' );
                    e.preventDefault();
				}
            );
		}
    );
    /**
     * Success message
     */
    $( "#crc_settings_message" ).delay( 1500 ).addClass( "in" ).fadeOut( 2000 );
})( jQuery );
