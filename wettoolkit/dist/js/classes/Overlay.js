var Overlay = function(message){
	this.setProperties(message);
	//bind to jQuery
	$(this.init);
}

Overlay.prototype.setProperties = function(message) {
	this.container = $("#wb-body");
	this.id = "overlay-widget";
	this.message = message;
	
	var top = (document.documentElement && document.documentElement.scrollTop) || 
              document.body.scrollTop;
	top = $(window).scrollTop();
	var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
	var height = (isIE ? 40 : 100);

	this.content = '<div id="'+this.id+'" style="position:absolute;top:'+top+'px;left:0;background:#111;opacity:.8;z-index:99999;display:table;width:60%;height:'+height+'%;padding:20%;">'
						+'<div style="display:table-cell;vertical-align:middle;">'
							+'<div style="text-align:center;"><h2 style="color:#f5f5f5;">'+this.message+'</h2></div>'
						+'</div>'
					+'</div>';
	that = this;
}

Overlay.prototype.init = function() {
	that.container.append(that.content);
	$(window).scroll(function() {
		$("#"+that.id).css('top', $(window).scrollTop());
	});
}

Overlay.prototype.remove = function() {
	$('#overlay-widget').remove();
}