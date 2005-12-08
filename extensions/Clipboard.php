<?php
/*
 Extension Name: Clipboard
 Extension Url: http://lussumo.com/docs/
 Description: Allows users to save little bits of information for easy posting. This extension is only compatible with Vanilla 0.9.3 or greater.
 Version: 2.0
 Author: Mark O'Sullivan
 Author Url: http://www.markosullivan.ca/

 Copyright 2003 - 2005 Mark O'Sullivan
 This file is part of Vanilla.
 Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 The latest source code for Vanilla is available at www.lussumo.com
 Contact Mark O'Sullivan at mark [at] lussumo [dot] com
 
 You must add the following definitions to your conf/your_language.php file
 (replace "your_language" with your chosen language, of course):

 $Context->Dictionary["ClipboardManagement"] = "Clipboard Management";
 $Context->Dictionary["SelectItemToEdit"] = "1. Select the item you would like to edit";
 $Context->Dictionary["ClipboardItems"] = "Clipboard Items";
 $Context->Dictionary["ModifyItemDefinition"] = "2. Modify the item definition";
 $Context->Dictionary["DefineNewClipboardItem"] = "Define the new clipboard item";
 $Context->Dictionary["ItemLabel"] = "Item Label";
 $Context->Dictionary["ItemLabelNotes"] = "The label you assign to your clipboard item is what you will click on when adding that item to comments. Html is not allowed.";
 $Context->Dictionary["ClipboardItemContents"] = "Your clipboard item contents";
 $Context->Dictionary["ClipboardItemContentsNotes"] = "Enter anything you want into this input. You will then be able to paste this information into your discussion comments with a simple button-click.";
 $Context->Dictionary["YourClipboard"] = "Manage Clipboard";
 $Context->Dictionary["ClipboardItems"] = "Clipboard Items";
 $Context->Dictionary["RemoveClipboardItemConfirm"] = "Are you sure you wish to remove this clipboard item?\\nThis action cannot be undone.";
 $Context->Dictionary["NewClipboardItem"] = "Create a new clipboard item";
 $Context->Dictionary["ManageYourClipboard"] = "Manage Clipboard";
 $Context->Dictionary["CopyFromYourClipboard"] = "Copy from your clipboard <small>(optional)</small>";
 
*/

// Let it skip over these classes if it doesn't need them
if (in_array($Context->SelfUrl, array("account.php", "comments.php", "post.php", "getclipping.php"))) {
   include_once($Configuration["EXTENSIONS_PATH"]."Clipboard/Clipboard.Class.Clipping.php");
   include_once($Configuration["EXTENSIONS_PATH"]."Clipboard/Clipboard.Class.ClippingManager.php");
}

// If looking at the account page, include and instantiate the clipboard form control
if ($Context->SelfUrl == "account.php") {
   $Head->AddStyleSheet("./extensions/Clipboard/style.css");
   
   $AccountUserID = ForceIncomingInt("u", $Context->Session->UserID);
   if ($AccountUserID == $Context->Session->UserID) {
      include_once($Configuration["EXTENSIONS_PATH"]."Clipboard/Clipboard.Control.ClipboardForm.php");
      
      $Panel->AddListItem($Context->GetDefinition("AccountOptions"), $Context->GetDefinition("ManageYourClipboard"), $Context->SelfUrl."?PostBackAction=Clipboard", "", "", 40);
     	$Page->AddRenderControl($Context->ObjectFactory->NewContextObject($Context, "ClipboardForm"), $Configuration["CONTROL_POSITION_BODY_ITEM"]);
   }
}

if (in_array($Context->SelfUrl, array("comments.php", "post.php"))) {
   $Head->AddStyleSheet("./extensions/Clipboard/style.css");
   $Head->AddScript("./extensions/Clipboard/functions.js");
   
   function CommentForm_AddClipboardDropDown(&$CommentForm) {
      $ClippingManager = $CommentForm->Context->ObjectFactory->NewContextObject($CommentForm->Context, "ClippingManager");
      $Clippings = $ClippingManager->GetClippingsByUserID($CommentForm->Context->Session->UserID);
      $ClippingCount = $CommentForm->Context->Database->RowCount($Clippings);
      if ($ClippingCount > 0) {
         $ClipboardSelect = $CommentForm->Context->ObjectFactory->NewObject($CommentForm->Context, "Select");
         $ClipboardSelect->Name = "ClippingID";
         $ClipboardSelect->CssClass = "ClipboardSelect";
         $ClipboardSelect->Attributes = " onchange=\"GetClipping(this);\"";
         $ClipboardSelect->AddOption("", "");
         $ClipboardSelect->AddOptionsFromDataSet($CommentForm->Context->Database, $Clippings, "ClippingID", "Label");
         echo("<dt class=\"ClipboardInputLabel\">".$CommentForm->Context->GetDefinition("CopyFromYourClipboard")."</dt>
         <dd class=\"ClipboardInput\">".$ClipboardSelect->Get()."</dd>");
      }
   }
   $Context->AddToDelegate("DiscussionForm", "DiscussionForm_PreCommentRender", "CommentForm_AddClipboardDropDown");
   $Context->AddToDelegate("DiscussionForm", "CommentForm_PreCommentsInputRender", "CommentForm_AddClipboardDropDown");
}
?>