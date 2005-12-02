<?php
// Note: This file is included from the library/Vanilla.Control.ExtensionForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("Extensions")."</h1>
   <div class=\"ExtensionsForm\">"
      .$this->Get_Warnings()
      ."<div class=\"InputNote\">
         ".$this->Context->GetDefinition("ExtensionFormNotes")."
      </div>
      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
         <tr valign=\"top\">
            <td class=\"DisabledExtensions\">
               <h2>".$this->Context->GetDefinition("DisabledExtensions")."</h2>
               <dl class=\"Extensions\">");
               ksort($this->DisabledExtensions);
               $FirstExtension = 1;
               while (list($ExtensionKey, $Extension) = each($this->DisabledExtensions)) {
                  echo("<a name=\"".$ExtensionKey."\"></a>
                  <dt class=\"DisabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">
                     ".GetDynamicCheckBox("chk".$ExtensionKey, 1, 0, "document.location='settings.php?PostBackAction=ProcessExtension&ExtensionKey=".$ExtensionKey."';", "<strong>".$Extension->Name."</strong> ".$Extension->Version)
                     ."</dt>
                     <dd class=\"DisabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">");
                     if ($ExtensionKey == $SelectedExtensionKey) {
                        echo($Extension->Description
                        ."<br />".FormatHyperlink($Extension->Url)
                        ."<br />".FormatHyperlink($Extension->AuthorUrl,1,$Extension->Author));
                     } else {
                        echo("<a href=\"settings.php?PostBackAction=Extensions&Detail=".$ExtensionKey."#".$ExtensionKey."\">".SliceString($Extension->Description, 60)."</a>");
                     }
                     echo("</dd>");
                  $FirstExtension = 0;
               }
               echo("</dl></td>
            <td class=\"EnabledExtensions\">
               <h2>".$this->Context->GetDefinition("EnabledExtensions")."</h2>
               <dl class=\"Extensions\">");
               ksort($this->EnabledExtensions);
               $FirstExtension = 1;
               while (list($ExtensionKey, $Extension) = each($this->EnabledExtensions)) {
                  echo("<a name=\"".$ExtensionKey."\"></a>
                  <dt class=\"EnabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">
                     ".GetDynamicCheckBox("chk".$ExtensionKey, 1, 1, "document.location='settings.php?PostBackAction=ProcessExtension&ExtensionKey=".$ExtensionKey."';", "<strong>".$Extension->Name."</strong> ".$Extension->Version)
                     ."</dt>
                     <dd class=\"EnabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">");
                     if ($ExtensionKey == $SelectedExtensionKey) {
                        echo($Extension->Description
                        ."<br />".FormatHyperlink($Extension->Url)
                        ."<br />".FormatHyperlink($Extension->AuthorUrl,1,$Extension->Author));
                     } else {
                        echo("<a href=\"settings.php?PostBackAction=Extensions&Detail=".$ExtensionKey."#".$ExtensionKey."\">".SliceString($Extension->Description, 60)."</a>");
                     }
                     echo("</dd>");
                  $FirstExtension = 0;
               }
            echo("</dl></td>
         </tr>
      </table>					
   </div>
</div>");
?>