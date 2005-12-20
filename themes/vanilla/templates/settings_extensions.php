<?php
// Note: This file is included from the library/Vanilla.Control.ExtensionForm.php control.

echo("<div class=\"SettingsForm\">
   <h1>".$this->Context->GetDefinition("Extensions")."</h1>
   <div class=\"ExtensionsForm\">"
      .$this->Get_Warnings()
      ."<div class=\"InputNote\">
         ".$this->Context->GetDefinition("ExtensionFormNotes")."
      </div>
      <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">
         <tr valign=\"top\">
            <td class=\"DisabledExtensions\">
               <h2>".$this->Context->GetDefinition("DisabledExtensions")."</h2>");
               if (is_array($this->DisabledExtensions)) {
                  $ExtensionList = "<dl class=\"Extensions\">";
                  ksort($this->DisabledExtensions);
                  $FirstExtension = 1;
                  while (list($ExtensionKey, $Extension) = each($this->DisabledExtensions)) {
                     $ExtensionList .= "<dt class=\"DisabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">"
                        ."<a name=\"".$ExtensionKey."\"></a>"
                        .GetDynamicCheckBox("chk".$ExtensionKey, 1, 0, "document.location='".$this->Context->SelfUrl."?PostBackAction=ProcessExtension&amp;ExtensionKey=".$ExtensionKey."';", "<strong>".$Extension->Name."</strong> ".$Extension->Version)
                        ."</dt>
                        <dd class=\"DisabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">";
                        if ($ExtensionKey == $SelectedExtensionKey) {
                           $ExtensionList .= $Extension->Description
                           ."<br />".FormatHyperlink($Extension->Url)
                           ."<br />".FormatHyperlink($Extension->AuthorUrl,1,$Extension->Author);
                        } else {
                           $ExtensionList .= "<a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Extensions&amp;Detail=".$ExtensionKey."#".$ExtensionKey)."\">".SliceString($Extension->Description, 60)."</a>";
                        }
                        $ExtensionList .= "</dd>";
                     $FirstExtension = 0;
                  }
                  echo($ExtensionList . "</dl>");
               } else {
                  echo($this->Context->GetDefinition("NoDisabledExtensions"));
               }
            echo("</td>
            <td class=\"EnabledExtensions\">
               <h2>".$this->Context->GetDefinition("EnabledExtensions")."</h2>");
               if (is_array($this->EnabledExtensions)) {
                  $ExtensionList = "<dl class=\"Extensions\">";
                  ksort($this->EnabledExtensions);
                  $FirstExtension = 1;
                  while (list($ExtensionKey, $Extension) = each($this->EnabledExtensions)) {
                     $ExtensionList .= "<dt class=\"EnabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">"
                        ."<a name=\"".$ExtensionKey."\"></a>"
                        .GetDynamicCheckBox("chk".$ExtensionKey, 1, 1, "document.location='".$this->Context->SelfUrl."?PostBackAction=ProcessExtension&amp;ExtensionKey=".$ExtensionKey."';", "<strong>".$Extension->Name."</strong> ".$Extension->Version)
                        ."</dt>
                        <dd class=\"EnabledExtension".($ExtensionKey == $SelectedExtensionKey ? " SelectedExtension" : "").($FirstExtension?" FirstExtension":"")."\">";
                        if ($ExtensionKey == $SelectedExtensionKey) {
                           $ExtensionList .= $Extension->Description
                           ."<br />".FormatHyperlink($Extension->Url)
                           ."<br />".FormatHyperlink($Extension->AuthorUrl,1,$Extension->Author);
                        } else {
                           $ExtensionList .= "<a href=\"".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=Extensions&amp;Detail=".$ExtensionKey."#".$ExtensionKey)."\">".SliceString($Extension->Description, 60)."</a>";
                        }
                        $ExtensionList .= "</dd>";
                     $FirstExtension = 0;
                  }
                  echo($ExtensionList."</dl>");
               } else {
                  echo($this->Context->GetDefinition("NoEnabledExtensions"));
               }
            echo("</td>
         </tr>
      </table>					
   </div>
</div>");
?>