<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Lussumo's Software Library.
* Lussumo's Software Library is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Lussumo's Software Library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
* 
* Description: Non-application specific helper functions
* Applications utilizing this file: Vanilla; Filebrowser;
*/

function AddDaysToTimeStamp($TimeStamp, $NumberOfDaysToAdd) {
	if ($NumberOfDaysToSubtract == 0) {
		return $TimeStamp;
	} else {
		return strtotime("+".$NumberOfDaysToAdd." day", $TimeStamp);
	}
}

// Append a folder (or file) to an existing path (ensures the / exists)
function AppendFolder($RootPath, $FolderToAppend) {
	if (substr($RootPath, strlen($RootPath)-1, strlen($RootPath)) == "/") $RootPath = substr($RootPath, 0, strlen($RootPath) - 1);
	if (substr($FolderToAppend,0,1) == "/") $FolderToAppend = substr($FolderToAppend,1,strlen($FolderToAppend));
	return $RootPath."/".$FolderToAppend;
}

// Append two paths
function ConcatenatePath($OriginalPath, $PathToConcatenate) {
	if (strpos($PathToConcatenate, "http://") !== false) return $PathToConcatenate;
	if (substr($OriginalPath, strlen($OriginalPath)-1, strlen($OriginalPath)) != "/") $OriginalPath .= "/";
	if (substr($PathToConcatenate,0,1) == "/") $PathToConcatenate = substr($PathToConcatenate,1,strlen($PathToConcatenate));
	return $OriginalPath.$PathToConcatenate;
}

// Based on the total number of items and the number of items per page,
// this function will calculate how many pages there are.
// Returns the number of pages available
function CalculateNumberOfPages($ItemCount, $ItemsPerPage) {
	$TmpCount = ($ItemCount/$ItemsPerPage);
	$RoundedCount = intval($TmpCount);
	$PageCount = 0;
	if ($TmpCount > 1) {
		if ($TmpCount > $RoundedCount) {
			$PageCount = $RoundedCount + 1;
		} else {
			$PageCount = $RoundedCount;
		}
	} else {
		$PageCount = 1;
	}
	return $PageCount;
}

// performs the opposite of htmlentities
function DecodeHtmlEntities($String) {
	/*
   $TranslationTable = get_html_translation_table(HTML_ENTITIES);
	print_r($TranslationTable);
   $TranslationTable = array_flip($TranslationTable);
   return strtr($String, $TranslationTable);
	
	return html_entity_decode(htmlentities($String, ENT_COMPAT, 'UTF-8'));
   */
   $String= html_entity_decode($String,ENT_QUOTES,"ISO-8859-1"); #NOTE: UTF-8 does not work!
   $String= preg_replace('/&#(\d+);/me',"chr(\\1)",$String); #decimal notation
   $String= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$String);  #hex notation
   return $String;
	
}

function DefineVerificationKey() {
	// Define the key as an MD5'd string containing 
	// the user's current ip (minus "." delimiters) 
	// concatentated with the current unix timestamp.
	return md5(str_replace(".","",GetRemoteIp()).time());
}

// return the opposite of the given boolean value
function FlipBool($Bool) {
	$Bool = ForceBool($Bool, 0);
	return $Bool?0:1;
}

// Take a value and force it to be an array.
function ForceArray($InValue, $DefaultValue) {
	if(is_array($InValue)) {
		$aReturn = $InValue;
	} else {
		// assume it's a string
		$sReturn = trim($InValue);
		$length = strlen($sReturn);
		if (empty($length) && strlen($sReturn) == 0) {
			$aReturn = $DefaultValue;
		} else {
			$aReturn = array($sReturn);
		}
	}
	return $aReturn;
}

