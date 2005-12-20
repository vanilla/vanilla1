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

$Context->Dictionary["AllowHtml"] = "Allow HTML in this comment";
$Context->Dictionary["BlockHtml"] = "Block HTML in this comment";
$Context->Dictionary["BlockComment"] = "block comment";
$Context->Dictionary["BlockCommentTitle"] = "Block HTML in this comment";
$Context->Dictionary["UnblockComment"] = "unblock comment";
$Context->Dictionary["UnblockCommentTitle"] = "Allow HTML in this comment";
$Context->Dictionary["BlockUserHtml"] = "Block HTML in all comments by this user on the forum";
$Context->Dictionary["AllowUserHtml"] = "Allow HTML in all comments by this user on the forum";
$Context->Dictionary["BlockUser"] = "block user";
$Context->Dictionary["BlockUserTitle"] = "Block HTML in all comments by this user on the forum";
$Context->Dictionary["UnblockUser"] = "unblock user";
$Context->Dictionary["UnblockUserTitle"] = "Allow HTML in all comments by this user on the forum";



if ($Context->SelfUrl == "comments.php") {
	// Include required js for ajaxing of comment/user blocking
   $Head->AddScript("./extensions/CommentProtection/functions.js");	
	
	// Add the options to the grid
	function CommentGrid_BlockOptions(&$CommentGrid) {
      if ($CommentGrid->Context->Session->UserID > 0) {
         $Comment = $CommentGrid->DelegateParameters["Comment"];
         $ShowHtml = $CommentGrid->DelegateParameters["ShowHtml"];
			$CommentList = &$CommentGrid->DelegateParameters["CommentList"];
         if ($CommentGrid->Context->Session->User->Preference("HtmlOn") && !$Comment->Deleted) {
            $CommentList .= "<div class=\"CommentBlockUser\"><a id=\"BlockUser_".$Comment->AuthUserID."_Comment_".$Comment->CommentID."\" onclick=\"BlockUser('".$Comment->AuthUserID."', '".FlipBool($Comment->AuthBlocked)."', '".$CommentGrid->Context->GetDefinition("UnblockUser")."', '".$CommentGrid->Context->GetDefinition("UnblockUserTitle")."', '".$CommentGrid->Context->GetDefinition("BlockUser")."', '".$CommentGrid->Context->GetDefinition("BlockUserTitle")."', '".$CommentGrid->Context->GetDefinition("UnblockComment")."', '".$CommentGrid->Context->GetDefinition("UnblockCommentTitle")."', '".$CommentGrid->Context->GetDefinition("BlockComment")."', '".$CommentGrid->Context->GetDefinition("BlockCommentTitle")."');\" title=\"".$CommentGrid->Context->GetDefinition(GetBool(!$Comment->AuthBlocked,"BlockUserHtml","AllowUserHtml"))."\">".$CommentGrid->Context->GetDefinition(GetBool(!$Comment->AuthBlocked,"BlockUser","UnblockUser"))."</a></div>";
         }
         $CommentList .= "<div class=\"CommentBlockComment\"><a id=\"BlockComment_".$Comment->CommentID."\" onclick=\"BlockComment('".$Comment->CommentID."', '".$ShowHtml."', 1, false, '".$CommentGrid->Context->GetDefinition("UnblockComment")."', '".$CommentGrid->Context->GetDefinition("UnblockCommentTitle")."', '".$CommentGrid->Context->GetDefinition("BlockComment")."', '".$CommentGrid->Context->GetDefinition("BlockCommentTitle")."');\" title=\"".$CommentGrid->Context->GetDefinition(GetBool($ShowHtml,"BlockHtml","AllowHtml"))."\">".$CommentGrid->Context->GetDefinition(GetBool($ShowHtml,"BlockComment","UnblockComment"))."</a></div>";
		}
	}
	
	$Context->AddToDelegate("CommentGrid",
		"PostCommentOptionsRender",
		"CommentGrid_BlockOptions");
      
}
?>