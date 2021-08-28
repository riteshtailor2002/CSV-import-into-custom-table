<?php
global $wpdb;
// If button is pressed to "Import to DB"
if(isset($_POST['execute_button']))
{
   // Check that "Input File" has proper .csv file extension
   $ext = pathinfo( $_POST[ 'csv_file' ], PATHINFO_EXTENSION );
   // If all fields are input; and file is correct .csv format; continue
	if ( ! empty( $_POST[ 'csv_file' ] ) && ($ext === 'csv') ) 
	{
      $db_cols = $wpdb->get_col( "DESC " . $wpdb->prefix .'tl_csv_import', 0 );  // Array of db column names      
		array_shift($db_cols);
	   $myCSV   = $_POST[ 'csv_file' ];
	   $path    = parse_url( $myCSV, PHP_URL_PATH );
	   $myCSV   = $_SERVER[ 'DOCUMENT_ROOT' ] . $path;
      $myPDF = '';
		if ( ( $fh = @fopen( $myCSV, 'r' )) !== false ) 
		{	
			// Set variables
			$values              = array();
			$too_many    = '';  // Used to alert users if columns do not match
			$date = date("d-F-Y h:i:sa");
			while ( ( $row = fgetcsv( $fh )) !== false ) 
			{  // Get file contents and set up row array 
				$num = count($row);
				if(!empty($row) && $row != '' && !array_filter($row) == [])
				{
					array_push($row,$myPDF);
					array_push($row,$date);
					$row = array_map( function($v) 
					{
						return esc_sql( $v );
					}, $row );
					if(!empty($row) && $row != '' && $row != $db_cols)
					{
						$values[] = '("' . implode( '", "', $row ) . '")';  // Each new line of .csv file becomes an array
					}
				}
			}
			$num_var = 1;
			$values = array_slice( $values, $num_var );
		}
      // If the user DID NOT input more rows than are available from the .csv file
		if ( $too_many !== 'true' ) 
		{
         $db_query_update = '';
         $db_query_insert = '';
         // Format $db_cols to a string
         $db_cols_implode = implode( ',', $db_cols );
         // Format $values to a string
         $values_implode = implode( ',', $values );
         // If "Update DB Rows" was checked
			if ( isset( $_POST[ 'insert_db' ] ) ) 
			{			
				$wpdb->query('TRUNCATE TABLE `wp_tl_csv_import`');
				$sql  = 'INSERT INTO `wp_tl_csv_import` (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode;
				$wpdb->query( $sql );

				$upload_dir   = wp_upload_dir();
				$foldername = 'allpdf';
				$folder_path = $upload_dir['basedir'].'/'.$foldername.'/';	
				if(is_dir($folder_path))
				{
					$this->plug_delFolder($folder_path) ;
				}
				$location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin&imported=1';
				echo '<script>window.location = "'.$location.'";</script>';exit;
			}
			else
			{
				$sql  = 'INSERT INTO `wp_tl_csv_import` (' . $db_cols_implode . ') ' . 'VALUES ' . $values_implode;
				$wpdb->query( $sql );
            $location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin&imported=2';
            echo '<script>window.location = "'.$location.'";</script>';exit;
			}
		}
		else
		{
         $location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin&imported=3';
         echo '<script>window.location = "'.$location.'";</script>';exit;
		}
			
	}
	else
	{
      $location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin&imported=4';
      echo '<script>window.location = "'.$location.'";</script>';exit;
	}
	
 }
 
/*this is for deleting / updating existing file */
if(isset($_POST["pdf_file"]))
{
   if (count($_POST["pdf_file"]) > 0)
   {
      $pdfCount = count($_POST["pdf_file"]);
      for($i=0;$i<$pdfCount;$i++) 
      {         
         $wpdb->update('wp_tl_csv_import', array('pdf_url'=> $_POST["pdf_file"][$i]), array('id'=>$_POST["csv_id"][$i]));
      } 
      if(isset($_POST['actualpdf']) && $_POST['actualpdf']!='')
      {
         $upload_dir   = wp_upload_dir();
         $foldername = 'allpdf';
         $pdf_file_path = $upload_dir['basedir'].'/'.$foldername.'/';
         $pdfpath = $pdf_file_path.$_POST['actualpdf'];
         if(file_exists($pdfpath))
         {
            unlink($pdfpath);            
         }   
         $location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin&filedeteletd=1';
         echo '<script>window.location = "'.$location.'";</script>';exit;      
      }
      else
      {
         $location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin&records=1';
         echo '<script>window.location = "'.$location.'";</script>';exit;  
      }
      
   }   
   $location  = site_url().'/wp-admin/admin.php?page=csv_import_plugin';
   echo '<script>window.location = "'.$location.'";</script>';exit; 
}?>