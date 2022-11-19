(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).on('click','.wps-password-hidden', function() {
            if ($('.wps-form__password').attr('type') == 'text') {
                $('.wps-form__password').attr('type', 'password');
            } else {
                $('.wps-form__password').attr('type', 'text');
            }
        });
	$(document).on('click','#add',function(){
		var row = '<tr><th><input type="text" name="column_id[]" placeholder="COMPANY_NAME" /></th><th><input type="text"  name="wpform_id[]" placeholder="2" /></th><th><button type="button" class="remove">Remove</button></th></tr>';
		$(".formulas-table tbody").append(row);
	});
	$(document).on('click','.add-model-field',function(){
		let formula = $(this).text();
		var row = '<tr><th><input type="text" name="column_id[]" value="'+formula+'" /></th><th><input type="text"  name="wpform_id[]" placeholder="2" /></th><th><button type="button" class="remove">Remove</button></th></tr>';
		$(".formulas-table tbody").append(row);
	});
	$(document).on('click','.remove',function(){
		$(this).parents("tr").remove();
	});
	$(document).on('click','#column-list',function(e){
		e.preventDefault();
		$('#column-id-list').css('display','block');
	});
	$(document).on('click','#close-modal',function(e){
		$('#column-id-list').css('display','none');
	});
})( jQuery );