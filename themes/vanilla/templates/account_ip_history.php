<?php
// Note: This file is included from the extensions/IpHistory.php file in the IpHistory control.

echo("<div class=\"IpHistory\">
   <h1>".$this->Context->GetDefinition("IpHistory")."</h1>");
   // Loop through the user's ip history
   $SharedCount = 0;
   $HistoryCount = count($this->History);
   if ($HistoryCount > 0) {
      $i = 0;
      for ($i = 0; $i < $HistoryCount; $i++) {
         $SharedCount = count($this->History[$i]["SharedWith"]);
         echo("<blockquote>
            <h2>".$this->History[$i]["IP"]."</h2>
            <small>(".
               FormatPlural($this->History[$i]["UsageCount"],
                  str_replace("\\1", $this->History[$i]["UsageCount"], $this->Context->GetDefinition("time")),
                  str_replace("\\1", $this->History[$i]["UsageCount"], $this->Context->GetDefinition("times")))
               .")</small>");
            if ($SharedCount > 0) {
               echo("<h3>".$this->Context->GetDefinition("IpAlsoUsedBy")."</h3>
               <p>");
                  for ($j = 0; $j < $SharedCount; $j++) {
                     $SharedUserName = $this->History[$i]["SharedWith"][$j]["Name"];
                     $SharedUserID = $this->History[$i]["SharedWith"][$j]["UserID"];
                     if ($j > 0) echo(", ");
                     echo("<a href=\"account.php?u=".$SharedUserID."\">".$SharedUserName."</a>");
                  }
                  echo("</p>");
            } else {
               echo("<h3>".$this->Context->GetDefinition("IpNotShared")."</h3>");
            }
         echo("</blockquote>");
      }
   } else {
      echo("<blockquote>".$this->Context->GetDefinition("NoIps")."</blockquote>");
   }
echo("</div>");
?>