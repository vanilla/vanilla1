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
* Description: File used by Dynamic Data Management object to handle any type of boolean switch
*/

include("../appg/settings.php");
include("../conf/settings.php");
include("../appg/init_ajax.php");

$Type = ForceIncomingString("Type", "");
$Switch = ForceIncomingBool("Switch", 0);
$DiscussionID = ForceIncomingInt("DiscussionID", 0);
$CommentID = ForceIncomingInt("CommentID", 0);
$SearchID = ForceIncomingInt("SearchID", 0);

// Don't create unnecessary objects
if (in_array($Type, array("Active", "Closed", "Sticky"))) {
	$dm = $Context->ObjectFactory->NewContextObject($Context, "DiscussionManager");
} elseif ($Type == "Comment") {
	$cm = $Context->ObjectFactory->NewContextObject($Context, "CommentManager");
} else {
	// This will allow the switch class to be used to add new custom user settings
	$um = $Context->ObjectFactory->NewContextObject($Context, "UserManager");
}
// Handle the switches
if ($Type == "Bookmark" && $DiscussionID > 0) {
	if ($Switch) {
		$um->AddBookmark($Context->Session->UserID, $DiscussionID);
	} else {
		$um->RemoveBookmark($Context->Session->UserID, $DiscussionID);
	}
	if ($NoAjax) {
		header("location: comments.php?DiscussionID=".$DiscussionID);
		die();
	}
} elseif ($DiscussionID > 0 && (
	($Type == "Active" && $Context->Session->User->Permission("PERMISSION_HIDE_DISCUSSIONS"))
	|| ($Type == "Closed" && $Context->Session->User->Permission("PERMISSION_CLOSE_DISCUSSIONS"))
	|| ($Type == "Sticky" && $Context->Session->User->Permission("PERMISSION_STICK_DISCUSSIONS"))
	)) {
	$dm->SwitchDiscussionProperty($DiscussionID, $Type, $Switch);
	if ($NoAjax) {
		header("location: comments.php?DiscussionID=".$DiscussionID);
		die();
	}
} elseif ($Type == "Comment" && $CommentID > 0 && $DiscussionID > 0 && $Context->Session->User->Permission("PERMISSION_HIDE_COMMENTS")) {
	$cm->SwitchCommentProperty($CommentID, $DiscussionID, $Switch);
	if ($NoAjax) {
		header("location: comments.php?DiscussionID=".$DiscussionID."&Focus=".$CommentID."#Comment_".$CommentID);
		die();
	}
} elseif ($Type == "SendNewApplicantNotifications") {
	$um->SwitchUserProperty($Context->Session->UserID, $Type, $Switch);
} elseif ($Type != "") {
	$um->SwitchUserPreference($Type, $Switch);
}

if ($NoAjax) {
	header("location: account.php?PostBackAction=Functionality");
	die();
}	

// $Page->FireEvents();
echo("Complete");
?>