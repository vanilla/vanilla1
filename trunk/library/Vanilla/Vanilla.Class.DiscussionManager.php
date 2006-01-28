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
* Description: Discussion Management classes
*/

class DiscussionManager extends Delegation {
	var $Name;				// The name of this class
   var $Context;			// The context object that contains all global objects (database, error manager, warning collector, session, etc)
	
	function DiscussionManager(&$Context) {
		$this->Name = 'DiscussionManager';
		$this->Delegation($Context);
	}	

	function GetBookmarkedDiscussionsByUserID($UserID, $RecordsToReturn = '0', $IncludeDiscussionID = '0') {
		$IncludeDiscussionID = ForceInt($IncludeDiscussionID, 0);
		$UserID = ForceInt($UserID, 0);
		$RecordsToReturn = ForceInt($RecordsToReturn, 0);
		
		$s = $this->GetDiscussionBuilder();
		if (!$this->Context->Session->User->Permission('PERMISSION_HIDE_DISCUSSIONS') || !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) $s->AddWhere('t.Active', '1', '=');
		$s->AddWhere('b.DiscussionID', 't.DiscussionID', '=', 'and', '', 0, 1);
		$s->AddWhere('b.UserID', $UserID, '=');
		$s->EndWhereGroup();
		$s->AddWhere('t.DiscussionID', $IncludeDiscussionID, '=', 'or');
		$s->AddOrderBy('DateLastActive', 't', 'desc');
		if ($RecordsToReturn > 0) $s->AddLimit(0, $RecordsToReturn);
		return $this->Context->Database->Select($s, $this->Name, 'GetBookmarkedDiscussionsByUserID', 'An error occurred while retrieving discussions.');
	}
	
