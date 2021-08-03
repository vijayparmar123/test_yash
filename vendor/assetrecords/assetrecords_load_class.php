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


class SSP_assetrecords {
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
					// debug($column['dt']);
					// debug($column['formatter']);
					// debug($data[$i]);die;
					// $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
				}
				else if($j== 4)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$category = self::get_category_title($uid);
					$row[ $column['dt'] ] = $category;
				}
				else if($j== 6)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$purchase_date = self::get_date($uid);
					$row[ $column['dt'] ] = $purchase_date;
				}
				else if($j== 8)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$project = self::get_projectname($uid);
					$row[ $column['dt'] ] = $project;
				}
				else if($j== 9)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html = '';
										
					if(self::retrive_accessrights($role,'viewaddasset')==1)
					{
						$html .= "<a href='viewaddasset/{$uid}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View</a>";
					}
					
					$row[ $column['dt'] ] =$html;
				}
				else if($j== 10)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$asset_code=$data[$i][ $columns[1]['field'] ];
					$html1 = '';
										
					if(self::retrive_accessrights($role,'ViewTransferHistory')==1)
					{
						$html1 .= "<button type='button'  id='transfereasset' data-type='transferedetails' data-toggle='modal' 
								data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$uid}' asset_code='{$asset_code}'><i class='icon-eye-open'></i> View</button>";
					}
					
					$row[ $column['dt'] ] =$html1;
				}
				else if($j== 11)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html2 = '';
										
					if(self::retrive_accessrights($role,'ViewIssuedHistory')==1)
					{
						$html2 .= "<button type='button'  id='issuedhistory' data-type='issuedhistory' data-toggle='modal' 
								data-target='#load_modal_issued_history' class='btn btn-info issuedhistoryviewmodal btn-clean' asset_id='{$uid}'><i class='icon-eye-open'></i> View</button>";
					}
					
					$row[ $column['dt'] ] =$html2;
				}
				else if($j== 12)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html3 = '';
										
					if(self::retrive_accessrights($role,'ViewSalesDetails')==1)
					{
						$html3 .= "<button type='button'  id='transfereasset' data-type='saledetails' data-toggle='modal' 
								data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$uid}'>						
								<i class='icon-eye-open'></i> View </button>";
					}
					
					$row[ $column['dt'] ] =$html3;
				}
				else if($j== 13)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html4 = '';
										
					if(self::retrive_accessrights($role,'ViewTheftDetails')==1)
					{
						$html4 .= "<button type='button'  id='transfereasset' data-type='theftdetails' data-toggle='modal' data-target='#load_modal' class='btn btn-info viewmodal btn-clean' asset_id='{$uid}'><i class='icon-eye-open'></i> View</a></button>";
					}
					
					$row[ $column['dt'] ] =$html4;
				}
				else if($j== 14)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html5 = '';
										
					if(self::retrive_accessrights($role,'equipmentlogownrecord')==1)
					{
						$html5 .= "<a href='equipmentlogownrecord/{$uid}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View</a>";
					}
					
					$row[ $column['dt'] ] =$html5;
				}
				else if($j== 15)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$expense = '';					
					if(self::retrive_accessrights($role,'TotalMaintenanceExpence')==1)
					{
						$expense = self::get_asset_expense($uid);
					}
					
					$row[ $column['dt'] ] =$expense;
				}
				else if($j== 16)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html6 = '';
										
					if(self::retrive_accessrights($role,'maintenancerecords')==1)
					{
						$html6 .= "<a href='maintenancerecords/{$uid}' class='btn btn-clean btn-info'><i class='icon-eye-open'></i> View</a>";
					}
					
					$row[ $column['dt'] ] =$html6;
				}
				else if($j== 17)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html7 = '';
										
					if(self::retrive_accessrights($role,'ViewBookingHistory')==1)
					{
						$html7 .= "<button type='button'  id='bookinghistory' data-type='bookinghistory' data-toggle='modal' 
									data-target='#load_modal_booking_history' class='btn btn-info bookinghistoryviewmodal btn-clean' asset_id='{$uid}'><i class='icon-eye-open'></i> View</a></td></button>";
					}
					
					$row[ $column['dt'] ] =$html7;
				}
				else if($j== 18)
				{
					$uid=$data[$i][ $columns[$j]['field'] ];
					$role = self::get_user_role($user);
					$html7 = '';
										
					 if(self::retrive_accessrights($role,'ViewEfficiencyHistory')==1)
					 {
						$html7 .= "<button type='button'  id='efficiencyhistory' data-type='bookinghistory' data-toggle='modal' 
									data-target='#Efficiency_history' class='btn btn-info efficiencyhistorymodal btn-clean' asset_id='{$uid}'><i class='icon-eye-open'></i> View</a></td></button>";
					 }
					
					$row[ $column['dt'] ] =$html7;
				}
				else {
					$row[ $column['dt'] ] = $data[$i][ $columns[$j]['field'] ];
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

					$orderBy[] = ' '.$column['db'].' '.$dir;
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
					$globalSearch[] = "`".$column['field']."` LIKE ".$binding;
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
					$columnSearch[] = "`".$column['field']."` LIKE ".$binding;
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
		
		return $where;
		
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
	static function simple ( $request, $conn, $table, $primaryKey, $columns ,$joinQuery,$user)
	{	
		$role = self::get_user_role($user);
		$post = $request;
		// debug($post);die;
		$projects_ids = self::users_project($user);
		$or = array();
		$deployed_to = explode(",",$post["project_id"]);
		$make_id = explode(",",$post["make_id"]);
		$asset_group = explode(",",$post["asset_group"]);
		$asset_name = explode(",",$post["asset_name"]);
		$status = explode(",",$post["status"]);
		
		$or["asset.purchase_date >="] = ($post["purchase_from_date"] != "")?date("Y-m-d",strtotime($post["purchase_from_date"])):NULL;
		$or["asset.purchase_date <="] = ($post["purchase_to_date"] != "")?date("Y-m-d",strtotime($post["purchase_to_date"])):NULL;
		$or["asset.deployed_to IN"] = (!empty($deployed_to) && $deployed_to[0] != "All" )?$post["project_id"]:NULL;
		$or["asset.asset_make IN"] = (!empty($make_id) && $make_id[0] != "All" )?$post["make_id"]:NULL;
		$or["asset.asset_group IN"] = (!empty($asset_group) && $asset_group[0] != "All" )?$post["asset_group"]:NULL;
		$or["asset.asset_name IN"] = (!empty($asset_name) && $asset_name[0] != "All" )?$post["asset_name"]:NULL;
		$or["asset.asset_code ="] = (!empty($post["asset_id"]))?$post["asset_id"]:NULL;
		$or["asset.capacity ="] = (!empty($post["asset_capacity"]))?$post["asset_capacity"]:NULL;
		$or["asset.vehicle_no ="] = (!empty($post["identity"]))?$post["identity"]:NULL;
		
		
		if($or["asset.deployed_to IN"] == NULL)
		{
			if(self::project_alloted($role)==1){ 
				$or["asset.deployed_to IN"] = $projects_ids;
			}
		}
		$asset_ids = array();
		if(in_array("breakdown",$status))
		{
			$breakdown_asset = self::get_breakdown_asset();
			$asset_ids = array_merge($asset_ids,$breakdown_asset);
		}
		
		if(in_array("idle",$status))
		{
			$idle_asset = self::get_idle_asset();
			$asset_ids = array_merge($asset_ids,$idle_asset);
		}
		
		if(in_array("sold",$status))
		{
			$sold_asset = self::get_sold_asset();
			$asset_ids = array_merge($asset_ids,$sold_asset);
		}
		
		if(in_array("theft",$status))
		{
			$theft_asset = self::get_theft_asset();
			$asset_ids = array_merge($asset_ids,$theft_asset);
		}
		
		if(!empty($asset_ids))
		{
			$asset_ids = array_unique($asset_ids);
			$or["asset.asset_id IN"] = implode(",",$asset_ids);
		}
						
		$keys = array_keys($or,"");				
		foreach ($keys as $k)
		{unset($or[$k]);}
		
		// echo "<pre>";
		// print_r($post);
		// echo "</pre>";
		// echo "<pre>";
		// print_r($or);
		// echo "</pre>";
		// die;
			
		$bindings = array();
		$db = self::db( $conn );
	
		$new_table = $table;
		
		$limit = self::limit( $request, $columns );
		$order = self::order( $request, $columns );
		$where = self::filter( $request, $columns, $bindings );
		// debug($extrawhere);die;
		$extraquery = '';
		$extrawhere = '';
		$i = 0;
		foreach($or as $key=>$value)
		{
			if($i == 0)
			{
				if($key == "asset.deployed_to IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
					// $extraquery .= ' '. $key .' '. '('.implode(",",$value).')';
				}elseif($key == "asset.asset_make IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}elseif($key == "asset.asset_group IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}elseif($key == "asset.asset_name IN")
				{
					$extraquery .= ' '. $key .' '. '("'.$value.'")';
				}elseif($key == "asset.asset_id IN")
				{
					$extraquery .= ' '. $key .' '. '('.$value.')';
				}else
				{
					$extraquery .= ' '. $key .' '. "'$value'";
				}
			}
			else
			{
				if($key == "asset.deployed_to IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}elseif($key == "asset.asset_make IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}elseif($key == "asset.asset_group IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}elseif($key == "asset.asset_name IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '("'.$value.'")';
				}elseif($key == "asset.asset_id IN")
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. '('.$value.')';
				}else
				{
					$extraquery .= ' '.'AND' .' '. $key .' '. "'$value'";
				}
			}
			$i++;
		}
		// debug($extraquery);die;
		if($extraquery != '' )
		{
			$extrawhere = ($where) ? 'AND'.$extraquery : 'WHERE'. $extraquery;
		}
		
		// echo "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", self::pluck($columns, 'db'))."
			// FROM $joinQuery
			// $where
			// $extrawhere
			// $order
			// $limit";die;
		$data = self::sql_exec( $db, $bindings,
			"SELECT SQL_CALC_FOUND_ROWS ".implode(", ", self::pluck($columns, 'db'))."
			FROM $joinQuery
			$where
			$extrawhere
			$order
			$limit"
		);
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
	
	static function get_date($date)
	{
			return date('d-m-Y',strtotime($date));
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
	
	static function users_project($user_id)
	{
		$erp_projects_assign = TableRegistry::get('erp_projects_assign'); 
		$result = $erp_projects_assign->find()->where(['user_id'=>$user_id]);
		$projects_id = array();
		foreach($result as $retrive_data)
		{
			$projects_id[] = $retrive_data['project_id'];
		}

		return $projects_id;
	}
	
	static function asset_group()
	{
		$erp_asset_groups = TableRegistry::get("erp_asset_groups");
		$groups = $erp_asset_groups->find();
		$asset_group = array();
		foreach($groups as $group)
		{
			$asset_group[$group->id] = array('id'=>$group->id,'code'=>$group->code,'title'=>$group->title);
		}
		return $asset_group;
	}
	
	static function get_asset_group_name($id)
	{
		$asset_group = self::asset_group();
		return $asset_group[$id]['title'];
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
	
	static function get_asset_last_issueto($asset_id){
		$erp_asset_issued_history = TableRegistry::get('erp_asset_issued_history');
		$row = $erp_asset_issued_history->find()->where(["asset_id"=>$asset_id])->order(['id' => 'desc'])->first();
		if(!empty($row))
		{
			return $row->issued_to;
		}else{
			return "NA";
		}
	}
	
	static function get_asset_release_date($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id])->order(['history_id' => 'desc'])->first();
		if(!empty($row))
		{
			return ($row->release_date != "")?date("d-m-Y",strtotime($row->release_date)):'NA';
		}else{
			return "NA";
		}
	}
	
	static function is_asset_accept_remain($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$count = $erp_assets_history->find()->where(["asset_id"=>$asset_id,"accepted"=>0])->count();
		return $count;
	}
	
	public function get_asset_last_transfer_project($asset_id){
		$erp_assets_history = TableRegistry::get('erp_assets_history');
		$row = $erp_assets_history->find()->where(["asset_id"=>$asset_id,"accepted"=>0])->first();
		if(!empty($row))
		{
			return $row->new_project;
		}else{
			return "";
		}
	}
	
	static function get_asset_expense($asset_id)
	{
		$maint_tbl = TableRegistry::get("erp_assets_maintenance");
		$expenses = $maint_tbl->find()->where(["asset_id"=>$asset_id])->hydrate(false)->toArray();
		$total = 0;
		if(!empty($expenses))
		{		
			foreach($expenses as$expense)
			{
				$total += $expense["expense_amount"];
			}
			return $total;
		}else{
			return "NA";
		}
	}
	
	static function get_breakdown_asset()
	{
		$erp_equipmentown_log = TableRegistry::get("erp_equipmentown_log");
		$ids = $erp_equipmentown_log->find()->where(["working_status"=>"breakdown"])->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
	
	static function get_idle_asset()
	{
		$erp_equipmentown_log = TableRegistry::get("erp_equipmentown_log");
		$ids = $erp_equipmentown_log->find()->where(["working_status"=>"idle"])->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
	
	static function get_sold_asset()
	{
		$erp_assets_sold_history = TableRegistry::get("erp_assets_sold_history");
		$ids = $erp_assets_sold_history->find()->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
	
	static function get_theft_asset()
	{
		$erp_assets_theft_history = TableRegistry::get("erp_assets_theft_history");
		$ids = $erp_assets_theft_history->find()->hydrate(false)->toArray();
		$asset_ids = array();
		if(!empty($ids))
		{		
			foreach($ids as $retrive)
			{
				$asset_ids[] = $retrive["asset_id"];
			}
		}
		return $asset_ids;
	}
}

