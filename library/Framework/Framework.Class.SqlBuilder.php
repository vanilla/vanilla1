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
* DESCRIPTION: Class that builds a string of sql to be executed
* Applications utilizing this file: Vanilla;
*/
class SqlBuilder {
	var $Fields;		// String of select fields
	var $FieldValues;	// Array of field name/value pairs for inserting/updating
	var $MainTable;		// Associative array with information about the main table in the statement
	var $Joins;			// String of join clauses
	var $Wheres;		// String of where clauses
	var $GroupBys;		// String of group by fields
	var $OrderBys;		// String of order by clauses
	var $Limit;			// Limit for a select
	var $Name;			// The name of this class
	var $TablePrefix;	// Prefix all tables with this string
   var $Context;
	
	
	function AddFieldNameValue($FieldName, $FieldValue = "", $QuoteValue = 1, $Function = "") {
		if ($QuoteValue) $FieldValue = "'".$FieldValue."'";
		if ($Function != "") $FieldValue = $Function."(".$FieldValue.")";
		$this->FieldValues[$FieldName] = $FieldValue;
	}
	
	function AddGroupBy($Field, $TableAlias = "") {
		if (is_array($Field)) {
			$FieldCount = count($Field);
			$i = 0;
			for ($i = 0; $i < $FieldCount; $i++) {
				$this->AddGroupBy($Field[$i],$TableAlias);
			}
		} else {
			if ($Field != "") {
				if ($TableAlias != "") $Field = $TableAlias.".".$Field;
				if ($this->GroupBys != "") $this->GroupBys .= ", ";
				$this->GroupBys .= $Field;
			}
		}
	}
	
	// Adds a table to the join clause
	function AddJoin($NewTable, $NewTableAlias, $NewTableField, $ExistingAlias, $ExistingField, $JoinMethod) {
		$this->Joins .= $JoinMethod." ".$this->TablePrefix.$NewTable." ".$NewTableAlias
			." on ".$ExistingAlias.".".$ExistingField." = ".$NewTableAlias.".".$NewTableField." ";
	}
	
	function AddLimit($Index, $Length) {
		$this->Limit = " limit $Index, $Length";
	}
	
	function AddOrderBy($FieldName, $TableAlias = "", $SortDirection = "asc") {
		if ($this->OrderBys != "") $this->OrderBys .= ", ";
		if ($TableAlias != "") $FieldName = $TableAlias.".".$FieldName;
		$this->OrderBys .= " ".$FieldName." ".$SortDirection;
	}
	
	// $Field == the field to select (or fields if you supply an array)
	// $TableAlias == the alias of the table to select the field from
	// $FieldAlias == the alternate name for a field (ie. select field as blah) - ignored if you supply an array for $Field
	function AddSelect($Field, $TableAlias = "", $FieldAlias = "", $Function = "", $FunctionParameters = "", $GroupByThisField = "0") {
		if (is_array($Field)) {
			$FieldCount = count($Field);
			$i = 0;
			for ($i = 0; $i < $FieldCount; $i++) {
				$this->AddSelect($Field[$i],$TableAlias, "", "", "", $GroupByThisField);
			}
		} else {
			if ($Field != "") {
				// $GroupByThisField = ForceBool($GroupByThisField, 0);
				if ($GroupByThisField) {
					if ($this->GroupBys != "") $this->GroupBys .= ", ";
					$this->GroupBys .= ($TableAlias != "" ? $TableAlias.".".$Field : $Field);
					// $this->AddGroupBy($Field, $TableAlias);
				}
				if ($TableAlias != "") $Field = $TableAlias.".".$Field;
				if ($Function != "" && $FunctionParameters == "") $Field = $Function."(".$Field.")";
				if ($Function != "" && $FunctionParameters != "") $Field = $Function."(".$Field.", ".$FunctionParameters.")";
				if ($this->Fields != "") $this->Fields .= ", ";
				$this->Fields .= $Field;
				if ($FieldAlias != "") $this->Fields .= " as ".$FieldAlias;
			}
		}
	}
	
