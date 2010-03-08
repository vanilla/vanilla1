<?php 
/*
Extension Name: Panel Lists
Extension Url: http://lussumo.com/docs/
Description: Allows users to display various discussion lists in the control panel. Only compatible with Vanilla >= 0.9.3
Version: 1.2
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/


Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Vanilla.
Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code for Vanilla is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com

You should cut & paste these language definitions into your
conf/your_language.php file (replace "your_language" with your chosen language,
of course):
*/

$Context->SetDefinition("Bookmarks", "Bookmarks");
$Context->SetDefinition("YourDiscussions", "Your Discussions");
$Context->SetDefinition("BrowsingHistory", "Browsing History");
$Context->SetDefinition("WhisperedDiscussions", "Whispered Discussions");
$Context->SetDefinition("DisplayBookmarks", "Display your bookmarks in the side panel");
$Context->SetDefinition("DisplayPrivateDiscussions", "Display your private discussions in the side panel");
$Context->SetDefinition("DisplayYourDiscussions", "Display your discussions in the side panel");
$Context->SetDefinition("DisplayBrowsingHistory", "Display your browsing history in the side panel");
$Context->SetDefinition('XNew', '//1 new');
$Context->SetDefinition('MaxBookmarksInPanel', 'Max bookmarks in panel');
$Context->SetDefinition('MaxPrivateInPanel', 'Max private in panel');
$Context->SetDefinition('MaxBrowsingHistoryInPanel', 'Max history in panel');
$Context->SetDefinition('MaxDiscussionsInPanel', 'Max own discussions in panel');


// Set up some default configuration options
CreateArrayEntry($Configuration, 'PANEL_BOOKMARK_COUNT', 10);
CreateArrayEntry($Configuration, 'PANEL_PRIVATE_COUNT', 5);
CreateArrayEntry($Configuration, 'PANEL_HISTORY_COUNT', 5);
CreateArrayEntry($Configuration, 'PANEL_USER_DISCUSSIONS_COUNT', 5);

if ($Context->SelfUrl == "account.php") {
   $Context->AddToDelegate("PreferencesForm", "PreRender", "PreferencesForm_AddPanelLists");
   function PreferencesForm_AddPanelLists(&$PreferencesForm) {
      $PreferencesForm->AddPreference("ControlPanel", "DisplayBookmarks", "ShowBookmarks");
      $PreferencesForm->AddPreference("ControlPanel", "DisplayYourDiscussions", "ShowRecentDiscussions");
      $PreferencesForm->AddPreference("ControlPanel", "DisplayBrowsingHistory", "ShowBrowsingHistory");
		if ($PreferencesForm->Context->Configuration["ENABLE_WHISPERS"]) $PreferencesForm->AddPreference("ControlPanel", "DisplayPrivateDiscussions", "ShowPrivateDiscussions");
   }
}

