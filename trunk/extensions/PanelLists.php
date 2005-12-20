<?php
/*
Extension Name: Panel Lists
Extension Url: http://lussumo.com/docs/
Description: Allows users to display various discussion lists in the control panel. Only compatible with Vanilla >= 0.9.3
Version: 1.0
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

$Context->Dictionary["Bookmarks"] = "Bookmarks";
$Context->Dictionary["YourDiscussions"] = "Your Discussions";
$Context->Dictionary["History"] = "History";
$Context->Dictionary["Private"] = "Private";
$Context->Dictionary["DisplayBookmarks"] = "Display your bookmarks in the control panel";
$Context->Dictionary["DisplayPrivateDiscussions"] = "Display your private discussions in the control panel";
$Context->Dictionary["DisplayYourDiscussions"] = "Display your discussions in the control panel";
$Context->Dictionary["DisplayBrowsingHistory"] = "Display your browsing history in the control panel";


if ($Context->SelfUrl == "account.php") {
   $Context->AddToDelegate("PreferencesForm", "PreRender", "AddPanelLists");
   function AddPanelLists(&$PreferencesForm) {
      $PreferencesForm->AddPreference("ControlPanel", "DisplayBookmarks", "ShowBookmarks");
      $PreferencesForm->AddPreference("ControlPanel", "DisplayYourDiscussions", "ShowRecentDiscussions");
      $PreferencesForm->AddPreference("ControlPanel", "DisplayBrowsingHistory", "ShowBrowsingHistory");
		if ($PreferencesForm->Context->Configuration["ENABLE_WHISPERS"]) $PreferencesForm->AddPreference("ControlPanel", "DisplayPrivateDiscussions", "ShowPrivateDiscussions");
   }
}

if (in_array($Context->SelfUrl, array("index.php", "comments.php"))) {
   function AddBookmarksToPanel(&$Context, &$Panel, &$DiscussionManager, $OptionalDiscussionID = "0") {
      
      $sReturn = "";
      // Check for a template file first (allows people to customize this if need be)
      if (!@include($Context->Configuration["THEME_PATH"]."templates/panel_lists_bookmarks.php")) {
         if ($Context->Session->User->Preference("ShowBookmarks")) {
            $UserBookmarks = $DiscussionManager->GetBookmarkedDiscussionsByUserID($Context->Session->UserID, $Context->Configuration["PANEL_BOOKMARK_COUNT"], $OptionalDiscussionID);
            $Count = $Context->Database->RowCount($UserBookmarks);
            $OtherBookmarksExist = 0;
            $ThisDiscussionIsBookmarked = 0;
            if ($Count > 0) {
               $Discussion = $Context->ObjectFactory->NewObject($Context, "Discussion");
               while ($Row = $Context->Database->GetRow($UserBookmarks)) {
                  $Discussion->Clear();
                  $Discussion->GetPropertiesFromDataSet($Row, $Context->Configuration);
                  $Discussion->FormatPropertiesForDisplay();
                  if ($Discussion->DiscussionID != $OptionalDiscussionID) $OtherBookmarksExist = 1;
                  if ($Discussion->DiscussionID == $OptionalDiscussionID && $Discussion->Bookmarked) $ThisDiscussionIsBookmarked = 1;
                  $sReturn .= "<li id=\"Bookmark_".$Discussion->DiscussionID."\"".(($Discussion->DiscussionID == $OptionalDiscussionID && !$Discussion->Bookmarked)?" style=\"display: none;\"":"")."><a class=\"PanelLink\" href=\"".GetUrl($Context->Configuration, "comments.php", "", "DiscussionID", $Discussion->DiscussionID)."\">".$Discussion->Name."</a>";
                  if ($Discussion->NewComments > 0) $sReturn .= " <small><strong>".$Discussion->NewComments." ".$Context->GetDefinition("New")."</strong></small>";
                  $sReturn .= "</li>";
               }
               if ($Count >= $Context->Configuration["PANEL_BOOKMARK_COUNT"]) {
                  $sReturn .= "<li><a class=\"PanelLink\" href=\"".GetUrl($Context->Configuration, "index.php", "", "", "", "", "View=YourBookmarks")."\">".$Context->GetDefinition("ShowAll")."</a></li>";
               }
      
               $sReturn = "<h2 id=\"BookmarkTitle\"".(($OtherBookmarksExist || $ThisDiscussionIsBookmarked)?"":" style=\"display: none;\"").">".$Context->GetDefinition("Bookmarks")."</h2>
               <ul class=\"LinkedList\" id=\"BookmarkList\"".(($OtherBookmarksExist || $ThisDiscussionIsBookmarked)?"":" style=\"display: none;\"").">"
               .$sReturn
               ."</ul>";
      
            }
            $sReturn .= "<form name=\"frmBookmark\" action=\"\"><input type=\"hidden\" name=\"OtherBookmarksExist\" value=\"".$OtherBookmarksExist."\" /></form>";
         } else {
            $sReturn = "<form name=\"frmBookmark\" action=\"\"><input type=\"hidden\" name=\"OtherBookmarksExist\" value=\"1\" /></form>";
         }
      }
      $Panel->AddString($sReturn, 20);
   }
   
   function AddDiscussionsToPanel(&$Context, &$Panel, $DataManager, $GetDataMethod, $MaxRecords, $ListTitle, $UrlAction, $PermissionRequirement) {
      if ($PermissionRequirement && $Context->Session->UserID > 0) {
         $Data = $DataManager->$GetDataMethod($Context->Session->UserID, $MaxRecords);
         $ActualRecords = $Context->Database->RowCount($Data);
         if ($ActualRecords > 0) {
				$Panel->AddList($ListTitle, 21);
            $Discussion = $Context->ObjectFactory->NewObject($Context, "Discussion");
            while ($Row = $Context->Database->GetRow($Data)) {
               $Discussion->Clear();
               $Discussion->GetPropertiesFromDataSet($Row, $Context->Configuration);
               $Discussion->FormatPropertiesForDisplay();
               $Suffix = "";
               if ($Discussion->NewComments > 0) $Suffix .= $Discussion->NewComments." ".$Context->GetDefinition("New");
               $Panel->AddListItem($ListTitle, $Discussion->Name, GetUrl($Context->Configuration, "comments.php", "", "DiscussionID", $Discussion->DiscussionID), $Suffix);
            }
            if ($ActualRecords >= $MaxRecords) $Panel->AddListItem($ListTitle, $Context->GetDefinition("ShowAll"), GetUrl($Context->Configuration, "index.php", "", "", "", "", "View=".$UrlAction));
         }
      }
   }
   
	$DiscussionManager = $Context->ObjectFactory->NewContextObject($Context, "DiscussionManager");
	AddBookmarksToPanel($Context, $Panel, $DiscussionManager, ForceIncomingInt("DiscussionID", 0));
	AddDiscussionsToPanel($Context, $Panel, $DiscussionManager, "GetDiscussionsByUserID",$Configuration["PANEL_USER_DISCUSSIONS_COUNT"], $Context->GetDefinition("YourDiscussions"), "Recent", $Context->Session->User->Preference("ShowRecentDiscussions"));
	AddDiscussionsToPanel($Context, $Panel, $DiscussionManager, "GetViewedDiscussionsByUserID", $Configuration["PANEL_HISTORY_COUNT"], $Context->GetDefinition("History"), "History", $Context->Session->User->Preference("ShowBrowsingHistory"));
   if ($Configuration["ENABLE_WHISPERS"] && $Context->Session->User->Preference("ShowPrivateDiscussions")) {
      AddDiscussionsToPanel($Context, $Panel, $DiscussionManager, "GetPrivateDiscussionsByUserID", $Configuration["PANEL_PRIVATE_COUNT"], $Context->GetDefinition("Private"), "Private", $Context->Session->User->Preference("ShowPrivateDiscussions"));
	}
	
}

   
?>