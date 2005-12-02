<?php
/*
Extension Name: Quick Keys
Extension Url: http://lussumo.com/docs/
Description: Allows users to use ALT+[KeyCode] to access various pages of Vanilla.
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
if (in_array($Context->SelfUrl, array("account.php", "categories.php", "comments.php", "index.php", "post.php", "search.php", "settings.php")) && $Context->Session->UserID > 0) {
   if (@$Menu && @$Context->Session->User) {
      if ($Context->Session->User->Preference("UseQuickKeys")) {
         // Clear out existing tabs and put in the new quickkey tabs
         $Menu->ClearTabs();
         $Menu->AddTab($Context->GetDefinition("Discussions_QuickKey"), "discussions", "./", "DiscussionsTab", "accesskey=\"d\"");
         if ($this->Context->Configuration["USE_CATEGORIES"]) $Menu->AddTab($Context->GetDefinition("Categories_QuickKey"), "categories", "categories.php", "CategoriesTab", "accesskey=\"c\"");
         $Menu->AddTab($Context->GetDefinition("Search_QuickKey"), "search", "search.php", "SearchTab", "accesskey=\"s\"");

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
			for ($i = 0; $i < $RequiredPermissionsCount; $i++) {
				if ($Context->Session->User->Permission($RequiredPermissions[$i])) {
					$Menu->AddTab($Context->GetDefinition("Settings_QuickKey"), "settings", "settings.php", "SettingsTab", "accesskey=\"e\"");
					break;
				}
			}

         $Menu->AddTab($Context->GetDefinition("Account_QuickKey"), "account", "account.php", "AccountTab", "accesskey=\"a\"");
         
         // Set up the "Start a new discussion" button
         $Panel->NewDiscussionText = $Context->GetDefinition("StartANewDiscussion_Quickkey");
         $Panel->NewDiscussionAttributes = " accesskey=\"n\"";
      }
   }
}
// Add the QuickKeys setting to the forum preferences form
if ($Context->SelfUrl == "account.php" && $Context->Session->UserID > 0) {
	$PostBackAction = ForceIncomingString("PostBackAction", "");
	if ($PostBackAction == "Functionality") {
      $QuickKeysOption = "<h2>".$Context->GetDefinition("Other")."</h2>
         <div class=\"InputBlock\">
				<div class=\"CheckBox\">".GetDynamicCheckBox("UseQuickKeys", 1, $Context->Session->User->Preference("UseQuickKeys"), "PanelSwitch('UseQuickKeys', 1);", $Context->GetDefinition("UseQuickKeys"))."</div>
         </div>";
		$Context->ObjectFactory->AddControlString("FunctionalityForm", "RenderCustomPreferences", $QuickKeysOption);
	}
}

?>