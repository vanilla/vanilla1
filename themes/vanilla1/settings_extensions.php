<?php
// Note: This file is included from the library/Vanilla.Control.ExtensionForm.php control.

echo('<div id="Form" class="Account Extensions">
   <fieldset>
      <legend>'.$this->Context->GetDefinition('Extensions').'</legend>'
      .$this->Get_Warnings()
      .'<form action="#" method="post">
		<p>'.$this->Context->GetDefinition('ExtensionFormNotes').'</p>
      
      <div class="ExtensionListContainer clearfix">
         <div class="ExtensionsDisabled">
            <h2>'.$this->Context->GetDefinition('DisabledExtensions').'</h2>
            <ul>');
               if (is_array($this->DisabledExtensions)) {
                  $ExtensionList = '';
                  ksort($this->DisabledExtensions);
                  $FirstExtension = 1;
                  while (list($ExtensionKey, $Extension) = each($this->DisabledExtensions)) {
                     $ExtensionList .= '<li>
                        <h3>
                           '.GetDynamicCheckBox('chk'.$ExtensionKey, 1, 0, "document.location='".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=ProcessExtension&amp;ExtensionKey=".$ExtensionKey)."';", $Extension->Name.' <span>'.$Extension->Version.'</span>').'
                        </h3>
                        <p>';
                        if ($ExtensionKey == $SelectedExtensionKey) {
                           $ExtensionList .= $Extension->Description
                           .'<br />'.FormatHyperlink($Extension->Url)
                           .'<br />'.FormatHyperlink($Extension->AuthorUrl,1,$Extension->Author);
                        } else {
                           $ExtensionList .= '<a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl, '', '', '', '', 'PostBackAction=Extensions&amp;Detail='.$ExtensionKey.'#'.$ExtensionKey).'">'.SliceString($Extension->Description, 60).'</a>';
                        }
                        $ExtensionList .= '<p>
                     </li>';
                  }
                  echo($ExtensionList);
               } else {
                  echo('<li><p>'.$this->Context->GetDefinition('NoDisabledExtensions').'</p></li>');
               }
            echo('</ul>
         </div>
         <div class="ExtensionsEnabled">
            <h2>
               '.$this->Context->GetDefinition('EnabledExtensions').'
            </h2>
            <ul>');
               if (is_array($this->EnabledExtensions)) {
                  $ExtensionList = '';
                  ksort($this->EnabledExtensions);
                  $FirstExtension = 1;
                  while (list($ExtensionKey, $Extension) = each($this->EnabledExtensions)) {
                     $ExtensionList .= '<li>
                        <h3>'.GetDynamicCheckBox('chk'.$ExtensionKey, 1, 1, "document.location='".GetUrl($this->Context->Configuration, $this->Context->SelfUrl, "", "", "", "", "PostBackAction=ProcessExtension&amp;ExtensionKey=".$ExtensionKey)."';", $Extension->Name.' <span>'.$Extension->Version.'</span>').'</h3>
                        <p>';
                        if ($ExtensionKey == $SelectedExtensionKey) {
                           $ExtensionList .= $Extension->Description
                           .'<br />'.FormatHyperlink($Extension->Url)
                           .'<br />'.FormatHyperlink($Extension->AuthorUrl,1,$Extension->Author);
                        } else {
                           $ExtensionList .= '<a href="'.GetUrl($this->Context->Configuration, $this->Context->SelfUrl, '', '', '', '', 'PostBackAction=Extensions&amp;Detail='.$ExtensionKey.'#'.$ExtensionKey).'">'.SliceString($Extension->Description, 60).'</a>';
                        }
                        $ExtensionList .= '</p>
                     </li>';
                  }
                  echo($ExtensionList);
               } else {
                  echo('<li><p>'.$this->Context->GetDefinition('NoEnabledExtensions').'</p></li>');
               }
            echo('</ul>
         </div>
      </div>
      </form>					
   </fieldset>
</div>');
?>