if (in_array($Context->SelfUrl, array("index.php", "comments.php"))) {
   function AddBookmarksToPanel(&$Context, &$Panel, &$DiscussionManager, $OptionalDiscussionID = "0") {
      
      $sReturn = "";
		if ($Context->Session->User->Preference("ShowBookmarks")) {
			$UserBookmarks = $DiscussionManager->GetBookmarkedDiscussionsByUserID($Context->Session->UserID, $Context->Configuration["PANEL_BOOKMARK_COUNT"], $OptionalDiscussionID);
			$Count = $Context->Database->RowCount($UserBookmarks);
			$OtherBookmarksExist = 0;
			$ThisDiscussionIsBookmarked = 0;
			if ($Count > 0) {
				$Discussion = $Context->ObjectFactory->NewContextObject($Context, "Discussion");
				while ($Row = $Context->Database->GetRow($UserBookmarks)) {
					$Discussion->Clear();
					$Discussion->GetPropertiesFromDataSet($Row, $Context->Configuration);
					$Discussion->FormatPropertiesForDisplay();
					if ($Discussion->DiscussionID != $OptionalDiscussionID) $OtherBookmarksExist = 1;
					if ($Discussion->DiscussionID == $OptionalDiscussionID && $Discussion->Bookmarked) $ThisDiscussionIsBookmarked = 1;
					$sReturn .= "<li id=\"Bookmark_".$Discussion->DiscussionID."\"".(($Discussion->DiscussionID == $OptionalDiscussionID && !$Discussion->Bookmarked)?" style=\"display: none;\"":"")."><a href=\"".GetUnreadQuerystring($Discussion, $Context->Configuration, $Context->Session->User->Preference('JumpToLastReadComment'))."\">".$Discussion->Name;
					if ($Discussion->NewComments > 0) $sReturn .= " <span>".str_replace('//1', $Discussion->NewComments, $Context->GetDefinition("XNew"))."</span>";
					$sReturn .= "</a></li>";
				}
				if ($Count >= $Context->Configuration["PANEL_BOOKMARK_COUNT"]) {
					$sReturn .= "<li><a href=\"".GetUrl($Context->Configuration, "index.php", "", "", "", "", "View=Bookmarks")."\">".$Context->GetDefinition("ShowAll")."</a></li>";
				}
	
				$sReturn = "<ul><li><h2 id=\"BookmarkTitle\"".(($OtherBookmarksExist || $ThisDiscussionIsBookmarked)?"":" style=\"display: none;\"").">".$Context->GetDefinition("Bookmarks")."</h2>
				<ul id=\"BookmarkList\"".(($OtherBookmarksExist || $ThisDiscussionIsBookmarked)?"":" style=\"display: none;\"").">"
				.$sReturn
				."</ul></li></ul>";
	
			}
			$sReturn .= "<form id=\"frmBookmark\" action=\"\"><div class=\"Hidden\"><input type=\"hidden\" name=\"OtherBookmarksExist\" value=\"".$OtherBookmarksExist."\" /></div></form>";
		} else {
			$sReturn = "<form id=\"frmBookmark\" action=\"\"><div class=\"Hidden\"><input type=\"hidden\" name=\"OtherBookmarksExist\" value=\"1\" /></div></form>";
		}
      $Panel->AddString($sReturn, 20);
   }
   
   function AddDiscussionsToPanel(&$Context, &$Panel, $DataManager, $GetDataMethod, $MaxRecords, $ListTitle, $UrlAction, $PermissionRequirement) {
      if ($PermissionRequirement && $Context->Session->UserID > 0) {
         $Data = $DataManager->$GetDataMethod($Context->Session->UserID, $MaxRecords);
         $ActualRecords = $Context->Database->RowCount($Data);
         if ($ActualRecords > 0) {
				$Panel->AddList($ListTitle, 21);
            $Discussion = $Context->ObjectFactory->NewContextObject($Context, "Discussion");
            while ($Row = $Context->Database->GetRow($Data)) {
               $Discussion->Clear();
               $Discussion->GetPropertiesFromDataSet($Row, $Context->Configuration);
               $Discussion->FormatPropertiesForDisplay();
               $Suffix = "";
               if ($Discussion->NewComments > 0) $Suffix .= str_replace('//1', $Discussion->NewComments, $Context->GetDefinition("XNew"));
               $Panel->AddListItem($ListTitle, $Discussion->Name, GetUnreadQuerystring($Discussion, $Context->Configuration, $Context->Session->User->Preference('JumpToLastReadComment')), $Suffix);
            }
            if ($ActualRecords >= $MaxRecords) $Panel->AddListItem($ListTitle, $Context->GetDefinition("ShowAll"), GetUrl($Context->Configuration, "index.php", "", "", "", "", "View=".$UrlAction));
         }
      }
   }
   
	$DiscussionManager = $Context->ObjectFactory->NewContextObject($Context, "DiscussionManager");
	AddBookmarksToPanel($Context, $Panel, $DiscussionManager, ForceIncomingInt("DiscussionID", 0));
	AddDiscussionsToPanel($Context, $Panel, $DiscussionManager, "GetDiscussionsByUserID",$Configuration["PANEL_USER_DISCUSSIONS_COUNT"], $Context->GetDefinition("YourDiscussions"), "YourDiscussions", $Context->Session->User->Preference("ShowRecentDiscussions"));
	AddDiscussionsToPanel($Context, $Panel, $DiscussionManager, "GetViewedDiscussionsByUserID", $Configuration["PANEL_HISTORY_COUNT"], $Context->GetDefinition('BrowsingHistory'), "History", $Context->Session->User->Preference("ShowBrowsingHistory"));
   if ($Configuration["ENABLE_WHISPERS"] && $Context->Session->User->Preference("ShowPrivateDiscussions")) {
      AddDiscussionsToPanel($Context, $Panel, $DiscussionManager, "GetPrivateDiscussionsByUserID", $Configuration["PANEL_PRIVATE_COUNT"], $Context->GetDefinition("WhisperedDiscussions"), "Private", $Context->Session->User->Preference("ShowPrivateDiscussions"));
	}
	
}

