$(function(){
	$('#register_form').validate({
		rules: {
			name: "required",

			email1: {
				required: true,
				email: true
			},

			email2: {
				required: true,
				equalTo:"input[name='email1']"
			},

			password1: "required",

			password_verif: {
				required: true,
				equalTo:"input[name='password1']"
			}
		}
	});
});