	// Returns a SqlBuilder object with all of the Discussion properties already defined in the select
	function GetDiscussionBuilder($s = 0) {
		if (!$s) $s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
		$s->SetMainTable('Discussion', 't');
		$s->AddSelect(array('DiscussionID', 'FirstCommentID', 'AuthUserID', 'WhisperUserID', 'Active', 'Closed', 'Sticky', 'Name', 'DateCreated', 'LastUserID', 'DateLastActive', 'CountComments', 'CategoryID'), 't');

		// Get author data
		$s->AddJoin('User', 'u', 'UserID', 't', 'AuthUserID', 'left join');
		$s->AddSelect('Name', 'u', 'AuthUsername');
		// These fullnames are not used and are slowing things down
		// $s->AddSelect('FirstName', 'u', 'AuthFullName', 'concat', '' ',u.LastName');

		// Get last poster data
		$s->AddJoin('User', 'lu', 'UserID', 't', 'LastUserID', 'left join');
		$s->AddSelect('Name', 'lu', 'LastUsername');
		// $s->AddSelect('FirstName', 'lu', 'LastFullName', 'concat', '' ',lu.LastName');
      
		if ($this->Context->Configuration['ENABLE_WHISPERS']) {
         // Get Whisper user data
         $s->AddJoin('User', 'wt', 'UserID', 't', 'WhisperUserID', 'left join');
         $s->AddSelect('Name', 'wt', 'WhisperUsername');
			$s->AddGroupBy('DiscussionID', 't');
         
         // Get data on the last user to send a whisper (to the current user) in the discussion
         if ($this->Context->Session->User->Permission('PERMISSION_VIEW_ALL_WHISPERS')) {
            // Get the counts (grouped - hence the need to move the 'whisper to' and 'whisper from' values to the Discussion table for admins).
            // Select 'whisper from' and 'whisper to' columns from the Discussion table
            $s->AddJoin('DiscussionUserWhisperFrom', 'tuwf', 'DiscussionID', 't', 'DiscussionID', 'left join');
            $s->AddJoin('User', 'wluf', 'UserID', 't', 'WhisperFromLastUserID', 'left join');
            $s->AddJoin('User', 'wlut', 'UserID', 't', 'WhisperToLastUserID', 'left join');
            $s->AddSelect(array('WhisperFromLastUserID', 'WhisperToLastUserID'), 't');
            $s->AddSelect('DateLastWhisper', 't', 'WhisperFromDateLastActive');
            $s->AddSelect('DateLastWhisper', 't', 'WhisperToDateLastActive');
            // Get the total whisper from count
            $s->AddSelect('TotalWhisperCount', 't', 'CountWhispersFrom');
            // Count the whispers to (admin's see all)
            $s->AddSelect('0', '', 'CountWhispersTo');
         } else {
            // Select 'whisper from' columns from the user-specific tables         
            // Get data on the last user to receive a whisper (for the current, viewing user)
            $s->AddJoin('DiscussionUserWhisperFrom', 'tuwf', 'DiscussionID and tuwf.WhisperFromUserID = '.$this->Context->Session->UserID, 't', 'DiscussionID', 'left join');
            $s->AddJoin('User', 'wluf', 'UserID', 'tuwf', 'LastUserID', 'left join');
            $s->AddSelect('LastUserID', 'tuwf', 'WhisperFromLastUserID');
            $s->AddSelect('DateLastActive', 'tuwf', 'WhisperFromDateLastActive');
            // Get the total whisper from count
            $s->AddSelect('CountWhispers', 'tuwf', 'CountWhispersFrom');
            
            // Select 'whisper to' columns from the user specific tables
            // Get data on the last user to send a whisper (for the current, viewing user)
            $s->AddJoin('DiscussionUserWhisperTo', 'tuwt', 'DiscussionID and tuwt.WhisperToUserID = '.$this->Context->Session->UserID, 't', 'DiscussionID', 'left join');
            $s->AddJoin('User', 'wlut', 'UserID', 'tuwt', 'LastUserID', 'left join');
            $s->AddSelect('LastUserID', 'tuwt', 'WhisperToLastUserID');
            $s->AddSelect('DateLastActive', 'tuwt', 'WhisperToDateLastActive');
            // Count the whispers to
            $s->AddSelect('CountWhispers', 'tuwt', 'CountWhispersTo');
         }
         
         // Now that the wluf and wlut tables are defined, assign the whisper names
         $s->AddSelect('Name', 'wluf', 'WhisperFromLastUsername');
         $s->AddSelect('Name', 'wlut', 'WhisperToLastUsername');
		}
		
		// Get category data
		$s->AddJoin('Category', 'c', 'CategoryID', 't', 'CategoryID', 'left join');
		$s->AddSelect('Name', 'c', 'Category');
		
		// Limit to roles with access to this category
      if ($this->Context->Session->UserID > 0) {
			$s->AddJoin('CategoryRoleBlock', 'crb', 'CategoryID and crb.RoleID = '.$this->Context->Session->User->RoleID, 't', 'CategoryID', 'left join');
		} else {
			$s->AddJoin('CategoryRoleBlock', 'crb', 'CategoryID and crb.RoleID = 1', 't', 'CategoryID', 'left join');
		}
		// coalesce seems to be slowing things down
		// $s->AddWhere('coalesce(crb.Blocked, 0)', '0', '=', 'and', '', 0, 0);
      $s->AddWhere('crb.Blocked', '0', '=', 'and', '', 1, 1);
      $s->AddWhere('crb.Blocked', '0', '=', 'or', '', 0, 0);
		$s->AddWhere('crb.Blocked', 'null', 'is', 'or', '', 0, 0);
		$s->EndWhereGroup();
		
		// Bookmark data
		$s->AddJoin('UserBookmark', 'b', 'DiscussionID and b.UserID = '.$this->Context->Session->UserID, 't', 'DiscussionID', 'left join');
		$s->AddSelect('DiscussionID is not null', 'b', 'Bookmarked');
		
		// Discussion watch data for the current user
		$s->AddJoin('UserDiscussionWatch', 'utw', 'DiscussionID and utw.UserID = '.$this->Context->Session->UserID, 't', 'DiscussionID', 'left join');
		$s->AddSelect('LastViewed', 'utw');
		$s->AddSelect('CountComments', 'utw', 'LastViewCountComments', 'coalesce', '0');
		// $s->AddGroupBy('DiscussionID', 't');
      
		$this->DelegateParameters['SqlBuilder'] = &$s;
		$this->CallDelegate('PostGetDiscussionBuilder');

		return $s;
	}
	