// Force a boolean value
// Accept a default value if the input value does not represent a boolean value
function ForceBool($InValue, $DefaultBool) {
	// If the invalue doesn't exist (ie an array element that doesn't exist) use the default
	if (!$InValue) return $DefaultBool;
	$InValue = strtoupper($InValue);
	if ($InValue == 1) {
		return 1;
	} elseif ($InValue === 0) {
		return 0;
	} elseif ($InValue == "Y") {
		return 1;
	} elseif ($InValue == "N") {
		return 0;		
	} elseif ($InValue == "TRUE") {
		return 1;
	} elseif ($InValue == "FALSE") {
		return 0;
	} else {
		return $DefaultBool;
	}
}

// Take a value and force it to be a float (decimal) with a specific number of decimal places.
function ForceFloat($InValue, $DefaultValue, $DecimalPlaces = 2) {
	$fReturn = floatval($InValue);
	if ($fReturn == 0) $fReturn = $DefaultValue;
	$fReturn = number_format($fReturn, $DecimalPlaces);
	return $fReturn;
}

// Check both the get and post incoming data for a variable
function ForceIncomingArray($VariableName, $DefaultValue) {
	// First check the querystring
	$aReturn = ForceSet(@$_GET[$VariableName], $DefaultValue);
	$aReturn = ForceArray($aReturn, $DefaultValue);
	// If the default value was defined, then check the post variables
	if ($aReturn == $DefaultValue) {
		$aReturn = ForceSet(@$_POST[$VariableName], $DefaultValue);
		$aReturn = ForceArray($aReturn, $DefaultValue);
	}
	return $aReturn;	
}

// Check both the get and post incoming data for a variable
function ForceIncomingBool($VariableName, $DefaultBool) {
	// First check the querystring
	$bReturn = ForceSet(@$_GET[$VariableName], $DefaultBool);
	$bReturn = ForceBool($bReturn, $DefaultBool);
	// If the default value was defined, then check the post variables
	if ($bReturn == $DefaultBool) {
		$bReturn = ForceSet(@$_POST[$VariableName], $DefaultBool);
		$bReturn = ForceBool($bReturn, $DefaultBool);
	}
	return $bReturn;	
}

function ForceIncomingCookieString($VariableName, $DefaultValue) {
	$sReturn = ForceSet(@$_COOKIE[$VariableName], $DefaultValue);
	$sReturn = ForceString($sReturn, $DefaultValue);
	return $sReturn;	
}

// Check both the get and post incoming data for a variable
// Does not allow integers to be less than 0
function ForceIncomingInt($VariableName, $DefaultValue) {
	// First check the querystring
	$iReturn = ForceSet(@$_GET[$VariableName], $DefaultValue);
	$iReturn = ForceInt($iReturn, $DefaultValue);
	// If the default value was defined, then check the form variables
	if ($iReturn == $DefaultValue) {
		$iReturn = ForceSet(@$_POST[$VariableName], $DefaultValue);
		$iReturn = ForceInt($iReturn, $DefaultValue);
	}
	// If the value found was less than 0, set it to the default value
	if($iReturn < 0) $iReturn == $DefaultValue;

	return $iReturn;	
}

// Check both the get and post incoming data for a variable
function ForceIncomingString($VariableName, $DefaultValue) {
	if (isset($_GET[$VariableName])) {
		return Strip_Slashes(ForceString($_GET[$VariableName], $DefaultValue));
	} elseif (isset($_POST[$VariableName])) {
		return Strip_Slashes(ForceString($_POST[$VariableName], $DefaultValue));
	} else {
		return $DefaultValue;
	}
}

// Take a value and force it to be an integer.
function ForceInt($InValue, $DefaultValue) {
	$iReturn = intval($InValue);
	return ($iReturn == 0) ? $DefaultValue : $iReturn;
}

// Takes a variable and checks to see if it's set. 
// Returns the value if set, or the default value if not set.
function ForceSet($InValue, $DefaultValue) {
	return isset($InValue) ? $InValue : $DefaultValue;
}

