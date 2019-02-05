$(function(){
	$(".btn-default").on("click",function(e){
		var name = $('#name').val();
		var phone = $('#phone').val();
		if (name == '' ) {
			alert('Please fill the name field');
		} else if (phone == '') {
			alert('Please fill the phone field');
		}else{
		e.preventDefault();
		$.post(ajaxurl,{action:"phone_book",name:name,phone:phone },function(response){
			$( ".inner" ).prepend( '<tr class="de-'+response+'"><td class="old-name-'+response+'">'+name+'</td><td style="display: none;" class="new-name-'+response+'"><input type="text" name="name" id="up-nm-'+response+'" value="'+name+'"></td><td class="old-phone-'+response+'">'+phone+'</td><td style="display: none;" class="new-phone-'+response+'"><input type="text" name="name" id="up-ph-'+response+'" value="phone"></td><td class="old-btns-'+response+'"><input type="button" value="Delete" class="delete" id="'+response+'"><input type="button" value="Edit" class="edit" id="'+response+'"></td><td style="display: none;" class="new-btns-'+response+'"><input type="button" value="Update" class="updates" id="'+response+'"></td></tr>' );
		});
		}
	});

});


	$('.delete').click(function() {

	var id = $(this).attr('id');
	$.post(ajaxurl,{action:"delete",id:id},function(response){
		$( ".de-"+id ).remove();
		 });
});


$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});

});


$(function(){
	$('.edit').click(function() {
		var id = $(this).attr('id');
		$('.old-name-'+id).hide();
		$('.new-name-'+id).show();
		$('.old-phone-'+id).hide();
		$('.new-phone-'+id).show();
		$('.old-btns-'+id).hide();
		$('.new-btns-'+id).show();
	});
});


$(function(){
	$('.updates').click(function() {
		var id = $(this).attr('id');
		var name = $('#up-nm-'+id).val();
		var phone = $('#up-ph-'+id).val();
		$.post(ajaxurl,{action:"update_phone_book",name:name,phone:phone,id:id },function(response){
			$('.old-name-'+id).show();
			$('.new-name-'+id).hide();
			$('.old-phone-'+id).show();
			$('.new-phone-'+id).hide();
			$('.old-btns-'+id).show();
			$('.new-btns-'+id).hide();

			$('.old-name-'+id).html(name);
			$('.old-phone-'+id).html(phone);
			});
	});
});
