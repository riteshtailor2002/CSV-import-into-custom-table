<?php
global $wp_session;
$user = wp_get_current_user();
$userDataArray = array(
    "user_id" => $user->ID,
);
$wp_session['userData'] = $userDataArray;
if(isset($wp_session['userData'])) {
    $value = $wp_session['userData'];
} else {
    $value = '';
}
$backendURL = site_url().'/wp-admin/admin.php?page=csv_import_plugin';
include('processing.php');
if ( in_array( 'administrator', (array) $user->roles ) || in_array( 'editor', (array) $user->roles )) {
?>
<div id="tabs-1" class="importingform">
   <form id="tl_csv_imp_to_db_form" method="post" action="">
       <table class="form-table">
           <tr>
				  	<td valign="top"><?php _e( 'Select Input File:', 'tl_csv_imp_to_db' ); ?></td>
               <td valign="top">
                   <?php $repop_file    = isset( $_POST[ 'csv_file' ] ) ? $_POST[ 'csv_file' ] : null; ?>
                   <?php $repop_csv_cols        = isset( $_POST[ 'num_cols_csv_file' ] ) ? $_POST[ 'num_cols_csv_file' ] : '0'; ?>
                   <input id="csv_file" name="csv_file"  type="text" size="70" value="<?php echo $repop_file; ?>" />
                   <input id="csv_file_button" type="button" value="Upload" />
                   <input id="num_cols" name="num_cols" type="hidden" value="" />
                   <input id="num_cols_csv_file" name="num_cols_csv_file" type="hidden" value="" />
                   <br><?php _e( 'File must end with a .csv extension.', 'tl_csv_imp_to_db' ); ?>
                   <br><?php
                   _e( 'Download sample csv from here', 'tl_csv_imp_to_db' );
                   echo ' ';
                   ?><span id="return_csv_col_count1"><a href="<?php echo site_url(); ?>/wp-content/uploads/2020/07/01.07.20-Customtable.csv" download="sample.csv"><?php _e('Download'); ?></a></span>
					</td>
					<td valign="top">
                   <input id="insert_db" name="insert_db" type="checkbox" />
                  <?php _e( 'Please select the checkbox if you would like the existing data erased and the new import to replace it.', 'tl_csv_imp_to_db' ); ?>
					</td>
					<td valign="top">					
						<input id="execute_button" name="execute_button" type="submit" class="button-primary" value="<?php _e( 'Import to DB', 'tl_csv_imp_to_db' ) ?>" />					
               </td>
			  </tr> 
			  <?php if(isset($_REQUEST['imported']) && $_REQUEST['imported']!=''):?>
			  <tr>
				  <td colspan="4">
					<div class="error-success">
						<?php if(isset($_REQUEST['imported']) && $_REQUEST['imported']=='1'):?>
							<p class="success-import">All of the existing records have been deleted and the new records have now been imported.</p>
						<?php endif;?>
						<?php if(isset($_REQUEST['imported']) && $_REQUEST['imported']=='2'):?>
							<p class="success-import">Records have been added.</p>
						<?php endif;?>
						<?php if(isset($_REQUEST['imported']) && $_REQUEST['imported']=='3'):?>
							<p class="error-import">Some of the columns have not matched. Please check the sample csv.</p>
						<?php endif;?>
						<?php if(isset($_REQUEST['imported']) && $_REQUEST['imported']=='4'):?>
							<p class="error-import">Please upload the csv file.</p>
						<?php endif;?>
						</td>
				  	</div>
				</tr> 
			  <?php endif;?>         
       </table>       
	</form>
<!--uploading scripts starts here //added by R-->
<table class="uploadertable form-table">
	<tr>
		<td width="50%">
			<div class="form-field col-md-12">				
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Upload Pdf files<span></h3>
					</div>
					<div class="areatoupload">
						<div id="drag-and-drop-zone" class="uploader">
							<div>Drag &amp; Drop files Here</div>
							<div class="or">-or-</div>
							<div class="browser">
								<label>
									<span>Click to open the file Browser</span>
									<input type="file" name="files[]"  multiple="multiple" title='Click to add files'>
									<input type="hidden" name="completedcontract" id="completedcontract" value="" />
								</label>
							</div>
						</div>
					</div>
				</div>
				<hr/>
			</div>
		</td>
		<td width="50%">
			<div class="form-field col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Uploads<span>Once all of the files have uploaded successfully please <a href="javascript:void(0);" onclick="javascript:window.location='<?php echo $backendURL;?>';">click here</a> to refresh the page to see the uploaded files.</span></h3>
					</div>
					<?php
					$uploadedString = '';								
					$uploadedString .= '<div class="panel-body demo-panel-files" id="demo-files">';
					$uploadedString .= '<span class="demo-note">No Files have been selected/droped yet...</span>';
					$uploadedString .= '</div>';
					echo $uploadedString;
					?>
					
				</div>
			</div>
		</td>
		<td>
			<div class="uploaded-files">
				<?php
				if(isset($completedcontract_array) && is_array($completedcontract_array) && !empty($completedcontract_array))
				{
					foreach($completedcontract_array as $single_file)
					{
						$file_icon = GetFileIcons($single_file);
						$div_id = str_replace(".","",$single_file);
						$metakey = $pid.'_completedcontract';
						echo '<div class="file_content" id="'.$div_id.'">';											
						echo  '<span class="file_url"><a target="_blank" href="'.$filepath_url.$single_file.'">'.$file_icon.'</a></span>';
						echo '<span class="delete_url">';
						echo '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="deleteFile(/'.$single_file.'/,/'.$metakey.'/,/'.$user_id.'/);"><i class="fa fa-trash-o"></i></a></span>';											
						echo '</div>';
					}
				}
				?>
			</div>
		</td>
	</tr>
</table>
<!--uploading scripts ends here //added by R-->

</div> <!-- End tab 1 -->
<!-- uploading functionality starts here -->
<?php
/* display table starts here */
if ( in_array( 'administrator', (array) $user->roles ) || in_array( 'editor', (array) $user->roles )) {
    //The user has the "administrator" role
	global $wpdb;
	$where = 'where ';
   $conditions = array();
   if(isset($_GET['search_by_title']) && $_GET['search_by_title']!=''){    
		$sub_conditions = array();
		$sub_conditions[] = '(elite_job_number like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(order_date like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(supplier like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(supplier_inv_number like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(order_number like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(freight_mode like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(shipment_mode like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(fob_ex_works like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(load_port like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(disch_port like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(container_number like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(cont_count_type like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(number_pcs like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(type_packaging like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(departure_vessel like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(intended_arrival_vessel like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(arrival_voyage like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(etd like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(eta like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(estimated_delivery like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(master_house_bill_number like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(order_status_description like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(comments like "%'.$_GET['search_by_title'].'%")';
		$sub_conditions[] = '(pdf_url like "%'.$_GET['search_by_title'].'%")';
		$conditions[] = '('.implode(' or ',$sub_conditions).')';
	}  
	$where .=  implode(' and ',$conditions);
	$returnData = array();
	$postData = array();
	$table_name = $wpdb->prefix .'tl_csv_import';
	if(isset($_GET['submit']) && !empty($_GET['submit']) && $_GET['search_by_title']!=''){      
		$count_query = "SELECT COUNT(id) FROM $table_name ".$where;
		$pagenum = isset( $_GET['pagenum'] ) ? abs( (int) $_GET['pagenum'] ) : 1;;
	}else{
		$count_query = "SELECT COUNT(id) FROM $table_name ";
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	}
	$postData['pagenum'] = $pagenum;
   $limit = 60;
	$postData['limit'] = $limit;
	$offset = ( $pagenum - 1 ) * $limit;
	$postData['offset'] = $offset;	
	if(isset($_GET['search_by_title']) && $_GET['search_by_title']!='' && isset($_GET['submit'])){
		$search_query = "SELECT * FROM $table_name ".$where." ORDER BY id asc LIMIT $offset, $limit" ;
	}else{
		$search_query = "SELECT * FROM {$wpdb->prefix}tl_csv_import LIMIT $offset, $limit" ;
	}
	$results = $wpdb->get_results($search_query , OBJECT );
	$total = $wpdb->get_var($count_query);
	$postData['total'] = $total;
	$num_of_pages = ceil( $total / $limit );
	$postData['num_of_pages'] = $num_of_pages;
	$postData['returnData'] = $results;
?>
<div class="wrapper">
   <h2><?php _e('CSV Table Data'); ?></h2>
<form name="searchCsv" id="searchCsv" action="<?php echo esc_url( home_url( '/wp-admin/admin.php' ) ); ?>" method="GET">
	<div class="row col-sm-12">
		<div class="span5">
			<div class="control-group">
				<div class="controls">
					<input type="hidden" name="page" value="csv_import_plugin" />
					<input class="span12" placeholder="Search here" name="search_by_title" id="search_by_title" type="text" value="<?php if(isset($_GET['search_by_title']) && $_GET['search_by_title']!=''){echo $_GET['search_by_title'];}?>" />
					<!--<input type="hidden" name="reffer" value="<?php //echo $_SERVER['REQUEST_URI']?>" />-->
					<input type="submit" name="submit" class="btn btn-primary" value="Search" />
					<a href="<?php echo site_url().'/wp-admin/admin.php?page=csv_import_plugin';?>" class="btn btn-primary">Clear All</a>	
				</div>
			</div>
		</div>
	</div><!--end of row-->
</form><!--end of form-->

<div id="table_preview">
<form class="form-horizontal bulk_action" id="bulk_action" action="<?php //echo $formUrl;?>" name="bulk_action" method="post">
	<div class="table-toolbar text-right">

		<div class="btn-group">
			<?php if(isset($_REQUEST['records']) && $_REQUEST['records']=='1'):?>
				<span class="success-import inner">Records saved successfully.</span>
			<?php endif;?>
			<?php if(isset($_REQUEST['filedeteletd']) && $_REQUEST['filedeteletd']=='1'):?>
				<span class="success-import inner">File has been deleted successfully.</span>
			<?php endif;?>
			<input type="hidden" name="reffer" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<button type="submit" id="upload_button" name="upload_button" class="btn btn-primary">Bulk Save</button></a>
		</div>
	</div>

	<table id="ajax_table" class="table table-striped table-bordered joblistingtable">
		<thead>
		<tr>
			<th>S No.</th>
			<th>Elite Job Number</th>
			<th>Order Date</th>
			<th>Supplier</th>
			<th>Supplier Inv No.</th>
			<th>Order #</th>
			<th>Pdf File</th>
			<th width="4%">Upload Pdf</th>	
		</tr>
		</thead>
		<tbody>					
			<?php 						
			$c=1;
			foreach($results as $result){
			?>
			<tr>
				<td><?php echo $result->id; ?></td>
				<td><?php echo $result->elite_job_number; ?></td>
				<td><?php echo $result->order_date; ?></td>
				<td><?php echo $result->supplier; ?></td>
				<td><?php echo $result->supplier_inv_number; ?></td>
				<td><?php echo $result->order_number; ?></td>
				<?php 

					$upload_dir   = wp_upload_dir();
					$foldername = 'allpdf';
					$pdf_file_path = $upload_dir['basedir'].'/'.$foldername.'/';
					$pdfpath = $pdf_file_path.$result->elite_job_number;
					$filename = $result->elite_job_number.'.pdf';
				?>				
				<td>
				<?php 
				if($result->pdf_url != ''){ ?>
				<a href="<?php echo $result->pdf_url; ?>" target="_blank"><img src="<?php echo site_url(); ?>/wp-content/plugins/tl-csv-import/assets/images/icon-pdf.png" /></a>
				<?php } 
				else  if(file_exists($pdf_file_path.$filename)){ ?>
				<a href="<?php echo site_url(); ?>/wp-content/uploads/allpdf/<?php echo $filename ?>" target="_blank"><img src="<?php echo site_url(); ?>/wp-content/plugins/tl-csv-import/assets/images/icon-pdf.png" /></a>
				<?php } 
				if(file_exists($pdf_file_path.$filename) || $result->pdf_url != '' ):
				?>
				<a href="javascript:void(0);" onclick="clearFromTextbox('pdf_file_<?php echo $c; ?>','<?php echo $filename;?>');" style="display:block;">Delete pdf</a>
				<?php endif;?>
				</td>
				<td width="4%">
					<input id="pdf_file_<?php echo $c; ?>" class="pdf_file" name="pdf_file[]"  type="text" value="<?php echo $result->pdf_url; ?>" />
					<input id="pdf_file_button_<?php echo $c; ?>" type="button" value="Choose File" />
					<input type="hidden" id="csv_id_<?php echo $c;?>" name="csv_id[]" value="<?php echo $result->id;?>" >
               <button type="submit" id="upload_button" name="upload_button" class="btn btn-success">Save</button></a>
				</td>
			</tr>						
			<?php $c++;
			}	?>						
		</tbody>
	</table>
</form>
<script>
jQuery( document ).ready(function() {
	jQuery('.success-import,.error-import').fadeOut(8000);
	jQuery('.pagination.admin ul.page-numbers li').each(function(index, element) {
		if(jQuery(this).find('a.page-numbers').length && jQuery(this).find('a.page-numbers').attr('href')!='')
		{
			var url = jQuery(this).find('a.page-numbers').attr('href');			
			var parameter1 = 'imported';
			var parameter2 = 'records';
			var parameter3 = 'filedeteletd';
			url = removeURLParameter(url, parameter1);
			url = removeURLParameter(url, parameter2);
			url = removeURLParameter(url, parameter3);
			jQuery(this).find('a.page-numbers').attr('href',url);
		}
	});
});
function clearFromTextbox(textbox,actualpdf)
{
	
	if (confirm('Are you sure you want to delete ?'))
	{
	    jQuery('<input>').attr({
                                type: 'hidden',
                                id: 'actualpdf',
                                name: 'actualpdf',
                                value:actualpdf
                            }).appendTo('form#bulk_action');
		jQuery('#'+textbox).val('');
		jQuery('#bulk_action').submit();
		return true;
	}
	return false;
}
function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts = url.split('?');   
    if (urlparts.length >= 2) {

        var prefix = encodeURIComponent(parameter) + '=';
        var pars = urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i = pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        return urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : '');
    }
    return url;
}
</script>
</div>
<?php	  
	$pagination_array = array(
										'base' 	 => add_query_arg( 'pagenum', '%#%' ) ,
										'format' => '',
										'current' => $postData['pagenum'],
										'total' => $postData['num_of_pages'],
										'prev_next' => false,
										'type' => 'list',
									);
	$paginationVar = paginate_links($pagination_array);
	if(isset($paginationVar) && $paginationVar!='')
	{
		echo '<div class="table-toolbar"><div class="pull-right"><div class="dataTables_paginate paging_bootstrap pagination admin">';
		echo $paginationVar;
		echo '</div></div></div>';	
	}				
?>
</div>

<?php
}
}
elseif(in_array( 'subscriber', (array) $user->roles ))
{
	echo do_shortcode('[renderRecordListing]'); 
}