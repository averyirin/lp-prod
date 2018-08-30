$(function(){
	$(".time-toggle").change(function(){
		var value = $(this).val();
		if(value == 0){
			$(this).parent().find('input').val('');
			$(this).parent().find('select').val('');
		}
		$(this).parent().find('.time-release').slideToggle();
	});
});