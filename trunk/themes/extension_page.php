<?php
// Note: This file is included from the library/Framework/Framework.Control.Filler.php class.

if ($this->PostBackAction == '') {
   echo('<div class="Title">'.$this->Context->GetDefinition('AboutExtensionPage').'</div>
   <div class="SettingsBody">
     '.$this->Context->GetDefinition('AboutExtensionPageNotes').'
   </div>');
}
?>