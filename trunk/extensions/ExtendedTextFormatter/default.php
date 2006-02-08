<?php
/*
Extension Name: Extended Text Formatter
Extension Url: http://lussumo.com/docs/
Description: Extends the text formatter to make it replace /me and autolink urls.
Version: 1.0
Author: Mark O'Sullivan
Author Url: N/A

Copyright 2003 - 2005 Mark O'Sullivan
This file is part of Lussumo's Software Library.
Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
The latest source code is available at www.lussumo.com
Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*/

if (in_array($Context->SelfUrl, array("comments.php", "post.php"))) {
   // An implementation of the string filter interface for plain text strings
   class ExtendedTextFormatter extends StringFormatter {
      function Parse ($String, $Object, $FormatPurpose) {
         $sReturn = $String;
         // Only format plain text strings if they are being displayed (save in database as is)
         if ($FormatPurpose == FORMAT_STRING_FOR_DISPLAY) {
            $sReturn = preg_replace('/(?!\w)(?<!\w)\/me/', $this->GetAccountLink($Object), $sReturn);
            $sReturn = $this->AutoLink($sReturn);
         }
         return $sReturn;
      }
      function AutoLink($String) {
         // autolink example from www.zend.com (Code Gallery ) by http://www.zend.com/search_code_author.php?author=goten
         return preg_replace("/(?<!<a href=\")(?<!\")(?<!\">)((http|https|ftp):\/\/[\w?=&.\/-;#~%-\+]+)/","<a href=\"\\1\" target=\"_blank\">\\1</a>",$String);
      }
   
      function GetAccountLink($Object) {
         if ($Object->AuthUserID != "" && $Object->AuthUsername != "") {
            return "<a href=\"account.php?u=".$Object->AuthUserID."\">".$Object->AuthUsername."</a>";
         } else {
            return "/me";
         }
      }
   }
   
   $ExtendedTextFormatter = $Context->ObjectFactory->NewObject($Context, "ExtendedTextFormatter");
   /*
   $TextFormatter = 0;
   $TextFormatter &= $Context->StringManipulator->Formatters[$Configuration["DEFAULT_FORMAT_TYPE"]];
   if ($TextFormatter) $TextFormatter->AddChildFormatter($ExtendedTextFormatter);
   */
   
   $Context->StringManipulator->Formatters[$Configuration["DEFAULT_FORMAT_TYPE"]]->AddChildFormatter($ExtendedTextFormatter);
}

?>