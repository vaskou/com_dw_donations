function fn_ajax_controller_calls(func,format,request)
{
	return jQuery.ajax({
		url	: 'index.php?option=com_dw_donations&task=dwdonationform.'+func+'&format='+format,
		type   : 'POST',
		data   : request,
		success: function (response) {
			//console.log(response);
			try{
				response=jQuery.parseJSON(response);
				//console.log(response);
				var n_options={status:"danger",timeout:2000,pos:"top-center"};
				if(response.success){
					if(response.data.ngo_info.html){
						jQuery('.ngo_info').html(response.data.ngo_info.html);
						jQuery('.donate-btn-beneficiary').text(response.data.ngo_info.ngo_data.ngo_name);				
					}
				}else{
					jQuery.UIkit.notify(response.message,n_options);
				}
			}catch(e){
				//console.log(e);
				document.open();
				document.write(response);
				document.close();
			}
		},
		error: function(response) {
			console.log(response.responseText);
		}
	});
}

function fn_ngo_donate_button_submit(redirect_url,order_id)
{
	jQuery("#form-moneydonation").submit(function(event) {
		event.preventDefault();
		fn_toggle_redirect_message();
		var n_options={status:"danger",timeout:2000,pos:"top-center"};
		var form = jQuery(this),
			formData = form.serialize(),
			formMethod = form.attr("method");				
		formData += "&ajax=1";	
		jQuery.ajax({
			type: formMethod,
			data: formData,
			timeout:10000,
			success:function(response){
				//console.log(response);
				try{
					response=jQuery.parseJSON(response);
					if(response.success){
						if(response.data.success){
							order_id +=response.data.success.orderId;
							jQuery(".redirect-link").attr("href",redirect_url+order_id);
							window.setTimeout(function(){
								location.href=redirect_url+order_id;
							},3000);
						}else if(response.data.error){
							jQuery.UIkit.notify(response.data.error.error_text,n_options);
							fn_toggle_redirect_message();
						}
					}else{
						jQuery.UIkit.notify(response.message,n_options);
						fn_toggle_redirect_message();
					}
				}catch(e){
					//console.log(e);
					document.open();
					document.write(response);
					document.close();
				}
			},
			error:function(jqXHR, textStatus, errorThrown){
				jQuery.UIkit.notify(errorThrown,n_options);
				fn_toggle_redirect_message();
			}
		});
	});
}

function fn_toggle_redirect_message()
{
	jQuery(".payment-step-2,.payment-step-3").toggleClass('uk-hidden');
}

function fn_ngo_donate_button_click(ngo_id,custom_url,ngo_url)
{
	fn_url_change(custom_url);
	jQuery("#jform_beneficiary_id").val(ngo_id);
	
	jQuery(".ngo_info .uk-thumbnail").attr("href",ngo_url+"&userid="+ngo_id);
	
	var image=jQuery(".ngo-row[data-benef-id='"+ngo_id+"'] .list-img img").clone();
	jQuery(".ngo_info .ngo_avatar").replaceWith(image);
	
	var ngo_name=jQuery(".ngo-row[data-benef-id='"+ngo_id+"'] .ngo_name").text();
	jQuery(".ngo_info .ngo_name").text(ngo_name);
	jQuery("#form-moneydonation .donate-btn-beneficiary").text(ngo_name);
	
	jQuery(".payment-step-1").slideToggle(1000);
	jQuery(".payment-step-2").slideToggle(1000);
	jQuery("body,html").animate({scrollTop :0}, 1000,function(){ });
}

function fn_ajax_loader()
{
	jQuery(document).ajaxStart(function() {
        jQuery('.ngo-loader').show();
		jQuery('#lightbox-loading').css({"opacity":0});
    });
	jQuery(document).ajaxStop(function() {
        jQuery('.ngo-loader').hide();
		jQuery('#lightbox-loading').css({"opacity":1});
    });
}

