function getDesiredWidth() {
	if (window.innerHeight) {
		var desiredWidth  = window.innerWidth - 280;
	}
	else {
		var desiredWidth  = document.documentElement.clientWidth - 280;
	}
	return desiredWidth;
}

function changeDimensions() {
	$$('img.InlineImage').each(function(elmt) {
		var originalWidth = elmt.getWidth();
		var desiredWidth  = getDesiredWidth();
		if (originalWidth > desiredWidth) {
			elmt.setStyle({width:desiredWidth+'px'});
		}
	});
}

Event.observe(window, 'load', function() {
	changeDimensions();
});

window.onresize = function() {
	$$('img.InlineImage').each(function(elmt) {
		elmt.setStyle({width:'auto'});
	});
	changeDimensions();
};

window.onmaximize = function() {
	$$('img.InlineImage').each(function(elmt) {
		elmt.setStyle({width:'auto'});
	});
	changeDimensions();
};