if ($Context->SelfUrl == "comments.php") {
	if ($Context->Session->User->Preference("ShowBookmarks")) {
		// Include the js required to remove/add the discussions to the panel when
		// items are un/bookmarked
		$Head->AddScript('extensions/PanelLists/functions.js');
		$Context->PassThruVars['SetBookmarkOnClick'] .= ' SetBookmarkList('.ForceIncomingInt('DiscussionID', 0).');';
	}
}

// Apply discussion filters
if ($Context->SelfUrl == 'index.php') {
   $View = ForceIncomingString("View", "");
   switch ($View) {
      case "Bookmarks":
         $Context->PageTitle = $Context->GetDefinition("Bookmarks");
         function DiscussionManager_FilterToBookmarks(&$DiscussionManager) {
            $s = &$DiscussionManager->DelegateParameters['SqlBuilder'];
            $s->AddWhere('b', 'DiscussionID', 't', 'DiscussionID', '=', 'and', '', 0, 1);
            $s->AddWhere('b', 'UserID', '', $DiscussionManager->Context->Session->UserID, '=');
            $s->EndWhereGroup();
         }
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionList",
            "DiscussionManager_FilterToBookmarks");
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionCount",
            "DiscussionManager_FilterToBookmarks");
         break;
      
      case "YourDiscussions":
         $Context->PageTitle = $Context->GetDefinition("YourDiscussions");
         function DiscussionManager_FilterToYourDiscussions(&$DiscussionManager) {
            $s = &$DiscussionManager->DelegateParameters['SqlBuilder'];
      		$s->AddWhere('t', 'AuthUserID', '', $DiscussionManager->Context->Session->UserID, '=');            
         }
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionList",
            "DiscussionManager_FilterToYourDiscussions");
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionCount",
            "DiscussionManager_FilterToYourDiscussions");
         break;
      
      case "Private":
         $Context->PageTitle = $Context->GetDefinition("WhisperedDiscussions");
         function DiscussionManager_FilterToPrivateDiscussions(&$DiscussionManager) {
            $s = &$DiscussionManager->DelegateParameters['SqlBuilder'];
            $s->AddWhere('t', 'WhisperUserID', '', $DiscussionManager->Context->Session->UserID, '=', 'and', '', 0, 1);
            $s->AddWhere('t', 'AuthUserID', '', $DiscussionManager->Context->Session->UserID, '=', 'or', '', 0, 1);
            $s->AddWhere('t', 'WhisperUserID', '', 0, '>', 'and');
            $s->EndWhereGroup();
            $s->EndWhereGroup();
         }
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionList",
            "DiscussionManager_FilterToPrivateDiscussions");
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionCount",
            "DiscussionManager_FilterToPrivateDiscussions");
         break;
      case "History":
         $Context->PageTitle = $Context->GetDefinition("BrowsingHistory");
         function DiscussionManager_FilterToDiscussionHistory(&$DiscussionManager) {
            $s = &$DiscussionManager->DelegateParameters['SqlBuilder'];
				$s->AddWhere('utw', 'UserID', '', $DiscussionManager->Context->Session->UserID, '=');
				$s->AddOrderBy('LastViewed', 'utw', 'desc');
         }
         $Context->AddToDelegate("DiscussionManager",
            "PreGetDiscussionList",
            "DiscussionManager_FilterToDiscussionHistory");
				
			function DiscussionManager_FilterToHistoryCount(&$DiscussionManager) {
				$s = &$DiscussionManager->DelegateParameters['SqlBuilder'];
				$s->AddJoin('UserDiscussionWatch', 'utw', 'DiscussionID', 't', 'DiscussionID', 'left join', ' and utw.'.$DiscussionManager->Context->DatabaseColumns['UserDiscussionWatch']['UserID'].' = '.$DiscussionManager->Context->Session->UserID);
				$s->AddWhere('utw', 'UserID', '', $DiscussionManager->Context->Session->UserID, '=');
			}
			$Context->AddToDelegate('DiscussionManager',
				'PreGetDiscussionCount',
				'DiscussionManager_FilterToHistoryCount');				
				
			function DiscussionGrid_ClearPageJump(&$DiscussionGrid) {
				$DiscussionGrid->PageJump = '';
			}
			$Context->AddToDelegate('DiscussionGrid',
				'PreDataLoad',
				'DiscussionGrid_ClearPageJump');
				
         break;
   }   
}

