var MessageBox = function(){
	this.setProperties();

	//bind to jQuery
	$(this.init);
}

MessageBox.prototype.setProperties = function(){
	this.dropdown = $('div#message-box');
	this.dropdownMessages = 'message-box-body';
	this.dropdownHTML = "<div id='message-box'><div class='message-types'><a href='#' id='private-messages'>Private Messages</a><a href='#' id='notifications'>Notifications</a></div><div class='divider'></div>"+
	"<div id='message-box-body'></div></div>";
	this.container = $('ul.elgg-menu > li.elgg-menu-item-messages');
	this.hasLoaded = false;

	messageBox = this;
}

MessageBox.prototype.init = function(){
	messageBox.createDropdown();
	messageBox.container.bind('click', messageBox.toggleDropdown);
}

MessageBox.prototype.createDropdown = function(){
	messageBox.container.append(messageBox.dropdownHTML);
}

MessageBox.prototype.toggleDropdown = function(){
	if(!messageBox.hasLoaded){
		content = messageBox.loadDropdown();
		console.log($('div#'+messageBox.dropdownMessages));
		$('div#'+messageBox.dropdownMessages).html(content);
		hasLoaded = true;
	}
	return false;
}

MessageBox.prototype.loadDropdown = function(messageType){
	messageType = typeof messageType !== 'undefined' ? messageType : null;
	var content = '';

	elgg.get('messages/inbox', {
		data:{
			message_type: messageType
		},
		success: function(resultText){
			obj = JSON.parse(resultText);
			content = obj.output.content;
		}
	});
	return content;
}