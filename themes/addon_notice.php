<?php
// Note: This file is included from the library/Framework/Framework.Control.Filler.php class.
if ($this->Context->Configuration['ADDON_NOTICE']) {
   if ($this->Context->Session->User->Permission('PERMISSION_MANAGE_EXTENSIONS')) {
      $HideNotice = ForceIncomingBool('TurnOffAddonNotice', 0);
      if ($HideNotice) {
         $SettingsFile = $this->Context->Configuration['APPLICATION_PATH'].'conf/settings.php';
         $SettingsManager = $this->Context->ObjectFactory->NewContextObject($this->Context, 'ConfigurationManager');
         $SettingsManager->DefineSetting("ADDON_NOTICE", '0', 1);
         $SettingsManager->SaveSettingsToFile($SettingsFile);
      } else {
         echo '<div class="SystemMessages">
            <div>
               '.$this->Context->GetDefinition('WelcomeToVanillaGetSomeAddons').'
               <br /><a href="'.GetUrl($this->Context->Configuration, 'index.php', '', '', '', '', 'TurnOffAddonNotice=1').'">'.$this->Context->GetDefinition('RemoveThisNotice').'</a>
            </div>
         </div>';
            
      }
   }
}
?>