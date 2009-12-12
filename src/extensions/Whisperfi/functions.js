function SetWhisperfi(Type,ElementID,Value,Elem,Class,NewText) {
	var pathFinder, root, ajax;
	pathFinder = new PathFinder();
	root = pathFinder.getRootPath('link', 'href', 'themes/vanilla/styles/default/favicon.ico') ||
		pathFinder.getRootPath('script', 'src', 'extensions/Whisperfi/functions.js') || '';
	ajax = new Ajax.Request( root + 'extensions/Whisperfi/ajax.php', {
		parameters:'PostBackAction=ChangeWhisperfi&Type='+Type+'&ElementID='+ElementID+'&Value='+Value,
		onSuccess: function(r) {
			Element.removeClassName(Elem,Class);
			if (NewText) {
				Elem.innerHTML = NewText;
			}
		}
	});
	return true;
}

function Whisperfi() {
	Element.addClassName('WhisperfiCont','PreferenceProgress');
	if ($('WhisperfiField').checked == true) Value = 1;
	else Value = 0;
	SetWhisperfi('OWN',0,Value,'WhisperfiCont','PreferenceProgress','');
}