	// $Parameter1 == the first field in the comparison operation
	// $Parameter2 == the second field in the comparison operation
	// $Comparison operator == "=,>,<,in,<>,like" etc
	// $AppendMethod == the method by which this where should be attached to existing wheres
	function AddWhere($Parameter1, $Parameter2, $ComparisonOperator, $AppendMethod = "and", $Function = "", $QuoteParameter2 = "1", $StartWhereGroup = "0") {
		$StartWhereGroup = ForceBool($StartWhereGroup, 0);

		// Add the append method if there is an existing clause
		if (!empty($this->Wheres) && substr($this->Wheres,strlen($this->Wheres)-1) != "(") {
			$this->Wheres .= $AppendMethod." ";
		}
		if ($StartWhereGroup) $this->Wheres .= "(";
		if ($QuoteParameter2 == '1') $Parameter2 = "'".$Parameter2."'";
		if ($Function != "") $Parameter2 = $Function."(".$Parameter2.")";
		
		// Do the comparison operation
		$this->Wheres .= $Parameter1." ".$ComparisonOperator." ".$Parameter2." ";
	}
	
	function Clear() {
		$this->Fields = "";
		$this->FieldValues = array();
		$this->MainTable = array();
		$this->Joins = "";
		$this->Wheres = "";
		$this->GroupBys = "";
		$this->OrderBys = "";
		$this->Limit = "";
		$this->Name = "SqlBuilder";
		$this->TablePrefix = $this->Context->Configuration["DATABASE_TABLE_PREFIX"];
	}
	
	function EndWhereGroup() {
		$this->Wheres .= ") ";
	}
	
	// Returns a delete statement
	function GetDelete() {
		$sReturn = "delete ";
		$sReturn .= "from ".$this->MainTable["TableName"]." ";
		if ($this->Wheres != "") $sReturn .= " where ".$this->Wheres." ";
		$this->WriteDebug($sReturn);
		return $sReturn;
	}

	// Returns an insert statement
	function GetInsert($UseIgnore = "0") {
		$sReturn = "insert ";
		if ($UseIgnore == "1") $sReturn .= "ignore ";
		$sReturn .= "into ";
		$sReturn .= $this->MainTable["TableName"]." ";
		$Fields = "";
		$Values = "";
		while (list($name, $value) = each($this->FieldValues)) {
			if ($Fields != "") {
				$Fields .= ", ";
				$Values .= ", ";
			}
			$Fields .= $name;
			$Values .= $value;
		}
		reset($this->FieldValues);
		$sReturn .= "($Fields) ";
		$sReturn .= "values ($Values)";
		$this->WriteDebug($sReturn);
		return $sReturn;
	}
	
	// Returns a select statement
	function GetSelect($SelectPrefix = "") {
		$sReturn = $SelectPrefix." select ";
		$sReturn .= $this->Fields." ";
		
		// Build the from statement
		$sReturn .= "from ".$this->MainTable["TableName"]." ";
		$TableAlias = ForceString($this->MainTable["TableAlias"], "");
		if ($TableAlias != "") $sReturn .= $TableAlias." ";
		
		$sReturn .= $this->Joins." ";
		if ($this->Wheres != "") $sReturn .= " where ".$this->Wheres." ";
		if ($this->GroupBys != "") $sReturn .= " group by ".$this->GroupBys;
		if ($this->OrderBys != "") $sReturn .= " order by ".$this->OrderBys;
		$sReturn .= $this->Limit;
		$this->WriteDebug($sReturn);
		return $sReturn;
	}

	// returns an update statement
	function GetUpdate() {
		$sReturn = "update ";
		$sReturn .= $this->MainTable["TableName"]." set ";
		$Delimiter = "";
		while (list($name, $value) = each($this->FieldValues)) {
			$sReturn .= $Delimiter.$name."=".$value;
			$Delimiter = ", ";
		}
		reset($this->FieldValues);
		if ($this->Wheres != "") $sReturn .= " where ".$this->Wheres;
		$this->WriteDebug($sReturn);
		return $sReturn;
	}
	
	// Takes the current where clause and wraps it in parentheses
	function GroupWheres() {
		$this->Wheres = "(".$this->Wheres.") ";
	}
	
	// If the user specifies two selectfrom's, this will effectively overwrite any previous items with the current one
	function SetMainTable($TableName, $TableAlias = "") {
		$this->MainTable = array("TableName" => $this->TablePrefix.$TableName, "TableAlias" => $TableAlias);
	}

	function StartWhereGroup() {
		$this->Wheres .= " (";
	}
	
	function SqlBuilder(&$Context) {
		$this->Context = &$Context;
		$this->Clear();
	}
	
	function WriteDebug($String) {
		if ($this->Context->Session->User) {
			if ($this->Context->Session->User->Permission("PERMISSION_ALLOW_DEBUG_INFO") && $this->Context->Mode == MODE_DEBUG) $this->Context->SqlCollector->Add($String);
		}
$this->Context->SqlCollector->Add($String);
	}
}
?>