	function GetDiscussionById($DiscussionID, $RecordDiscussionView = '0') {
		$RecordDiscussionView = ForceBool($RecordDiscussionView, 0);
		$Discussion = $this->Context->ObjectFactory->NewObject($this->Context, 'Discussion');
		$s = $this->GetDiscussionBuilder();
		$s->AddWhere('t.DiscussionID', $DiscussionID, '=');
		$this->GetDiscussionWhisperFilter($s);

		$result = $this->Context->Database->Select($s, $this->Name, 'GetDiscussionById', 'An error occurred while attempting to retrieve the requested discussion.');
		if ($this->Context->Database->RowCount($result) == 0) {
			$this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrDiscussionNotFound'));
			$Discussion = false;
		}
		while ($rows = $this->Context->Database->GetRow($result)) {
			$Discussion->GetPropertiesFromDataSet($rows, $this->Context->Configuration);
		}
		if ($Discussion && $RecordDiscussionView) {
			$s->Clear();
			$s->SetMainTable('UserDiscussionWatch', 'utw');
			$s->AddFieldNameValue('CountComments', $Discussion->CountComments);
			$s->AddFieldNameValue('LastViewed', MysqlDateTime());
			// If there was no entry, create a new one
			if ($Discussion->LastViewed == '') {
				$s->AddFieldNameValue('UserID', $this->Context->Session->UserID);
				$s->AddFieldNameValue('DiscussionID', $DiscussionID);
				// fail silently
            $this->Context->Database->Insert($s, $this->Name, 'GetDiscussionById', 'An error occurred while recording this discussion viewing.', 0, 0);
			} else {
				// otherwise update
            $s->AddWhere('UserID', $this->Context->Session->UserID, '=');
            $s->AddWhere('DiscussionID', $Discussion->DiscussionID, '=');
				// fail silently
            $this->Context->Database->Update($s, $this->Name, 'GetDiscussionById', 'An error occurred while recording this discussion viewing.', 0);
			}
		}
		return $this->Context->WarningCollector->Iif($Discussion, false);
	}
	
	function GetDiscussionCount($CategoryID) {
		$CategoryID = ForceInt($CategoryID, 0);
		$TotalNumberOfRecords = 0;
		
		$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
		$s->AddSelect('DiscussionID', 't', 'Count', 'count');
		$s->SetMainTable('Discussion', 't');
		$s->AddJoin('UserBookmark', 'b', 'DiscussionID and b.UserID = '.$this->Context->Session->UserID, 't', 'DiscussionID', 'left join');

		// Limit to roles with access to this category
      if ($this->Context->Session->UserID > 0) {
			$s->AddJoin('CategoryRoleBlock', 'crb', 'CategoryID and crb.RoleID = '.$this->Context->Session->User->RoleID, 't', 'CategoryID', 'left join');
		} else {
			$s->AddJoin('CategoryRoleBlock', 'crb', 'CategoryID and crb.RoleID = 1', 't', 'CategoryID', 'left join');
		}
      $s->AddWhere('crb.Blocked', '0', '=', 'and', '', 1, 1);
      $s->AddWhere('crb.Blocked', '0', '=', 'or', '', 0, 0);
		$s->AddWhere('crb.Blocked', 'null', 'is', 'or', '', 0, 0);
		$s->EndWhereGroup();
		
		$this->DelegateParameters['SqlBuilder'] = &$s;
		$this->CallDelegate('PreGetDiscussionCount');

		// If the current user is not admin only show active Discussions
		if (!$this->Context->Session->User->Permission('PERMISSION_VIEW_HIDDEN_DISCUSSIONS')
			|| !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) {
			$s->AddWhere('t.Active', '1', '=');
		}
		if ($CategoryID > 0) {
			$s->AddWhere('t.CategoryID', $CategoryID, '=');
		} elseif ($this->Context->Session->UserID > 0) {
			$s->AddJoin('CategoryBlock', 'cb', 'CategoryID and cb.UserID = '.$this->Context->Session->UserID, 't', 'CategoryID', 'left join');
			// $s->AddWhere('coalesce(cb.Blocked,0)', 1, '<>');
			$s->AddWhere('cb.Blocked', '0', '=', 'and', '', 1, 1);
			$s->AddWhere('cb.Blocked', '0', '=', 'or', '', 0, 0);
			$s->AddWhere('cb.Blocked', 'null', 'is', 'or', '', 0, 0);
			$s->EndWhereGroup();
		}
		
		$this->GetDiscussionWhisperFilter($s);

		$result = $this->Context->Database->Select($s, $this->Name, 'GetDiscussionCount', 'An error occurred while retrieving Discussion information.');
		while ($rows = $this->Context->Database->GetRow($result)) {
			$TotalNumberOfRecords = $rows['Count'];
		}
		return $TotalNumberOfRecords;
	}
	
