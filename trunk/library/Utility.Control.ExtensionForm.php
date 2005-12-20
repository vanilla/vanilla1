<?php
/*
* Copyright 2003 - 2005 Mark O'Sullivan
* This file is part of Vanilla.
* Vanilla is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
* Vanilla is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Vanilla; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* The latest source code for Vanilla is available at www.lussumo.com
* Contact Mark O'Sullivan at mark [at] lussumo [dot] com
*
* Description: The ExtensionForm control is used to turn extensions on and off in Vanilla.
*/

class ExtensionForm extends PostBackControl {
	
	var $EnabledExtensions;
   var $DisabledExtensions;
	
	// New DefineExtensions Method by Clay Loveless [clay at killersoft dot com] - 2005-08-05
	function DefineExtensions() {
		// We will want to check current Extensions to see if 
		// they are currently used by the system. Read current Extensions
		// once here.
		$CurrExtensions = array();
		$CurrentExtensions = @file($this->Context->Configuration["APPLICATION_PATH"].'conf/extensions.php');
		if (!$CurrentExtensions) {
			$this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrReadFileExtensions').$this->Context->Configuration["APPLICATION_PATH"].'conf/extensions.php');
		} else {
			foreach ($CurrentExtensions as $ExLine) {
				if (substr($ExLine, 0, 7) == 'include') {
					$CurrExtensions[] = substr(trim($ExLine), 43, -3);
				}
			}
		}
		 
		// Examine Extensions directory
		$FolderHandle = @opendir($this->Context->Configuration["EXTENSIONS_PATH"]);
		if (!$FolderHandle) {
			$this->Context->WarningCollector->Add(
				str_replace("//1", $this->Context->Configuration["EXTENSIONS_PATH"], $this->Context->GetDefinition('ErrOpenDirectoryExtensions')));
		} else {
			// Loop through each Extension file
			while (false !== ($Item = readdir($FolderHandle))) {
				$Extension = $this->Context->ObjectFactory->NewObject($this->Context, 'Extension');
				$RecordItem = true;
				// skip directories and hidden files
				if (strlen($Item) < 1) {
					continue;
				}
				if ($Item{0} == '.' || is_dir($this->Context->Configuration["EXTENSIONS_PATH"].$Item)) {
					continue;
				}
				// Retrieve Extension properties
				$Lines = @file($this->Context->Configuration["EXTENSIONS_PATH"].$Item);
				if (!$Lines) {
					$this->Context->WarningCollector->Add($this->Context->GetDefinition('ErrReadExtensionDefinition')." {$Item}");                
				} else {
					// We only examine the first 30 lines of the file
					$Header = array_slice($Lines, 0, 30);
					$Extension->FileName = $Item;
					foreach ($Header as $CurrentLine) {
						@list($key, $val) = @explode(': ', trim($CurrentLine), 2);
						switch ($key) {
							case 'Extension Name':
								$Extension->Name = FormatStringForDisplay($val);
								break;
							case 'Extension Url':
								$Extension->Url = FormatStringForDisplay($val);
								break;
							case 'Description':
								$Extension->Description = FormatStringForDisplay($val);
								break;
							case 'Version':
								$Extension->Version = FormatStringForDisplay($val);
								break;
							case 'Author':
								$Extension->Author = FormatStringForDisplay($val);
								break;
							case 'Author Url':
								$Extension->AuthorUrl = FormatStringForDisplay($val);
								break;
							default:
								// nothing
						}
					}
					if ($Extension->IsValid()) {
						if (in_array($Item, $CurrExtensions)) {
							$this->EnabledExtensions[$this->RemoveFileExtension($Extension->FileName)] = $Extension;
						} else {
							$this->DisabledExtensions[$this->RemoveFileExtension($Extension->FileName)] = $Extension;
						}
					}
				}
			}
		}
	}
	
	function RemoveFileExtension($FileName) {
		$FileNameArray = explode(".", $FileName);
		array_pop($FileNameArray); // Pop the extension off the filename
		return implode(".", $FileNameArray);
	}
	
