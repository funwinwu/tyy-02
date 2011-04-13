<?php
/*
 *
 * Copyright (c) Shoppingcart4U Corporation.  All rights reserved.
 *
 * $Id: db_mysql.inc,v 1.2 2000/09/03 19:19:42 nelson Exp $
 *
 */


/***********************************************************************

mySQL Database Access Class

Heavily based on the PHPLIB database access class available at 
http://phplib.netuse.de.

We use only a subset of the functions available in PHPLIB and their syntax is 
exactly the same.  This makes working with previous version of pCart seamless
and keeps a consistent API for database access.  

Methods in the class are:

query($q) - Established connection to database and runs the query returning 
            a query ID if successfull.

next_record() - Returns the next row in the RecordSet for the last query run.  
                Returns False if RecordSet is empty or at the end.

num_rows()  -Returns the number of rows in the RecordSet from a query.

f($field_name) - Returns the value of the given field name for the current
                 record in the RecordSet.  

sf($field_name) - Returns the value of the field name from the $vars variable
                  if it is set, otherwise returns the value of the current
		  record in the RecordSet.  Useful for handling forms that have
		  been submitted with errors.  This way, fields retain the values 
		  sent in the $vars variable (user input) instead of the database
		  values.

p($field_name) - Prints the value of the given field name for the current
                 record in the RecordSet.

sp($field_name) - Prints the value of the field name from the $vars variable
                  if it is set, otherwise prints the value of the current
		  record in the RecordSet.  Useful for handling forms that have
		  been submitted with errors.  This way, fields retain the values 
		  sent in the $vars variable (user input) instead of the database
		  values.


************************************************************************/


class myMysql {
  
  var $lid = 0;             	// Link ID for database connection
  var $qid = 0;			// Query ID for current query
  var $row;			// Current row in query result set
  var $record = array();	// Current row record data
  var $error = "";		// Error Message
  var $errno = "";		// Error Number
  
  var $charset;


	function __construct( $host,$user,$pwd,$db_name,$db_char='' )
	{
		if ($this->lid == 0) {
			$this->lid = mysql_connect($host,$user,$pwd); 
			if (!$this->lid) {
				$this->halt("connect(" . $host . "," . $user,$pwd . ",PASSWORD)  failed.");
			} 

			if (!@mysql_select_db($db_name,$this->lid)) {
				$this->halt("Cannot connect to database ".$db_name);
				return 0;
			}
			
			if( !empty( $db_char ) ){
				$this->query( "set names '{$db_char}'" );
			}
		}
		return $this->lid;
	}
  // Connects to DB and returns DB lid 
  // PRIVATE
  function connect( $host,$user,$pwd,$db_name,$db_char='' )
  {
	return $this->__construct( $host,$user,$pwd,$db_name,$db_char );    
  }


  // Runs query and sets up the query id for the class.
  // PUBLIC
  function query($q) {
    
    if (empty($q))
      return 0;
    /*
    if (!$this->connect()) {
      return 0; 
    }
    */
    if ($this->qid) {
      @mysql_free_result($this->qid);
      $this->qid = 0;
    }
    
    $this->qid = @mysql_query($q, $this->lid);
    $this->row   = 0;
    $this->errno = mysql_errno();
    $this->error = mysql_error();
    if (!$this->qid) {
      $this->halt("Invalid SQL: ".$q);
    }

    return $this->qid;
  }
  

  // Return next record in result set  
  // PUBLIC
  function next_record() {

    if (!$this->qid) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }
    
    $this->record = @mysql_fetch_array($this->qid);
    $this->row   += 1;
    $this->errno  = mysql_errno();
    $this->error  = mysql_error();
    
    $stat = is_array($this->record);
    return $stat;
  }
  
  function fetch_array( ) 
  {
	if (!$this->qid)
	{
		$this->halt("next_record called with no query pending.");
		return 0;
	} 
	$arrRecord =@mysql_fetch_assoc($this->qid);
	$this->row   += 1;
	$this->errno  = mysql_errno();
        $this->error  = mysql_error();
	if( is_array($arrRecord) )
	{
		return  $arrRecord ;
	}
	else
	{
		return  false;
	}
  } 
  
  //function af ,return query result as an array
     function af()
     {
          $arrRecord=array();
		  
          if(!$this->qid){
                $this->halt('af called wi no query pending.');
                return 0;
          }
          while($record_array=@mysql_fetch_assoc($this->qid))
                $arrRecord[]=$record_array;
          if(is_array($arrRecord))
                return $arrRecord;
          else return false;
     }

  // Field Value  return the field
  // PUBLIC 
  function f($field_name) {
    return stripslashes($this->record[$field_name]);
  }

  // Selective field value   selective field return 
  // PUBLIC
  function sf($field_name) {
    global $vars, $default;

    if ($vars["error"] and $vars["$field_name"]) {
      return stripslashes($vars["$field_name"]);
    } elseif ($default["$field_name"]) {
      return stripslashes($default["$field_name"]);
    } else {
      return stripslashes($this->record[$field_name]);
    }
  }                             

  // Print field
  // PUBLIC
  function p($field_name) {
      print stripslashes($this->record[$field_name]);
  }                             

  // Selective print field  
  // PUBLIC
  function sp($field_name) {
    global $vars, $default;

    if ($vars["error"] and $vars["$field_name"]) {
      print stripslashes($vars["$field_name"]);
    } elseif ($default["$field_name"]) {
      print stripslashes($default["$field_name"]);
    } else {
      print stripslashes($this->record[$field_name]);
    }
  }                          

  // Returns the number of rows in query  result
  function num_rows() { 
    
    if ($this->lid) { 
      return @mysql_num_rows($this->qid); //fixed 06-5-2 
    } 
    else { 
      return 0; 
    } 
  }

  // Halt and display error message
  // PRIVATE
  function halt($msg) {
    $this->error = @mysql_error($this->lid);
    $this->errno = @mysql_errno($this->lid);

    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
	   $this->errno,
	   $this->error);
    
    exit;

  }

  // Returns the number of fields in query result
  function num_fields() { 
    
    if ($this->lid) { 
      return @mysql_num_fields($this->qid); 
    } 
    else { 
      return 0; 
    } 
  }
  
  function table_exist( $table_name , $case = 0 )
  {
	!$case && $table_name = strtolower( $table_name ); 
	
	$this->query( 'SHOW TABLES' );	
	$table_fields = array();
	
	while( $this->next_record() ){	
		if( !$case ){
			$table_fields[] = strtolower( $this->f( '0' ) );	
		}else{
			$table_fields[] = $this->f( '0' );	
		}
	}
	
	if( !in_array( $table_name,$table_fields ) ){
		return false;
	}else return true;
  }

}
?>
