<?php

/*
 * Generic / Common functions are stored here. 
 */

/**
 * This function validates the input as best as generically possible.
 * It checks every value in the array to see if it is "". If it is then it is invalid 
 * and the associated key is added to the "error" array for searching. 
 *
 * It also checks if the key is "email". If it is then it additionally checks to see if the 
 * value is a proper e-mail address by ensuring that '@' and '.' are both present in the 
 * string. 
 *
 * @return TRUE if and only if all of the values in the array are valid according to the 
 * 	above definition. 
 *
 * Note: this function was taken from ethanwilder.com with the permission of Ethan Wilder (me) 
 */
function validateForm($array) {
	$ret = TRUE;
	$error = array();	//this was originally part of an object with an error array we're not using it here.
		
	foreach($array as $k => $v) {

		/* If it's an ignore then we ignore it */
		if($v == "ignore")
			continue;

		/* Check for validity */
		if( ($v == "") || 
		    (strtolower($v) == "select") 
		  ) {
			array_push($error, $k);
			$ret = FALSE;

			continue;
		}

		/* Special E-mail Check */
		if(strtolower($k) == "email") {
			if( (strpos($v, "@") === FALSE) || 
			    (strpos($v, ".") === FALSE) 
			) {
				array_push($error, $k);
				$ret = FALSE;
			}
		}
	}

	return $ret;

}


/**
 * This function displays a list of all the elements in the table in HTML selection box format.
 * This function takes advantage of the MySQL database naming policies in order to accomplish this.
 * It utilizes get_pkey() to get the primary key of that table.
 * 
 * @param $table - the table to select from
 * @param $selected - This parameter will tell which option is to be default selected.  
 *
 * @return void - this function prints its output.
 */
