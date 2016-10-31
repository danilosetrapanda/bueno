(function( $ ) {
	$(window).load(function() {	
		// check cart total minimum and maximum values 
		
		var min_val_id = $('.wc_input_price.input-text.regular-input.min_amount').attr('id');
		var max_val_id = $('.wc_input_price.input-text.regular-input.max_amount').attr('id');
		
		var min_total_val_onload = $('#'+min_val_id).val();
		var max_total_val_onload = $('#'+max_val_id).val();
		
		if( min_total_val_onload > 0 ) {
			$('#'+max_val_id).attr('readonly','readonly');
		}
		
		if( max_total_val_onload > 0 ) {
			$('#'+min_val_id).attr('readonly','readonly');
		}
		
		// minimum cart total
		$('body').on('keyup','#'+min_val_id,function() {
			var min_total_value = $(this).val();
			if( min_total_value == '' ) {
				$('#'+max_val_id).removeAttr('readonly');	
			} 
			if( min_total_value == 0) {
				$('#'+max_val_id).removeAttr('readonly');	
			}
			if( min_total_value < 0 ) {				
				$('#'+max_val_id).removeAttr('readonly');	
			}
			if( min_total_value > 0 ) {
				$('#'+max_val_id).val('0');
				$('#'+max_val_id).attr('readonly','readonly');
				$('#'+min_val_id).attr('value',min_total_value);
			}
		}); 
		// maximum cart total
		$('body').on('keyup','#'+max_val_id,function(){
			var max_total_value = $(this).val();
			if( max_total_value == '' ) {
				$('#'+min_val_id).removeAttr('readonly');	
			} 
			if( max_total_value == 0) {
				$('#'+min_val_id).removeAttr('readonly');	
			}
			if( max_total_value < 0 ) {
				$(this).val('');
				$('#'+min_val_id).removeAttr('readonly');	
			}
			if( max_total_value > 0 ) {
				$('#'+min_val_id).val('0');
				$('#'+min_val_id).attr('readonly','readonly');
				$('#'+max_val_id).attr('value',max_total_value);
			}
		}); 
		
		// check cart quantity values minimum maximum
		
		var cart_min_qauntity_id = $('.input-text.regular-input.min_extra_quantity').attr('id');	
		var cart_max_qauntity_id = $('.input-text.regular-input.max_quantity').attr('id');
		
		var min_quantity_val_onload = $('#'+cart_min_qauntity_id).val();
		var max_quantity_val_onload = $('#'+cart_max_qauntity_id).val();
		
		if( min_quantity_val_onload > 0 ) {
			$('#'+cart_max_qauntity_id).attr('readonly','readonly');
		}
		
		if( max_quantity_val_onload > 0 ) {
			$('#'+cart_min_qauntity_id).attr('readonly','readonly');
		}
		
		$('body').on('keyup','#'+cart_min_qauntity_id,function(){
			var minimum_quantity_value = $(this).val();
			
			if(minimum_quantity_value == ''){
				$('#'+cart_max_qauntity_id).removeAttr('readonly');
			}
			if(minimum_quantity_value == 0){
				$('#'+cart_max_qauntity_id).removeAttr('readonly');
			}
			if(minimum_quantity_value < 0){
				$(this).val('');
				$('#'+cart_max_qauntity_id).removeAttr('readonly');
			}
			if(minimum_quantity_value > 0){
				$('#'+cart_max_qauntity_id).val('0');
				$('#'+cart_max_qauntity_id).attr('readonly','readonly');
				$('#'+cart_min_qauntity_id).attr('value',minimum_quantity_value);
			}
			
		});
		
		$('body').on('keyup','#'+cart_max_qauntity_id,function(){
			var maximum_quantity_value = $(this).val();
			
			if(maximum_quantity_value == ''){
				$('#'+cart_min_qauntity_id).removeAttr('readonly');
			}
			if(maximum_quantity_value == 0){
				$('#'+cart_min_qauntity_id).removeAttr('readonly');
			}
			if(maximum_quantity_value < 0){
				$('#'+cart_min_qauntity_id).removeAttr('readonly');
			}
			if(maximum_quantity_value > 0){
				$('#'+cart_min_qauntity_id).val('0');
				$('#'+cart_min_qauntity_id).attr('readonly','readonly');
				$('#'+cart_max_qauntity_id).attr('value',maximum_quantity_value);
			}
			
		});
		
		// check cart weight values minimum maximum
		var cart_min_weight_id = $('.input-text.regular-input.min_weight').attr('id');	
		var cart_max_weight_id = $('.input-text.regular-input.max_weight').attr('id');
		
		var min_weight_val_onload = $('#'+cart_min_weight_id).val();
		var max_weight_val_onload = $('#'+cart_max_weight_id).val();
		
		if( min_weight_val_onload > 0 ) {
			$('#'+cart_max_weight_id).attr('readonly','readonly');
		}
		
		if( max_weight_val_onload > 0 ) {
			$('#'+cart_min_weight_id).attr('readonly','readonly');
		}
		
		$('body').on('keyup','#'+cart_min_weight_id,function(){
			var minimum_weight_value = $(this).val();
			if(minimum_weight_value == '') {
				$('#'+cart_max_weight_id).removeAttr('readonly');
			}
			if(minimum_weight_value == 0){
				$('#'+cart_max_weight_id).removeAttr('readonly');
			}
			if(minimum_weight_value < 0){
				$('#'+cart_max_weight_id).removeAttr('readonly');
			}
			if(minimum_weight_value > 0){
				$('#'+cart_max_weight_id).attr('value','0');
				$('#'+cart_max_weight_id).attr('readonly','readonly');
				$('#'+cart_min_weight_id).attr('value',minimum_weight_value);
			}
		});
		
		$('body').on('keyup','#'+cart_max_weight_id,function(){
			var maximum_weight_value = $(this).val();
			if(maximum_weight_value == ''){
				$('#'+cart_min_weight_id).removeAttr('readonly');
			}
			if(maximum_weight_value == 0){
				$('#'+cart_min_weight_id).removeAttr('readonly');
			}
			if(maximum_weight_value < 0){
				$('#'+cart_min_weight_id).removeAttr('readonly');
			}
			if(maximum_weight_value > 0){
				$('#'+cart_min_weight_id).attr('value','0');
				$('#'+cart_min_weight_id).attr('readonly','readonly');
				$('#'+cart_max_weight_id).attr('value',maximum_weight_value);
			}
		});
			
	});
})( jQuery );