<?php
/*
Extension Name: Cleanup
Extension Url: http://lussumo.com/docs/
Description: Allows administrators to do various clean-up tasks on the database like removing dead user accounts, permanently deleting hidden comments and discussions, purging all discussions & some backup procedures.
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

Installation Notes:
You will need to add the following code to your conf/settings.php file in order
to enable this extension. This code is used to define the default permission
value for this extension. Once you have enabled the extension, you will then
need to grant your role permission to use this extension.
 
$Configuration["PERMISSION_DATABASE_CLEANUP"] = "0";

You should also cut & paste these language definitions into your
conf/your_language.php file (replace "your_language" with your chosen language,
of course):
*/
 
$Context->Dictionary['PERMISSION_DATABASE_CLEANUP'] = 'Database Cleanup Permission';
$Context->Dictionary['XHiddenDiscussions'] = 'There are currently //1 hidden discussions.';
$Context->Dictionary['XHiddenComments'] = 'There are currently //1 hidden comments.';
$Context->Dictionary['SystemCleanup'] = 'System Cleanup';
$Context->Dictionary['BackupDatabase'] = 'Backup Database';
$Context->Dictionary['BackupDatabaseNotes'] = 'If you find that this feature creates a blank file, you will need to fully define the path to mysqldump on your server. You can define this value on line 21 of the extensions/Cleanup.php file. Also be sure that the user you have specified to connect to the database has access to execute mysqldump.';
$Context->Dictionary['ClickHereToBackupDatabase'] = 'Click here to create a database backup';
$Context->Dictionary['RemoveUsersConfirm'] = 'Are you sure you wish to remove these users?\nThis action cannot be undone!';
$Context->Dictionary['CleanupUsers'] = 'Cleanup Users';
$Context->Dictionary['RemoveUsersMessage'] = 'There are currently //1 members who have never posted a comment. Remove non-participating members that have been on the forum for more than //2 days: ';
$Context->Dictionary['Go'] = 'Go';
$Context->Dictionary['CleanupDiscussions'] = 'Cleanup Discussions';
$Context->Dictionary['CleanupComments'] = 'Cleanup Comments';
$Context->Dictionary['CommentsRemovedSuccessfully'] = 'All hidden comments were successfully deleted.';
$Context->Dictionary['DiscussionsRemovedSuccessfully'] = 'All hidden discussions were successfully deleted.';
$Context->Dictionary['PurgeDiscussions'] = 'Purge Discussions';
$Context->Dictionary['DiscussionsPurgedSuccessfully'] = 'All discussions have been removed from the database.';
$Context->Dictionary['XHiddenDiscussions'] = 'There are currently //1 hidden discussions: ';
$Context->Dictionary['XHiddenComments'] = 'There are currently //1 hidden comments: ';
$Context->Dictionary['ClickHereToRemoveAllHiddenDiscussions'] = 'Remove';
$Context->Dictionary['RemoveDiscussionsConfirm'] = 'Are you sure you wish to delete all hidden discussions from the database?\nThis action cannot be undone!';
$Context->Dictionary['ClickHereToRemoveAllHiddenComments'] = 'Remove';
$Context->Dictionary['RemoveCommentsConfirm'] = 'Are you sure you wish to delete all hidden comments from the database?\nThis action cannot be undone!';
$Context->Dictionary['ClickHereToPurgeAllDiscussions'] = 'Click here to completely purge all discussions and comments from the database';
$Context->Dictionary['PurgeDiscussionsConfirm'] = 'Are you sure you wish to completely DELETE ALL DISCUSSIONS from the database?\nThis action cannot be undone!!';
$Context->Dictionary['UsersRemovedSuccessfully'] = '//1 members were removed.';
$Context->Dictionary['MasterAdministrator'] = 'Administrative privileges for all other features';
 

