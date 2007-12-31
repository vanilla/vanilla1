/*
* Copyright 2003 - 2006 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Utility functions specific to Vanilla
*/


// Add a new custom name/value pair input to the account form
function AddLabelValuePair() {
	var CounterEl = jQuery('#LabelValuePairCount');
	var Container = jQuery('#CustomInfo');
	if (CounterEl.size() > 0 && Container.size() > 0) {
		var Counter = +(CounterEl.attr('value')) + 1;
		CounterEl.attr('value', Counter);

		Container.append('<li><input type="text" name="Label' + Counter +'" maxlength="20" class="LVLabelInput" /></li>')
		Container.append('<li><input type="text" name="Value' + Counter +'" maxlength="200" class="LVValueInput" /></li>')
	}
}

function DiscussionSwitch(AjaxUrl, SwitchType, DiscussionID, SwitchValue, SenderID, PostBackKey) {
	jQuery("#" + SenderID).attr('class', 'Progress');
	var Parameters = "Type="+SwitchType+"&DiscussionID="+DiscussionID+"&Switch="+SwitchValue+"&PostBackKey="+PostBackKey;
	var dm = new DataManager();
	dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
	dm.RequestFailedEvent = HandleFailure;
	dm.LoadData(AjaxUrl+"?"+Parameters);
}

function HideComment(AjaxUrl, Switch, DiscussionID, CommentID, ShowText, HideText, SenderID, PostBackKey) {
	var ConfirmText = (Switch==1?HideText:ShowText);
	if (confirm(ConfirmText)) {
		jQuery("#" + SenderID).attr('class', 'HideProgress').html('&nbsp;');
		var dm = new DataManager();
		dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
		dm.RequestFailedEvent = HandleFailure;
		dm.LoadData(AjaxUrl+"?Type=Comment&Switch="+Switch+"&DiscussionID="+DiscussionID+"&CommentID="+CommentID+"&PostBackKey="+PostBackKey);
	}
}

// Apply or remove a bookmark
function SetBookmark(AjaxUrl, CurrentSwitchVal, Identifier, BookmarkText, UnbookmarkText, PostBackKey) {
	var Switch, FlipSwitch, Sender = jQuery("#SetBookmark"), dm;
	if (Sender.size() > 0) {
		Switch = Sender.attr('name');
		Switch = Switch == '' ? CurrentSwitchVal : Switch;
		FlipSwitch = Switch == 1 ? 0 : 1;

		Sender.attr({'name': FlipSwitch, 'class': 'Progress'});

		dm = new DataManager();
		dm.Param = (FlipSwitch == 0 ? BookmarkText : UnbookmarkText);
		dm.RequestCompleteEvent = BookmarkComplete;
		dm.RequestFailedEvent = BookmarkFailed;
		dm.LoadData(AjaxUrl+"?Type=Bookmark&Switch="+FlipSwitch+"&DiscussionID="+Identifier+"&PostBackKey="+PostBackKey);
	}
}

function ApplyBookmark(Element, ClassName, Text) {
	jQuery("#" + Element).attr('class', ClassName).html(Text);
}

function BookmarkComplete(Request) {
	setTimeout("ApplyBookmark('SetBookmark', 'Complete', '"+this.Param+"');", 400);
}

function BookmarkFailed(Request) {
	var Button = jQuery('#SetBookmark');
	if (Button.size() > 0) {
		Button.attr('class', 'Complete');
		alert("Failed: ("+Request.status+") "+Request.statusText);
	}
}

function ShowAdvancedSearch() {
	jQuery("#SearchSimpleFields").hide();
	jQuery("#SearchDiscussionFields").show();
	jQuery("#SearchCommentFields").show();
	jQuery("#SearchUserFields").show();
}

function ShowSimpleSearch() {
	jQuery("#SearchSimpleFields").show();
	jQuery("#SearchDiscussionFields").hide();
	jQuery("#SearchCommentFields").hide();
	jQuery("#SearchUserFields").hide();
}

function ToggleCategoryBlock(AjaxUrl, CategoryID, Block, SenderID, PostBackKey) {
	jQuery("#" + SenderID).attr('class', 'HideProgress').html('&nbsp;');
	var Parameters = "BlockCategoryID="+CategoryID+"&Block="+Block+'&PostBackKey='+PostBackKey;
	var dm = new DataManager();
	dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
	dm.RequestFailedEvent = HandleFailure;
	dm.LoadData(AjaxUrl+"?"+Parameters);
}

function ToggleCommentBox(AjaxUrl, SmallText, BigText, PostBackKey) {
	SwitchElementClass('CommentBox', 'CommentBoxController', 'SmallCommentBox', 'LargeCommentBox', BigText, SmallText);
	var SwitchVal = 0;
	var className = jQuery("#CommentBox").attr('class');
	if (className) {
		if (className == "LargeCommentBox") {
			SwitchVal = 1;
		}
		var Parameters = "Type=ShowLargeCommentBox&Switch="+SwitchVal+"&PostBackKey="+PostBackKey;
		var dm = new DataManager();
		dm.RequestCompleteEvent = ToggleCommentBoxComplete;
		dm.RequestFailedEvent = HandleFailure;
		dm.LoadData(AjaxUrl+"?"+Parameters);
	}
}
function ToggleCommentBoxComplete(Request) {
	// Don't do anything.
}

function WhisperBack(DiscussionID, WhisperTo, BaseUrl) {
	var frm = document.getElementById("frmPostComment");
	if (!frm) {
		document.location = BaseUrl + "post.php?PostBackAction=Reply&DiscussionID="+DiscussionID+"&WhisperUsername="+escape(WhisperTo);
	} else {
		frm.WhisperUsername.value = WhisperTo;
		frm.Body.focus();
	}
}
