/*
* Copyright 2003 - 2006 Mark O'Sullivan
* This file is part of Lussumo's Software Library.
* Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Non-application specific utility functions
*/

// Using jQuery without the "$" shortcut
jQuery.noConflict();

if(document.all && !document.getElementById) {
	document.getElementById = function(id) {
		 return document.all[id];
	}
}

function BlockSubmit(evt, Handler) {
	 var Key = evt.keyCode || evt.which;
	 if (Key == 13) {
		  Handler();
		  return false;
	 } else {
		  return true;
	 }
}

function CheckAll(IdToMatch) {
	var Ids = Explode(IdToMatch, ',');
	for (j = 0; j < Ids.length; j++) {
		CheckSwitch(Ids[j], true);
	}
}

function CheckNone(IdToMatch) {
	var Ids = Explode(IdToMatch, ',');
	for (j = 0; j < Ids.length; j++) {
		CheckSwitch(Ids[j], false);
	}
}

function CheckSwitch(IdToMatch, Switch) {
	jQuery("input:checkbox[id^=" + IdToMatch + "]").attr("checked", Switch);
}

function ClearContents(Container) {
	if (Container) Container.innerHTML = "";
}

function CompletePreferenceSet(PreferenceName) {
	//@todo	Should add the class or replace all class with this one?
	jQuery("#" + PreferenceName).attr("class", 'PreferenceComplete');
}

function Explode(inString, Delimiter) {
	 return inString.split(Delimiter);
}

function Focus(ElementID) {
	jQuery("#" + ElementID).focus();
}

function GetElements(ElementName, ElementIDPrefix) {
	return jQuery(ElementName + "[id^=" + ElementIDPrefix + "]").get();
}

function HideElement(ElementID, ClearElement) {
	var el = jQuery("#" + ElementID).hide();
	if (ClearElement == 1 && el.size() > 0) el.html('');
}

function PathFinder(){
	this.params = new function(){
		this.url = document.URL;
		this.domain = document.domain;
		this.httpMethod = this.url.replace(/^(http|https)(:\/\/).*$/, "$1$2");
		return this;
	};
	this.getRootPath = function(tag, attr, path) {
		var root = '';
		$(tag).each(function(){
			if (root === '') {
				var src = $(this).attr(attr);
				if(src.match(path)){
					root = src.replace(path, '');
					root = root.replace(/^http(s)?:\/\/[^\/]+/, ''); //because the src attr could have been a partial or complete url
				}
			}
		});
		return root || false;
	}
	return this;
};

function PopTermsOfService(Url) {
	window.open(Url, "TermsOfService", "toolbar=no,status=yes,location=no,menubar=no,resizable=yes,height=600,width=400,scrollbars=yes");
}

function PreferenceSet(Request) {
	setTimeout("CompletePreferenceSet('"+this.Param+"');", 400);
}

function RefreshPage(Timeout) {
	 if (!Timeout) Timeout = 400;
	 setTimeout("document.location.reload();", Timeout);
}

function RefreshPageWhenAjaxComplete(Request) {
	 RefreshPage();
}

function SubmitForm(FormName, Sender, WaitText) {
	Wait(Sender, WaitText);
	document[FormName].submit();
}

function SwitchElementClass(ElementToChangeID, SenderID, StyleA, StyleB, CommentA, CommentB) {
	 var Element = document.getElementById(ElementToChangeID);
	 Sender = document.getElementById(SenderID);
	 if (Element && Sender) {
		  if (Element.className == StyleB) {
				Element.className = StyleA;
				Sender.innerHTML = CommentA;
		  } else {
				Element.className = StyleB;
				Sender.innerHTML = CommentB;
		  }
	 }
}

function SwitchExtension(AjaxUrl, ExtensionKey, PostBackKey) {
	var Item = document.getElementById(ExtensionKey);
	if (Item) Item.className = "Processing";
	var Parameters = "ExtensionKey="+ExtensionKey+"&PostBackKey="+PostBackKey;
	var dm = new DataManager();
	dm.Param = ExtensionKey;
	dm.RequestFailedEvent = SwitchExtensionResult;
	dm.RequestCompleteEvent = SwitchExtensionResult;
	dm.LoadData(AjaxUrl+"?"+Parameters);
}