	function ExtensionForm(&$Context) {
      $this->Name = "ExtensionForm";
		$this->ValidActions = array("Extensions", "ProcessExtension");
		$this->Constructor($Context);
		if (!$this->Context->Session->User->Permission("PERMISSION_MANAGE_EXTENSIONS")) {
			$this->IsPostBack = 0;
		} elseif ($this->IsPostBack) {
	      $this->Extensions = array();
			$this->DefineExtensions();
			if ($this->PostBackAction == "ProcessExtension") {
				$Extension = false;
				$ExtensionKey = ForceIncomingString("ExtensionKey", "");
				$ExtensionInUse = false;
				// Grab that extension from the extension array
            if (array_key_exists($ExtensionKey, $this->EnabledExtensions)) {
					// The extension is currently enabled, so disable it
	            $Extension = $this->EnabledExtensions[$ExtensionKey];
					$ExtensionInUse = true;
					$this->DelegateParameters["ExtensionName"] = $ExtensionKey;
					$this->CallDelegate("PostExtensionDisable");
					
				} elseif (array_key_exists($ExtensionKey, $this->DisabledExtensions)) {
					// The extension is currently disabled, so enable it
	            $Extension = $this->DisabledExtensions[$ExtensionKey];
					$this->DelegateParameters["ExtensionName"] = $ExtensionKey;
					$this->CallDelegate("PostExtensionEnable");
               
				} else {
					$this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrExtensionNotFound"));
				}
				if ($Extension) {
					// Open the extensions file for editing
               $ExtensionsFile = $this->Context->Configuration["APPLICATION_PATH"]."conf/extensions.php";
					$CurrentExtensions = @file($ExtensionsFile);
					if (!$CurrentExtensions) {
						$this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrReadFileExtensions")." ".$this->Context->Configuration["APPLICATION_PATH"]."conf/extensions.php");
					} else {
						// Loop through the lines
                  $ExtensionCount = count($CurrentExtensions);
						for ($j = 0; $j < $ExtensionCount; $j++) {
							if ($ExtensionInUse) {
								if (trim($CurrentExtensions[$j]) == "include(\$Configuration[\"EXTENSIONS_PATH\"].\"".$Extension->FileName."\");") {
									// If the extension is currently in use, remove it
									array_splice($CurrentExtensions, $j, 1);
									$j = count($CurrentExtensions);
								}
							} elseif (trim($CurrentExtensions[$j]) == "?>") {
								// If the extension is NOT currently in use, add it
								$CurrentExtensions[$j] = "include(\$Configuration[\"EXTENSIONS_PATH\"].\"".$Extension->FileName."\");\r\n";
								$CurrentExtensions[] = "?>";
								$j = count($CurrentExtensions);
							}
						}
						// Save the extensions file
						// Open for writing only.
						// Place the file pointer at the beginning of the file and truncate the file to zero length. 
						$FileHandle = @fopen($ExtensionsFile, "wb");
						$FileContents = implode("", $CurrentExtensions);
						if (!$FileHandle) {
							$this->Context->WarningCollector->Add(str_replace("//1", $ExtensionsFile, $this->Context->GetDefinition("ErrOpenFile")));
						} else {
							if (!@fwrite($FileHandle, $FileContents)) $this->Context->WarningCollector->Add($this->Context->GetDefinition("ErrWriteFile"));
						}
						@fclose($FileHandle);
						
						// If everything was successful, redirect back to this page
						if ($this->Context->WarningCollector->Iif()) {
							header("Location: ".$this->Context->SelfUrl."?PostBackAction=Extensions&Detail=".$ExtensionKey."#".$ExtensionKey);
							die();
						} else {
							$this->PostBackAction = "Extensions";
						}
					}
				}
			}
		}
      $this->CallDelegate("Constructor");
	}
	
	function Render() {
		if ($this->IsPostBack) {
			$SelectedExtensionKey = ForceIncomingString("Detail", "");
			$this->CallDelegate("PreRender");
			include($this->Context->Configuration["THEME_PATH"]."templates/settings_extensions.php");
			$this->CallDelegate("PostRender");
		}
	}
}
?>