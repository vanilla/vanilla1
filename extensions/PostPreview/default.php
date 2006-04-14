<?php
/*
Extension Name: Post Preview
Extension Url: http://lussumo.com/docs/
Description: Allows users to preview their comments before posting them.
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
$Context->Dictionary["Preview"] = "Preview";


// Add the Preview button to the account and post pages
if (in_array($Context->SelfUrl, array("comments.php", "post.php"))) {
   // Add the extension js
   $Head->AddScript($Configuration['WEB_ROOT'].'extensions/PostPreview/functions.js');
   // Add the css
   $Head->AddStyleSheet($Configuration['WEB_ROOT'].'extensions/PostPreview/PreviewStyle.css');
   
   // Add to the discussion form
   function DiscussionForm_AddPreviewButton(&$Form) {
      echo(" <input type=\"button\" name=\"Preview\" class=\"Button SubmitButton\" value=\"".$Form->Context->GetDefinition("Preview")."\"  onclick=\"PreviewPost('frmPostDiscussion', this, '".$Form->Context->GetDefinition("Wait")."');\" />
      <input type=\"Hidden\" name=\"IsPreview\" value=\"0\" />");
   }
   $Context->AddToDelegate("DiscussionForm",
      "DiscussionForm_PostSubmitRender",
      "DiscussionForm_AddPreviewButton");
      
   // Add to the comment form
   function CommentForm_AddPreviewButton(&$Form) {
      echo(" <input type=\"button\" name=\"Preview\" class=\"Button SubmitButton\" value=\"".$Form->Context->GetDefinition("Preview")."\"  onclick=\"PreviewPost('frmPostComment', this, '".$Form->Context->GetDefinition("Wait")."');\" />
      <input type=\"Hidden\" name=\"IsPreview\" value=\"0\" />");
   }
   $Context->AddToDelegate("DiscussionForm",
      "CommentForm_PostSubmitRender",
      "CommentForm_AddPreviewButton");
   
   // Set up the preview
   function DiscussionForm_SetUpCommentPreview(&$DiscussionForm) {
      if (ForceIncomingBool("IsPreview", 0)) {
         $DiscussionForm->IsPostBack = 1;
         if ($DiscussionForm->PostBackAction == "SaveDiscussion") {
            $DiscussionForm->Discussion->Clear();
            $DiscussionForm->Discussion->GetPropertiesFromForm($DiscussionForm->Context);
         } elseif ($DiscussionForm->PostBackAction == "SaveComment") {
            $DiscussionForm->Comment->Clear();
            $DiscussionForm->Comment->GetPropertiesFromForm();
            $DiscussionForm->Comment->DiscussionID = $DiscussionForm->DiscussionID;
            $DiscussionForm->Discussion = $DiscussionForm->DelegateParameters["DiscussionManager"]->GetDiscussionById($DiscussionForm->Comment->DiscussionID);
         }
         
         // Make sure it doesn't save anything
         $DiscussionForm->PostBackAction = "Preview";
      }      
   }
   $Context->AddToDelegate("DiscussionForm",
      "PostLoadData",
      "DiscussionForm_SetUpCommentPreview");
      
   // Render the preview for the discussion form
   function DiscussionForm_PreviewDiscussionComment(&$DiscussionForm) {
      if (ForceIncomingBool("IsPreview", 0)) {
         $Comment = $DiscussionForm->Discussion->Comment;
         // Make sure that the selected FormatType is used when previewing
         $Comment->FormatType = ForceIncomingString("FormatType", "");
         $Comment->Deleted = 0;
         $Comment->AuthCanPostHtml = 1;
         $Comment->AuthBlocked = 0;
         $Comment->CommentBlocked = 0;
         $Comment->FormatPropertiesForDisplay();
         echo("<div class=\"Title PreviewPostTitle\">".$DiscussionForm->Context->GetDefinition("Preview")."</div>
            <div class=\"CommentBody CommentPreview\">".$Comment->Body."</div>");
      }
   }      
   $Context->AddToDelegate("DiscussionForm",
      "DiscussionForm_PreRender",
      "DiscussionForm_PreviewDiscussionComment");
      
   // Render the preview for the comment form
   function DiscussionForm_PreviewComment(&$DiscussionForm) {
      if (ForceIncomingBool("IsPreview", 0)) {
         
         $Comment = $DiscussionForm->Comment;
         // Make sure that the selected FormatType is used when previewing
         $Comment->Deleted = 0;
         $Comment->AuthCanPostHtml = 1;
         $Comment->AuthBlocked = 0;
         $Comment->CommentBlocked = 0;
         $Comment->FormatPropertiesForDisplay();
         
         echo("<div class=\"Title PreviewPostTitle\">".$DiscussionForm->Context->GetDefinition("Preview")."</div>
            <div class=\"CommentBody CommentPreview\">".$Comment->Body."</div>");
            
         // Now make sure that the "back to discussion" link is displayed
         $DiscussionForm->PostBackAction = "SaveComment";
      }
   }      
   $Context->AddToDelegate("DiscussionForm",
      "CommentForm_PreRender",
      "DiscussionForm_PreviewComment");
}

?>