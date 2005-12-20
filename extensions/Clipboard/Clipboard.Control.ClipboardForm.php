<?php
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
               header("location: ".GetUrl($Context->Configuration, "account.php", "", "", "", "", "PostBackAction=Clipboard"));
            }
         } elseif ($this->PostBackAction == "ClippingRemove") {
            if ($this->ClippingManager->RemoveClipping($ClippingID)) {
               header("location: ".GetUrl($Context->Configuration, "account.php", "", "", "", "", "PostBackAction=Clipboard"));
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
                  $this->ClippingSelect->Attributes = "onchange=\"document.location='?PostBackAction=Clipping&amp;ClippingID='+this.options[this.selectedIndex].value;\"";
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
                  <dl class=\"ClipboardTextbox\">
                     <dt>".$this->Context->GetDefinition("ClipboardItemContents")."</dt>
                     <dd><textarea name=\"Contents\" class=\"LargeTextbox\">".$this->Clipping->Contents."</textarea> ".$this->Context->GetDefinition("Required")."</dd>
                  </dl>
                  <div class=\"InputNote\">".$this->Context->GetDefinition("ClipboardItemContentsNotes")."</div>
                  <div class=\"FormButtons\">
                     <input type=\"submit\" name=\"btnSave\" value=\"".$this->Context->GetDefinition("Save")."\" class=\"Button SubmitButton\" />
                     <a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "", "", "", "PostBackAction=Clipboard")."\" class=\"CancelButton\">".$this->Context->GetDefinition("Cancel")."</a>
                  </div>
                  </form>
               </div>
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
                              <a class=\"SortRemove\" href=\"".GetUrl($this->Context->Configuration, "account.php", "", "", "", "", "PostBackAction=ClippingRemove&amp;ClippingID=".$Clipping->ClippingID)."\" onclick=\"return confirm('".$this->Context->GetDefinition("RemoveClipboardItemConfirm")."');\"><img src=\"".$this->Context->StyleUrl."images/btn.remove.gif\" height=\"15\" width=\"15\" border=\"0\" alt=\"".$this->Context->GetDefinition("Remove")."\" /></a>
                              <a class=\"SortEdit\" href=\"".GetUrl($this->Context->Configuration, "account.php", "", "", "", "", "PostBackAction=Clipping&amp;ClippingID=".$Clipping->ClippingID)."\">".$this->Context->GetDefinition("Edit")."</a>
                              ".$Clipping->Label."
                           </li>");
                        }
                     }
                  echo("</ul>
                  <div class=\"FormLink\"><a href=\"".GetUrl($this->Context->Configuration, "account.php", "", "", "", "", "PostBackAction=Clipping")."\">".$this->Context->GetDefinition("NewClipboardItem")."</a></div>
               </div>
            </div>");
         }
      }
   }
}
?>