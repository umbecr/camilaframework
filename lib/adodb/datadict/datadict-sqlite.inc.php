<?php

/**
  V4.93 10 Oct 2006  (c) 2000-2006 John Lim (jlim#natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.
	
  Set tabs to 4 for best viewing.
 
*/

// security - hide paths
if (!defined('ADODB_DIR')) die();

class ADODB2_sqlite extends ADODB_DataDict {
	
	var $databaseType = 'generic';
	var $seqField = false;
	
 	function ActualType($meta)
	{
		switch($meta) {
			case 'B' :
                case 'C' : 
                case 'C2':
                    return 'VARCHAR';
                case 'XL':
                case 'X' : 
                case 'X2': 
                    return 'VARCHAR(250)';
                case 'D' : 
                case 'T' : 
                    return 'DATE';
                    
                case 'L' : 
                case 'I' : 
                case 'I1':
                case 'I2': 
                case 'I4': 
                case 'I8': 
                    return 'INTEGER';
                    
                case 'F': return 'DECIMAL(32,8)';
                case 'N': return 'DECIMAL';
                default:
                    return $meta;
		}
	}


	function AlterColumnSQL($tabname, $flds)
	{
		if ($this->debug) ADOConnection::outp("AlterColumnSQL not supported");
		return array();
	}
	
	
	function DropColumnSQL($tabname, $flds)
	{
		if ($this->debug) ADOConnection::outp("DropColumnSQL not supported");
		return array();
	}
	
}

?>