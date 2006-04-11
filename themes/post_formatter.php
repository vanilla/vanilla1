<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.DiscussionForm.php class.

if ($this->Context->Session->User->Preference('ShowFormatSelector') && $FormatCount > 1) {
   $f = $this->Context->ObjectFactory->NewObject($this->Context, 'Radio');
   $f->Name = 'FormatType';
   $f->CssClass = 'FormatTypeRadio';
   $f->SelectedID = $SelectedFormatType;
   while (list($Name, $Object) = each($this->Context->StringManipulator->Formatters)) {
      $f->AddOption($Name, $this->Context->GetDefinition($Name));
   }
   
   $sReturn .= '<li>'
      .$this->Context->GetDefinition('FormatCommentsAs')
      .$f->Get()
   .'</li>';
} else {
   $FormatTypeToUse = $this->Context->Session->User->DefaultFormatType;
   if (!array_key_exists($FormatTypeToUse, $this->Context->StringManipulator->Formatters)) {
      $FormatTypeToUse = $this->Context->Configuration['DEFAULT_FORMAT_TYPE'];
   }
   
   $sReturn .= '<li><input type="hidden" name="FormatType" value="'.$FormatTypeToUse.'" /></li>';
}
?>