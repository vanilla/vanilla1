<?php
// Note: This file is included from the library/Vanilla.Control.DiscussionForm.php class.

if ($this->Context->Session->User->Preference("ShowFormatSelector") && $FormatCount > 1) {
   $f = $this->Context->ObjectFactory->NewObject($this->Context, "Radio");
   $f->Name = "FormatType";
   $f->CssClass = "FormatTypeRadio";
   $f->SelectedID = $SelectedFormatType;
   while (list($Name, $Object) = each($this->Context->StringManipulator->Formatters)) {
      $f->AddOption($Name, $this->Context->GetDefinition($Name));
   }
   $sReturn .= "<div class=\"FormatType\"><label>".$this->Context->GetDefinition("FormatCommentsAs")."</label>"
      .$f->Get()
   ."</div>";
} else {
   $FormatTypeToUse = $this->Context->Session->User->DefaultFormatType;
   if (!array_key_exists($FormatTypeToUse, $this->Context->StringManipulator->Formatters)) {
      $FormatTypeToUse = $this->Context->Configuration["DEFAULT_STRING_FORMAT"];
   }
   
   $sReturn .= "<input type=\"hidden\" name=\"FormatType\" value=\"".$FormatTypeToUse."\" />";
}
?>