function SwitchExtensionResult(Request) {
	var Item = document.getElementById(Trim(Request.responseText));
	if (Item) {
		  setTimeout("SwitchExtensionItemClass('"+Trim(Request.responseText)+"')",400);
	 } else {
		  alert(Trim(Request.responseText));
	 }
}

function SwitchExtensionItemClass(ItemID) {
	var Item = document.getElementById(ItemID);
	var chk = document.getElementById('chk'+ItemID+'ID');
	if (Item && chk) Item.className = chk.checked ? 'Enabled' : 'Disabled';
}

function SwitchPreference(AjaxUrl, PreferenceName, RefreshPageWhenComplete, PostBackKey) {
	 var Container = document.getElementById(PreferenceName);
	 var CheckBox  = document.getElementById(PreferenceName+'ID');
	 if (CheckBox && Container) {
		  Container.className = 'PreferenceProgress';
		  var dm = new DataManager();
		  dm.Param = PreferenceName;
		  dm.RequestFailedEvent = HandleFailure;
		  if (RefreshPageWhenComplete == 1) {
	 		  dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
		  } else {
	 		  dm.RequestCompleteEvent = PreferenceSet;
		  }
		  dm.LoadData(AjaxUrl+"?Type="+PreferenceName+"&PostBackKey="+PostBackKey+"&Switch="+CheckBox.checked);
	 }
}

function Trim(String) {
   return String.replace(/^\s*|\s*$/g,"");
}

function UpdateCheck(AjaxUrl, RequestName, PostBackKey) {
	var dm = new DataManager();
	dm.RequestCompleteEvent = UpdateCheckStatus;
	dm.RequestFailedEvent = UpdateCheckStatus;
	dm.Param = AjaxUrl;
	dm.LoadData(AjaxUrl+"?RequestName="+RequestName+"&PostBackKey="+PostBackKey);
}

function UpdateCheckStatus(Request) {
	var ItemName, itemId, Message, PostBackKey, Item, ItemDetails, itemClass, detailMessage;

	if (Request.responseText == "COMPLETE") return;


	Message = Request.responseText.slice(Request.responseText.indexOf("|")+1);
	PostBackKey = $("#FormPostBackKey").attr('value') || '';
	ItemName = itemId = Request.responseText.substring(0, Request.responseText.indexOf("|"));
	if (ItemName == "First") {
		itemId = "Core";
	}

	Item = jQuery("#" + itemId);
	ItemDetails = jQuery("#" + itemId + "Details");


	if (Item.size() > 0 && ItemDetails.size() > 0) {
		if (Message.indexOf("ERROR]") == 1) {
			itemClass = "UpdateError";
			detailMessage = Message.replace(/\[ERROR\]/g,"");
		} else {
			// Change the class of the item
			if (Message.indexOf("OLD]") == 1) {
				itemClass = "UpdateOld";
			} else if (Message.indexOf("UNKNOWN]") == 1) {
				itemClass = "UpdateUnknown";
			} else {
				itemClass = "UpdateGood";
			}
			// Report the status of the returned extension
			detailMessage = Message.replace(/\[OLD\]/g,"").replace(/\[UNKNOWN\]/g, "").replace(/\[GOOD\]/g, "");
			// Request the next extension
			setTimeout("UpdateCheck('"+this.Param+"', '"+ItemName+"', '"+PostBackKey+"');", 300);
		}
		Item.attr('class', itemClass);
		ItemDetails.html(detailMessage);
	} else {
		alert('Error: '+Request.responseText);
	}
}

function Wait(Sender, WaitText) {
	jQuery(Sender).attr({'disabled': true, 'value': WaitText}).parents("form").submit();
}

function WriteEmail(Domain, Name, Label, Reference) {
	Label = Label || Name + "@" + Domain;
	$("#" + Reference).before('<a href="' + 'mai' + 'lto:' + Name + '@' + Domain + '">' + Label + '</a>');
}