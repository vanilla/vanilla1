<?php
/*
Extension Name: Html Formatter
Extension Url: http://lussumo.com/docs/
Description: Allows html to be used in strings, but breaks out all "script" related activities.
Version: 1.0
Author: SirNot
Author Url: mailto:sirnot@gmail.com
*/

//thanks to http://ha.ckers.org/xss.html and jos, for pointing that out to me
class HtmlFormatter extends StringFormatter
{
	function Execute($String)
	{
		$Patterns = array(
			"/<[\/]*(link|iframe|frame|object|embed|style|applet).*?>/i", //inline styles'll be allowed, though
			"/<(.+?)>/esi", //doing most of the work inside all tags
			"/s(?i)(cript)/", //now we can go through and cancel out any script tags
			"/S(?i)(cript)/", 
			"/&{(.+?)}/i" //js includes (haven't actually tried this XSS method, just read about it)
		);//unused: "/<(.+?)\s+?on[\w]+\s*=[^>]+? >/i"
		$Replacements = array(
			'', 
			'"<".$this->RemoveEvilAttribs(stripslashes("\\1")).">"', 
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
	
	function RemoveEvilAttribs($String)
	{
		$AllowedProtocols = array('http', 'ftp', 'https', 'irc', 'gopher', 'mailto');
		$P = array(
			"/(\s+?)(href|src|background|url)\s*=(\W*)(.+?):([^\\3]+?)/ei", 
			"/(\s+?)on([\w]+)\s*=(.+?)/i"
		);
		$R = array(
			'stripslashes("\\1\\2=\\3").(in_array(strtolower("\\4"), $AllowedProtocols) ? "\\4:" : "http://").stripslashes("\\5")', 
			'\\1&#79;n\\2=\\3'
		);
		$sReturn = preg_replace($P, $R, $String);
		
		//for situations like: <div style="ex/* */pres/* " */sion(alert('hi'));"></div>
		//ParseCSS() will get nested comments, but if there's a qoute buried in there, this's needed
		do
		{
			$String = $sReturn;
			$sReturn = preg_replace("/style\s*=(\W*)(.+?)\\1/ei", '"style=\\1".$this->ParseCSS(stripslashes("\\2"))."\\1"', $String);
		}
		while($sReturn != $String);
		
		return $sReturn;
	}
	
	function ParseCSS($String)
	{
		return preg_replace(
			array("!/\*((?>[^*\/]+)|(?R)|.*)\*/!i", "/expression\((.+?)\)/i"), //first remove comments, then the expression() functionality 
			array('', '\\1'), //admittadly, there's still probably ways around this, but this was the best I could do short of 
			$String //looping through the entire string
		);
	}
	
	function Parse($String, $Object, $FormatPurpose)
	{
		if($FormatPurpose == agFORMATSTRINGFORDISPLAY)
			// Do this transformation if the string is being displayed
			return $this->Execute($String);
		else
			// Do not perform this transformation if the string is being saved to the db
			return $String;
	}
}

// Instantiate the formatter and add it to the context object's string manipulator
$HtmlFormatter = $Context->ObjectFactory->NewContextObject($Context, "HtmlFormatter");
$Context->StringManipulator->AddManipulator("Html", $HtmlFormatter);
?>