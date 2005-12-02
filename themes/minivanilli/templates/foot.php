<?php
// Note: This file is included from the controls/Common.Controls.php file
// in the Foot class.

echo("</div>
</div>
<div class=\"Foot".$this->CssClass."\">
   <div class=\"Copyright\"><a href=\"http://lussumo.com\">Lussumo Vanilla (".VANILLA_VERSION.")</a> ".$this->Context->GetDefinition("Copyright")."</div>
   <div class=\"Links\">
      <a href=\"feeds/\">".$this->Context->GetDefinition("Feeds")."</a>
      | <a href=\"http://lussumo.com/docs/doku.php?id=vanilla:bugs\">".$this->Context->GetDefinition("ReportABug")."</a>
      | <a href=\"javascript:PopTermsOfService();\">".$this->Context->GetDefinition("TermsOfService")."</a>
      | <a href=\"http://lussumo.com/docs/\" target=\"_blank\">".$this->Context->GetDefinition("Documentation")."</a>
   </div>
</div>");
$IsAdmin = 0;
if ($this->Context->Session->User) {
   if ($this->Context->Session->User->MasterAdmin) $IsAdmin = 1;
}
if ($this->Context->Mode == MODE_DEBUG) {
   echo("<div class=\"DebugBar\" id=\"DebugBar\">
   <b>Debug Options</b> | Resize: <a href=\"javascript:window.resizeTo(800,600);\">800x600</a>, <a href=\"javascript:window.resizeTo(1024, 768);\">1024x768</a> | <a href=\"javascript:HideElement('DebugBar');\">Hide This</a>");
   echo($this->Context->SqlCollector->GetMessages());
   echo("</div>");
}
?>