<?php
/*
Extension Name: Clipboard
Extension Url: http://lussumo.com/docs/
Description: Allows users to save little bits of information for easy posting. This extension is only compatible with Vanilla 0.9.3 or greater.
Version: 2.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
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

// Let it skip over these classes if it doesn't need them
if (in_array($Context->SelfUrl, array("account.php", "comments.php", "post.php", "getclipping.php"))) {
   class Clipping {
      var $ClippingID;
      var $UserID;
      var $Label;
      var $Contents;
      
      function Clipping() {
         $this->Clear();
      }
      
      function Clear() {
         $this->ClippingID = 0;
         $this->UserID = 0;
         $this->Label = "";
         $this->Contents = "";
      }
      
      function FormatContentsForJavascript() {
         $this->Contents = str_replace("\"","\\\"",$this->Contents);
         $this->Contents = str_replace("\r\n","{newline}",$this->Contents);
         $this->Contents = str_replace("\t","{tab}",$this->Contents);
      }
      
      function FormatPropertiesForDatabaseInput() {
         $this->Label = FormatStringForDatabaseInput($this->Label);
         $this->Contents = FormatStringForDatabaseInput($this->Contents);
         $this->Contents = eregi_replace("&lt;textarea&gt;", "<textarea>", $this->Contents);
         $this->Contents = eregi_replace("&lt;//textarea&gt;", "</textarea>", $this->Contents);
      }
      
      function FormatPropertiesForDisplay($IncludeContents = "0") {
         $IncludeContents = ForceBool($IncludeContents, 0);
         $this->Label = FormatStringForDisplay($this->Label);
         if ($IncludeContents) {
            $this->Contents = htmlspecialchars($this->Contents);
         }
      }
      
      function GetPropertiesFromDataSet($DataSet) {
         $this->ClippingID = ForceInt(@$DataSet["ClippingID"],0);
         $this->UserID = ForceInt(@$DataSet["UserID"],0);
         $this->Label = ForceString(@$DataSet["Label"],"");
         $this->Contents = ForceString(@$DataSet["Contents"],"");
      }
      
      function GetPropertiesFromForm() {
         $this->ClippingID = ForceIncomingInt("ClippingID", 0);
         $this->Label = ForceIncomingString("Label", "");
         $this->Contents = ForceIncomingString("Contents", "");
      }
   }
   class ClippingManager {
      var $Name;				// The name of this class
      var $Context;			// The context object that contains all global objects (database, error manager, warning collector, session, etc)
      
      function ClippingManager(&$Context) {
         $this->Name = "ClippingManager";
         $this->Context = &$Context;
      }
   
      function GetClippingById($ClippingID) {
         $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
         $s->SetMainTable("Clipping", "c");
         $s->AddSelect(array("ClippingID", "UserID", "Label", "Contents"), "c");
         $s->AddWhere("ClippingID", $ClippingID, "=");
         $s->AddWhere("UserID", $this->Context->Session->UserID, "=");
         $ResultSet = $this->Context->Database->Select($this->Context, $s, $this->Name, "GetClippingById", "An error occurred while attempting to retrieve the requested Clipping.");
         $Clipping = false;
         while ($rows = $this->Context->Database->GetRow($ResultSet)) {
            $Clipping = $this->Context->ObjectFactory->NewObject($this->Context, "Clipping");
            $Clipping->GetPropertiesFromDataSet($rows);
         }
         return $Clipping;
      }
      
      function GetClippingsByUserID($UserID) {
         $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
         $s->SetMainTable("Clipping", "c");
         $s->AddSelect(array("ClippingID", "UserID", "Label", "Contents"), "c");
         $s->AddWhere("UserID", $UserID, "=");
         return $this->Context->Database->Select($this->Context, $s, $this->Name, "GetClippingsByUserID", "An error occurred while attempting to retrieve Clipping items.");
      }
      
      function RemoveClipping($ClippingID) {
         $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
         $s->SetMainTable("Clipping", "c");
         $s->AddWhere("ClippingID", $ClippingID, "=");
         $s->AddWhere("UserID", $this->Context->Session->UserID, "=");
         $this->Context->Database->Delete($this->Context, $s, $this->Name, "RemoveClipping", "An error occurred while removing the Clipping item.");
         return true;
      }
      
      function SaveClipping($Clipping) {
         $Clipping->FormatPropertiesForDatabaseInput();
         if ($Clipping->Label == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrClippingLabelRequired"));
         if ($Clipping->Contents == "") $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrClippingContents"));
         if ($this->Context->WarningCollector->Count() == 0) {
            $s = $this->Context->ObjectFactory->NewContextObject($this->Context, "SqlBuilder");
            $s->SetMainTable("Clipping", "c");
            $s->AddFieldNameValue("Label", $Clipping->Label);
            $s->AddFieldNameValue("Contents", $Clipping->Contents);
            if ($Clipping->ClippingID > 0) {
               $s->AddWhere("ClippingID", $Clipping->ClippingID, "=");
               $s->AddWhere("UserID", $this->Context->Session->UserID, "=");
               $this->Context->Database->Update($this->Context, $s, $this->Name, "SaveClipping", "An error occurred while saving your Clipping item.");
            } else {
               $s->AddFieldNameValue("UserID", $this->Context->Session->UserID);
               $this->Context->Database->Insert($this->Context, $s, $this->Name, "SaveClipping", "An error occurred while creating a new item for your Clipping.");
            }
         }
         return $this->Context->WarningCollector->Iif();
      }
   }
}

// If looking at the account page, include and instantiate the clipboard form control
if ($Context->SelfUrl == "account.php") {
   $AccountUserID = ForceIncomingInt("u", $Context->Session->UserID);
   if ($AccountUserID == $Context->Session->UserID) {
      class ClipboardForm extends PostBackControl {
         
         var $ClippingManager;
         var $ClippingData;
         var $ClippingSelect;
         var $Clipping;
      
         function ClipboardForm(&$Context) {
            $this->ValidActions = array("Clipboard", "Clipping", "ProcessClipping", "ClippingRemove");
            $this->Constructor($Context);
            if ($this->IsPostBack) {
               $ClippingID = ForceIncomingInt("ClippingID", 0);
               $this->ClippingManager = $this->Context->ObjectFactory->NewContextObject($this->Context, "ClippingManager");
               
               if ($this->PostBackAction == "ProcessClipping") {
                  $this->Clipping = $this->Context->ObjectFactory->NewObject($this->Context, "Clipping");
                  $this->Clipping->GetPropertiesFromForm($this->Context);
                  if ($this->ClippingManager->SaveClipping($this->Clipping)) {
                     header("location: account.php?PostBackAction=Clipboard");
                  }
               } elseif ($this->PostBackAction == "ClippingRemove") {
                  if ($this->ClippingManager->RemoveClipping($ClippingID)) {
                     header("location: account.php?PostBackAction=Clipboard");
                  }
               }
               
               if (in_array($this->PostBackAction, array("ClippingRemove", "Clipboard", "Clipping", "ProcessClipping"))) {
                  $this->ClippingData = $this->ClippingManager->GetClippingsByUserID($this->Context->Session->UserID);
               }
               if (in_array($this->PostBackAction, array("ClippingRemove", "Clipping"))) {
                  $this->ClippingSelect = $this->Context->ObjectFactory->NewObject($this->Context, "Select");
                  $this->ClippingSelect->Name = "ClippingID";
                  $this->ClippingSelect->CssClass = "SmallInput";
                  $this->ClippingSelect->AddOption("", "Choose...");
                  $this->ClippingSelect->AddOptionsFromDataSet($this->Context->Database, $this->ClippingData, "ClippingID", "Label");
               }
               if ($this->PostBackAction == "Clipping") {
                  if ($ClippingID > 0) {
                     $this->Clipping = $this->ClippingManager->GetClippingById($ClippingID);
                  } else {
                     $this->Clipping = $this->Context->ObjectFactory->NewObject($this->Context, "Clipping");
                  }
               }
               if (in_array($this->PostBackAction, array("ProcessClipping"))) {
                  // Show the form again with errors
                  $this->PostBackAction = str_replace("Process", "", $this->PostBackAction);
               }
            }
         }
         
         function Render() {
            if ($this->IsPostBack) {
               $this->PostBackParams->Clear();
               $ClippingID = ForceIncomingInt("ClippingID", 0);
               
               if ($this->PostBackAction == "Clipping") {
                  $this->Clipping->FormatPropertiesForDisplay(1);
                  $this->PostBackParams->Set("PostBackAction", "ProcessClipping");
                  echo("<div class=\"SettingsForm\">
                     <h1>".$this->Context->GetDefinition("ClipboardManagement")."</h1>");
                     if ($ClippingID > 0) {
                        $this->ClippingSelect->Attributes = "onchange=\"document.location='?PostBackAction=Clipping&ClippingID='+this.options[this.selectedIndex].value;\"";
                        $this->ClippingSelect->SelectedID = $ClippingID;
                        echo("<div class=\"Form\" id=\"Clippings\">
                           ".$this->Get_Warnings()."
                           ".$this->Get_PostBackForm("frmClipping")."
                           <h2>".$this->Context->GetDefinition("SelectItemToEdit")."</h2>
                           <dl>
                              <dt>".$this->Context->GetDefinition("ClipboardItems")."</dt>
                              <dd>".$this->ClippingSelect->Get()." ".$this->Context->GetDefinition("Required")."</dd>
                           </dl>
                           <h2>".$this->Context->GetDefinition("ModifyItemDefinition")."</h2>");
                     } else {
                        echo("<div class=\"Form\" id=\"Clippings\">
                           ".$this->Get_Warnings()."
                           ".$this->Get_PostBackForm("frmClipping")."
                           <h2>".$this->Context->GetDefinition("DefineNewClipboardItem")."</h2>");
                     }
                     echo("<dl>
                        <dt>".$this->Context->GetDefinition("ItemLabel")."</dt>
                        <dd><input type=\"text\" name=\"Label\" value=\"".$this->Clipping->Label."\" maxlength=\"30\" class=\"SmallInput\" id=\"txtClippingName\" /> ".$this->Context->GetDefinition("Required")."</dd>
                     </dl>
                     <div class=\"InputNote\">".$this->Context->GetDefinition("ItemLabelNotes")."</div>
                     <dl>
                        <dt>".$this->Context->GetDefinition("ClipboardItemContents")."</dt>
                        <dd><textarea name=\"Contents\" class=\"LargeTextbox\">".$this->Clipping->Contents."</textarea> ".$this->Context->GetDefinition("Required")."</dd>
                     </dl>
                     <div class=\"InputNote\">".$this->Context->GetDefinition("ClipboardItemContentsNotes")."</div>
                     <div class=\"FormButtons\">
                        <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
                        <a href=\"./account.php?PostBackAction=Clipboard\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
                     </div>
                     </form>
                  </div>");			
               } else {
                  echo("<div class=\"SettingsForm\">
                     ".$this->Get_Warnings()."
                     <h1>".$this->Context->GetDefinition("YourClipboard")."</h1>
                     <div class=\"Form\" id=\"Clipboard\">
                        <h2>".$this->Context->GetDefinition("ClipboardItems")."</h2>
                        <ul class=\"SortList\">");
                           if ($this->ClippingData) {
                              $Clipping = $this->Context->ObjectFactory->NewObject($this->Context, "Clipping");
                              
                              while ($Row = $this->Context->Database->GetRow($this->ClippingData)) {
                                 $Clipping->Clear();
                                 $Clipping->GetPropertiesFromDataSet($Row);
                                 $Clipping->FormatPropertiesForDisplay();
                                 echo("<li class=\"SortListItem\">
                                    <ul>
                                       <li class=\"SortRemove\"><a href=\"./account.php?PostBackAction=ClippingRemove&ClippingID=".$Clipping->ClippingID."\" onclick=\"return confirm('".$this->Context->GetDefinition("RemoveClipboardItemConfirm")."');\"><img src=\"".$this->Context->Session->User->StyleUrl."btn.remove.gif\" height=\"13\" width=\"13\" border=\"0\" alt=\"".$this->Context->GetDefinition("Remove")."\" /></a></li>
                                       <li class=\"SortItem\"><a href=\"./account.php?PostBackAction=Clipping&ClippingID=".$Clipping->ClippingID."\">".$Clipping->Label."</a></li>
                                    </ul>
                                 </li>");
                              }
                           }
                        echo("</ul>
                        <div class=\"FormLink\"><a href=\"account.php?PostBackAction=Clipping\">".$this->Context->GetDefinition("NewClipboardItem")."</a></div>
                     </div>
                  </div>");
               }
            }
         }
      }
      
      $Panel->AddListItem($Context->GetDefinition("AccountOptions"), $Context->GetDefinition("ManageYourClipboard"), $Context->SelfUrl."?PostBackAction=Clipboard", "", "", 40);
     	$Body->AddControl($Context->ObjectFactory->NewContextObject($Context, "ClipboardForm"));
   }
}

if (in_array($Context->SelfUrl, array("comments.php", "post.php"))) {
   function AddClipboardDropDownToCommentForm(&$CommentFormControl, &$FunctionParameters) {
      $ClippingManager = $CommentFormControl->Context->ObjectFactory->NewContextObject($Context, "ClippingManager");
      $Clippings = $ClippingManager->GetClippingsByUserID($CommentFormControl->Context->Session->UserID);
      $ClippingCount = $CommentFormControl->Context->Database->RowCount($Clippings);
      $sReturn = "";
      if ($ClippingCount > 0) {
         $ClipboardSelect = $CommentFormControl->Context->ObjectFactory->NewObject($CommentFormControl->Context, "Select");
         $ClipboardSelect->Name = "ClippingID";
         $ClipboardSelect->CssClass = "ClipboardSelect";
         $ClipboardSelect->Attributes = " onchange=\"GetClipping(this);\"";
         $ClipboardSelect->AddOption("", "");
         $ClipboardSelect->AddOptionsFromDataSet($CommentFormControl->Context->Database, $Clippings, "ClippingID", "Label");
         $sReturn = "<dt class=\"ClipboardInputLabel\">".$CommentFormControl->Context->GetDefinition("CopyFromYourClipboard")."</dt>
         <dd class=\"ClipboardInput\">".$ClipboardSelect->Get()."</dd>";
      }
      return $sReturn;
   }
   $Context->AddToDelegate("DiscussionForm", "DiscussionForm_PreCommentReturn", "AddClipboardDropDownToCommentForm");
   $Context->AddToDelegate("DiscussionForm", "CommentForm_PreCommentsInputReturn", "AddClipboardDropDownToCommentForm");
}
?>