	function GetDiscussionList($RowsPerPage, $CurrentPage, $CategoryID) {
		$CategoryID = ForceInt($CategoryID, 0);
		$TotalNumberOfRecords = 0;
		
		if ($RowsPerPage > 0) {
			$CurrentPage = ForceInt($CurrentPage, 1);
			if ($CurrentPage < 1) $CurrentPage == 1;
			$RowsPerPage = ForceInt($RowsPerPage, 50);
			$FirstRecord = ($CurrentPage * $RowsPerPage) - $RowsPerPage;
		}
		
		$s = $this->GetDiscussionBuilder();
		$this->DelegateParameters["SqlBuilder"] = &$s;
		$this->CallDelegate('PreGetDiscussionList');
		
		// If the current user is not admin only show active Discussions
		if (!$this->Context->Session->User->Permission('PERMISSION_VIEW_HIDDEN_DISCUSSIONS')
			|| !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) {
			$s->AddWhere('t.Active', '1', '=');
		}
		if ($CategoryID > 0) {
			$s->AddWhere('t.CategoryID', $CategoryID, '=');
		} elseif ($this->Context->Session->UserID > 0) {
			$s->AddJoin('CategoryBlock', 'cb', 'CategoryID and cb.UserID = '.$this->Context->Session->UserID, 't', 'CategoryID', 'left join');
			// This coalesce seems to be slowing things down
			// $s->AddWhere('coalesce(cb.Blocked,0)', 1, '<>');			
			$s->AddWhere('cb.Blocked', '0', '=', 'and', '', 1, 1);
			$s->AddWhere('cb.Blocked', '0', '=', 'or', '', 0, 0);
			$s->AddWhere('cb.Blocked', 'null', 'is', 'or', '', 0, 0);
			$s->EndWhereGroup();
		}
		$this->GetDiscussionWhisperFilter($s);

		$s->AddOrderBy('Sticky', 't');
		//if ($this->Context->Configuration['ENABLE_WHISPERS']) {
		//	$s->AddOrderBy('greatest(t.DateLastWhisper, t.DateLastActive)', '', 'desc');
		//} else {
			$s->AddOrderBy('t.DateLastActive', '', 'desc');
		//}
		if ($RowsPerPage > 0) $s->AddLimit($FirstRecord, $RowsPerPage);
		return $this->Context->Database->Select($s, $this->Name, 'GetDiscussionList', 'An error occurred while retrieving discussions.');
	}

	function GetDiscussionsByUserID($UserID, $RecordsToReturn = '0') {
		$UserID = ForceInt($UserID, 0);
		$RecordsToReturn = ForceInt($RecordsToReturn, 0);
		
		$s = $this->GetDiscussionBuilder();
		$s->AddWhere('t.AuthUserID', $UserID, '=');
		if (!$this->Context->Session->User->Permission('PERMISSION_HIDE_DISCUSSIONS') || !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) $s->AddWhere('t.Active', '1', '=');
		$this->GetDiscussionWhisperFilter($s);
		$s->AddOrderBy('DateLastActive', 't', 'desc');
		if ($RecordsToReturn > 0) $s->AddLimit(0, $RecordsToReturn);

		return $this->Context->Database->Select($s, $this->Name, 'GetDiscussionsByUserID', 'An error occurred while retrieving discussions.');
	}
	
