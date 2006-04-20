<?php
// Note: This file is included from the library/Framework/Framework.Control.Filler.php class.
if ($this->Context->Configuration['UPDATE_REMINDER'] != '') {
   if ($this->Context->Session->User->Permission('PERMISSION_CHECK_FOR_UPDATES')) {
      $ShowUpdateMessage = 0;
      $LastUpdate = $this->Context->Configuration['LAST_UPDATE'];
      if ($LastUpdate == '') $LastUpdate = time();
      $Difference = time() - $LastUpdate;
      $Days = floor($Difference/60/60/24);
      if ($this->Context->Configuration['LAST_UPDATE'] == '') {
         $ShowUpdateMessage = 1;
      } elseif ($this->Context->Configuration['UPDATE_REMINDER'] == 'Weekly') {
         if ($Days > 7) $ShowUpdateMessage = 1;
      } elseif ($this->Context->Configuration['UPDATE_REMINDER'] == 'Monthly') {
         if ($Days > 30) $ShowUpdateMessage = 1;
      } elseif ($this->Context->Configuration['UPDATE_REMINDER'] == 'Quarterly') {
         if ($Days > 90) $ShowUpdateMessage = 1;
      }
      
      if ($ShowUpdateMessage) {
         $Message = '';
         if ($Days == 0) {
            $Message = $this->Context->GetDefinition('NeverCheckedForUpdates');
         } else {
            $Message = str_replace('//1', $Days, $this->Context->GetDefinition('XDaysSinceUpdateCheck'));
         }
         echo '<div class="SystemMessages">
            <div>'.$Message.' <a href="'.GetUrl($this->Context->Configuration, 'settings.php', '', '', '', '', 'PostBackAction=ProcessUpdateCheck').'">'.$this->Context->GetDefinition('CheckForUpdatesNow').'</a></div>
         </div>';
            
      }
   }
}
?>