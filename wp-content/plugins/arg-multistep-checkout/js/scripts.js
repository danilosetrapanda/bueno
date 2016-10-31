(function($){
    
    "use strict";

	//Validate Woocommerce Checkout Forms
	
	//Overwrite Validation Engine Defaults
	$.validationEngine.defaults.validationEventTrigger = "blur";
	$.validationEngine.defaults.scroll = true;
	$.validationEngine.defaults.focusFirstField = true;
	$.validationEngine.defaults.showPrompts = false;
	$.validationEngine.defaults.scrollOffset = 200;
	
	//Overwrite Validation Engine Prompts
	$('.arg-mc-wrapper .login, .arg-mc-wrapper .checkout').bind("jqv.field.result", function (event, field, errorFound, prompText) {
		var formGroup = field.parents(".form-row").first();
		if (errorFound) {
			formGroup.addClass("has-error");
			$("label.error", formGroup).last().remove();
			formGroup.append('<label class="error">' + prompText + '</label>');
		}
		else {
			formGroup.removeClass("has-error");
			$("label.error", formGroup).last().remove();
		}
	});
	
	
	//Validate login form
    $('.arg-mc-wrapper #username').addClass('validate[required]');
    $('.arg-mc-wrapper #password').addClass('validate[required]');
	
	$('.arg-mc-wrapper .login').validationEngine();
	
    $('.arg-mc-wrapper').on('submit', '.login', function(e) {
        e.preventDefault();
 
        $.ajax({
            url: jsVars.ajaxURL,
			dataType: 'json',
			type: 'POST',
            data: {
                action: 'login',
                username: $('#username').val(),
                password: $('#password').val(),
                rememberme: $('#rememberme').is(':checked'),
                security: jsVars.loginNonce                  
            }
        }) 
        .done(function(data) {
            if (data['success'] == true) {
                location.reload();
            } else {
				if ($('.login-errors').length > 0) {
					$('.login-errors').html(data['error']);
				} else {	
					$('.login .form-row-first').before('<ul class="woocommerce-error login-errors"><li>' + data['error'] + '</li></ul>');
				}
            }
        });
    });

	//Validate checkout form
	$('.validate-required :input').addClass('validate[required]');
	$('.validate-required :input[type="email"]').removeClass('validate[required]').addClass('validate[required,custom[email]]');		
	
	$('.arg-mc-wrapper .checkout').validationEngine({maxErrorsPerField : 1,
													prettySelect: true,
													usePrefix: 's2id_'});


	//Billing and shipping forms
	
	//Validate postcode
	$('#billing_postcode').removeClass('validate[required]')
						  .addClass('validate[required,funcCall[checkPostcode]')
						  .data('fieldset-key', 'billing');
						  
	$('#shipping_postcode').removeClass('validate[required]')
						   .addClass('validate[required,funcCall[checkPostcode]')
						   .data('fieldset-key', 'shipping');
	
	
	//Validate phone
	$('#billing_phone').removeClass('validate[required]')
					   .addClass('validate[required,funcCall[validatePhone]')
					   .data('fieldset-key', 'billing');
					   			   
	
    //Validate state
    $('.arg-mc-wrapper').on('change', '#billing_country', function() {
		
		if (!$(this).parent().hasClass('woocommerce-validated') && !$(this).hasClass('validate[required]')) {
		
			$(this).addClass('validate[required]')
				   .siblings('select2-container')
				   .addClass('validate[required]');
		}
		
		setTimeout(function() {
			if ($('#billing_state').parent().hasClass('validate-required')) {
				if (!$('#billing_state').hasClass('validate[required]')) {
				
					$('#billing_state').addClass('validate[required]')
									   .siblings('select2-container')
									   .addClass('validate[required]');
				}
			} else {
				$('#billing_state').removeClass('validate[required]')
								   .siblings('select2-container')
								   .removeClass('validate[required]');
				
				
				//Remove state error on country change				   
				$('#billing_state').validationEngine('hide');
				$('#billing_state').next('.error').remove();
			}
			
		}, 500);
    });
		
    $('.arg-mc-wrapper').on('change', '#shipping_country', function() {
		
		if (!$(this).parent().hasClass('woocommerce-validated') && !$(this).hasClass('validate[required]')) {
			
			$(this).addClass('validate[required]')
				   .siblings('select2-container')
				   .addClass('validate[required]');
		}
		
		setTimeout(function() {
			if ($('#shipping_state').parent().hasClass('validate-required')) {
				if (!$('#shipping_state').hasClass('validate[required]')) {
				
					$('#shipping_state').addClass('validate[required]')
										.siblings('select2-container').addClass('validate[required]');
				}
			} else {
				$('#shipping_state').removeClass('validate[required]')
									.siblings('select2-container').removeClass('validate[required]');
			}
			
			//Remove state error on country change
			$('#shipping_state').validationEngine('hide');
			$('#shipping_state').next('.error').remove();			
		}, 500);
		
    });
	
	
	$('.arg-mc-wrapper').on('change', '#billing_state', function() {
		
		if ($('#billing_state').parent().hasClass('validate-required')) {
			
			if($('#billing_state').val() == '' ) {
	
				if (!$('#billing_state').hasClass('validate[required]')) {
				
					$('#billing_state').addClass('validate[required]')
									   .siblings('select2-container')
									   .addClass('validate[required]');
				}
			} else {
				$('#billing_state').removeClass('validate[required]')
								   .siblings('select2-container')
								   .removeClass('validate[required]');
				
				
				//Remove state error on country change
				$('#billing_state').parent().removeClass('has-error');
				$('#billing_state').validationEngine('hide');
				$('#billing_state').next('.error').remove();
			}
		}
		
	});
	
	
	$('.arg-mc-wrapper').on('change', '#shipping_state', function() {
		
		if ($('#shipping_state').parent().hasClass('validate-required')) {
			
			if($('#shipping_state').val() == '' ) {
		
				if (!$('#shipping_state').hasClass('validate[required]')) {
				
					$('#shipping_state').addClass('validate[required]')
									    .siblings('select2-container')
									    .addClass('validate[required]');
				}
			} else {
				$('#shipping_state').removeClass('validate[required]')
								    .siblings('select2-container')
								    .removeClass('validate[required]');
				
				
				//Remove state error on country change
				$('#shipping_state').parent().removeClass('has-error');
				$('#shipping_state').validationEngine('hide');
				$('#shipping_state').next('.error').remove();
			}
		}	
	});
	
	
	//Navigation	
    var sections    = $('.arg-mc-form-steps'),
        tabs        = $('.arg-mc-tab-item');
		
    function navigateTo(index) {
            
        var atTheEnd = index >= sections.length - 1;

        if (index == 0 && !sections.eq(0).hasClass('animate')) {
            sections.eq(0).addClass('animate')
        }
        
        var animationTopTiming = 0,
            animationSwitchSectionsDelay = 0;
        
        if ($(window).scrollTop() > 10) {
            animationTopTiming = 300;
            animationSwitchSectionsDelay = 100;
        } 
        
        $("html, body").animate({scrollTop: 0},
			animationTopTiming, function() {
				
				setTimeout(function(){
				  
					// Mark the current section with the class 'current'
					sections
						.removeClass('current')
						.eq(index)
						.addClass('current');
		
					tabs
						.removeClass('current')
						.eq(index)
						.addClass('current visited')
												
					
					//Show only the navigation buttons that make sense for the current section:
					$('.arg-mc-nav .arg-mc-previous').toggle(index > 0);
					$('.arg-mc-nav #arg-mc-next').toggle((!atTheEnd) && !$('.arg-mc-form-steps.current').hasClass('arg-mc-login-step'));
					$('.arg-mc-nav #arg-mc-skip-login').toggle($('.arg-mc-form-steps.current').hasClass('arg-mc-login-step'));
					$('.arg-mc-nav .arg-mc-submit').toggle(atTheEnd);
						
				 }, animationSwitchSectionsDelay)
			});
		}
    
	
	//Return the current index by looking at which section has the class 'current'	
    function curIndex() {
		return sections.index(sections.filter('.current'));
    }
    
	
    //Previous button is easy, just go back
    $('.arg-mc-nav .arg-mc-previous').on('click', function() {
        navigateTo(curIndex() - 1);        
    });
       
        
    //Next button goes forward if current block validates
    $('.arg-mc-nav .arg-mc-next').on('click', function() {
		$('.woocommerce-validated .select2-container').removeClass('validate[required]');
		
		if ($('.arg-mc-form-step-' + (curIndex() + 1)).hasClass('arg-mc-login-step') || $('.arg-mc-form-step-' + (curIndex() + 1)).hasClass('arg-mc-coupon-step')) {
		
			if (!tabs.eq(curIndex()).hasClass('completed')) {
				tabs.eq(curIndex()).addClass('completed');
			}
			navigateTo(curIndex() + 1);
			
		} else {
			$('.arg-mc-wrapper').addClass('select2Loaded');	
			if ($('.checkout').validationEngine('validate') == true) {
			
				if (!tabs.eq(curIndex()).hasClass('completed')) {
					tabs.eq(curIndex()).addClass('completed');
				}
				
				navigateTo(curIndex() + 1);
			}
		}
    });
    
    tabs.on('click', function() {
        
        var thatTab = $(this),
            thatIndex = 0;
        
        if (thatTab.hasClass('visited')) {
            thatIndex = thatTab.index();
            navigateTo(thatIndex);
        }
    })   

    
    //Login checkbox
	var loginCheckbox =  $('.woocommerce-checkout .woocommerce .arg-mc-wrapper form.login label[for="rememberme"] input[type="checkbox"]');
    
    if (loginCheckbox.is(':checked')) {
        loginCheckbox.parent().addClass('checked');
    } else {
        loginCheckbox.parent().removeClass('checked');
    }  
    
    loginCheckbox.on('change', function () {
        
        var that = $(this); 
        
        if (that.is(':checked')) {
            that.parent().addClass('checked');
        } else {
            that.parent().removeClass('checked');
        }  
    });	
	
	
	//Ship to different address checkbox
	if ($('#ship-to-different-address-checkbox').length) {
		if ($('#ship-to-different-address-checkbox').prev().hasClass('checkbox')) {
			$('#ship-to-different-address-checkbox').insertBefore($('#ship-to-different-address-checkbox').prev());
		}
	}
	
	
	//Place order
    $('#arg-mc-submit').on('click', function() {
		$('#terms').removeClass('validate[required]').addClass('validate[required]');
		if ($('.checkout').validationEngine('validate') == true) {
			$("#place_order").trigger("click");
		}
    });

    
    //Disable form submit on enter
    $('.arg-mc-wrapper .checkout').on('keypress', function(e) {

        if (e.which === 13) { 
            e.preventDefault();
            return false;
        }
        
        return true;
    });
    
})(jQuery);

//Validate postcode
function checkPostcode(field, rules, i, options) {
	data = jQuery.ajax({
		url: jsVars.ajaxURL,
		async: false,
		dataType: 'json',
		type: 'POST',
		data: {
			action: 'validate_fields',
			rule: 'postcode',
			fieldset_key: jQuery(field).data('fieldset-key'),
			country: jQuery('#' + jQuery(field).data('fieldset-key') + '_country').val(),
			postcode: jQuery(field).val()                   
		}				
		
	});

	data = jQuery.parseJSON(data.responseText);

	if (data.success == false) {
		return data.error;
	}
	
	return true;
}

//Validate phone
function validatePhone(field, rules, i, options) { 
	data = jQuery.ajax({
		url: jsVars.ajaxURL,
		async: false,
		dataType: 'json',
		type: 'POST',
		data: {
			action: 'validate_fields',
			rule: 'phone',
			fieldset_key: jQuery(field).data('fieldset-key'),
			phone: jQuery(field).val()                  
		}			
	});
	
	data = jQuery.parseJSON(data.responseText);

	if (data.success == false) {
		return data.error;
	}
	
	return true;
}