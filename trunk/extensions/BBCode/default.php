<?php
/*
Extension Name: BB Code
Extension Url: http://lussumo.com/docs/
Description: A BBCode-to-HTML conversion tool for discussion comments
Version: 1.1
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
	var $AllowedProtocols = array('http', 'https', 'ftp', 'news', 'nntp', 'feed', 'gopher', 'mailto');
	var $DefaultProtocol = 'http://';
	
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
   
   function Url($String)
   {
   	   	$String = str_replace("\\\"", '&quot;', $String);
		$SplitUrl = explode('://', $String);
		
		if(count($SplitUrl) < 2) return $this->DefaultProtocol.$String;
		else if(!in_array($SplitUrl[0], $this->AllowedProtocols)) return $this->DefaultProtocol.$String;
		else return $String;
   }
   
   function BBEncode($String)
   {
   	   $Patterns = array(
	      "/\[img\](.+?)\[\/img\]/ei", 
	      "/\[url\=(.+?)\](.+?)\[\/url\]/ei", 
	      "/\[url\](.+?)\[\/url\]/ei", 
	      "/\[b\](.+?)\[\/b\]/is", 
	      "/\[i\](.+?)\[\/i\]/is", 
	      "/\[u\](.+?)\[\/u\]/is"
	  );
      
      $Replacements = array(
	      '\'<img src="\'.$this->Url(\'$1\').\'" />\'', 
	      '\'<a href="\'.$this->Url(\'$1\').\'" target="_blank">$2</a>\'', 
	      '\'<a href="\'.$this->Url(\'$1\').\'">$1</a>\'', 
	      '<strong>$1</strong>', 
	      '<em>$1</em>', 
	      '<u>$1</u>'
	  );
      
      return preg_replace($Patterns, $Replacements, $String);
   }
}
// Instantiate the bbcode object and add it to the string manipulation methods
$BBCodeFormatter = $Context->ObjectFactory->NewObject($Context, "BBCodeFormatter");
$Context->StringManipulator->AddManipulator("BBCode", $BBCodeFormatter);
?>