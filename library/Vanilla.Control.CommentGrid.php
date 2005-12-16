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
* Description: The CommentGrid control displays comments for a discussion in a paging format.
*/
// Displays a comment grid
class CommentGrid extends Control {
	var $PageJump;
	var $CurrentPage;
	var $Discussion;
	var $CommentData;
	var $CommentDataCount;
	var $pl;
	var $ShowForm;
	
	function CommentGrid(&$Context, $DiscussionManager, $DiscussionID) {
		$this->Name = "CommentGrid";
		$this->Control($Context);
		$this->CurrentPage = ForceIncomingInt("page", 1);
		
		// Load information about this discussion
		$RecordDiscussionView = 1;
		if ($this->Context->Session->UserID == 0) $RecordDiscussionView = 0;
      $this->Discussion = $DiscussionManager->GetDiscussionById($DiscussionID, $RecordDiscussionView);
		if ($this->Discussion) {
			$this->Discussion->FormatPropertiesForDisplay();
			if (!$this->Discussion->Active && !$this->Context->Session->User->Permission("PERMISSION_HIDE_DISCUSSIONS")) {
				$this->Discussion = false;
				$this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrDiscussionNotFound"));
			}
		}
		
		if ($this->Context->WarningCollector->Count() > 0) {
			$this->CommentData = false;
			$this->CommentDataCount = 0;
		} else {
			// Load the data
			$CommentManager = $Context->ObjectFactory->NewContextObject($Context, "CommentManager");
			$this->CommentData = $CommentManager->GetCommentList($this->Context->Configuration["COMMENTS_PER_PAGE"], $this->CurrentPage, $DiscussionID);
			$this->CommentDataCount = $CommentManager->GetCommentCount($DiscussionID);
		}
		
		// Set up the pagelist
		$this->pl = $this->Context->ObjectFactory->NewContextObject($this->Context, "PageList", "DiscussionID", $this->Discussion->DiscussionID);
		// $this->pl->Parameters = $this->Context->ObjectFactory->NewObject($this->Context, "UrlBuilder", "", $this->Context->Configuration["URL_BUILDING_METHOD"], $this->Context->Configuration["REWRITE_DISCUSSIONS"]);
		$this->pl->NextText = $this->Context->GetDefinition("Next");
		$this->pl->PreviousText = $this->Context->GetDefinition("Previous");
		$this->pl->CssClass = "PageList";
		$this->pl->TotalRecords = $this->CommentDataCount;
		$this->pl->CurrentPage = $this->CurrentPage;
		$this->pl->RecordsPerPage = $this->Context->Configuration["COMMENTS_PER_PAGE"];
		$this->pl->PagesToDisplay = 10;
		$this->pl->PageParameterName = "page";
		$this->pl->DefineProperties();


		$this->ShowForm = 0;
		if ($this->Context->Session->UserID > 0
			&& ($this->pl->PageCount == 1 || $this->pl->PageCount == $this->CurrentPage)
			&& ((!$this->Discussion->Closed && $this->Discussion->Active) || $this->Context->Session->User->Permission("PERMISSION_ADD_COMMENTS_TO_CLOSED_DISCUSSION"))
			&& $this->CommentData) $this->ShowForm = 1;
			
		$this->CallDelegate("Constructor");
	}
	
   function Render() {
		$this->CallDelegate("PreRender");
		include($this->Context->Configuration["THEME_PATH"]."templates/comments.php");		
		$this->CallDelegate("PostRender");
   }	
}

?>