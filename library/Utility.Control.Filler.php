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
* Description: The Filler control can be used to dump any custom template in the page.
*/

class Filler extends Control {
   var $TemplateFile;
	var $Properties;
   
	function Filler(&$Context, $templateFile = "") {
		$this->Name = "Filler";
		$this->Control($Context);
		$this->Properties = array();
      if ($templateFile != "") $this->TemplateFile = $templateFile;
	}
	
   function Render() {
      if ($this->TemplateFile != "") {
         $Template = $this->Context->Configuration["THEME_PATH"]."templates/".$this->TemplateFile;
         if (file_exists($Template)) {
            $this->CallDelegate("PreRender");
            include($Template);
            $this->CallDelegate("PostRender");
         }
      }
   }
}
?>