function fn_url_change(data)
{
	if ( window.history.pushState ) {
		window.history.pushState({}, null, data);
	}else{	
		window.location = data;
	}
}

function fn_onpopstate()
{
	var popped = false;
	var initialURL = location.href;
	jQuery(window).bind('popstate', function(event) {
		var initialPop = !popped && location.href == initialURL;
		popped = true;
		if ( !initialPop ) {
			window.location.reload();
		}
	});
}

function fn_payment_step_back()
{
	jQuery(".payment-step-back").click(function(){
		var step=jQuery(this).closest(".payment-step").attr('data-step');
		var prev=step-1;
		jQuery(".payment-step-"+step).slideToggle(1000);
		jQuery(".payment-step-"+prev).slideToggle(1000);
		jQuery("body,html").animate({scrollTop :0}, 1000,function(){ });
	});
}

function fn_update_donate_button(){
	jQuery('.donate-btn-amount').text(jQuery('.amnt input[type="radio"]:checked').val()+'€');
	
	jQuery('.amnt input[type="radio"]').click(function(){
		jQuery('.donate-btn-amount').text(jQuery('.amnt input[type="radio"]:checked').val()+'€');
	});
	jQuery(".amnt-button").click(function(){
		jQuery('.donate-btn-amount').text(jQuery('.amnt input[type="radio"]:checked').val()+'€');
	});
}

function fn_pagination_click(data,form)
{
	jQuery("#ngo_list_pagination li a").click(function(){
		jQuery("#ngo_page").val(jQuery(this).data('page'));
		fn_url_change(data+form.serialize());
	});
}

function fn_moneydonationwizard_init(current_url,plus,isPopup,ngo_url)
{
	var form = jQuery('#form-moneydonation-filters');
	var formData;
	var formMethod;
	
	var values_ngoItemsInList=jQuery('#ngo_item_no_list').val();
	if(values_ngoItemsInList==0){ values_ngoItemsInList=10; }
	
	var options = {
		valueNames: [ 'ngoName', 'ngoObjectives', 'ngoObjective', 'ngoActionArea', 'ngoPriority' ],
		page:values_ngoItemsInList,
		plugins: [ ListPagination({'innerWindow':1,'outerWindow':2}) ]
	};
	
	if(!isPopup){
		var ngoList = new List('form-moneydonation-filters', options);
	
		var updateList=function(){
			var values_ngoObjective=jQuery('#ngo_objective_list').val();
			var values_ngoActionArea=jQuery('#ngo_actionarea_list').val();
			ngoList.filter(function(item) {
				return (item.values().ngoObjective.indexOf(values_ngoObjective,0)>-1 || values_ngoObjective==0) && (item.values().ngoActionArea==values_ngoActionArea || values_ngoActionArea==0)
			});
			
		}
		updateList();
	}
	jQuery('.ngo_filter').change(function(){
		updateList();
		
		formData = form.serialize();
		fn_url_change(current_url+plus+formData);
	});
	
	jQuery('#ngo_item_no_list').change(function(){
		var count=jQuery(this).val();
		if(count==0){ count=10; }
		ngoList.show(1,count);
	});
	
	fn_payment_step_back();
	
	fn_onpopstate();
	
	fn_update_donate_button();
	
	jQuery('#ngo_sort_filter').change(function(){
		var stype=jQuery(this).find('option:selected').data('stype');
		ngoList.sort(stype,{order:jQuery(this).val()});
	})
	
	jQuery('.ngo-row').click(function(){
		var benef_id=jQuery(this).data('benef-id');
		formData = form.serialize();
		var custom_url=current_url+plus+'beneficiary_id='+benef_id;
		fn_ngo_donate_button_click(benef_id,custom_url,ngo_url);
	});
	
	jQuery('.payment-step-back').click(function(){
		formData = form.serialize();
		fn_url_change(current_url+plus+formData);
	});
	
	//fn_ajax_loader();
	
	jQuery('a[data-lightbox]').lightbox();
}