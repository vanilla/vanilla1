<?php
/*
Extension Name: Html Formatter
Extension Url: http://lussumo.com/docs/
Description: Allows html to be used in strings, but breaks out all "script" related activities.
Version: 2.0
Author: SirNot
Author Url: N/A
*/

class HtmlFormatter extends StringFormatter
{
	var $Table;
	var $AllowedProtocols = array('http', 'ftp', 'https', 'irc', 'gopher', 'mailto');
	var $DefaultProtocol = 'http://';
	
	function HtmlFormatter()
	{
		$this->Table = array_flip(get_html_translation_table(HTML_ENTITIES));
		unset($this->Table['&amp;'], $this->Table['&lt;'], $this->Table['&gt;']);
	}
	
	function Execute($String)
	{
		//because of those annoying possibilities such as <img src=">" onerror="alert('hi');">
		$String = $this->ParseTags($String);
		
		$Patterns = array(
			"/<[\/]*(link|iframe|frame|frameset|object|embed|style|applet|meta)[^<]*?>/i", //inline styles'll be allowed, though
			"/<([^>]+)>/ei", 
			"/s(?i)(cript)/", //now we can go through and cancel out any script tags
			"/S(?i)(cript)/", 
			"/&{(.+?)}/i" //js includes (haven't actually tried this XSS method, just read about it)
		);
		$Replacements = array(
			'', 
			'"<".$this->RemoveEvilAttribs(str_replace(chr(0), \' \', $this->DecodeEntities(stripslashes("\\1")))).">"', 
			"&#115;\\1", 
			"&#83;\\1", 
			"&amp;{\\1}" 
		);
		
		$String = str_replace(chr(0), ' ', $String);
		return str_replace(
			array("\r\n", "\r", "\n"), 
			array("\n", "\n", '<br>'), 
			preg_replace($Patterns, $Replacements, $String)
		);
	}
	
	function DecodeEntities($String)
	{
		$String = preg_replace(
			array("/&#x([0-9a-f]{2});?/ei", "/&#(0{0,7})([0-9]{0,7});?/ei", "/&#([0-9]+?);?/ei"), //note that order DOES matter
			array('chr(hexdec("\\1"))', 'chr((int)"\\2")', 'chr((int)"\\1")'), 
			$String);
		$String = strtr($String, $this->Table);
		return $String;
	}
	
	function ParseTags($String)
	{
		$Len = strlen($String);
		$Out = '';
		for($i = $Escape = $CurStr = $InTag = 0; $i < $Len; $i++)
		{
			$Got = 0;
			if($InTag)
			{
				if($String[$i] == '"' || $String[$i] == '\'' || $String[$i] == '`')
				{
					if(!$Escape)
					{
						if(!$CurStr) $CurStr = $String[$i];
						else $CurStr = 0;
					}
					else if($CurStr && @$String[$i+1] == '>') $InTag = $CurStr = 0; //in case we're mistaking escaped quotes for folder paths
				}
				else if($String[$i] == '<')
				{
					$Out .= '&lt;';
					$Got = 1;
				}
				else if($String[$i] == '>')
				{
					if($CurStr)
					{
						$Out .= '&gt;';
						$Got = 1;
					}
					else $InTag = 0;
				}
				else if($String[$i] == "\\") {if(!$Escape && $CurStr) $Escape = 1;}
			}
			else
			{
				if($String[$i] == '<') $InTag = 1;
				else if($String[$i] == '>') {$Out .= '&gt;'; $Got = 1;}
			}
			
			if(!$Got) $Out .= $String[$i];
			if($Escape == 1) $Escape = 2;
			else if($Escape == 2) $Escape = 0;
		}
		if($InTag) $Out .= '>';
		
		return $Out;
	}
	
	function RemoveEvilAttribs($String)
	{
		$P = array(
			"/(\s+?)(href|src|background|url|dynsrc|lowsrc)\s*=(\W*)(.+?):([^\\3]+?)/ei", 
			"/(\s+?)on([\w]+)\s*=(.+?)/i"
		);
		$R = array(
			'stripslashes("\\1\\2=\\3").(in_array(strtolower("\\4"), $this->AllowedProtocols) ? "\\4:" : $this->DefaultProtocol).stripslashes("\\5")', 
			'\\1&#79;n\\2=\\3'
		);
		$sReturn = preg_replace($P, $R, $String);
		
		//for situations like: <div style="ex/* */pres/* " */sion(alert('hi'));"></div>
		//ParseCSS() will get sub-comments, but if there's a qoute buried in there, this's needed
		do
		{
			$String = $sReturn;
			$sReturn = preg_replace("/style\s*=(\W*)(.+)\\1/ei", '"style=".stripslashes("\\1").$this->ParseCSS(stripslashes("\\2")).stripslashes("\\1")', $String);
		}
		while($sReturn != $String);
		
		return $sReturn;
	}
	
	function ParseCSS($String)
	{
		return preg_replace(
			array("#/\*(.*|(?R))\*/#i", "/expression\((.+)\)/i", "/url\s*\((\W*)(.+?):([^\\1)]+?)/ei"), //first remove comments, then the expression() functionality 
			array('', '\\1', 'stripslashes("url(\\1".(in_array("\\2", $this->AllowedProtocols) ? "\\2" : $this->DefaultProtocol).":\\3")'), //admittedly, there's still probably ways around this, but this was the best I could do short of 
			$String //looping through the entire string
		);
	}
	
	function Parse($String, $Object, $FormatPurpose)
	{
		if($FormatPurpose == FORMAT_STRING_FOR_DISPLAY) return $this->Execute($String);
		else return $String;
	}
}

$HtmlFormatter = $Context->ObjectFactory->NewContextObject($Context, "HtmlFormatter");
$Context->StringManipulator->AddManipulator("Html", $HtmlFormatter);

?>