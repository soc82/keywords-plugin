jQuery(document).ready(function($) {
	$('#pa-repeater-btn').click(function(){
		var get_value = $('#repeater').val();
		var get_field = $('#repeater').attr('name');
		if(get_value != ''){
			$('#pa-repeater-area').append('<input type="text" value="'+get_value+'" name="'+get_field+'" placeholder="" class="input full" style="width:100%; padding:10px;">');
			$('#repeater').val('');
		}
	});
	s
});