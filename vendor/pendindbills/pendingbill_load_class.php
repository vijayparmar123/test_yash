<?php
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */


// REMOVE THIS BLOCK - used for DataTables test environment only!
$file = $_SERVER['DOCUMENT_ROOT'].'/datatables/mysql.php';
if ( is_file( $file ) ) {
	include( $file );
}


class SSP_Patient {
	/**
	 * Create the data output array for the DataTables rows
	 *
	 *  @param  array $columns Column information array
	 *  @param  array $data    Data from the SQL get
	 *  @return array          Formatted data in a row based format
	 */
	static function data_output ( $columns, $data ,$user)
	{
		$out = array();

		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];

				// Is there a formatter?
				if ( isset( $column['formatter'] ) ) {
					$row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
				}
				else if($j== 0)
				{
					
				}
				else if($j== 1)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$date = self::get_date($uid);
					 $row[ $column['dt'] ] = $date;
				}
				else if($j== 3)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$project = self::get_projectname($uid);
					 $row[ $column['dt'] ] = $project;
				}
				else if($j== 5)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$party_type = $data[$i][ $columns[17]['db'] ];
					$new_party_name = $data[$i][ $columns[18]['db'] ];
					$is_agency = strpos($uid,"NEC");
									
					if(($uid == "0" || $is_agency == 1 ) && $party_type == "old" )
					{
					    $ag_name = self::get_agency_name_by_code($uid);
					}
					else if($party_type == "new")
					{
						$ag_name = $new_party_name;
					}
					else
					{
						$ag_name = self::get_vendor_name($uid);										
					}
					
					$row[ $column['dt'] ] = $ag_name;
				}
				else if($j== 10)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$date = self::get_date($uid);
					 $row[ $column['dt'] ] = $date;
				}
				else if($j== 12)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$date =  date('Y-m-d');
					$bill_date = $data[$i][ $columns[10]['db'] ];
					$pending_days = date('Y-m-d', strtotime( $date. " + {$uid} days"));
					// $datediff = $bill_date - $date;
					// $days_diff = floor($datediff/(60*60*24));
							
					$date1 = new DateTime($bill_date);
					$date2 = new DateTime($date);
					$diff = $date2->diff($date1)->format("%r%a");
					$rem = $diff + intval($uid);
					$row[ $column['dt'] ] = $rem;
				}
				else if($j== 13)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$qty_checked_by = self::get_category_title($uid);
					$row[ $column['dt'] ] = $qty_checked_by;
				}else if($j== 14)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$rate_checked_by = self::get_category_title($uid);
					$row[ $column['dt'] ] = $rate_checked_by;
				}
				else if($j== 15)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$role = self::get_user_role($user);
					$html = '';
					
					if(self::retrive_accessrights($role,'editpendingbill')==1)
					{
						$html .= "<a href='addinwardbill/{$uid}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-pencil'></i> Edit</a>";
					}
					if(self::retrive_accessrights($role,'deletependingbill')==1)
					{
						$html .= "<a href='disapprove/{$uid}' target='_blank' class='btn btn-danger btn-clean'><i class='icon-pencil'></i> Delete</a>";
					}
					
					$html .= "<a href='viewbill/{$uid}' target='_blank' class='btn btn-primary btn-clean'><i class='icon-pencil'></i> View</a>";
					
					$row[ $column['dt'] ] =$html;
				}
				else if($j== 16)
				{
					$uid=$data[$i][ $columns[$j]['db'] ];
					$html = '';
					if(self::retrive_accessrights($role,'acceptpendingbill')==1)
					{					
						$html .= "<input type='checkbox' name='ch_pend[]' value='accept' class='ch_pend' dataid='{$uid}'>";
					}
					$row[ $column['dt'] ] =$html;
				}
				//else if('ID11' == $columns[$j]['db'])
				//{
					//$edit_delete = '<a href="?page=hmgt_treatment&tab=addtreatment&action=edit&treatment_id='.$data[$i][ $columns[$j]['db'] ].'" class="btn btn-info">Edit </a>
					//<a href="?page=hmgt_treatment&tab=treatmentlist&action=delete&treatment_id='.$data[$i][ $columns[$j]['db'] ].'" class="btn btn-danger"  onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete </a>
					//';
					//$row[ $column['dt'] ] = 5;
				/*	<a href="?page=hmgt_treatment&tab=addtreatment&action=edit&treatment_id=<?php echo $retrieved_data->treatment_id;?>" class="btn btn-info"> 
               	<?php _e('Edit', 'hospital_mgt' ) ;?></a>
                <a href="?page=hmgt_treatment&tab=treatmentlist&action=delete&treatment_id=<?php echo $retrieved_data->treatment_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php _e('Are you sure you want to delete this record?','hospital_mgt');?>');">
                <?php _e( 'Delete', 'hospital_mgt' ) ;?> </a>*/
				//}
				else {
					$row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
				}
			}

			$out[] = $row;
		}

		return $out;
	}


	/**
	 * Database connection
	 *
	 * Obtain an PHP PDO connection from a connection details array
	 *
	 *  @param  array $conn SQL connection details. The array should have
	 *    the following properties
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 *  @return resource PDO connection
	 */
	static function db ( $conn )
	{
		if ( is_array( $conn ) ) {
			return self::sql_connect( $conn );
		}

		return $conn;
	}


	/**
	 * Paging
	 *
	 * Construct the LIMIT clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL limit clause
	 */
	static function limit ( $request, $columns )
	{
		$limit = '';

		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
		}

		return $limit;
	}


	/**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL order by clause
	 */
	static function order ( $request, $columns )
	{
		$order = '';

		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = array();
			$dtColumns = self::pluck( $columns, 'dt' );

			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';

					$orderBy[] = '`'.$column['db'].'` '.$dir;
				}
			}

			$order = 'ORDER BY '.implode(', ', $orderBy);
		}

		return $order;
	}


	/**
	 * Searching / Filtering
	 *
	 * Construct the WHERE clause for server-side processing SQL query.
	 *
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here performance on large
	 * databases would be very poor
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @param  array $bindings Array of values for PDO bindings, used in the
	 *    sql_exec() function
	 *  @return string SQL where clause
	 */
	static function filter ( $request, $columns, &$bindings )
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck( $columns, 'dt' );
		//$request['search'] = array('value'=>30);
			//$request['search']['value'] = 'aa';
		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['searchable'] == 'true' ) {
					$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
					$globalSearch[] = "`".$column['db']."` LIKE ".$binding;
				}
			}
		}

		// Individual column filtering
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				$str = $requestColumn['search']['value'];

				if ( $requestColumn['searchable'] == 'true' &&
				 $str != '' ) {
					$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
					$columnSearch[] = "`".$column['db']."` LIKE ".$binding;
				}
			}
		}

		// Combine the filters into a single string
		$where = '';

		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}

		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}

		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}
		// if ( $where === '' ) {
			// $where = 'WHERE (