	function GetDiscussionSearch($RowsPerPage, $CurrentPage, $Search) {
		$s = $this->GetSearchBuilder($Search);
		if (!$this->Context->Session->User->Permission('PERMISSION_HIDE_DISCUSSIONS') || !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) $s->AddWhere('t.Active', '1', '=');
		if ($RowsPerPage > 0) {
			$CurrentPage = ForceInt($CurrentPage, 1);
			if ($CurrentPage < 1) $CurrentPage == 1;
			$RowsPerPage = ForceInt($RowsPerPage, 50);
			$FirstRecord = ($CurrentPage * $RowsPerPage) - $RowsPerPage;
		}		
		if ($RowsPerPage > 0) $s->AddLimit($FirstRecord, $RowsPerPage+1);
      if ($this->Context->Configuration['ENABLE_WHISPERS'] && $this->Context->Session->User->Permission('PERMISSION_VIEW_ALL_WHISPERS')) {
         $s->AddOrderBy('greatest(t.DateLastWhisper, t.DateLastActive)', '', 'desc');
      } else {
			$this->GetDiscussionWhisperFilter($s);
			if ($this->Context->Configuration['ENABLE_WHISPERS']) $s->AddOrderBy('greatest(tuwt.DateLastActive, tuwf.DateLastActive, t.DateLastActive)', '', 'desc');
		}

		return $this->Context->Database->Select($s, $this->Name, 'GetDiscussionSearch', 'An error occurred while retrieving search results.');
	}
	
	function GetDiscussionWhisperFilter(&$SqlBuilder) {
		if (!$this->Context->Configuration['ENABLE_WHISPERS']) {
			// If the user cannot view all whispers, make sure that:
			// if the current topic is a whisper, make sure it is the
			// author or the whisper recipient viewing
			$SqlBuilder->AddWhere('t.WhisperUserID', 0, '=', 'and', '', 1, 1);
			$SqlBuilder->AddWhere('t.WhisperUserID', 0, '=', 'or', '' ,0);
			$SqlBuilder->AddWhere('t.WhisperUserID', 'null', 'is', 'or', '' ,0);
			$SqlBuilder->EndWhereGroup();
		} elseif (!$this->Context->Session->User->Permission('PERMISSION_VIEW_ALL_WHISPERS')) {
			$SqlBuilder->AddWhere('t.WhisperUserID', $this->Context->Session->UserID, '=', 'and', '', 1, 1);
			$SqlBuilder->AddWhere('t.WhisperUserID', $this->Context->Session->UserID, '=', 'or', '' , 0);
			$SqlBuilder->AddWhere('t.AuthUserID', $this->Context->Session->UserID, '=', 'or', '' , 1);
			$SqlBuilder->AddWhere('t.AuthUserID', $this->Context->Session->UserID, '=', 'or', '' , 0);
//			$SqlBuilder->EndWhereGroup();
			$SqlBuilder->AddWhere('t.WhisperUserID', 'null', 'is', 'or', '', 0);
			$SqlBuilder->AddWhere('t.WhisperUserID', '0', '=', 'or', '', 0);
			$SqlBuilder->AddWhere('t.WhisperUserID', '0', '=', 'or', '', 1);
			$SqlBuilder->EndWhereGroup();
		}
	}

	function GetPrivateDiscussionsByUserID($UserID, $RecordsToReturn = '0') {
		$UserID = ForceInt($UserID, 0);
		$RecordsToReturn = ForceInt($RecordsToReturn, 0);
		
		$s = $this->GetDiscussionBuilder();
		if (!$this->Context->Session->User->Permission('PERMISSION_REMOVE_CATEGORIES') || !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) $s->AddWhere('t.Active', '1', '=');
		$s->AddWhere('t.WhisperUserID', $UserID, '=', 'and', '', 0, 1);
		$s->AddWhere('t.AuthUserID', $UserID, '=', 'or', '', 0, 1);
		$s->AddWhere('t.WhisperUserID', 0, '>', 'and');
		$s->EndWhereGroup();
		$s->EndWhereGroup();
		$s->AddOrderBy('DateLastActive', 't', 'desc');
		if ($RecordsToReturn > 0) $s->AddLimit(0, $RecordsToReturn);

		return $this->Context->Database->Select($s, $this->Name, 'GetPrivateDiscussionsByUserID', 'An error occurred while retrieving private discussions.');
	}
	
