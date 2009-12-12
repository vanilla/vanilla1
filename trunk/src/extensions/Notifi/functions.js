function show(id) {
	document.getElementById(id).style.display='block';
}

function hide(id) {
	document.getElementById(id).style.display='none';
}

function SetNotifi(Type,ElementID,Value,Elem,Class,NewText) {
	var pathFinder, root, ajax;
	pathFinder = new PathFinder();
	root = pathFinder.getRootPath('link', 'href', 'themes/vanilla/styles/default/favicon.ico') ||
		pathFinder.getRootPath('script', 'src', 'extensions/Notifi/functions.js') || '';
	ajax = new Ajax.Request( root + 'extensions/Notifi/ajax.php', {
		parameters:'PostBackAction=ChangeNotifi&Type='+Type+'&ElementID='+ElementID+'&Value='+Value,
		onSuccess: function(r) {
			if (Type === "ALL" && Elem === "NotifiAllCont") {
				if (Value === 1) {
					hide('NotifiOwnCont');
					hide('NotifiCommentCont');
					hide('categoriesContainer');
					hide('discussionsContainer');
				} else if (!Value) {
					show('NotifiOwnCont');
					show('NotifiCommentCont');
					show('categoriesContainer');
					show('discussionsContainer');
				}
			}
			if (Type === "COMMENT" && Elem === "NotifiCommentCont") {
				if (Value === 1) {
					hide('NotifiOwnCont');
				} else if (!Value) {
					show('NotifiOwnCont');
				}
			}
			if (Elem === "SetNotifiAll" || Elem === "SetNotifiDiscussion_"+ElementID || Elem === "SetNotifiCategory_"+ElementID) {
				Element.removeClassName(Elem,Class);
				if (NewText) {
					Elem.innerHTML = NewText;
				}
				$(Elem).innerHTML = NewText;
			} else {
				Element.removeClassName(Elem,Class);
				if (NewText) {
					Elem.innerHTML = NewText;
				}
			}
		}
	});
	return true;
}

function NotifiCat(CategoryID) {
	Element.addClassName('NotifiCatCont_'+CategoryID,'PreferenceProgress');
	if ($('NotifiCat_'+CategoryID).checked == true) Value = 1;
	else Value = 0;
	SetNotifi('CATEGORY',CategoryID,Value,'NotifiCatCont_'+CategoryID,'PreferenceProgress','');
}

function NotifiDiscussion(DiscussionID) {
	Element.addClassName('NotifiDiscussionCont_'+DiscussionID,'PreferenceProgress');
	if ($('NotifiDiscussion_'+DiscussionID).checked == true) Value = 1;
	else Value = 0;
	SetNotifi('DISCUSSION',DiscussionID,Value,'NotifiDiscussionCont_'+DiscussionID,'PreferenceProgress','');
}

function PNotifiAll(SetText,UnSetText) {
	Element.addClassName('SetNotifiAll','Progress');
	if ($('SetNotifiAll').innerHTML == SetText) {
		Value = 1;
		NewText = UnSetText;
	} else {
		Value = 0;
		NewText = SetText;
	}
	SetNotifi('ALL',0,Value,'SetNotifiAll','Progress',NewText);
}

function PNotifiCategory(CategoryID,SetText,UnSetText) {
	Element.addClassName('SetNotifiCategory_'+CategoryID,'Progress');
	if ($('SetNotifiCategory_'+CategoryID).innerHTML == SetText) {
		Value = 1;
		NewText = UnSetText;
	} else {
		Value = 0;
		NewText = SetText;
	}
	SetNotifi('CATEGORY',CategoryID,Value,'SetNotifiCategory_'+CategoryID,'Progress',NewText);
}

function PNotifiDiscussion(DiscussionID,SetText,UnSetText) {
	Element.addClassName('SetNotifiDiscussion_'+DiscussionID,'Progress');
	if ($('SetNotifiDiscussion_'+DiscussionID).innerHTML == SetText) {
		Value = 1;
		NewText = UnSetText;
	} else {
		Value = 0;
		NewText = SetText;
	}
	SetNotifi('DISCUSSION',DiscussionID,Value,'SetNotifiDiscussion_'+DiscussionID,'Progress',NewText);
}

function NotifiAll() {
	Element.addClassName('NotifiAllCont','PreferenceProgress');
	if ($('NotifiAllField').checked == true) Value = 1;
	else Value = 0;
	SetNotifi('ALL',0,Value,'NotifiAllCont','PreferenceProgress','');
}

function NotifiOwn() {
	Element.addClassName('NotifiOwnCont','PreferenceProgress');
	if ($('NotifiOwnField').checked == true) Value = 1;
	else Value = 0;
	SetNotifi('OWN',0,Value,'NotifiOwnCont','PreferenceProgress','');
}

function NotifiComment() {
	Element.addClassName('NotifiCommentCont','PreferenceProgress');
	if ($('NotifiCommentField').checked == true) Value = 1;
	else Value = 0;
	SetNotifi('COMMENT',0,Value,'NotifiCommentCont','PreferenceProgress','');
}

function KeepEmailing() {
	Element.addClassName('KeepEmailingCont','PreferenceProgress');
	if ($('KeepEmailingField').checked == true) Value = 1;
	else Value = 0;
	SetNotifi('KEEPEMAILING',0,Value,'KeepEmailingCont','PreferenceProgress','');
}