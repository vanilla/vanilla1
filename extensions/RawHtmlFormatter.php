<?php
/*
Extension Name: Raw Html Formatter
Extension Url: http://lussumo.com/docs/
Description: Allows clean, unfiltered html & scripts to be used in comments. WARNING: This will allow your members to post javascript in their comments - giving hackers access to other member's cookies. It should ONLY be used on a forum where you trust your members implicitly.
Version: 1.0
Author: Mark O'Sullivan
Author Url: http://www.markosullivan.ca/
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

class RawHtmlFormatter extends StringFormatter {
   function Parse($String, $Object, $FormatPurpose) {
      // Do not perform this transformation if the string is being saved to the db
      return $String;
   }
}

// Instantiate the formatter and add it to the context object's string manipulator
$RawHtmlFormatter = $Context->ObjectFactory->NewContextObject($Context, "RawHtmlFormatter");
$Context->StringManipulator->AddManipulator("Raw Html", $RawHtmlFormatter);
?>