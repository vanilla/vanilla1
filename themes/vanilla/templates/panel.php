<?php
// Note: This file is included from the library/Utility.Control.Panel.php class.

echo('<div class="Panel'.$this->CssClass.'" id="Panel">');

$this->CallDelegate('PostStartButtonRender');

while (list($Key, $PanelElement) = each($this->PanelElements)) {
   $Type = $PanelElement['Type'];
   $Key = $PanelElement['Key'];
   if ($Type == 'List') {
      $Links = $this->Lists[$Key];
      if (count($Links) > 0) {
         ksort($Links);
         echo('<h2>'.$Key.'</h2>
         <ul class="LinkedList">');
         while (list($LinkKey, $Link) = each($Links)) {
            echo('<li><a class="PanelLink" '.($Link['Link'] != '' ? 'href="'.$Link['Link'].'"' : '').' '.$Link['LinkAttributes'].'>'.$Link['Item'].'</a>');
            if ($Link['Suffix'] != '') echo('<small><strong>'.$this->Context->GetDefinition($Link['Suffix']).'</strong></small>');
            echo('</li>');
         }
         echo('</ul>');
      }
   } elseif ($Type == 'String') {
      echo($this->Strings[$Key]);
   }
}

$this->CallDelegate('PostElementsRender');

echo('</div>
<div class="PageBody '.$this->BodyCssClass.'" id="Body">');
?>