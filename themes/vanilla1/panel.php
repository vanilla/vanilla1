<?php
// Note: This file is included from the library/Framework/Framework.Control.Panel.php class.

echo('<div id="Panel">');

$this->CallDelegate('PostStartButtonRender');

while (list($Key, $PanelElement) = each($this->PanelElements)) {
   $Type = $PanelElement['Type'];
   $Key = $PanelElement['Key'];
   if ($Type == 'List') {
      $Links = $this->Lists[$Key];
      if (count($Links) > 0) {
         ksort($Links);
         echo('<ul>
            <li>
               <h2>'.$Key.'</h2>
               <ul>');
               while (list($LinkKey, $Link) = each($Links)) {
                  echo('<li>
                     <a '.($Link['Link'] != '' ? 'href="'.$Link['Link'].'"' : '').' '.$Link['LinkAttributes'].'>'.$Link['Item'].'</a>');
                     if ($Link['Suffix'] != '') echo(' '.$this->Context->GetDefinition($Link['Suffix']));
                  echo('</li>');
               }
               echo('</ul>
            </li>
         </ul>');
      }
   } elseif ($Type == 'String') {
      echo($this->Strings[$Key]);
   }
}

$this->CallDelegate('PostElementsRender');

echo('</div>
<div id="Content">');
?>