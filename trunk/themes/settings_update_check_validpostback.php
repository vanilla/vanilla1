<?php
// Note: This file is included from the library/Framework/Framework.Control.UpdateCheck.php control.

echo '<div id="Form" class="Account UpdateCheck">
   <fieldset>
      <legend>'.$this->Context->GetDefinition('UpdateCheck').'</legend>
      <form id="frmUpdateCheck" method="post" action="">
      <p class="Description">'.$this->LussumoMessage.'</p>
      </form>
   </fieldset>
</div>';
?>