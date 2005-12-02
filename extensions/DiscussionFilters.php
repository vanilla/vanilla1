<?php
/*
Extension Name: Discussion Filters
Extension Url: http://lussumo.com/docs/
Description: Adds quick discussion filters to the panel like "my bookmarks" or "my discussions" or "unread discussions"
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/
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

if (in_array($Context->SelfUrl, array("categories.php", "comments.php", "index.php", "post.php")) && $Context->Session->UserID > 0) {
   
   $DiscussionFilters = $Context->GetDefinition("DiscussionFilters");
   $Panel->AddList($DiscussionFilters, 10);
   if (!$Context->Session->User->Preference("ShowBookmarks")) $Panel->AddListItem($DiscussionFilters, $Context->GetDefinition("BookmarkedDiscussions"), "./?View=Bookmarks", "", "", 10);
   if (!$Context->Session->User->Preference("ShowRecentDiscussions")) $Panel->AddListItem($DiscussionFilters, $Context->GetDefinition("YourDiscussions"), "./?View=YourDiscussions", "", "", 20);
   if ($Configuration["ENABLE_WHISPERS"] && !$Context->Session->User->Preference("ShowPrivateDiscussions")) $Panel->AddListItem($Context->GetDefinition("DiscussionFilters"), $Context->GetDefinition("PrivateDiscussions"), "./?View=Private", "", "", 30);
   
   
   
   
}
?>