// If looking at the settings page, use this form
if ($Context->SelfUrl == 'settings.php' && $Context->Session->User->Permission('PERMISSION_DATABASE_CLEANUP')) {
	class CleanupForm extends PostBackControl {
      var $Name;                 // The name of this form
      var $HiddenDiscussions;    // The number of hidden discussions in the database
      var $HiddenComments;       // The number of hidden comments in the database
      var $InactiveUsers;        // The number of inactive users in the database
      var $NumberOfUsersRemoved; // The number of users that were removed by the user cleanup process
		
		function CleanupForm(&$Context) {
			$this->ValidActions = array('Cleanup', 'CleanupUsers', 'CleanupComments', 'CleanupDiscussions', 'PurgeDiscussions', 'BackupDatabase');
			$this->Constructor($Context);
         $this->Name = 'CleanupForm';
			if ($this->IsPostBack) {
				if ($this->PostBackAction == 'CleanupUsers') {
					$Days = ForceIncomingInt('Days', 30);
					$InactiveUsers = $this->GetInactiveUsers($Days);
					if (count($InactiveUsers) > 0) {
						// Wipe out category blocks
						$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
						$s->SetMainTable('CategoryBlock', 'cb');
						$s->AddWhere('cb', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user category blocks.');
						
						// Wipe out clippings
                  $s->Clear();
						$s->SetMainTable('Clipping', 'c');
						$s->AddWhere('c', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user clippings.');
						
						// Wipe out comment blocks
                  $s->Clear();
						$s->SetMainTable('CommentBlock', 'c');
						$s->AddWhere('c', 'BlockingUserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user comment blocks.');
						
						// Wipe out the ip history
                  $s->Clear();
						$s->SetMainTable('IpHistory', 'I');
						$s->AddWhere('I', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user IP history.');
						
						// Update any styles associated with this user to be system styles
                  $s->Clear();
						$s->SetMainTable('Style', 's');
						$s->AddFieldNameValue('AuthUserID', '0');
						$s->AddUpdateWhere('s', 'AuthUserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Update($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user style relations.');
						
						// Wipe out any user blocks
                  $s->Clear();
						$s->SetMainTable('UserBlock', 'ub');
						$s->AddWhere('ub', 'BlockingUserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'or', '', 0);
						$s->AddWhere('ub', 'BlockedUserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'or', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user blocks.');
						
						// Wipe out bookmarks
                  $s->Clear();
						$s->SetMainTable('UserBookmark', 'ub');
						$s->AddWhere('ub', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user bookmarks.');
						
						// Wipe out user discussion watch
                  $s->Clear();
						$s->SetMainTable('UserDiscussionWatch', 'udw');
						$s->AddWhere('udw', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user discussion tracking data.');
						
						// Wipe out role history
                  $s->Clear();
						$s->SetMainTable('UserRoleHistory', 'urh');
						$s->AddWhere('urh', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user role history.');
						
						// Wipe out saved searches
                  $s->Clear();
						$s->SetMainTable('UserSearch', 'us');
						$s->AddWhere('us', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove user searches.');
						
						// Delete the users
                  $s->Clear();
						$s->SetMainTable('User', 'u');
						$s->AddWhere('u', 'UserID', '', '('.implode(',',$InactiveUsers).')', 'in', 'and', '', 0);
						$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove the users.');
					}
					$this->NumberOfUsersRemoved = count($InactiveUsers);
					$this->PostBackValidated = 1;
					
				} elseif ($this->PostBackAction == 'CleanupComments') {
					// First get all of the hidden comment ids
					$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
					$s->SetMainTable('Comment', 'c');
					$s->AddWhere('c', 'Deleted', '', '1', '=', 'and', '', 0);
					$s->AddWhere('c', 'Deleted', '', '1', '=', 'or');
					$s->AddSelect('CommentID', 'c');
					$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve hidden comments.');
					$HiddenCommentIDs = array();
					while ($Row = $this->Context->Database->GetRow($Result)) {
						$HiddenCommentIDs[] = ForceInt($Row['CommentID'], 0);
					}
					$HiddenCommentIDs[] = 0;
					
					// Now remove comment blocks
					$s->Clear();
					$s->SetMainTable('CommentBlock', 'cb');
					$s->AddWhere('cb', 'BlockedCommentID', '', '('.implode(',',$HiddenCommentIDs).')', 'in', 'and', '', 0);
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden comment blocks.');
					
					// Now remove the comments
					$s->Clear();
					$s->SetMainTable('Comment', 'c');
					$s->AddWhere('c', 'Deleted', '', '1', '=', 'and', '', 0);
					$s->AddWhere('c', 'Deleted', '', '1', '=', 'or');
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden comments.');
					$this->PostBackValidated = 1;
					
				} elseif ($this->PostBackAction == 'CleanupDiscussions') {
					// First get all of the hidden discussion ids
					$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
					$s->SetMainTable('Discussion', 'd');
					$s->AddSelect('DiscussionID', 'd');
					$s->AddWhere('d', 'Active', '', '0', '=', 'and', '', 0);
					$s->AddWhere('d', 'Active', '', '0', '=', 'or');
					$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve hidden discussions.');
					$HiddenDiscussionIDs = array();
					while ($Row = $this->Context->Database->GetRow($Result)) {
						$HiddenDiscussionIDs[] = ForceInt($Row['DiscussionID'], 0);
					}
					$HiddenDiscussionIDs[] = 0;
					
					// Now remove comments associated with those discussions
               $s->Clear();
					$s->SetMainTable('Comment', 'c');
					$s->AddWhere('c', 'DiscussionID', '', '('.implode(',',$HiddenDiscussionIDs).')', 'in', 'and', '', 0);
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden discussion comments.');
					
					// Clean up the whisper tables
               $s->Clear();
					$s->SetMainTable('DiscussionUserWhisperFrom', 'wf');
					$s->AddWhere('wf', 'DiscussionID', '', '('.implode(',',$HiddenDiscussionIDs).')', 'in', 'and', '', 0);
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden discussion whisper data.');
               
               $s->Clear();
					$s->SetMainTable('DiscussionUserWhisperTo', 'wt');
					$s->AddWhere('wt', 'DiscussionID', '', '('.implode(',',$HiddenDiscussionIDs).')', 'in', 'and', '', 0);
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden discussion whisper data.');
					
					// Remove bookmarks
               $s->Clear();
					$s->SetMainTable('UserBookmark', 'ub');
					$s->AddWhere('ub', 'DiscussionID', '', '('.implode(',',$HiddenDiscussionIDs).')', 'in', 'and', '', 0);
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden discussion bookmark data.');
					
					// Discussion Watch data
               $s->Clear();
					$s->SetMainTable('UserDiscussionWatch', 'uw');
					$s->AddWhere('uw', 'DiscussionID', '', '('.implode(',',$HiddenDiscussionIDs).')', 'in', 'and', '', 0);
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden discussion watch data.');
					
					// Now remove the discussions themselves
               $s->Clear();
					$s->SetMainTable('Discussion', 'd');
					$s->AddWhere('d', 'Active', '', '0', '=', 'and', '', 0);
					$s->AddWhere('d', 'Active', '', '0', '=', 'or');
					$this->Context->Database->Delete($s, $this->Name, 'Constructor', 'An error occurred while attempting to remove hidden discussions.');
					$this->PostBackValidated = 1;
					
				} elseif ($this->PostBackAction == 'PurgeDiscussions') {
					// Purge Whisper tables
               $Sql = 'truncate table LUM_DiscussionUserWhisperFrom';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate whisper relationships.');
               $Sql = 'truncate table LUM_DiscussionUserWhisperTo';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate whisper relationships.');
					
					// Comment Blocks
               $Sql = 'truncate table LUM_CommentBlock';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate comment blocks.');
               
					// Comments
               $Sql = 'truncate table LUM_Comment';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate comments.');
               
					// Discussions
               $Sql = 'truncate table LUM_Discussion';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate discussions.');
               
					// Bookmarks
               $Sql = 'truncate table LUM_UserBookmark';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate bookmarks.');
               
               // User discussion watch
               $Sql = 'truncate table LUM_UserDiscussionWatch';
					$this->Context->Database->Execute($Sql, $this->Name, 'Constructor', 'An error occurred while attempting to truncate user discussion tracking data.');
					
					$this->PostBackValidated = 1;
					
				} elseif ($this->PostBackAction == 'BackupDatabase') {
					$FileName = date('Y-m-d-H-i',mktime()).'-'.$this->Context->Configuration['DATABASE_NAME'].'.sql';
					$Return = 1;
					$StringArray = array();
					// In order to enable the "system" function in windows, you've got to give
					// "read & execute" and "read" access to the internet guest account:
					// (machinename\iuser_machinename).
					@system($this->Context->Configuration['MYSQL_DUMP_PATH']
						.'mysqldump --opt -u '.$this->Context->Configuration['DATABASE_USER']
						.' --password='.$this->Context->Configuration['DATABASE_PASSWORD']
						.' '.$this->Context->Configuration['DATABASE_NAME']
						.' > '.$this->Context->Configuration['APPLICATION_PATH']
						.'images/'.$FileName);
					SaveAsDialogue($this->Context->Configuration['APPLICATION_PATH']
						.'images/',$FileName,1);
					
            } elseif ($this->PostBackAction == 'Cleanup') {
					// Load some stats
					
					// 1. The number of hidden discussions
					$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
					$s->SetMainTable('Discussion', 'd');
					$s->AddSelect('DiscussionID', 'd', 'HiddenDiscussionCount', 'count');
					$s->AddWhere('d', 'Active', '', '0', '=', 'and', '', 0);
					$s->AddWhere('d', 'Active', '', '0', '=', 'or');
					$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve hidden discussion statistics.');
					$this->HiddenDiscussions = 0;
					while ($Row = $this->Context->Database->GetRow($Result)) {
						$this->HiddenDiscussions = ForceInt($Row['HiddenDiscussionCount'], 0);
					}
					
					// 2. The number of hidden comments
					$s->Clear();
					$s->SetMainTable('Comment', 'd');
					$s->AddSelect('CommentID', 'd', 'HiddenCommentCount', 'count');
					$s->AddWhere('d', 'Deleted', '', '1', '=', 'and', '', 0);
					$s->AddWhere('d', 'Deleted', '', '1', '=', 'or');
					$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve hidden comment statistics.');
					$this->HiddenComments = 0;
					while ($Row = $this->Context->Database->GetRow($Result)) {
						$this->HiddenComments = ForceInt($Row['HiddenCommentCount'], 0);
					}
					
					// 3. The number of non-posting users
					$this->InactiveUsers = count($this->GetInactiveUsers());
				}
			}
		}
		
		function GetInactiveUsers($DaysOfMembership = '0') {
			$MembershipDate = SubtractDaysFromTimeStamp(mktime(), $DaysOfMembership);
			$s = $this->Context->ObjectFactory->NewContextObject($this->Context, 'SqlBuilder');
			$s->SetMainTable('User', 'u');
			$s->AddSelect('UserID', 'u');
			$s->AddWhere('u', 'CountComments', '', '0', '=', 'and', '', 0, 1);
			$s->AddWhere('u', 'CountComments', '', '0', '=', 'or');
			$s->EndWhereGroup();
			$s->AddWhere('u', 'CountDiscussions', '', '0', '=', 'and', '', 0, 1);
			$s->AddWhere('u', 'CountDiscussions', '', '0', '=', 'or');
			$s->EndWhereGroup();
			if ($DaysOfMembership > 0) $s->AddWhere('u', 'DateFirstVisit', '', MysqlDateTime($MembershipDate), '<');
			$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve inactive user statistics.');
			$this->InactiveUsers = 0;
			$aInactiveUsers = array();
			while ($Row = $this->Context->Database->GetRow($Result)) {
				$aInactiveUsers[] = ForceInt($Row['UserID'], 0);
			}
			
			if (count($aInactiveUsers) > 0) {
				// Now (of these users), remove ones that have whispered
				$s->Clear();
				$s->SetMainTable('DiscussionUserWhisperFrom', 'wf');
				$s->AddSelect('WhisperFromUserID', 'wf');
				$s->AddWhere('wf', 'WhisperFromUserID', '', '('.implode(',',$aInactiveUsers).')', 'in', 'and', '', 0);
				$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve inactive user statistics.');
				$CurrentWhisperUserID = 0;
				while ($Row = $this->Context->Database->GetRow($Result)) {
					$CurrentWhisperUserID = ForceInt($Row['WhisperFromUserID'], 0);
					$Key = array_search($CurrentWhisperUserID, $aInactiveUsers);
					if ($Key !== false) array_splice($aInactiveUsers, $Key, 1);
				}
			}
			
			if (count($aInactiveUsers) > 0) {
				// Now (of these users), remove ones that have received whispers
				$s->Clear();
				$s->SetMainTable('DiscussionUserWhisperTo', 'wt');
				$s->AddSelect('WhisperToUserID', 'wt');
				$s->AddWhere('wt', 'WhisperToUserID', '', '('.implode(',',$aInactiveUsers).')', 'in', 'and', '', 0);
				$Result = $this->Context->Database->Select($s, $this->Name, 'Constructor', 'An error occurred while attempting to retrieve inactive user statistics.');
				$CurrentWhisperUserID = 0;
				while ($Row = $this->Context->Database->GetRow($Result)) {
					$CurrentWhisperUserID = ForceInt($Row['WhisperToUserID'], 0);
					$Key = array_search($CurrentWhisperUserID, $aInactiveUsers);
					if ($Key !== false) array_splice($aInactiveUsers, $Key, 1);
				}
			}
			
			return $aInactiveUsers;
		}
		
		function Render_ValidPostBack() {
			echo '<div id="Form" class="Account CleanupForm">
				<fieldset>';
			if ($this->PostBackAction == 'CleanupUsers') {
				echo '<legend>'.$this->Context->GetDefinition('CleanupUsers').'</legend>
				<form>
				<ul>
					<li>
						<p class="Description">'.str_replace('//1', $this->NumberOfUsersRemoved, $this->Context->GetDefinition('UsersRemovedSuccessfully')).'</p>
					</li>';
			} elseif ($this->PostBackAction == 'CleanupComments') {
				echo '<legend>'.$this->Context->GetDefinition('CleanupComments').'</legend>
				<form>
				<ul>
					<li>
						<p class="Description">'.$this->Context->GetDefinition('CommentsRemovedSuccessfully').'</p>
					</li>';
			} elseif ($this->PostBackAction == 'CleanupDiscussions') {
				echo '<legend>'.$this->Context->GetDefinition('CleanupDiscussions').'</legend>
				<form>
				<ul>
					<li>
						<p class="Description">'.$this->Context->GetDefinition('DiscussionsRemovedSuccessfully').'</p>
					</li>';
			} elseif ($this->PostBackAction == 'PurgeDiscussions') {
				echo '<legend>'.$this->Context->GetDefinition('PurgeDiscussions').'</legend>
				<form>
				<ul>
					<li>
						<p class="Description">'.$this->Context->GetDefinition('DiscussionsPurgedSuccessfully').'</p>
					</li>';
			}
			echo '<li>
				<p class="Description"><a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=Cleanup').'">'.$this->Context->GetDefinition('ClickHereToContinue').'</a></p>
				</li>
			</ul>
			</form>
			</fieldset>
			</div>';
		}
		
		function Render_NoPostBack() {
			if ($this->IsPostBack) {
				if ($this->PostBackAction == 'Cleanup') {
					$DaySelect = $this->Context->ObjectFactory->NewObject($this->Context, 'Select');
					$DaySelect->Name = 'Days';
					$DaySelect->CssClass = 'InlineSelect';
					$DaySelect->Attributes = ' id="DaySelect"';
					for ($i = 0; $i < 11; $i++) {
						$DaySelect->AddOption($i, $i);
					}
					$i = 15;
					while ($i < 31) {
						$DaySelect->AddOption($i, $i);
						$i += 5;
					}
					$i = 40;
					while ($i < 61) {
						$DaySelect->AddOption($i, $i);
						$i += 10;
					}
					$i = 90;
					while ($i < 370) {
						$DaySelect->AddOption($i, $i);
						$i += 30;
					}
					$DaySelect->SelectedValue = 30;
					echo '<div id="Form" class="Account CleanupForm">
						<fieldset>
						<legend>'.$this->Context->GetDefinition('SystemCleanup').'</legend>
						<form>
						<h2>'.$this->Context->GetDefinition("BackupDatabase").'</h2>
						<p><a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=BackupDatabase').'">'.$this->Context->GetDefinition('ClickHereToBackupDatabase').'</a></p>
						<p class="Description">'.$this->Context->GetDefinition("BackupDatabaseNotes").'</p>
						
						<h2>'.$this->Context->GetDefinition('CleanupUsers').'</h2>
						
						<script language="javascript" type="text/javascript">'."
						//<![CDATA[	
						function RemoveUsers() {
							if (confirm('".$this->Context->GetDefinition("RemoveUsersConfirm")."')) {
								var sel = document.getElementById('DaySelect');
								document.location = '".GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=CleanupUsers&Days='+sel.options[sel.selectedIndex].value").";
							}
						}
						//]]>".'
						</script>
						<p class="Description">'
							.str_replace(array('//1','//2'), array($this->InactiveUsers, $DaySelect->Get()), $this->Context->GetDefinition('RemoveUsersMessage'))
						.' <strong><a href="javascript:RemoveUsers();">'.$this->Context->GetDefinition('Go').'</a></strong></p>
						
						<h2>'.$this->Context->GetDefinition('CleanupDiscussions').'</h2>
						<script language="javascript" type="text/javascript">'."
						//<![CDATA[	
						function RemoveDiscussions() {
							if (confirm('".$this->Context->GetDefinition("RemoveDiscussionsConfirm")."')) {
								document.location = '".GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=CleanupDiscussions")."';
							}
						}
						function RemoveComments() {
							if (confirm('".$this->Context->GetDefinition("RemoveCommentsConfirm")."')) {
								document.location = '".GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=CleanupComments")."';
							}
						}
						function PurgeDiscussions() {
							if (confirm('".$this->Context->GetDefinition("PurgeDiscussionsConfirm")."')) {
								document.location = '".GetUrl($this->Context->Configuration, "settings.php", "", "", "", "", "PostBackAction=PurgeDiscussions")."';
							}
						}
						//]]>".'
						</script>
						<p class="Description">'
							.str_replace('//1', $this->HiddenDiscussions, $this->Context->GetDefinition('XHiddenDiscussions'))
							.'<a href="javascript:RemoveDiscussions();">'.$this->Context->GetDefinition('ClickHereToRemoveAllHiddenDiscussions').'</a>
						</p>
						<p class="Description">'
							.str_replace('//1', $this->HiddenComments, $this->Context->GetDefinition('XHiddenComments'))
							.'<a href="javascript:RemoveComments();">'.$this->Context->GetDefinition('ClickHereToRemoveAllHiddenComments').'</a>
						</p>
						<p class="Description"><a href="javascript:PurgeDiscussions();">'.$this->Context->GetDefinition('ClickHereToPurgeAllDiscussions').'</a></p>
						</form>
				</fieldset>
			</div>';
				}					
			}
		}
	}
	
	$CleanupForm = $Context->ObjectFactory->NewContextObject($Context, 'CleanupForm');
	$Page->AddRenderControl($CleanupForm, $Configuration['CONTROL_POSITION_BODY_ITEM'] + 80);
	$Panel->AddListItem($Context->GetDefinition('AdministrativeOptions'), $Context->GetDefinition('SystemCleanup'), GetUrl($Context->Configuration, $Context->SelfUrl, '', '', '', '', 'PostBackAction=Cleanup'), '', '', 91);
}
?>