// Add the preferences to the globals form
if ($Context->SelfUrl == 'settings.php') {
	function GlobalsForm_AddPanelFilterCounts(&$GlobalsForm) {
		echo '<li>
            <label for="ddMaxBookmarksInPanel">'.$GlobalsForm->Context->GetDefinition('MaxBookmarksInPanel').'</label>
            ';
            $Selector = $GlobalsForm->Context->ObjectFactory->NewObject($GlobalsForm->Context, 'Select');
            $Selector->CssClass = 'SmallSelect';
            $Selector->Attributes = ' id="ddMaxBookmarksInPanel"';
            $Selector->Name = 'PANEL_BOOKMARK_COUNT';
            for ($i = 3; $i < 11; $i++) {
               $Selector->AddOption($i, $i);
            }
            for ($i = 15; $i < 51; $i++) {
               $Selector->AddOption($i, $i);
               $i += 4;
            }
            $Selector->SelectedValue = $GlobalsForm->ConfigurationManager->GetSetting('PANEL_BOOKMARK_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxPrivateInPanel">'.$GlobalsForm->Context->GetDefinition('MaxPrivateInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_PRIVATE_COUNT';
            $Selector->Attributes = ' id="ddMaxPrivateInPanel"';
            $Selector->SelectedValue = $GlobalsForm->ConfigurationManager->GetSetting('PANEL_PRIVATE_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxBrowsingHistoryInPanel">'.$GlobalsForm->Context->GetDefinition('MaxBrowsingHistoryInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_HISTORY_COUNT';
            $Selector->Attributes = ' id="ddMaxBrowsingHistoryInPanel"';
            $Selector->SelectedValue = $GlobalsForm->ConfigurationManager->GetSetting('PANEL_HISTORY_COUNT');
            echo $Selector->Get().'
         </li>
         <li>
            <label for="ddMaxDiscussionsInPanel">'.$GlobalsForm->Context->GetDefinition('MaxDiscussionsInPanel').'</label>
            ';
            $Selector->Name = 'PANEL_USER_DISCUSSIONS_COUNT';
            $Selector->Attributes = ' id="ddMaxDiscussionsInPanel"';
            $Selector->SelectedValue = $GlobalsForm->ConfigurationManager->GetSetting('PANEL_USER_DISCUSSIONS_COUNT');
            echo $Selector->Get().'
         </li>';
	}
	
	$Context->AddToDelegate('GlobalsForm',
		'PostCounts',
		'GlobalsForm_AddPanelFilterCounts');

}  
?>