// Take a value and force it to be a string.
function ForceString($InValue, $DefaultValue) {
	if (is_string($InValue)) {
		$sReturn = trim($InValue);
		if (empty($sReturn) && strlen($sReturn) == 0) $sReturn = $DefaultValue;
	} else {
		$sReturn = $DefaultValue;
	}
	return $sReturn;
}

function FormatHyperlink($InString, $ExternalTarget = "1", $LinkText = "") {
	$ExternalTarget = ForceBool($ExternalTarget, 0);
	$Target = "";
	if ($ExternalTarget) $Target = " target=\"_blank\"";
	if (strpos($InString, "http://") == 0 && strpos($InString, "http://") !== false) {
		if ($LinkText == "") {
			$Display = $InString;
			if (substr($Display, strlen($Display)-1,1) == "/") $Display = substr($Display, 0, strlen($Display)-1);
			$Display = str_replace("http://", "", $Display);
		} else {
			$Display = $LinkText;
		}
		return "<a href=\"".$InString."\"".$Target.">".$Display."</a>";
	} elseif (strpos($InString, "mailto:") == 0 && strpos($InString, "mailto:") !== false) {
		if ($LinkText == "") {
			$Display = str_replace("mailto:", "", $InString);
		} else {
			$Display = $LinkText;
		}
		return "<a href=\"".$InString."\"".$Target.">".$Display."</a>";
	} elseif (strpos($InString, "ftp://") == 0 && strpos($InString, "ftp://") !== false) {
		if ($LinkText == "") {
			$Display = str_replace("ftp://", "", $InString);
		} else {
			$Display = $LinkText;
		}
		return "<a href=\"".$InString."\"".$Target.">".$Display."</a>";
	} elseif (strpos($InString, "aim:goim?screenname=") == 0 && strpos($InString, "aim:goim?screenname=") !== false) {
		if ($LinkText == "") {
			$Display = str_replace("aim:goim?screenname=", "", $InString);
		} else {
			$Display = $LinkText;
		}
		return "<a href=\"".$InString."\"".$Target.">".$Display."</a>";
	} else {
		return ($LinkText == "")?$InString:$LinkText;
	}
}

function FormatHtmlStringForNonDisplay($inValue) {
	return str_replace("\r\n", "<br />", htmlspecialchars($inValue));
}

function FormatHtmlStringInline($inValue, $StripSlashes = "0") {
	// $sReturn = ForceString($inValue, "");
   $sReturn = $inValue;
	if (ForceBool($StripSlashes, 0)) $sReturn = Strip_Slashes($sReturn);
	return str_replace("\r\n", " ", htmlspecialchars($sReturn));
}

function FormatPlural($Number, $Singular, $Plural) {
	return ($Number == 1) ? $Singular : $Plural;
}

// Formats a value so it's safe to insert into the database
function FormatStringForDatabaseInput($inValue, $bStripHtml = "0") {
	$bStripHtml = ForceBool($bStripHtml, 0);
	// $sReturn = stripslashes($inValue);
   $sReturn = $inValue;
	if ($bStripHtml) $sReturn = trim(strip_tags($sReturn));
	// return MAGIC_QUOTES_ON ? $sReturn : addslashes($sReturn);
   return addslashes($sReturn);
}

// Takes a user defined string and formats it for page display. 
// You can optionally remove html from the string.
function FormatStringForDisplay($inValue, $bStripHtml = true, $AllowEncodedQuotes = true) {
	$sReturn = trim($inValue);
	// $sReturn = stripslashes($sReturn);
	if ($bStripHtml) {
		$sReturn = strip_tags($sReturn);
		$sReturn = str_replace("\r\n", "<br />", $sReturn);
	}
	if (!$AllowEncodedQuotes) $sReturn = preg_replace("/(\"|\')/", "", $sReturn);
	global $Configuration;
	$sReturn = htmlspecialchars($sReturn, ENT_QUOTES, $Configuration["CHARSET"]);
	/*
	$sReturn = preg_replace('#(&\#*\w+)[\x00-\x20]+;#U',"$1;", $sReturn);
	$sReturn = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"$1$2;", $sReturn);
	$sReturn = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU',"$1>", $sReturn);
	$sReturn = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2nojavascript...', $sReturn);
	$sReturn = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2novbscript...', $sReturn);
	$sReturn = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2nojavascript...', $sReturn);
	$sReturn = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iU','$1=$2novbscript...', $sReturn);
	$sReturn = preg_replace('#</*\w+:\w[^>]*>#i',"", $sReturn);
										*/
	return $sReturn;

}