	function GetSearchBuilder($Search) {
		$Search->FormatPropertiesForDatabaseInput();
		$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlSearch');
		$s = $this->GetDiscussionBuilder($s);
		$s->UserQuery = $Search->Query;
		$s->SearchFields = array('t.Name');
		$s->DefineSearch();
		
		// If the current user is not admin only show active Discussions
		if (!$this->Context->Session->User->Permission('PERMISSION_HIDE_DISCUSSIONS')) $s->AddWhere('t.Active', '1', '=');
		if ($Search->Categories != '') {
			$Cats = explode(',',$Search->Categories);
			$CatCount = count($Cats);
			$s->AddWhere('1', '0', '=', 'and', '', 0, 1);
			$i = 0;
			for ($i = 0; $i < $CatCount; $i++) {
				$s->AddWhere('c.Name', trim($Cats[$i]), '=', 'or');
			}
			$s->EndWhereGroup();			
		}
		if ($Search->AuthUsername != '') $s->AddWhere('u.Name', $Search->AuthUsername, '=');
      if ($this->Context->Configuration['ENABLE_WHISPERS'] && $Search->WhisperFilter) $s->AddWhere('t.WhisperUserID', 0, '>');
		return $s;
	}
	
	function GetViewedDiscussionsByUserID($UserID, $RecordsToReturn = '0') {
		$UserID = ForceInt($UserID, 0);
		$RecordsToReturn = ForceInt($RecordsToReturn, 0);
		
		$s = $this->GetDiscussionBuilder();
		if (!$this->Context->Session->User->Permission('PERMISSION_HIDE_DISCUSSIONS') || !$this->Context->Session->User->Preference('ShowDeletedDiscussions')) $s->AddWhere('t.Active', '1', '=');
		$s->AddWhere('utw.UserID', $UserID, '=');
		$s->AddOrderBy('LastViewed', 'utw', 'desc');
		if ($RecordsToReturn > 0) $s->AddLimit(0, $RecordsToReturn);

		return $this->Context->Database->Select($s, $this->Name, 'GetViewedDiscussionsByUserID', 'An error occurred while retrieving discussions.');
	}
	
