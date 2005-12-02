<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: Constants and objects specific to forum pages.
*/

// GLOBAL INCLUDES
include($Configuration["APPLICATION_PATH"]."appg/headers.php");
include($Configuration["LIBRARY_PATH"]."Utility.Functions.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Database.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.SqlBuilder.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.MessageCollector.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.ErrorManager.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.ObjectFactory.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.StringManipulator.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Context.php");

include($Configuration["LIBRARY_PATH"]."Utility.Class.Delegation.php");
include($Configuration["LIBRARY_PATH"]."Utility.Class.Control.php");
include($Configuration["LIBRARY_PATH"]."People.Class.Authenticator.php");
include($Configuration["LIBRARY_PATH"]."Vanilla.Functions.php");
include($Configuration["LIBRARY_PATH"]."People.Class.Session.php");
include($Configuration["LIBRARY_PATH"]."People.Class.User.php");

// INSTANTIATE THE CONTEXT OBJECT
// The context object handles the following:
// - Open a connection to the database
// - Create a user session (autologging in any user with valid cookie credentials)
// - Instantiate debug and warning collectors
// - Instantiate an error manager
// - Define global variables relative to the current context (SelfUrl

$Context = new Context($Configuration);

// Set the ObjectFactory's reference for the authentication module
$Context->ObjectFactory->SetReference("Authenticator", $Configuration["AUTHENTICATION_MODULE"]);

// Start the session management
$Context->StartSession();

// DEFINE THE LANGUAGE DICTIONARY
include($Configuration["APPLICATION_PATH"]."conf/language.php");

// INSTANTIATE THE PAGE OBJECT
// The page object handles collecting all page controls
// and writing them when it's events are fired.
$Page = $Context->ObjectFactory->NewContextObject($Context, "Page", $Configuration["PAGE_EVENTS"]);

// FIRE INITIALIZATION EVENT
$Page->FireEvent("Page_Init");

// DEFINE THE MASTER PAGE CONTROLS
$Head = $Context->ObjectFactory->CreateControl($Context, "Head");
$Menu = $Context->ObjectFactory->CreateControl($Context, "Menu");
$Panel = $Context->ObjectFactory->CreateControl($Context, "Panel");
$Foot = $Context->ObjectFactory->CreateControl($Context, "Foot");
$PageEnd = $Context->ObjectFactory->CreateControl($Context, "PageEnd");

// BUILD THE PAGE HEAD
// Every page will require some basic definitions for the header.
$Head->AddScript("./js/global.js");
$Head->AddScript("./js/vanilla.js");
$Head->AddScript("./js/prototype.js");
$Head->AddScript("./js/scriptaculous.js");
$Head->AddScript("./js/sort.js");
$Head->AddStyleSheet($Context->StyleUrl."css/global.css", "screen");
$Head->AddStyleSheet($Context->StyleUrl."css/global.handheld.css", "handheld");
$Head->AddString("<link rel=\"alternate\" type=\"application/atom+xml\" href=\"".PrependString("http://", AppendFolder($Configuration["DOMAIN"], "feeds/?Type=atom"))."\" title=\"".$Context->GetDefinition("Atom")." ".$Context->GetDefinition("Feed")."\" />");


// Add the start button to the panel
if ($Context->Session->UserID > 0) {
   $CategoryID = ForceIncomingInt("CategoryID", 0);
	$Panel->AddString("<a class=\"PanelButton StartDiscussionButton\" href=\"post.php".($CategoryID > 0?"?CategoryID=".$CategoryID:"")."\">".$Context->GetDefinition("StartANewDiscussion")."</a>", 1, 1);
}

// BUILD THE MAIN MENU
$Menu->AddTab($Context->GetDefinition("Discussions"), "discussions", "./", "DiscussionsTab");
if ($Configuration["USE_CATEGORIES"]) $Menu->AddTab($Context->GetDefinition("Categories"), "categories", "categories.php", "CategoriesTab");
$Menu->AddTab($Context->GetDefinition("Search"), "search", "search.php", "SearchTab");
if ($Context->Session->UserID > 0) {
	// Make sure they should be seeing the settings tab
	$RequiredPermissions = array("PERMISSION_CHECK_FOR_UPDATES",
		"PERMISSION_APPROVE_APPLICANTS",
		"PERMISSION_MANAGE_REGISTRATION",
		"PERMISSION_ADD_ROLES",
		"PERMISSION_EDIT_ROLES",
		"PERMISSION_REMOVE_ROLES",
		"PERMISSION_ADD_CATEGORIES",
		"PERMISSION_EDIT_CATEGORIES",
		"PERMISSION_REMOVE_CATEGORIES",
		"PERMISSION_SORT_CATEGORIES",
		"PERMISSION_CHANGE_APPLICATION_SETTINGS",
		"PERMISSION_MANAGE_EXTENSIONS",
		"PERMISSION_MANAGE_LANGUAGE",
		"PERMISSION_MANAGE_STYLES");
		
	$RequiredPermissionsCount = count($RequiredPermissions);
	$i = 0;
	for ($i = 0; $i < $RequiredPermissionsCount; $i++) {
		if ($Context->Session->User->Permission($RequiredPermissions[$i])) {
			$Menu->AddTab($Context->GetDefinition("Settings"), "settings", "settings.php", "SettingsTab");
			break;
		}
	}

	// Add the account tab   
	$Menu->AddTab($Context->GetDefinition("Account"), "account", "account.php", "AccountTab");
}

// INCLUDE EXTENSIONS
include($Configuration["APPLICATION_PATH"]."conf/extensions.php");
?>