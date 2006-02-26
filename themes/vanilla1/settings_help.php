<?php
// Note: This file is included from the library/Vanilla.Control.SettingsHelp.php control.

echo('<div id="Help" class="Settings Help">
   <h1>'.$this->Context->GetDefinition('AboutSettings').'</h1>
   <p>'.$this->Context->GetDefinition('AboutSettingsNotes').'</p>
</div>');
?>