	function SaveDiscussion($Discussion) {
		if (!$this->Context->Session->User->Permission('PERMISSION_START_DISCUSSION')) {
			$this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrPermissionStartDiscussions'));
		} else {		
			// If not editing, and the posted discussion count is less than the
			// user's current discussion count, silently skip the posting and
			// redirect as if everything is normal.
			if ($Discussion->DiscussionID == 0 && $Discussion->UserDiscussionCount < $this->Context->Session->User->CountDiscussions) {
				// Silently fail to post the data
				// Need to get the user's last posted discussionID and direct them to it
				$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
				$s->SetMainTable('Discussion', 'd');
				$s->AddSelect('DiscussionID', 'd');
				$s->AddWhere('AuthUserID', $this->Context->Session->UserID, '=');
				$s->AddOrderBy('DateCreated', 'd', 'desc');
				$s->AddLimit(0,1);
				$LastDiscussionData = $this->Context->Database->Select($s, $this->Name, 'SaveDiscussion', 'An error occurred while retrieving your last discussion.');
				while ($Row = $this->Context->Database->GetRow($LastDiscussionData)) {
					$Discussion->DiscussionID = ForceInt($Row['DiscussionID'], 0);
				}
				// Make sure we got it
				if ($Discussion->DiscussionID == 0) $this->Context->ErrorManager->AddError($this->Context, $this->Name, 'SaveDiscussion', 'Your last discussion could not be found.');
			} else {
				$NewDiscussion = 0;
				$OldDiscussion = false;
				if ($Discussion->DiscussionID == 0) {
					$NewDiscussion = 1;
				} else {
					$OldDiscussion = $this->GetDiscussionById($Discussion->DiscussionID);			
				}
				// Validate the Discussion topic
				$Name = FormatStringForDatabaseInput($Discussion->Name);
				Validate($this->Context->GetDefinition('DiscussionTopicLower'), 1, $Name, 100, '', $this->Context);
				if ($Discussion->CategoryID <= 0) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrSelectCategory'));
				
				// Validate first comment
				$Discussion->Comment->DiscussionID = $Discussion->DiscussionID;
				if ($OldDiscussion) {
					$Discussion->Comment->CommentID = $OldDiscussion->FirstCommentID;
				} else {
					$Discussion->Comment->CommentID = 0;
				}
				$CommentManager = $this->Context->ObjectFactory->NewContextObject($this->Context, 'CommentManager');
				$CommentManager->ValidateComment($Discussion->Comment, 0);
				
				// Validate the whisperusername
				$CommentManager->ValidateWhisperUsername($Discussion);
					
				// If updating, validate that this is admin or the author
				if (!$NewDiscussion) {
					if ($OldDiscussion->AuthUserID != $this->Context->Session->UserID && !$this->Context->Session->User->Permission('PERMISSION_EDIT_DISCUSSIONS')) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrPermissionEditComments'));
				}
				
				// If validation was successful, then reset the properties to db safe values for saving
				if ($this->Context->WarningCollector->Count() == 0) $Discussion->Name = $Name;
		
				if($this->Context->WarningCollector->Iif()) {
					$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
					
					// Update the user info & check for spam
					if ($NewDiscussion) {
						$UserManager = $this->Context->ObjectFactory->NewContextObject($this->Context, 'UserManager');
						$UserManager->UpdateUserDiscussionCount($this->Context->Session->UserID);
					}
					
					// Proceed with the save if there are no warnings
					if ($this->Context->WarningCollector->Count() == 0) {
						$s->SetMainTable('Discussion');
						$s->AddFieldNameValue('Name', $Discussion->Name);
						$s->AddFieldNameValue('CategoryID', $Discussion->CategoryID);
						if ($NewDiscussion) {				
							$s->AddFieldNameValue('AuthUserID', $this->Context->Session->UserID);
							$s->AddFieldNameValue('DateCreated', MysqlDateTime());
							$s->AddFieldNameValue('DateLastactive', MysqlDateTime());
							$s->AddFieldNameValue('CountComments', 0);
                     $s->AddFieldNameValue('WhisperUserID', $Discussion->WhisperUserID);
							$Discussion->DiscussionID = $this->Context->Database->Insert($s, $this->Name, 'NewDiscussion', 'An error occurred while creating a new discussion.');
							$Discussion->Comment->DiscussionID = $Discussion->DiscussionID;
						} else {
							$s->AddWhere('DiscussionID', $Discussion->DiscussionID, '=');
							$this->Context->Database->Update($s, $this->Name, 'NewDiscussion', 'An error occurred while updating the discussion.');
						}
					}
					
					// Now save the associated Comment
					if ($Discussion->Comment->DiscussionID > 0) {
						$CommentManager->SaveComment($Discussion->Comment, 1);
						
						// Now update the topic table so that we know what the first comment in the discussion was
						if ($Discussion->Comment->CommentID > 0 && $NewDiscussion) {
							$s->Clear();
							$s->SetMainTable('Discussion', 'd');
							$s->AddFieldNameValue('FirstCommentID', $Discussion->Comment->CommentID);
							$s->AddWhere('DiscussionID', $Discussion->Comment->DiscussionID, '=');
							$this->Context->Database->Update($s, $this->Name, 'NewDiscussion', 'An error occurred while updating discussion properties.');
						}
					}
				}
			}
		}
		return $this->Context->WarningCollector->Iif($Discussion,false);		
	}
	
	function SwitchDiscussionProperty($DiscussionID, $PropertyName, $Switch) {
		$DiscussionID = ForceInt($DiscussionID, 0);
		if ($DiscussionID == 0) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrDiscussionID'));
		
		if ($this->Context->WarningCollector->Count() == 0) {
			$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
			$s->SetMainTable('Discussion');
			$s->AddFieldNameValue($PropertyName, $Switch);
			$s->AddWhere('DiscussionID', $DiscussionID, '=');
			switch($PropertyName) {
				case 'Active':
					if (!$this->Context->Session->User->Permission('PERMISSION_HIDE_DISCUSSIONS')) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrPermissionHideDiscussions'));
					break;
				case 'Closed':
					if (!$this->Context->Session->User->Permission('PERMISSION_CLOSE_DISCUSSIONS')) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrPermissionCloseDiscussions'));
					break;
				case 'Sticky':
					if (!$this->Context->Session->User->Permission('PERMISSION_STICK_DISCUSSIONS')) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrPermissionStickDiscussions'));
					break;
			}	
			if ($this->Context->Database->Update($s, $this->Name, 'SwitchDiscussionProperty', 'An error occurred while manipulating the '.$PropertyName.' property of the discussion.', 0) <= 0) $this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrPermissionDiscussionEdit'));
		}
		return $this->Context->WarningCollector->Iif();
	}
}
?>