function showTableOptions($table, $selected="null") {


	$uid = get_pkey($table);
	$name = get_unique_name($table);
	
	if($table=='CAPdata_programs')
	{
	$q = mysql_query("SELECT $uid, $name
			  FROM $table WHERE program_type='breeding'
			  ORDER BY $uid
			")
	     or die(mysql_error());
	}
	else
	{
	$q = mysql_query("SELECT $uid, $name
			  FROM $table 
			  ORDER BY $uid
			")
	     or die(mysql_error());
	}

	while($row = mysql_fetch_assoc($q)) {
		$sel = ($row[$uid] == $selected) ? "selected=\"selected\"" : "";
		echo "\n\t<option value=\"$row[$uid]\" $sel>$row[$name]</option>";
	}

}

/**
 * This function gets the primary key by querying the database
 * and then selecting the first field name in the database. That name
 * is always the primary key according to our database scheme
 *
 * @param $tablename - the table of which to get the primary key of.
 * @param $pkey - the primary key of the table
 */
function get_pkey ($tablename) {

	$result = mysql_query("DESC $tablename");
	$pkey="";

	while($row = mysql_fetch_assoc($result)) {
		foreach($row as $k=>$v) {
			if (strtolower($k)=='field') {
				$pkey=$v;
			}
			break;
		}
		break;
	}
	return $pkey;
}

/**
 * This function gets the unique name of the table that we have set 
 * a standard to be. It looks at the table creation string and finds
 * the first field with "name" in it. Pretty simple.
 *
 * @param $table - the table of which to search through.
 * @return the unique name field.
 *
 */
function get_unique_name($table) {

	$q = mysql_query("SHOW CREATE TABLE $table") or die(mysql_error());
	$row = mysql_fetch_row($q);
	
	$parts = explode("\n", $row[1]);

	for($i = 2; $i<count($parts); $i++) {

	  // dem nov10: Sometimes the column name is just "name", e.g. marker_synonym_types.
	  // if(preg_match("/_name/", $parts[$i], $matches)) {
	  if(preg_match("/name/", $parts[$i], $matches)) {
			$line = explode("`", $parts[$i]);
			return $line[1];
		}
	}
}

/**
 * This function gets the maximum number of a certain field of a the specified table.
 *
 * @param $table - the table of which to select from
 * @param $id - the field to select the maximum of.
 * @return - the maximum field value.
 */
function getMaxId($table, $id) {

	$res = mysql_query("SELECT max($id) FROM $table");
	$t = mysql_fetch_row($res);
	return $t[0];
}


/**
 * This function gets the total number of entries in a particular table. 
 * 
 * @param $table - the table of which to count entries of.
 * @return the number of entries in that table.
 */
function getNumEntries($table) {

	$res = mysql_query("SELECT count(*) FROM $table");
	$a = mysql_fetch_row($res);
	return $a[0];
}



function display_uploaded_file($filename) {

	require_once("excel/reader.php");	//include excel reader

	/* Creating the object */
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
	$data->read($filename);

	$data->trimSheet(0); 	//new function that I added to trim columns

	/* Setting Error Reporting */
	error_reporting(E_ALL ^ E_NOTICE);
		
	/* Show in table format */
	print "<table class=\"tableclass1\">";
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {

		/* Determine Row Colors */
		if($i == 1) 
			echo "<thead>";
		if($i == 3)
			echo "</thead>";

		/* Print Row */
		print "\n\t<tr>";
		for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
			echo "\n\t\t<td class=\"$class\">".$data->sheets[0]['cells'][$i][$j]."</td>";
		}
		echo "\n\t</tr>";
	}
	print "\n</table>";
}

/**
 * add_attribute 
 * try to add an attribute to a table in a MySQL database
 * 
 * @params string $key, string $value, string $tablename, string $keyid
 * @return array ( int $flag, int $id) $flag=-1->N/A, 0->duplicate, 1->OK, $id the record number
 */ 
function add_attribute ($key, $value, $tablename, $keyid) {

	$id=-1;
	$flag=-1;
	$value = addslashes($value);

	if (! isset($value) || $value=='') {
		$value="N/A";
	}

	$result=mysql_query("SELECT * FROM $tablename WHERE $key=\"$value\"");
	$num_rows=mysql_num_rows($result);

	if ($num_rows<=0) {
		mysql_query("insert into $tablename ($key, created_on) values (\"$value\", NOW())") ;// || die (mysql_error());
		$id=mysql_insert_id();
		$flag=1;
	}
	else {
		while( $row = mysql_fetch_assoc($result) ) {

			$id=$row[$keyid];
			$flag=0;

		}
	}	
	if ($value=="N/A") {
		$flag=-1;
	}	
	return array($flag, $id);
}	

function add_array_attributes($vals, $isnum, $tablename, $testKey, $testVal, $idkey) {

	$keystr="";
	$valstr="";

	$vsize=count($vals);
	$ks=array_keys($vals);

	$id=-1;

	/* 
	 * Does this entry exist in the database already? 
	 */
	$testResult = mysql_query("SELECT * FROM $tablename WHERE $testKey = \"$testVal\"");
	if(mysql_num_rows($testResult) > 0) {
		$row = mysql_fetch_assoc($testResult);
		$id=$row[$idkey];
		return array(0,$id); 			//execution will end here
	}

	/*
	 * Generate the Keys and Values for insertion
	 */
	for ($i=0; $i<$vsize; $i++) {

		$keystr.=$ks[$i];
		if ($isnum[$i]==1) {

			//default value of 0 for numbers
			if(!isset($vals[$ks[$i]]) || $vals[$ks[$i]] == "")
				$vals[$ks[$i]] = 0;

			$valstr.=$vals[$ks[$i]];
		}
		else {
			$valstr.="\"".addslashes($vals[$ks[$i]])."\"";
		}
		if ($i<$vsize-1) {
			$keystr.=",";
			$valstr.=",";
		}

	}

	mysql_query("insert into $tablename ($keystr) values ($valstr)");// || die(mysql_errno() . ":" . mysql_error());

	$id=mysql_insert_id();
	return array(1,$id);
}

/**
 * Add data into database table, default - insert; duplicate w/o conflict - update; duplicate w/ conflict - error/update
 * 
 * @param array $vals the keys and values of the data
 * @param array $isnum the property of the fields
 * @param string $tablename the name of the table for data input
 * @param array $testKey the keys to check for duplicates
 * @param array $testVal the values to be checked for duplicates in the database
 * @param string $idkey the field of the primary key
 * @param string $action the action when there is ducplicates
 * @return array ($flag, $id) $flag 0 - duplicated & ignored 0.5 - duplicated and forced update, 1 - insert OK; $id - key
 * 
 */
function add_array_data ($vals, $isnum, $tablename, $testKey, $testVal, $idkey, $action) {

	$keystr="";
	$valstr="";

	$vsize=count($vals);
	$ks=array_keys($vals);
	$iks=array_keys($vals);
	$id=-1;
	ini_set("memory_limit","24M");
	set_time_limit(300);
	// Check for duplicates between the data and the content of the database
	$dup_flag=0;
	$cflt_flag=0;
	$update_flag=0;
	$testStr="";
	$row=array();
	// The lengths of $testKey and $testVal are supposed to be identical, we stick to the length of the keys
	if (count($testKey)>0) {
		for ($i=0; $i<count($testKey); $i++) {
			$eleTest=strlen($testVal[$i])>0 ? $testVal[$i] : "N/A";
			$testStr.=$testKey[$i]."=\"".$eleTest."\"";
			if ($i<count($testKey)-1) $testStr.=" AND ";
		}
		// print "SELECT * FROM $tablename WHERE $testStr\n";
		$testResult = mysql_query("SELECT * FROM $tablename WHERE $testStr");
		if(mysql_num_rows($testResult) > 0) {
			$row = mysql_fetch_assoc($testResult);
			$id=$row[$idkey];
			$dup_flag++;
		}
	}

	if ($dup_flag>0) {

		// check for conflict
		foreach ($ks as $inkey) {
			if (isset($row[$inkey]) && strlen($row[$inkey])>0 && (! preg_match('/created_on|updated_on|published_on/', $inkey)) && $row[$inkey]!=$vals[$inkey]) {
				$cflt_flag++; 
			}
			if ((! isset($row[$inkey]) || strlen($row[$inkey])<=0) && (! preg_match('/created_on|updated_on|published_on/', $inkey)) && (isset($vals[$inkey]) && strlen($vals[$inkey])>0)) {
				$update_flag++; 
			}
		}

		if (($cflt_flag==0 && $update_flag>0) || $action=="force_update") {
			// if no conflict is found, or $action is force_update, update the table
			$update_str="update $tablename set ";
			for ($i=0; $i<$vsize; $i++) {
				$update_str.=$ks[$i]."=";
				$elestr=strlen($vals[$ks[$i]])>0 ? $vals[$ks[$i]] : "NULL";
				if ($isnum[$iks[$i]]==0 && strlen($vals[$ks[$i]])<=0) $elestr="N/A";
				$update_str.=$isnum[$iks[$i]]==1 ? $elestr : "\"".addslashes($elestr)."\"";
				if ($i<$vsize-1) $update_str.=",";
			}
			$update_str.=" where $testStr";
			print $updata_str."\n";
			mysql_query($update_str) or die(mysql_errno() . ":" . mysql_error());
			return array(0.5, $id);
		}
		else {
			return array(0, $id);
		}
	}
	else {
		// no duplication exists, just insert
		
		for ($i=0; $i<$vsize; $i++) {
			$keystr.=$ks[$i];
			$elestr=strlen($vals[$ks[$i]])>0 ? $vals[$ks[$i]] : "NULL";
			if ($isnum[$iks[$i]]==0 && strlen($vals[$ks[$i]])<=0) $elestr="N/A";
			$valstr.=$isnum[$iks[$i]]==1 ? $elestr : "\"".addslashes($elestr)."\"";
			if ($i<$vsize-1) {
				$keystr.=",";
				$valstr.=",";
			}
		}
		if (chk_tbl_field($tablename,'created_on')>=1) {
			$keystr.=",created_on";
			$valstr.=",now()"; 
		}
		if ($action=="print") print "insert into $tablename ($keystr) values ($valstr)\n";
		// print "insert into $tablename ($keystr) values ($valstr)\n";
		$sql = "insert into $tablename ($keystr) values ($valstr)";
		mysql_query($sql) or die($sql . ":" . mysql_error());
		$id=mysql_insert_id();
		return array(1,$id);
	}
}

/**
 * This function performs an UPDATE query on a particular table. 
 * It has a built in default integer value of 0 for numeric fields
 * and automatically updates the updated_on field if it exists in the table.
 *
 * This function is also capable of operating on a table with multiple fields 
 * as a primary key. 
 *
 * @param array $values - this MUST be an associative array containing every key
 *			  and value that the programmer would like to update. 
 * 
 *	  Note: The keys in the $values array MUST have the same name as the field names
 *		in the table.
 *
 * @param $table - the name of the table of which to query. 
 *
 * @param $pkey - this MUST be an associative array who's keys are the primary key's
 *		  column name in the table and who's value is the unique value that
 * 		  defines the particular row in the table to update. 
 *
 * @return TRUE if the query was successful or FALSE otherwise. If FALSE then an 
 * 	   error message is echoed containing the mysql_error(). 
 *
 * Note: The update query utilizes LIMIT 1 forcing the query to update only 1 row.
 */
function updateTable(array $values, $table, array $pkey) {

	$updateStr = "UPDATE $table SET ";
	$strs = array();
	$count = 0;

	// get table fields
	$cols = getTableColumns($table);
	
	// set values if they exist
	foreach($cols as $column=>$type) {

		// if the column name is in the $values array, then we want to update it
		if(array_key_exists($column, $values)) {
		
			if( (strpos($type, "varchar") !== FALSE) || 
			    ($values[$column] != "")
			  )
				$strs[$count++] = "$column = '" . $values[$column] . "'";

			else    //default 0 for numberic fields with empty values
				$strs[$count++] = "$column = 0";	
		}
	}

	// deal with primary key
	$where = "";
	$first = 0;
	foreach($pkey as $k=>$v) {
		if($first == 0)
			$where .= "$k='$v'";
		else
			$where .= " AND $k='$v'";	//multiple keys

		$first++;
	}

	// put the update string together
	$updateStr .= implode(",", $strs) . " WHERE $where LIMIT 1";

	// query	
	if(mysql_query($updateStr)) 
		return TRUE;
	else {
		error(1, mysql_error());
		return FALSE;
	}

}

/**
 * This function gets the column names and types of a particular table
 * 
 * @param $table - the table of which to describe
 * @return $names - an associative array who's key is the field name
 *		    and who's value is the type. 
 */
function getTableColumns($table) {

	$t = mysql_real_escape_string($table);
	$names = array();
	$types = array();

	$query = mysql_query("
		DESCRIBE $t
	") or die(mysql_error());

	// each name is on $row[0], push that onto the list of names
	while($row = mysql_fetch_assoc($query)) {
		array_push($names, $row['Field']);
		array_push($types, $row['Type']);
	}

	return array_combine($names, $types);
}




/**
 * recursively remove a directory
 */
// ------------ lixlpixel recursive PHP functions -------------
// recursive_remove_directory( directory to delete, empty )
// expects path to directory and optional TRUE / FALSE to empty
// of course PHP has to have the rights to delete the directory
// you specify and all files and folders inside the directory
// ------------------------------------------------------------

// to use this function to totally remove a directory, write:
// recursive_remove_directory('path/to/directory/to/delete');

// to use this function to empty a directory, write:
// recursive_remove_directory('path/to/full_directory',TRUE);

function recursive_remove_directory($directory, $empty=FALSE)
{
	// if the path has a slash at the end we remove it here
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if(!file_exists($directory) || !is_dir($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... if the path is not readable
	}elseif(!is_readable($directory))
	{
		// ... we return false and exit the function
		return FALSE;

	// ... else if the path is readable
	}else{

		// we open the directory
		$handle = opendir($directory);

		// and scan through the items inside
		while (FALSE !== ($item = readdir($handle)))
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if($item != '.' && $item != '..')
			{
				// we build the new path to delete
				$path = $directory.'/'.$item;

				// if the new path is a directory
				if(is_dir($path)) 
				{
					// we call this function with the new path
					recursive_remove_directory($path);

				// if the new path is a file
				}else{
					// we remove the file
					unlink($path);
				}
			}
		}
		// close the directory
		closedir($handle);

		// if the option to empty is not set to true
		if($empty == FALSE)
		{
			// try to delete the now empty directory
			if(!rmdir($directory))
			{
				// return false if not possible
				return FALSE;
			}
		}
		// return success
		return TRUE;
	}
}

/**
 * clean up the upload files
 */
function clean_up_temporary($tmpdir) {
	
	$upload_dir="uploads/processed_files";
	umask(0);
	if(!file_exists($upload_dir) || !is_dir($upload_dir)) {
		mkdir($upload_dir, 0777);
	}
	// move all the excel files from the temporary directory to the processed directory
	$handle = opendir($tmpdir);
	while (FALSE !== ($item = readdir($handle))) {
			if(preg_match("/\.xls/", strtolower($item))) {
				$dest_path = $upload_dir.'/'.$item;
				$source_path=$tmpdir."/".$item;
				if (file_exists($upload_dir."/".$item)) $dest_path.="_".date('m-d-Y');
				if (! copy($source_path, $dest_path)) {
					error(1, "Unable to copy the file $source_path to $dest_path");
				}
			}
	}
	// clean the temporary directory
	$rm_done=recursive_remove_directory($tmpdir);
	if ($rm_done) print "<p>Temporary directory $tmpdir cleaned successfully</p>";
	else print "<p>Having problems in deleting temporary directory $tmpdir</p>";
	return $rm_done;
}

/**
 * 
 */
function editGeneral($table, $where, $return_page, array $ignore, $limit="20", $start="0") {
	
	//get the primary key of this table
	$pkey = get_pkey($table);

	//generate the query string, store in separate variable for pagination purposes.
	$queryString = "SELECT *
			FROM $table
			WHERE $where
			ORDER BY $pkey";
	
	//query the DB
	$lines = mysql_query("
			$queryString 
			LIMIT $limit OFFSET $start
			") or die(mysql_error());

	//ignore created and updated on fields.
	array_push($ignore, "created_on");
	array_push($ignore, "updated_on");

	//get all the foreign tables associated with this table.
	$ftables = chk_tbl_references($table);
	
	//sort the tables. This will help keep things consistent
	sort($ftables);

	//get all the foreign pkeys from the table names.
	$fpkeys = array();
	foreach($ftables as $key) {
		$fpkeys[$key] = get_pkey($key);
	}

	showPages($queryString, $return_page, $start, $limit);

	echo "\n<table class=\"tableclass1\" cellpadding=0 cellspacing=0>\n";

	$count = 0;
	while($row = mysql_fetch_assoc($lines)) {

		if($count == 0) {  //first time, display column names

			echo "\t\t<thead>\n";
			echo "\t\t<tr>\n";
			echo "\t\t\t<th><strong>edit</strong></th>\n";

			//Show Foreign Key Names
			foreach($ftables as $key) {
				if( !in_array($fpkeys[$key], $ignore))
					echo "\t\t\t<th><strong>$key</strong></th>\n";
			}

			//Show column names, ensure that the column is not in the ignore array.
			foreach($row as $k=>$v) {
				if( !in_array($k, $ignore) && !in_array($k, $fpkeys) )
					echo "\t\t\t<th><strong>$k</th>\n";
			}

			echo "\t\t\t<th><strong>update</strong></th>\n";
			echo "\t\t</tr>\n";
			echo "\t\t</thead>\n";
		}


		/*
		 * Get Foreign Key Options :)
	 	 */
		ob_start();
		foreach($ftables as $key) {

			showTableOptions($key, $row[$fpkeys[$key]]);

			$$key = ob_get_contents();
			ob_clean();

		}
		ob_end_clean();


		/*
	 	 * Generate Edit Output
		 */
		$line = "
		<tr><form name=\"$table$count\" id=\"$table$count\" action=\"$return_page\" method=\"post\">
			<td><input type=\"checkbox\" value=\"$count\" onclick=\"toggleRow('$table$count')\" />\n";

		//Show Foreign Key Options
		foreach($ftables as $key) {
			if( !in_array($fpkeys[$key], $ignore))
				$line .= "\t\t\t<td><select name=\"$fpkeys[$key]\" disabled>" . $$key . "</select></td>\n";
		}

		
		//Show column values, ensure that the column is not in the ignore array.
		foreach($row as $k=>$v) {
			if( !in_array($k, $ignore) && !in_array($k, $fpkeys) )
				$line .= "\t\t\t<td><textarea name=\"$k\" rows=\"1\" cols=\"12\" disabled>".stripslashes($v)."</textarea></td>\n";
		}

		$line .= "\t\t\t<td><input type=\"submit\" name=\"$row[$pkey]\" value=\"Update\" disabled></td>
		</form></tr>";

		echo $line;
		$count++;

	}	

	echo "\n</table>\n";

	showPages($queryString, $return_page, $start, $limit);

}

/**
 * This function supports pagination. It must have the exact query string
 * it will generate its own limits.
 */
function showPages($queryString, $page, $current=0, $limit=20) {

	//get rid of the start= if it already exists in the page string
	if (($pos = strpos($page, "start=")) !== FALSE) {
		$found = substr($page, $pos);
		$page = substr($page, 0, -(strlen($found)+1));
	} 

	//should we use ? or &.
	if(strpos($page, "?") !== FALSE)
		$delim = "&";
	else
		$delim = "?";
	

	$query = mysql_query("
			$queryString
			") or die(mysql_error());

	$max = mysql_num_rows($query);

	echo "Page: ";

	$count = 1;
	for($i=0; $i<$max; $i++) {

		if(($i % $limit) == 0) {

			if($current >= $i  &&  $current < $i+20)
				echo "<a href=\"". $page . $delim ."start=$i\"><strong>$count</strong></a> ";
			else
				echo "<a href=\"". $page . $delim ."start=$i\">$count</a> ";

			$count++;
			if(($count % 41) == 0)	//break every 40 pages.
				echo "<br />";
		}
	}

}


/**
 * get the name of the tables that are implicitly referenced
 * 
 * @param string $nm search table
 * @return array $refernced_tables referenced tables
 */
function chk_tbl_references($nm) {

	// searching for foreign tables stop at datasets
	$dptbls=array();

	if ($nm=="datasets") 
		return $dptbls;

	// the tables to ignore
	$tbl_ignore = array('barley_pedigree_catalog');
	
	$result = mysql_query("SHOW CREATE TABLE $nm");

	while( $row = mysql_fetch_assoc($result) ) {

		$cstr=$row['Create Table'];
		preg_match_all('/REFERENCES\s`(.*?)`/', $cstr, $mts);

		foreach ($mts[1] as $mt) {

			if (in_array($mt,$tbl_ignore)) 
				continue;

			array_push($dptbls, $mt);
		}
	}
	return $dptbls;
}



/**
 * examine whether a field exists in a table
 * 
 * @param string $tblname name of the table
 * @param string $fldname name of the field
 * @return int $flddef 0 - not exist, 1 - exist && default NULL, 2 - exist && NOT NULL
 */
function chk_tbl_field ($tblname, $fldname) {
	$flddef=0;
	$showtable = mysql_query("show create table $tblname");
	while ( $row=mysql_fetch_assoc($showtable)) {
		$cstrs=explode("\n",$row['Create Table']);
		for ($i=1; $i<count($cstrs)-1; $i++) {
			if (preg_match('/KEY|CONSTRAINT/',$cstrs[$i])) continue;
			if (preg_match("/`$fldname`/", $cstrs[$i])) {
				$flddef=1;
				if (preg_match('/NOT\sNULL/', $cstrs[$i])) {
					$flddef=2;
				}
				break;
			}
		}
	}
	return $flddef;		
}

/**
 * check whether table 1 is referencing tables
 * 
 * @param string $nm1 searching table
 * @param string $nm2 targe table
 * 
 */
function chk_tbl_dep ($nm1) {
	$dptblkey=array();
	$flag=0;
	$result=mysql_query("show create table $nm1");
	while( $row = mysql_fetch_assoc($result) ) {
		$cstr=$row['Create Table'];
		preg_match_all('/REFERENCES\s`(.*?)`\s*\(`(.*?)`\)/', $cstr, $mts);
		for($i=0; $i<count($mts[1]); $i++) {
			$flag++;
			$dptblkey[$mts[1][$i]]=$mts[2][$i];
		}
	}
	return array($flag, $dptblkey);
}

/**
 * get the field names that are in the unikey key in a table
 */
function get_ukey ($tblname) {
	$ukeys=array();
	$ukeyres = mysql_query("SHOW CREATE TABLE $tblname");

	while( $row = mysql_fetch_assoc($ukeyres) ) {
		$cstr=$row['Create Table'];
		preg_match_all('/UNIQUE\sKEY\s`(.*?)`\s*\(`(.*?)`\)/', $cstr, $mts);
		for($i=0; $i<count($mts[1]); $i++) {
			$ukeys = array_merge($ukeys, explode("`,`", $mts[2][$i]));
		}
	}
	return $ukeys;
}



/**
 * get the information for a table
 */
function get_table_info ($tablename) {
	$result = mysql_query("desc $tablename");
	$tbl_info=array();
	while($row = mysql_fetch_assoc($result)) {
		$row_info=array();
		foreach($row as $k=>$v) {
			if (strtolower($k)=='field') {
				$row_info['field']=$v;
			}
			elseif (strtolower($k)=='type') {
				switch (1) {
					case (preg_match('/^int|^float|^double|^date/i',$v)) : $row_info['isnum']=1; break;
					default: $row_info['isnum']=0; break;
				}
				$row_info['type']=$v;
			}
			elseif (strtolower($k)=='key') {
				if (strtolower($v)=='pri') $row_info['primarykey']=1;
				else $row_info['primarykey']=0;
			}
		}
		array_push($tbl_info, $row_info);
	}
	return $tbl_info;
}


/*
 * Very simple function. Transforms a table name from an 
 * ugly unpleasant version to a more pleasant version.
 * 
 * Example:  line_records : Line Record
 * 
 * We're using this code in more than one place which is as good a reason
 * as any for it to be a function.
 *
 * If you want it to be plural (with an s) then set $plural to 1
 */
function beautifulTableName($tname, $plural=0) {

	$list = array("a", "o", "u", "s", "");

	if($plural) {
		if($tname[strlen($tname)-1] == "y") //special y case
			$end = substr($tname, 0, -1) . "ies";

		else if( !in_array($tname[strlen($tname)-1], $list) )	//pluralize if not already
			$end = $tname . "s";

		else	//already plural
			$end = $tname;
	}
	else {
		$end = ($tname[strlen($tname)-1] == "s" && $tname[strlen($tname)-2] != "u") ? substr($tname, 0, -1) : $tname;
	}

	return ucwords(str_replace("_", " ", $end));

}

/**
 * Store the file process information into the file_process table
 */
function store_file_process_info() {
	$tblkeys=array('file_name', 'def_file_name','dir_destination','file_desc','dataset_name','process_program', 'target_tables', 'users_name', 'process_result');
	$vals=array();
	$isnum=array();
	foreach ($tblkeys as $tblkey) {
		if (isset($_SESSION['fileProcessInfo'][$tblkey])) {
			$vals[$tblkey]=mysql_escape_string($_SESSION['fileProcessInfo'][$tblkey]);
			array_push($isnum, 0);
		} 
	}
	add_array_data($vals, $isnum, "file_process", array(), array(), "file_process_uid", "");
}


/**
 *
 * This function dumps a MySQL query out into a table format
 *
 * @param $query - the resource returned by the function mysql_query()
 * @param $sortable - this provides a sort mechanism to sort selected column in ascending or decending order
 * 	NOTE: this is default set to FALSE, if set to TRUE then the calling page must handle the sort mechanism
 * 
 * @return $ret - a string containing the table in all of its glory. 
	NOTE: this function DOES NOT direcly output its result.
 */
function displayTable($query, $sortable=FALSE, $link="") {

	$tables = array();
	$ret = "\n<table class='tableclass1'>\n";

	$first = TRUE;
	while($row = mysql_fetch_assoc($query)) {
		
		$ret .= "\n<tr>\n";

		if($first) {
			$ret .= "<thead>\n";
			foreach($row as $k=>$v) {
				
				if(strpos($k, "_name") !== FALSE)
					$tables[$k] = getTableByName($k);

				$ret .= "\t<td><strong>".ucwords(str_replace("_", " ", $k))."</strong>";

				if($sortable) {
					unset($_GET['sortby']);
					unset($_GET['sorttype']);

					$ret .= " <br />
						  <a href='". $_SERVER['PHP_SELF'] ."?". kvimplode("&", $_GET) ."&sortby=$k&sorttype=ASC'>Up</a> - 
					          <a href='". $_SERVER['PHP_SELF'] ."?". kvimplode("&", $_GET) ."&sortby=$k&sorttype=DESC'>Down</a>";

				}

				$ret .= "</td>\n";
			}
			$ret .= "</thead>\n</tr>\n<tbody>\n<tr>\n";
			$first = FALSE;
		}

		foreach($row as $k=>$v) {
			if(isset($tables[$k])) {
				$v = "<a href=\"view.php?table=" . urlencode($tables[$k]) . "&name=". urlencode($v)."\">$v</a>";
			}
			$ret .= "\t<td>$v</td>\n";
		}

		$ret .= "\n</tr>\n";

	}

	$ret .= "</tbody>\n</table>\n";

	return $ret;
}


/* Modified version of displayTable(), to output phenotype values with
 * truncation according to units.sigdigits_display.  DEM 11aug10
 */

function displayTableSigdig($query, $sortable=FALSE, $sigdig, $link="") {

	$tables = array();
	$ret = "\n<table class='tableclass1'>\n";

	$first = TRUE;
	while($row = mysql_fetch_assoc($query)) {
		
		$ret .= "\n<tr>\n";

		if($first) {
			$ret .= "<thead>\n";
			foreach($row as $k=>$v) {
				
				if(strpos($k, "_name") !== FALSE)
					$tables[$k] = getTableByName($k);

				$ret .= "\t<td><strong>".ucwords(str_replace("_", " ", $k))."</strong>";

				if($sortable) {
					unset($_GET['sortby']);
					unset($_GET['sorttype']);

					$ret .= " <br />
						  <a href='". $_SERVER['PHP_SELF'] ."?". kvimplode("&", $_GET) ."&sortby=$k&sorttype=ASC'>Up</a> - 
					          <a href='". $_SERVER['PHP_SELF'] ."?". kvimplode("&", $_GET) ."&sortby=$k&sorttype=DESC'>Down</a>";

				}

				$ret .= "</td>\n";
			}
			$ret .= "</thead>\n</tr>\n<tbody>\n<tr>\n";
			$first = FALSE;
		}

		foreach($row as $k=>$v) {
			if(isset($tables[$k])) {
				$v = "<a href=\"view.php?table=" . urlencode($tables[$k]) . "&name=". urlencode($v)."\">$v</a>";
			}
			if(strcmp($k,"trial_code") == 0) {
			  $v = "<a href=\"display_phenotype.php?trial_code=" . urlencode($v) . "\">$v</a>";
			}
                        if(strcmp($k,"value") == 0 && $sigdig>=0) {
                                $v = number_format($v,$sigdig);
                        }
			$ret .= "\t<td>$v</td>\n";
		}

		$ret .= "\n</tr>\n";

	}

	$ret .= "</tbody>\n</table>\n";

	return $ret;
}


/**
 * This function will get the table name based on the unique field name passed to it.
 * for example  "dataset_name" will return the table "datasets"
 * 
 * This is useful if you have a field and don't know where it came from.
 *
 * @param $name - the name of the field to find the table.
 */
function getTableByName($name) {

	$parts = explode("_", $name);
	$parts[count($parts)-1] = "";

	$possible_name = implode("_", $parts);

	if($possible_name[strlen($possible_name)-1] == "_")
		$possible_name = substr($possible_name, 0, -1);

	if($possible_name[strlen($possible_name)-1] == "s" || $possible_name[strlen($possible_name)-1] == "y")
		return $possible_name;
	else {
		$possible_name .= "_";
		$search = mysql_query("SHOW TABLES LIKE '$possible_name'");
		$t = mysql_fetch_row($search);
		return $t[0];
	}
}


/**
 * This function works the same as implode() except implode() only puts together the
 * values of the array. kvimplode() puts together the keys and values in the format: 
 * "key=value"
 *
 * Note: It uses $delim as the delimiter to seperate the key=value pairs.
 *
 * @param $delim - the delimiter to seperate the key=value pairs.
 * @param $array - the array by which to glue together.
 *
 * @return a string containing the keys and values of the array combined, each pair seperated by $delim
 */
function kvimplode($delim, array $array) {

	$ret = "";

	$first = TRUE;
	foreach($array as $k=>$v) {
		if($first) {
			$ret .= "$k=$v";
			$first = FALSE;
		}
		else
			$ret .= "$delim$k=$v";
	}

	return $ret;

}

/**
 * This function tests whether or not the given is a table record in the
 * database or not.
 *
 * @param $table - the table of which to search
 * @param $id - the unique id of the specific record to fetch
 * @return FALSE if the given is not a table record,
 *	   the result mysql resource if the given is a table record.
 */
function isRecord($table, $id) {

	if(!is_numeric($table)) {

		$pkey = @get_pkey($table);

		if(!$pkey) // not a valid table, return FALSE
			return FALSE;

		if(is_numeric($id)) {

			$source = mysql_query("
				SELECT *
				FROM $table
				WHERE $pkey = '$id'
			");
		}

		if(!$source || mysql_num_rows($source) < 1 || !is_numeric($id)) {	// maybe the id was a name

			$name = get_unique_name($table);
			$source = mysql_query("
				SELECT *
				FROM $table
				WHERE $name = '$id'
				");
		}

		if(@mysql_num_rows($source) > 0) {
			return $source;
		}
	} 	return FALSE;
}
 

/**
 * Store session variables for users, currently handles only simple arrays
 * 
 * @param string $svkey key for the session variable to be stored
 */
function store_session_variables ($svkey, $user) {
  if (!isset($_SESSION[$svkey]))
    return 0;
  $sql_users_name = mysql_real_escape_string($user);
  $sql = "select users_uid from users
where users_name='$sql_users_name'";
  $r = mysql_query($sql);
  if (!$r)
    return 0;
  $r = mysql_fetch_assoc($r);
  if (!$r)
    return 0;
  $users_uid = $r['users_uid'];
  $vals = array();
  $isnum = array();
  $vals['session_variables_name'] = mysql_escape_string($svkey);
  array_push($isnum, 0);
  $vals['serialized_values'] = base64_encode(serialize($_SESSION[$svkey]));
  array_push($isnum, 0);
  $vals['users_uid'] = $users_uid;
  array_push($isnum, 1);
  add_array_data($vals, $isnum, "session_variables",
		 array('users_uid', 'session_variables_name'),
		 array($users_uid, mysql_escape_string($svkey)),
		 "session_variables_uid", "force_update");
  return 1;
}

function retrieve_session_variables($svkey, $user) {
  if (isset($svkey) && strlen($svkey) > 0 && isset($user) &&
      strlen($user)>1) {
    $sql_user = mysql_real_escape_string($user);
    $result=mysql_query("select serialized_values
from session_variables where session_variables_name=\"$svkey\" and
users_uid=(select users_uid from users
where users_name='$sql_user')");
    if(mysql_num_rows($result)>0) {
      $row = mysql_fetch_assoc($result);
      $str = base64_decode($row['serialized_values']);
      $vals = unserialize($str);
      return $vals;
    }
  }
  return -1;
}

?>