function GetBasicCheckBox($Name, $Value = 1, $Checked, $Attributes = "") {
	return "<input type=\"checkbox\" name=\"".$Name."\" value=\"".$Value."\" ".(($Checked == 1)?" checked=\"checked\"":"")." $Attributes />";
}

function GetBool($Bool, $True = "Yes", $False = "No") {
	return ($Bool ? $True : $False);
}

function GetDynamicCheckBox($Name, $Value = 1, $Checked, $OnClick, $Text, $Attributes = "") {
	$CheckBoxID = $Name."ID";
	$Attributes .= " id=\"".$CheckBoxID."\"";
	if ($OnClick != "") $Attributes .= " onclick=\"".$OnClick."\"";
	// return GetBasicCheckBox($Name, $Value, $Checked, $Attributes)
	// 	." <a href=\"javascript:CheckBox('".$CheckBoxID."');".$OnClick."\">".$Text."</a>";
   return "<label>".GetBasicCheckBox($Name, $Value, $Checked, $Attributes)." ".$Text."</label>";
}

function GetEmail($Email, $LinkText = "") {
	if ($Email == "") {
		return "&nbsp;";
	} else {
		$EmailParts = explode("@", $Email);
		if (count($EmailParts) == 2) {
			return "<script type=\"text/javascript\">\r\nWriteEmail('".$EmailParts[1]."', '".$EmailParts[0]."', '".$LinkText."');\r\n</script>";
		} else {
			// Failsafe
			return "<a href=\"mailto:".$Email."\">".($LinkText==""?$Email:$LinkText)."</a>";
		}
	}
}

function GetImage($ImageUrl, $Height = "", $Width = "", $TagIdentifier = "", $EmptyImageReplacement = "&nbsp;") {
	$sReturn = "";
	if (ReturnNonEmpty($ImageUrl) == "&nbsp;") {
		$sReturn =  $EmptyImageReplacement;
	} else {
		$sReturn = "<img src=\"$ImageUrl\"";
		if ($Height != "") $sReturn .= " height=\"$Height\"";
		if ($Width != "") $sReturn .= " width=\"$Width\"";
		if ($TagIdentifier != "") $sReturn .= " id=\"$TagIdentifier\"";
		$sReturn .= " alt=\"\" border=\"0\" />";
	}
	return $sReturn;
}

function GetRemoteIp($FormatIpForDatabaseInput = "0") {
	$FormatIpForDatabaseInput = ForceBool($FormatIpForDatabaseInput, 0);
	$sReturn = ForceString(@$_SERVER['REMOTE_ADDR'], "");
	if (strlen($sReturn) > 20) $sReturn = substr($sReturn, 0, 19);
	if ($FormatIpForDatabaseInput) $sReturn = FormatStringForDatabaseInput($sReturn, 1);
	return $sReturn;	
}

