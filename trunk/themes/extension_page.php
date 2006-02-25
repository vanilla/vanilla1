<?php
if ($this->PostBackAction == '') {
   // Note: This file is included from the library/Vanilla.Control.DiscussionGrid.php class.
   echo('<div class="Title">'.$this->Context->GetDefinition('AboutExtensionPage').'</div>
   <div class="SettingsBody">
     '.$this->Context->GetDefinition('AboutExtensionPageNotes').'
   </div>');
}
?>