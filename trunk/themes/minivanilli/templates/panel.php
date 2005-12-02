<?php
// Note: This file is included from the controls/Common.Controls.php file
// in the Panel class.

echo("<div class=\"Panel".$this->CssClass."\" id=\"Panel\">");
$i = 0;
for ($i = 0; $i < count($this->PanelElements); $i++) {
   $Type = $this->PanelElements[$i]["Type"];
   $Key = $this->PanelElements[$i]["Key"];
   if ($Type == "List") {
      $Links = $this->Lists[$Key];
      if (count($Links) > 0) {
         echo("<h2>".$Key."</h2>
         <ul class=\"LinkedList\">");
         for ($j = 0; $j < count($Links); $j++) {
            echo("<li><a class=\"PanelLink\" href=\"".$Links[$j]["Link"]."\" ".$Links[$j]["LinkAttributes"].">".$this->Context->GetDefinition($Links[$j]["Item"])."</a>");
            if ($Links[$j]["Suffix"] != "") echo("<small><strong>".$this->Context->GetDefinition($Links[$j]["Suffix"])."</strong></small>");
            echo("</li>");
         }
         echo("</ul>");
      }
   } elseif ($Type == "String") {
      echo($this->Strings[$Key]);
   }
}

echo("</div>");
?>