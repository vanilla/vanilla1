/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Lussumo's Software Library.
* Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
* 
* Description: Non-application specific utility functions
*/

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

function ChangeElementText(ElementID, NewText) {
	var Element = document.getElementById(ElementID);
	if (Element) Element.innerHTML = NewText;
}

// A status indicator to keep the user informed
function ChangeLoaderText(NewText) {
	ChangeElementText("LoadStatus", NewText)
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
	var el = document.getElementsByTagName("input");
	for (i = 0; i < el.length; i++) {
		if (el[i].type == "checkbox" && el[i].id.indexOf(IdToMatch) == 0) {
			el[i].checked = Switch;
		}
	}
}

function ClearContents(Container) {
	if (Container) Container.innerHTML = "";
}

function CloseLoader() {
	setTimeout("SwitchLoader(0)",600);	
}

function Explode(inString, Delimiter) {
	aTmp = new Array(1);
	var Count = 0;
	var sTmp = new String(inString);

	while (sTmp.indexOf(Delimiter) > 0) {
		aTmp[Count] = sTmp.substr(0, sTmp.indexOf(Delimiter));
		sTmp = sTmp.substr(sTmp.indexOf(Delimiter) + 1, sTmp.length - sTmp.indexOf(Delimiter) + 1); 
		Count = Count + 1
	}

	aTmp[Count] = sTmp;
	return aTmp;
}

function Focus(ElementID) {
	var el = document.getElementById(ElementID);
	if (el) el.focus();
}

function GetElements(ElementName, ElementIDPrefix) {
	var Elements = document.getElementsByTagName(ElementName);
	var objects = new Array();
	for (i = 0; i < Elements.length; i++) {
		if (Elements[i].id.indexOf(ElementIDPrefix) == 0) {
			objects[objects.length] = Elements[i];			
		}
	}
	return objects;
}

function HandleSwitch(Request) {
	ChangeLoaderText("Complete");
	CloseLoader();
}

function HideElement(ElementID, ClearElement) {
	var Element = document.getElementById(ElementID);
	if (Element) {
		Element.style.display = "none";
		if (ClearElement == 1) ClearContents(Element);
	}
}

function PanelSwitch(AjaxUrl, PanelItem, RefreshPageWhenComplete, LoaderText) {
	 var chkBox = document.getElementById(PanelItem+"ID");
	 if (chkBox) {
		  ChangeLoaderText(LoaderText);
		  SwitchLoader(1);
		  var Parameters = "Type="+PanelItem+"&Switch="+chkBox.checked;
		  var dm = new DataManager();
		  dm.RequestFailedEvent = HandleFailure;
		  if (RefreshPageWhenComplete == 1) {
	 		  dm.RequestCompleteEvent = RefreshPage;
		  } else {
	 		  dm.RequestCompleteEvent = HandleSwitch;
		  }	
		  dm.LoadData(AjaxUrl+"?"+Parameters);		
	 }
}

function PopTermsOfService(Url) {
	window.open(Url, "TermsOfService", "toolbar=no,status=yes,location=no,menubar=no,resizable=yes,height=600,width=400,scrollbars=yes");
}

function RefreshPage() {
	document.location.reload();
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

function SwitchExtension(AjaxUrl, ExtensionKey) {
    var Item = document.getElementById(ExtensionKey);
    if (Item) Item.className = "Processing";
    var Parameters = "ExtensionKey="+ExtensionKey;
    var dm = new DataManager();
    dm.RequestFailedEvent = SwitchExtensionResult;
    dm.RequestCompleteEvent = SwitchExtensionResult;
    dm.LoadData(AjaxUrl+"?"+Parameters);
}

function SwitchExtensionResult(Request) {
    var Item = document.getElementById(Request.responseText);
    if (Item) {
        setTimeout("SwitchExtensionItemClass('"+Request.responseText+"')",600);
    } else {
        alert("Failed to modify extension.");
    }
}

function SwitchExtensionItemClass(ItemID) {
    var Item = document.getElementById(ItemID);
    var chk = document.getElementById('chk'+ItemID+'ID');
    if (Item && chk) Item.className = chk.checked ? 'Enabled' : 'Disabled';
}

function SwitchLoader(ShowLoader) {
	var Loader = document.getElementById("LoadStatus");
	if (Loader) Loader.style.display = (ShowLoader == 1)?"block":"none";
}

function Wait(Sender, WaitText) {
	 Sender.disabled = true;
	 Sender.value = WaitText;
	 
	 el = Sender.parentNode;
	 while(el != null) {
		  if (el.tagName == "FORM") {
				el.submit();
				break;
		  }
		  el = el.parentNode;
	 }
}

function WriteEmail(d, n, v) {
	document.write("<a "+"hre"+"f='mai"+"lto:"+n+"@"+d+"'>");
	if (v == '') {
		document.write(n+"@"+d);
	} else {
		document.write(v);
	}
	document.write("</a>");
}

