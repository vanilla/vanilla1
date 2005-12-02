<?php
// Note: This file is included from the library/Vanilla.Control.Foot.php class.

echo("</div>
</div>");
$this->CallDelegate("PostSiteContainerRender");
echo("<div class=\"Foot".$this->CssClass."\">
   <div class=\"Links\">");
   ksort($this->Links);
   $FirstLink = 1;
   $Links = "";
   while (list($Key, $Link) = each($this->Links)) {
      if ($FirstLink) {
         $FirstLink = 0;
      } else {
         $Links .= " | ";
      }
      $Links .= "<a href=\"".$Link["Url"]."\"";
      if ($Link["Target"] != "") $Links .= " target=\"".$Link["Target"]."\"";
      $Links .= ">".$Link["Text"]."</a>";
   }
   echo($Links
   ."</div>
   <div class=\"Copyright\"><a href=\"http://lussumo.com\">Lussumo Vanilla (".VANILLA_VERSION.")</a> ".$this->Context->GetDefinition("Copyright")."</div>
</div>");
$AllowDebugInfo = 0;
if ($this->Context->Session->User) {
   if ($this->Context->Session->User->Permission("PERMISSION_ALLOW_DEBUG_INFO")) $AllowDebugInfo = 1;
}
if ($this->Context->Mode == MODE_DEBUG && $AllowDebugInfo) {
   echo("<div class=\"DebugBar\" id=\"DebugBar\">
   <b>Debug Options</b> | Resize: <a href=\"javascript:window.resizeTo(800,600);\">800x600</a>, <a href=\"javascript:window.resizeTo(1024, 768);\">1024x768</a> | <a href=\"javascript:HideElement('DebugBar');\">Hide This</a>");
   echo($this->Context->SqlCollector->GetMessages());
   echo("</div>");
}
?>