// wp_usermeta.meta_key = \'patient_type\'
// AND wp_usermeta.meta_value = \'outpatient\'
// )
// AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = \'wp_capabilities\' AND meta_value RLIKE \'patient\')';
		// }
		// if ( $where !== '' ) {
			// $where .= ' AND (
// wp_usermeta.meta_key = \'patient_type\'
// AND wp_usermeta.meta_value = \'outpatient\'
// )
// AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = \'wp_capabilities\' AND meta_value RLIKE \'patient\') ';
		// }
		//echo $where ."hello";
		//exit;
return $where;
		/*return $where ."  where (
wp_usermeta.meta_key = 'patient_type'
AND wp_usermeta.meta_value = 'outpatient'
)
AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = 'wp_capabilities' AND meta_value RLIKE 'patient')";
		
		*/
	}


	/**
	 * Perform the SQL queries needed for an server-side processing requested,
	 * utilising the helper functions of this class, limit(), order() and
	 * filter() among others. The returned array is ready to be encoded as JSON
	 * in response to an SSP request, or can be modified if needed before
	 * sending back to the client.
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @return array          Server-side processing response array
	 */
	static function simple ( $request, $conn, $table, $primaryKey, $columns ,$user)
	{
		$bindings = array();
		$db = self::db( $conn );
	//var_dump($request);
		//echo 'Table ='.$table.'<BR>';
		//$table .= ' wp_usermeta';
		$new_table = $table;
		// $new_query = "   (
// wp_usermeta.meta_key = 'patient_type'
// AND wp_usermeta.meta_value = 'outpatient'
// )
// AND $table.id = wp_usermeta.user_id AND $table.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = 'wp_capabilities' AND meta_value RLIKE 'patient')";
		// Build the SQL query string from the request
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );
		
		if($where != '')
		{
			$where .= "AND status_inward = 'accept'";
		}
		else
		{
			$where = "where status_inward = 'accept'";
		}
		/*
		if(isset($request['search']) || isset( $request['columns'] ) )
		{ 
	
	$where = self::filter( $request, $columns, $bindings );
	//var_dump($where);
	if($where !== ' ')
	{
		
		$where = 'where  (
wp_usermeta.meta_key = \'patient_type\'
AND wp_usermeta.meta_value = \'outpatient\'
)
AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = \'wp_capabilities\' AND meta_value RLIKE \'patient\')';
	}
	else
	{
		$where = ' AND  (
wp_usermeta.meta_key = \'patient_type\'
AND wp_usermeta.meta_value = \'outpatient\'
)
AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = \'wp_capabilities\' AND meta_value RLIKE \'patient\')';
	}
	}
		else
		{
			$where = 'where  (
wp_usermeta.meta_key = \'patient_type\'
AND wp_usermeta.meta_value = \'outpatient\'
)
AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = \'wp_capabilities\' AND meta_value RLIKE \'patient\')';
		}
		*/
		//echo $where;
		//exit;
		/*if($where == "")
			$where = 'where  (
wp_usermeta.meta_key = \'patient_type\'
AND wp_usermeta.meta_value = \'outpatient\'
)
AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = \'wp_capabilities\' AND meta_value RLIKE \'patient\')';*/
		//else
		//	echo 'not space';
		
		//echo $where ."gtryrt";
		
		// Main query to actually get the data
		/*echo"SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table`, `wp_usermeta`
			 $where
			 $order
			 $limit";exit;*/
			// echo "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			// FROM `$table`
			// $where
			// $order
			// $limit";die;
		$data = self::sql_exec( $db, $bindings,
			"SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			FROM `$table`
			$where
			$order
			$limit"
		);
		/*echo "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table`, `wp_usermeta`
			 $where
			 $order
			 $limit";exit;*/
		/*var_dump($data);
echo "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table` `wp_usermeta`
			 $where
			 $order
			 $limit";
			 
	echo "<BR><BR><BR><BR> my custom".$new_query;
		 exit;*/
		// Data set length after filtering
		/*echo "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` , `wp_usermeta` where  (
wp_usermeta.meta_key = 'patient_type'
AND wp_usermeta.meta_value = 'outpatient'
)
AND wp_users.ID = wp_usermeta.user_id AND wp_users.ID = ANY (SELECT user_id FROM wp_usermeta WHERE meta_key = 'wp_capabilities' AND meta_value RLIKE 'patient')";
exit;*/
		$resFilterLength = self::sql_exec( $db,
			"SELECT FOUND_ROWS()"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec( $db,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` "
		);
		$recordsTotal = $resTotalLength[0][0];


		/*
		 * Output
		 */
		return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => self::data_output( $columns, $data , $user)
		);
	}


	/**
	 * The difference between this method and the `simple` one, is that you can
	 * apply additional `where` conditions to the SQL queries. These can be in
	 * one of two forms:
	 *
	 * * 'Result condition' - This is applied to the result set, but not the
	 *   overall paging information query - i.e. it will not effect the number
	 *   of records that a user sees they can have access to. This should be
	 *   used when you want apply a filtering condition that the user has sent.
	 * * 'All condition' - This is applied to all queries that are made and
	 *   reduces the number of records that the user can access. This should be
	 *   used in conditions where you don't want the user to ever have access to
	 *   particular records (for example, restricting by a login id).
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array|PDO $conn PDO connection resource or connection parameters array
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @param  string $whereResult WHERE condition to apply to the result set
	 *  @param  string $whereAll WHERE condition to apply to all queries
	 *  @return array          Server-side processing response array
	 */
	static function complex ( $request, $conn, $table, $primaryKey, $columns, $whereResult=null, $whereAll=null )
	{
		$bindings = array();
		$db = self::db( $conn );
		$localWhereResult = array();
		$localWhereAll = array();
		$whereAllSql = '';

		// Build the SQL query string from the request
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );

		$whereResult = self::_flatten( $whereResult );
		$whereAll = self::_flatten( $whereAll );

		if ( $whereResult ) {
			$where = $where ?
				$where .' AND '.$whereResult :
				'WHERE '.$whereResult;
		}

		if ( $whereAll ) {
			$where = $where ?
				$where .' AND '.$whereAll :
				'WHERE '.$whereAll;

			$whereAllSql = 'WHERE '.$whereAll;
		}

		// Main query to actually get the data
		$data = self::sql_exec( $db, $bindings,
			"SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
			 FROM `$table`
			 $where
			 $order
			 $limit"
		);

		// Data set length after filtering
		$resFilterLength = self::sql_exec( $db,
			"SELECT FOUND_ROWS()"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec( $db, $bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` ".
			$whereAllSql
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
		 * Output
		 */
		return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => self::data_output( $columns, $data )
		);
	}


	/**
	 * Connect to the database
	 *
	 * @param  array $sql_details SQL server connection details array, with the
	 *   properties:
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 * @return resource Database connection handle
	 */
	static function sql_connect ( $sql_details )
	{
		try {
			$db = @new PDO(
				"mysql:host={$sql_details['host']};dbname={$sql_details['db']}",
				$sql_details['user'],
				$sql_details['pass'],
				array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION )
			);
		}
		catch (PDOException $e) {
			self::fatal(
				"An error occurred while connecting to the database. ".
				"The error reported by the server was: ".$e->getMessage()
			);
		}

		return $db;
	}


	/**
	 * Execute an SQL query on the database
	 *
	 * @param  resource $db  Database handler
	 * @param  array    $bindings Array of PDO binding values from bind() to be
	 *   used for safely escaping strings. Note that this can be given as the
	 *   SQL query string if no bindings are required.
	 * @param  string   $sql SQL query to execute.
	 * @return array         Result from the query (all rows)
	 */
	static function sql_exec ( $db, $bindings, $sql=null )
	{
		

		// Argument shifting
		if ( $sql === null ) {
			$sql = $bindings;
		}

		$stmt = $db->prepare( $sql );
		//echo $sql;

		// Bind parameters
		if ( is_array( $bindings ) ) {
			for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
				$binding = $bindings[$i];
				$stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
			}
		}

		// Execute
		try {
			$stmt->execute();
		}
		catch (PDOException $e) {
			self::fatal( "An SQL error occurred: ".$e->getMessage() );
		}

		// Return all
		return $stmt->fetchAll();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Internal methods
	 */

	/**
	 * Throw a fatal error.
	 *
	 * This writes out an error message in a JSON string which DataTables will
	 * see and show to the user in the browser.
	 *
	 * @param  string $msg Message to send to the client
	 */
	static function fatal ( $msg )
	{
		echo json_encode( array( 
			"error" => $msg
		) );

		exit(0);
	}

	/**
	 * Create a PDO binding key which can be used for escaping variables safely
	 * when executing a query with sql_exec()
	 *
	 * @param  array &$a    Array of bindings
	 * @param  *      $val  Value to bind
	 * @param  int    $type PDO field type
	 * @return string       Bound key to be used in the SQL where this parameter
	 *   would be used.
	 */
	static function bind ( &$a, $val, $type )
	{
		$key = ':binding_'.count( $a );

		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);

		return $key;
	}


	/**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck ( $a, $prop )
	{
		$out = array();

		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			$out[] = $a[$i][$prop];
		}

		return $out;
	}


	/**
	 * Return a string from an array or a string
	 *
	 * @param  array|string $a Array to join
	 * @param  string $join Glue for the concatenation
	 * @return string Joined string
	 */
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
	
	static function get_projectname($project_id)
	{	
		$projectdetail = TableRegistry::get('erp_projects'); 
		$project_data = $projectdetail->find()->where(['project_id'=>$project_id]);	
		$result_arr = array();
		$result_arr['project_name'] = '-';
		if(!empty($project_data)){
		foreach($project_data as $retrive_data)
		{
			$result_arr['project_name'] = $retrive_data['project_name'];			
		}
	}
		return $result_arr['project_name'];
	}
	
	static function get_date($date)
	{
			return date('d-m-Y',strtotime($date));
	}
	static function get_agency_name_by_code($code)
	{
		$ag_tbl = TableRegistry::get("erp_agency");
		$agency = $ag_tbl->find()->where(["agency_id"=>$code])->hydrate(false)->toArray();
		if(!empty($agency))
		{
			return $agency[0]["agency_name"];
		}else{
			return "-";
		}
	}
	static function get_vendor_name($user_id)
	{
		$erp_users = TableRegistry::get('erp_vendor'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['first_name'] = $retrive_data['vendor_name'];
			
		}
		if(isset($res_array['first_name']))
		// return $res_array['first_name'].' '.$res_array['last_name'];
		return $res_array['first_name'];
		else
			return 'User No Exist More';
	}
	static function get_user_role($user_id)
	{
		$erp_users = TableRegistry::get('erp_users'); 
		$user_data = $erp_users->find()->where(['user_id'=>$user_id]);
		$res_array = array();
		foreach($user_data as $retrive_data)
		{
			$res_array['role'] = $retrive_data['role'];
		}
		if(isset($res_array['role']))
		return $res_array['role'];
	}
	
	static function retrive_accessrights($role,$pagename)
	{
		$data=0;		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->first();
		
		if(!empty($find))
		{
			$findvalue=json_decode($find->accessrights);
		}
		
		$findvalue=(array)$findvalue;	
		foreach($findvalue as $result){
			
			$selected = in_array($pagename,$result);
			if($selected==1){
				$data=1;
				return $data;
				break;
			}
		}
	}
	
	static function project_alloted($role){
		$alloted=1;		
		$findvalue=array();		
		$erp_accessrights_tbl = TableRegistry::get('erp_accessrights'); 
		
		$find=$erp_accessrights_tbl->find()->where(['role'=>$role])->first();
		if(!empty($find))
		{
			$alloted=$find->Alloted;
		}
		return $alloted;
	}
	
	static function get_category_title($cat_id)
	{
		$erp_category_master = TableRegistry::get('erp_category_master'); 
		$category_data = $erp_category_master->find()->where(['cat_id'=>$cat_id]);
		$res_array = array();
		foreach($category_data as $retrive_data)
		{
			$res_array['category_title'] = $retrive_data['category_title'];
			$res_array['cat_id'] = $retrive_data['cat_id'];
		}
		if(isset($res_array['category_title']))
			return $res_array['category_title'];
		else
			return '';
	}
}

