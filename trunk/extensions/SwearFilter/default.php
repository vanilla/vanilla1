<?php
/*
Extension Name: Swear Filter
Extension Url: http://lussumo.com/docs/
Description: Takes all words placed in the SwearFilter/words.txt file and replaces them in all posts with xxxxes.
Version: 1.0
Author: Mark O'Sullivan
Author Url: N/A
*/

/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/
if (in_array($Context->SelfUrl, array("comments.php","search.php"))) {
   class SwearFilter extends StringFormatter {
      var $Swears;
      
      function CheckProtocol($Check, $Allow, $Extra, $Prefix, $Suffix) {
         $sReturn = stripslashes($Prefix);
         if(!in_array($Check, $Allow)) $sReturn .= ($Extra.'http://');
      
         else $sReturn .= ($Extra.$Check.':');
         $sReturn .= stripslashes($Suffix);
         
         return $sReturn;
      }
      
      function Execute($String) {
         if (strlen($this->Swears) > 0) {
            return preg_replace("/\b($this->Swears)\b/ie", 'preg_replace("/./","*","\\1")', $String);
         } else {
            return $String;
         }
      }
      
      function Parse($String, $Object, $FormatPurpose) {
         if ($FormatPurpose == FORMAT_STRING_FOR_DISPLAY) {
            // Do this transformation if the string is being displayed
            return $this->Execute($String);
         } else {
            // Do not perform this transformation if the string is being saved to the db
            return $String;
         }
      }
      function SwearFilter() {
         $this->Swears = file("./extensions/SwearFilter/words.txt");
         $SwearCount = count($this->Swears);
         for ($i = 0; $i < $SwearCount; $i++) {
            $this->Swears[$i] = trim($this->Swears[$i]);
         }
         $this->Swears = implode("|", $this->Swears);
      }
   }
   
   // Instantiate the formatter and add it to the context object's string manipulator
   $SwearFilter = $Context->ObjectFactory->NewObject($Context, "SwearFilter");
   $Context->StringManipulator->AddGlobalManipulator("SwearFilter", $SwearFilter);
}
?>