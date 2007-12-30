
function AutoComplete (TextInputID, AllowMultipleChoices){
	/* ---- Public Variables ---- */
	this.TimeOut = 400; // Autocomplete Timeout in ms
	this.MouseSupport = true; // Enable Mouse Support. Depreciated

	this.AllowMultipleChoices = AllowMultipleChoices || false;
	this.Delimiter = ',';  // Delimiter for multiple autocomplete.

	this.StartCharacter = 1; // Show widget only after this number of characters is typed in.
	this.KeywordSourceUrl = "autocomplete.php?Search=";
	/* ---- Public Variables ---- */

	/* --- Styles --- */
	this.ResultContainerClass = 'ac_results';

	// depreciated
	// will use .<this.ResultContainerClass> li and .ac_over instead
	// (see themes/svanilla/styles/default/vanilla.css)
	this.StandardRowClass = 'AutoCompleteRow';
	this.HoverRowClass = 'ac_over';
	/* --- Styles --- */

	this.TableID = 'AutoCompleteTable';

	var that = this;

	jQuery(function(){
		var el = document.getElementById(TextInputID);
		jQuery(el).autocomplete(that.KeywordSourceUrl, {
			'resultsClass': that.ResultContainerClass,
			'delay': that.TimeOut,
			'minChars': that.StartCharacter,
			'multiple': that.AllowMultipleChoices,
			'multipleSeparator': that.Delimiter,
			'extraParams': {'Version': '2'}
		});
	});
}
/* Event Functions */

// Add an event to the obj given
// event_name refers to the event trigger, without the "on", like click or mouseover
// func_name refers to the function callback when event is triggered
function addEvent(obj,event_name,func_name){
	jQuery(obj).bind(event_name, func_name);
}

// Removes an event from the object.
// Assume the event has been added with jQuery.
function removeEvent(obj,event_name,func_name){
	jQuery(obj).unbind(event_name, func_name);
}

// Stop an event from bubbling up the event DOM.
// Assume the event has been added with jQuery.
function stopEvent(evt){
	evt.preventDefault();
	evt.stopPropagation();
}

// Get the obj that starts the event
function getElement(evt){
	if (window.event){
		return window.event.srcElement;
	}else{
		return evt.currentTarget;
	}
}

// Get the obj that triggers off the event.
// Assume the event has been added with jQuery.
function getTargetElement(evt){
	return evt.target;
}

// For IE only, stops the obj from being selected
function stopSelect(obj){
	if (typeof obj.onselectstart != 'undefined'){
		addEvent(obj,"selectstart",function(e){ e.preventDefault(); });
	}
}

/*    Escape function   */
String.prototype.addslashes = function(){
	return this.replace(/(["\\\.\|\[\]\^\*\+\?\$\(\)])/g, '\\$1');
}
String.prototype.trim = function () {
	return this.replace(/^\s*(\S*(\s+\S+)*)\s*$/, "$1");
};
/* --- Escape --- */

/* Offset position from top of the screen */
function curTop(obj){
	return jQuery(obj).offset().top;
}
function curLeft(obj){
	return jQuery(obj).offset().left;
}
/* ------ End of Offset function ------- */

/* Types Function */

// is a given input a number?
function isNumber(a) {
	return typeof a == 'number' && isFinite(a);
}

/* Object Functions */

function replaceHTML(obj,text){
	jQuery(obj).text(text);
}
