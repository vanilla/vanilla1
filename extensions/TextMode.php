<?php
/*
Extension Name: Text Mode Switch
Extension Url: http://lussumo.com/docs/
Description: Allows users to turn html-enabled comments off so that they appear as they were entered.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
*/

/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/

// Handle performing text mode switches
$HtmlOn = ForceIncomingString("h", "");
if ($HtmlOn != "" && $Context->Session->UserID > 0) {
	if (!@$UserManager) $UserManager = $Context->ObjectFactory->NewContextObject($Context, "UserManager", $Context);
			
	if (ForceBool($HtmlOn, 0)) {
		$UserManager->SwitchUserPreference("HtmlOn", 1);
	} else {
		$UserManager->SwitchUserPreference("HtmlOn", 0);
	}
}	

// Write the text mode switches
if (
		in_array($Context->SelfUrl, array("post.php", "index.php", "categories.php", "comments.php", "account.php", "search.php"))
		&& $Context->Session->UserID > 0
		&& $Context->Session->User->Preference("ShowTextToggle")
	) {

	$Params = $Context->ObjectFactory->NewObject($Context, "Parameters");
	$Params->DefineCollection($_GET);
	$Params->Remove("PageAction");
	if ($Context->Session->User->Preference("HtmlOn")) {
		$Params->Set("h", 0);
		$CurrentMode = $Context->GetDefinition("OffCaps");
		$CurrentModeCSS = "OFF";
		$OppositeMode = $Context->GetDefinition("On");
	} else {
		$Params->Set("h", 1);
		$CurrentMode = $Context->GetDefinition("OnCaps");
		$CurrentModeCSS = "ON";
		$OppositeMode = $Context->GetDefinition("Off");
	}		
	$Panel->AddString("<div class=\"PanelInformation TextMode".$CurrentModeCSS."\">".$Context->GetDefinition("TextOnlyModeIs")." ".$CurrentMode." (<a class=\"PanelLink\" href=\"".$Context->SelfUrl.$Params->GetQueryString()."\">".$Context->GetDefinition("Turn")." ".$OppositeMode."</a>)</div>",
		100);
}

// TODO: Handle integrating this switch in the comments and account pages here (rather than in-place)

// Make sure that a comment follows the user's preference and enables or disabled html where required
function Comment_UserHtmlPreference(&$Comment) {
	if (!$Comment->Context->Session->User->Preference("HtmlOn")) {
		$Comment->ShowHtml = 0;
		$Comment->AuthIcon = "";
	}
}

$Context->AddToDelegate("Comment",
	"PreFormatPropertiesForDisplay",
	"Comment_UserHtmlPreference");
	
	
if ($Context->SelfUrl == "account.php") {
	// Make sure that the pictures aren't displayed on the page if the user has turned text-only mode on
   function Account_HideImages(&$Account) {
		if (!$Account->Context->Session->User->Preference("HtmlOn")) {
			$Account->User->DisplayIcon = "";
			$Account->User->Picture = "";
		}		
	}

	$Context->AddToDelegate("Account",
		"PreUsernameRender",
		"Account_HideImages");
}

if ($Context->SelfUrl == "comments.php") {
	
}
?>