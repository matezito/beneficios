(function( $ ) {
	$(document).ready(function() {
		var i = 10;
		$('#add_fecha').on('click', function() {
			i++;
			var input = `<div id="_date_${i}" class="date-div"> <input type="datetime-local" class="regular-text date-field" name="_beneficio_date[]" /> <span class="remove-date dashicons dashicons-trash" data-id="#_date_${i}"></span></div>`;
			$('#beneficio-fechas').append(input);
		});
	});

	$(document).on('click','.remove-date',function(){
		var id = $(this).data('id');
		$(id).remove();
	});
})( jQuery );
