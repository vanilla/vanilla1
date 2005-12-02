<?php
/*
Extension Name: BB Code
Extension Url: http://lussumo.com/docs/
Description: A BBCode-to-HTML conversion tool for discussion comments
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://markosullivan.ca/
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

class BBCodeFormatter extends StringFormatter {
	function Parse($String, $Object, $FormatPurpose) {
		if ($FormatPurpose == FORMAT_STRING_FOR_DISPLAY) {
			$String = $this->ProtectString($String);
			return $this->BBEncode($String);
		} else {
			return $String;
		}
	}
	function ProtectString ($String) {
		$String = str_replace("<", "&lt;", $String);
      $String = str_replace("\r\n", "<br />", $String);
		return $String;
	}
   
   function BBEncode($String) {
      //[img] tags
      $String = preg_replace("/\[img\](.+?)\[\/img\]/","<img src=\"$1\" />",$String);
      //[url] tags
      $String = preg_replace("/\[url\=(.+?)\](.+?)\[\/url\]/","<a href=\"$1\" target=\"_blank\">$2</a>",$String);
      //[b] and [i] tags
      $String = preg_replace("/\[b\](.+?)\[\/b\]/","<strong>$1</strong>",$String);
      $String = preg_replace("/\[i\](.+?)\[\/i\]/","<em>$1</em>",$String);
      return $String;
   }
}

// Instantiate the bbcode object and add it to the string manipulation methods
$BBCodeFormatter = $Context->ObjectFactory->NewObject($Context, "BBCodeFormatter");
$Context->StringManipulator->AddManipulator("BBCode", $BBCodeFormatter);
?>