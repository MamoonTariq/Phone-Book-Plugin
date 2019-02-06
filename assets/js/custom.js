jQuery(function(){
	jQuery(".btn-default").on("click",function(e){
		var name = jQuery('#name').val();
		var phone = jQuery('#phone').val();
		if (name == '' ) {
			alert('Please fill the name field');
		} else if (phone == '') {
			alert('Please fill the phone field');
		}else{
		e.preventDefault();
		jQuery.post(ajaxurl,{action:"phone_book",name:name,phone:phone },function(response){
			jQuery( ".inner" ).prepend( '<tr class="de-'+response+'"><td class="old-name-'+response+'">'+name+'</td><td style="display: none;" class="new-name-'+response+'"><input type="text" name="name" id="up-nm-'+response+'" value="'+name+'"></td><td class="old-phone-'+response+'">'+phone+'</td><td style="display: none;" class="new-phone-'+response+'"><input type="text" name="name" id="up-ph-'+response+'" value="phone"></td><td class="old-btns-'+response+'"><input type="button" value="Delete" class="delete" id="'+response+'"><input type="button" value="Edit" class="edit" id="'+response+'"></td><td style="display: none;" class="new-btns-'+response+'"><input type="button" value="Update" class="updates" id="'+response+'"></td></tr>' );
		});
		}
	});

});


	jQuery('.delete').click(function() {

	var id = jQuery(this).attr('id');
	jQuery.post(ajaxurl,{action:"delete",id:id},function(response){
		jQuery( ".de-"+id ).remove();
		 });
});


jQuery(document).ready(function(){
	
	jQuery('ul.tabs li').click(function(){
		var tab_id = jQuery(this).attr('data-tab');

		jQuery('ul.tabs li').removeClass('current');
		jQuery('.tab-content').removeClass('current');

		jQuery(this).addClass('current');
		jQuery("#"+tab_id).addClass('current');
	});

});


jQuery(function(){
	jQuery('.edit').click(function() {
		var id = jQuery(this).attr('id');
		jQuery('.old-name-'+id).hide();
		jQuery('.new-name-'+id).show();
		jQuery('.old-phone-'+id).hide();
		jQuery('.new-phone-'+id).show();
		jQuery('.old-btns-'+id).hide();
		jQuery('.new-btns-'+id).show();
	});
});


jQuery(function(){
	jQuery('.updates').click(function() {
		var id = jQuery(this).attr('id');
		var name = jQuery('#up-nm-'+id).val();
		var phone = jQuery('#up-ph-'+id).val();
		jQuery.post(ajaxurl,{action:"update_phone_book",name:name,phone:phone,id:id },function(response){
			jQuery('.old-name-'+id).show();
			jQuery('.new-name-'+id).hide();
			jQuery('.old-phone-'+id).show();
			jQuery('.new-phone-'+id).hide();
			jQuery('.old-btns-'+id).show();
			jQuery('.new-btns-'+id).hide();

			jQuery('.old-name-'+id).html(name);
			jQuery('.old-phone-'+id).html(phone);
			});
	});
});