function GetUrl(&$Configuration, $PageName, $Divider = "", $Key = "", $Value = "", $PageNumber="", $Querystring="") {
	if ($Configuration["URL_BUILDING_METHOD"] == "mod_rewrite") {
		if ($PageName == "./") $PageName = "index.php";
		return $Configuration["BASE_URL"]
			.($PageName == "index.php" && $Value != "" ? "" : $Configuration["REWRITE_".$PageName])
			.(strlen($Value) != 0 ? $Divider : "")
			.(strlen($Value) != 0 ? $Value."/" : "")
			.($PageNumber != "" && ForceInt($PageNumber, 0) > 1? $PageNumber."/" : "")
			.($Querystring != "" && substr($Querystring, 0, 1) != "#" ? "?" : "")
			.($Querystring != "" ? $Querystring : "");
	} else {
		if ($PageName == "./" || $PageName == "index.php") $PageName = "";
		$sReturn = ($Value != "" && $Value != "0" ? $Key."=".$Value : "");
		if ($PageNumber != "") {
			if ($sReturn != "") $sReturn .= "&amp;";
			$sReturn .= "page=".$PageNumber;
		}
		if ($Querystring != "" && substr($Querystring, 0, 1) != "#") {
			if ($sReturn != "") $sReturn .= "&amp;";
			$sReturn .= $Querystring;
		}
		if ($sReturn != "") $sReturn = "?".$sReturn;
		return $Configuration["BASE_URL"].$PageName.$sReturn;
	}
}

// Create the html_entity_decode function for users prior to PHP 4.3.0
if (!function_exists("html_entity_decode")) {
	function html_entity_decode($String) {
		return strtr($String, array_flip(get_html_translation_table(HTML_ENTITIES)));
	}
}

// allows inline if statements
function Iif($Condition, $True, $False) {
	return $Condition ? $True : $False;
}

function MysqlDateTime($Timestamp = "") {
	if ($Timestamp == "") $Timestamp = mktime();
	return date("Y-m-d H:i:s", $Timestamp);
}

function PrefixString($string, $prefix, $length) {
	if (strlen($string) >= $length) {
		return $string;
	} else {
		return substr(($prefix.$string),strlen($prefix.$string)-$length, $length);
	}
}

function PrependString($Prepend, $String) {
	$pos = strpos(strtolower($String), strtolower($Prepend));
	if (($pos !== false && $pos == 0) || $String == "") {
		return $String;
	} else {
		return $Prepend.$String;
	}
}

// If a value is empty, return the non-empty value
function ReturnNonEmpty($InValue, $NonEmptyValue = "&nbsp;") {
	return trim($InValue) == "" ? $NonEmptyValue : $InValue;
}

function SaveAsDialogue($FolderPath, $FileName, $DeleteFile = "0") {
	$DeleteFile = ForceBool($DeleteFile, 0);
	if ($FolderPath != "") {
		if (substr($FolderPath,strlen($FolderPath)-1) != "/") $FolderPath = $FolderPath."/";
	}
	$FolderPath = $FolderPath.$FileName;
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment; filename=$FileName");
	header("Content-Transfer-Encoding: binary");
	readfile($FolderPath);
	if ($DeleteFile) unlink($FolderPath);
	die();
}

function SerializeArray($InArray) {
	$sReturn = "";
	if (is_array($InArray)) {
		if (count($InArray) > 0) {
			$sReturn = serialize($InArray);
			$sReturn = addslashes($sReturn);
		}
	}
	return $sReturn;
}

// Cuts a string to the specified length. 
// Then moves back to the previous space so words are not sliced half-way through.
function SliceString($InString, $Length) {
	$Space = " ";
	$sReturn = "";
	if (strlen($InString) > $Length) {
		$sReturn = substr(trim($InString), 0, $Length); 
		$sReturn = substr($sReturn, 0, strlen($sReturn) - strpos(strrev($sReturn), $Space));
	   $sReturn .= "...";
	} else {
		$sReturn = $InString;
	}
	return $sReturn;
}

function Strip_Slashes($InString) {
	return MAGIC_QUOTES_ON ? stripslashes($InString) : $InString;		
}

function SubtractDaysFromTimeStamp($TimeStamp, $NumberOfDaysToSubtract) {
	if ($NumberOfDaysToSubtract == 0) {
		return $TimeStamp;
	} else {
		return strtotime("-".$NumberOfDaysToSubtract." day", $TimeStamp);
	}
}

