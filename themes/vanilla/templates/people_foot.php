<?php
// Note: This file is included from the library/People.Control.PeopleFoot.php class.

      echo('</div>
      </div>
      <div class="Foot'.$this->CssClass.'"><a href="http://lussumo.com">Lussumo Vanilla, Swell, and People</a> Copyright &copy; 2001-2005</div>');
$AllowDebugInfo = 0;
if ($this->Context->Session->User) {
   if ($this->Context->Session->User->Permission('PERMISSION_ALLOW_DEBUG_INFO')) $AllowDebugInfo = 1;
}
if ($this->Context->Mode == MODE_DEBUG && $AllowDebugInfo) {
   echo('<div class="DebugBar" id="DebugBar">
   <b>Debug Options</b> | Resize: <a href="javascript:window.resizeTo(800,600);">800x600</a>, <a href="javascript:window.resizeTo(1024, 768);">1024x768</a> | <a href="'
   ."javascript:HideElement('DebugBar');"
   .'">Hide This</a>');
   echo($this->Context->SqlCollector->GetMessages());
   echo('</div>');
}
?>