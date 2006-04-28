<?php
/*
Extension Name: Comment Protection
Extension Url: http://lussumo.com/docs/
Description: Adds "block comment" and "block user" options to discussion comments, allowing users to prevent html or images from being displayed by a comment or all comments by a user.
Version: 2.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/

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

$Context->Dictionary['AllowHtml'] = 'Allow HTML in this comment';
$Context->Dictionary['BlockHtml'] = 'Block HTML in this comment';
$Context->Dictionary['BlockComment'] = 'block comment';
$Context->Dictionary['BlockCommentTitle'] = 'Block HTML in this comment';
$Context->Dictionary['UnblockComment'] = 'unblock comment';
$Context->Dictionary['UnblockCommentTitle'] = 'Allow HTML in this comment';
$Context->Dictionary['BlockUserHtml'] = 'Block HTML in all comments by this user on the forum';
$Context->Dictionary['AllowUserHtml'] = 'Allow HTML in all comments by this user on the forum';
$Context->Dictionary['BlockUser'] = 'block user';
$Context->Dictionary['BlockUserTitle'] = 'Block HTML in all comments by this user on the forum';
$Context->Dictionary['UnblockUser'] = 'unblock user';
$Context->Dictionary['UnblockUserTitle'] = 'Allow HTML in all comments by this user on the forum';

// Check to see if this extension has been configured
if (!array_key_exists('COMMENT_PROTECTION_SETUP', $Configuration)) {
	$Errors = 0;
	// Drop the comment block table if it is already in the db
   $CommentBlockDrop = "drop table if exists `LUM_CommentBlock`";
	if (!@mysql_query($CommentBlockDrop, $Context->Database->Connection)) $Errors = 1;
	// Create the CommentBlock table
   $CommentBlockCreate = "CREATE TABLE `LUM_CommentBlock` (
		`BlockingUserID` int(11) NOT NULL default '0',
		`BlockedCommentID` int(11) NOT NULL default '0',
		`Blocked` enum('1','0') NOT NULL default '1',
		KEY `comment_block_user` (`BlockingUserID`),
		KEY `comment_block_comment` (`BlockedCommentID`)
	);";
	if (!@mysql_query($CommentBlockCreate, $Context->Database->Connection)) $Errors = 1;
   
	// Drop the UserBlock table if it is already in the db
   $UserBlockDrop = "drop table if exists `LUM_UserBlock`";
	if (!@mysql_query($UserBlockDrop, $Context->Database->Connection)) $Errors = 1;
	// Create the UserBlock table
   $UserBlockCreate = "CREATE TABLE `LUM_UserBlock` (
		`BlockingUserID` int(11) NOT NULL default '0',
		`BlockedUserID` int(11) NOT NULL default '0',
		`Blocked` enum('1','0') NOT NULL default '1'
	);";
	if (!@mysql_query($UserBlockCreate, $Context->Database->Connection)) $Errors = 1;
	
	if ($Errors == 0) {
		// Add the db structure to the database configuration file
		$Structure = "// Comment Protection Table Structure
\$DatabaseTables['CommentBlock'] = 'CommentBlock';
\$DatabaseTables['UserBlock'] = 'UserBlock';
\$DatabaseColumns['CommentBlock']['BlockingUserID'] = 'BlockingUserID';
\$DatabaseColumns['CommentBlock']['BlockedCommentID'] = 'BlockedCommentID';
\$DatabaseColumns['CommentBlock']['Blocked'] = 'Blocked';
\$DatabaseColumns['UserBlock']['BlockingUserID'] = 'BlockingUserID';
\$DatabaseColumns['UserBlock']['BlockedUserID'] = 'BlockedUserID';
\$DatabaseColumns['UserBlock']['Blocked'] = 'Blocked';
";
		if (!AppendToConfigurationFile($Configuration['APPLICATION_PATH'].'conf/database.php', $Structure)) $Errors = 1;
	}	
	
	if ($Errors == 0) {
		// Mark this extension as enabled using a convenience method.
      AddConfigurationSetting($Context, 'COMMENT_PROTECTION_SETUP');
	}
   
} else {	
	if ($Context->SelfUrl == "comments.php") {
		// Include required js for ajaxing of comment/user blocking
		$Head->AddScript('extensions/CommentProtection/functions.js');
		
		// Fix up the comment grid query
      function CommentManager_ApplyProtectionBlocks(&$CommentManager) {
			$s = &$CommentManager->DelegateParameters['SqlBuilder'];
			$s->AddJoin('UserBlock', 'ab', 'BlockedUserID', 'm', 'AuthUserID', 'left join', ' and ab.'.$CommentManager->Context->DatabaseColumns['UserBlock']['BlockingUserID'].' = '.$CommentManager->Context->Session->UserID);
			$s->AddJoin('CommentBlock', 'cb', 'BlockedCommentID', 'm', 'CommentID', 'left join', ' and cb.'.$CommentManager->Context->DatabaseColumns['CommentBlock']['BlockingUserID'].' = '.$CommentManager->Context->Session->UserID);
			$s->AddSelect('Blocked', 'ab', 'AuthBlocked', 'coalesce', '0');
			$s->AddSelect('Blocked', 'cb', 'CommentBlocked', 'coalesce', '0');
		}
		
		$Context->AddToDelegate("CommentManager",
			"CommentBuilder_PreSelect",
			"CommentManager_ApplyProtectionBlocks");
		
		
		// Add the options to the grid
		function CommentGrid_BlockOptions(&$CommentGrid) {
			if ($CommentGrid->Context->Session->UserID > 0) {
				$Comment = $CommentGrid->DelegateParameters["Comment"];
				$ShowHtml = $CommentGrid->DelegateParameters["ShowHtml"];
				$CommentList = &$CommentGrid->DelegateParameters["CommentList"];
				if ($CommentGrid->Context->Session->User->Preference("HtmlOn") && !$Comment->Deleted) {
					$CommentList .= "<a href=\"./\" id=\"BlockUser_".$Comment->AuthUserID."_Comment_".$Comment->CommentID."\" onclick=\"BlockUser('".$CommentGrid->Context->Configuration['WEB_ROOT']."extensions/CommentProtection/block.php', '".$Comment->AuthUserID."', '".FlipBool($Comment->AuthBlocked)."', '".$CommentGrid->Context->GetDefinition("UnblockUser")."', '".$CommentGrid->Context->GetDefinition("UnblockUserTitle")."', '".$CommentGrid->Context->GetDefinition("BlockUser")."', '".$CommentGrid->Context->GetDefinition("BlockUserTitle")."', '".$CommentGrid->Context->GetDefinition("UnblockComment")."', '".$CommentGrid->Context->GetDefinition("UnblockCommentTitle")."', '".$CommentGrid->Context->GetDefinition("BlockComment")."', '".$CommentGrid->Context->GetDefinition("BlockCommentTitle")."'); return false;\" title=\"".$CommentGrid->Context->GetDefinition(GetBool(!$Comment->AuthBlocked,"BlockUserHtml","AllowUserHtml"))."\">".$CommentGrid->Context->GetDefinition(GetBool(!$Comment->AuthBlocked,"BlockUser","UnblockUser"))."</a>";
				}
				$CommentList .= "<a href=\"./\" id=\"BlockComment_".$Comment->CommentID."\" onclick=\"BlockComment('".$CommentGrid->Context->Configuration['WEB_ROOT']."extensions/CommentProtection/block.php', '".$Comment->CommentID."', '".$ShowHtml."', 1, false, '".$CommentGrid->Context->GetDefinition("UnblockComment")."', '".$CommentGrid->Context->GetDefinition("UnblockCommentTitle")."', '".$CommentGrid->Context->GetDefinition("BlockComment")."', '".$CommentGrid->Context->GetDefinition("BlockCommentTitle")."'); return false;\" title=\"".$CommentGrid->Context->GetDefinition(GetBool($ShowHtml,"BlockHtml","AllowHtml"))."\">".$CommentGrid->Context->GetDefinition(GetBool($ShowHtml,"BlockComment","UnblockComment"))."</a>";
			}
		}
		
		$Context->AddToDelegate("CommentGrid",
			"PostCommentOptionsRender",
			"CommentGrid_BlockOptions");
	}      
}
?>