function TimeDiff(&$Context, $Time, $TimeToCompare = "") {
	if ($TimeToCompare == "") $TimeToCompare = time();
	$Difference = $TimeToCompare-$Time;
	$Days = floor($Difference/60/60/24);
   
	if ($Days > 7) {
		return date($Context->GetDefinition("OldPostDateFormatCode"), $Time);
	} elseif ($Days > 1) {
		return str_replace("//1", $Days, $Context->GetDefinition("XDaysAgo"));
	} elseif ($Days == 1) {
		return str_replace("//1", $Days, $Context->GetDefinition("XDayAgo"));
	} else {
		
		$Difference -= $Days*60*60*24;
		$Hours = floor($Difference/60/60);
		if ($Hours > 1) {
			return str_replace("//1", $Hours, $Context->GetDefinition("XHoursAgo"));
		} elseif ($Hours == 1) {
			return str_replace("//1", $Hours, $Context->GetDefinition("XHourAgo"));
		} else {
			
			$Difference -= $Hours*60*60;
			$Minutes = floor($Difference/60);			
			if ($Minutes > 1) {
				return str_replace("//1", $Minutes, $Context->GetDefinition("XMinutesAgo"));
			} elseif ($Minutes == 1) {
				return str_replace("//1", $Minutes, $Context->GetDefinition("XMinuteAgo"));
			} else {
				
				$Difference -= $Minutes*60;
				$Seconds = $Difference;
				if ($Seconds == 1) {
					return str_replace("//1", $Seconds, $Context->GetDefinition("XSecondAgo"));
				} else {
					return str_replace("//1", $Seconds, $Context->GetDefinition("XSecondsAgo"));
				}
			}
		}
	}
}

// Convert a datetime to a timestamp
function UnixTimestamp($DateTime) {
	if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $DateTime, $Matches)) {
		$Year = $Matches[1];
		$Month = $Matches[2];
		$Day = $Matches[3];
		$Hour = $Matches[4];
		$Minute = $Matches[5];
		$Second = $Matches[6];
		return mktime($Hour, $Minute, $Second, $Month, $Day, $Year);
	
	} elseif (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $DateTime, $Matches)) {
		$Year = $Matches[1];
		$Month = $Matches[2];
		$Day = $Matches[3];	
		return mktime(0, 0, 0, $Month, $Day, $Year);	
	}
}

function UnserializeArray($InSerialArray) {
	$aReturn = array();
	if ($InSerialArray != "" && !is_array($InSerialArray)) {
		$aReturn = unserialize($InSerialArray);
		if (is_array($aReturn)) {
			$Count = count($aReturn);
			$i = 0;
			for ($i = 0; $i < $Count; $i++) {
				$aReturn[$i] = array_map("Strip_Slashes", $aReturn[$i]);
			}
		}
	}
	return $aReturn;	
}

function UnserializeAssociativeArray($InSerialArray) {
	$aReturn = array();
	if ($InSerialArray != "" && !is_array($InSerialArray)) {
		$aReturn = unserialize($InSerialArray);
	}
	return $aReturn;	
}

// Instantiate a simple validator
function Validate($InputName, $IsRequired, $Value, $MaxLength, $ValidationExpression, &$Context) {
	$Validator = $Context->ObjectFactory->NewContextObject($Context, "Validator");
	$Validator->InputName = $InputName;
	$Validator->isRequired = $IsRequired;
	$Validator->Value = $Value;
	$Validator->MaxLength = $MaxLength;
	if ($ValidationExpression != "") {
		$Validator->ValidationExpression = $ValidationExpression;
		$Validator->ValidationExpressionErrorMessage = $Context->GetDefinition("ErrImproperFormat")." ".$InputName;
	}
	return $Validator->Validate();
}

function WriteEmail($Email, $LinkText = "") {
	echo(GetEmail($Email, $LinkText));
}
	
?>