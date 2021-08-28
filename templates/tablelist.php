<?php
global $wp_session;
$frieght_mode = '';$fob_ex_works='';$shipment_mode = '';$load_port='';$disch_port='';$container_number='';$cont_count_type='';$number_pcs = '';$type_packaging='';$departure_vessel='';
$intended_arrival_vessel='';$arrival_voyage='';$etd='';$eta='';$estimated_delivery = '';$bill_number = '';$order_status = '';$comments='';
if(isset($_GET['submit']) && !empty($_GET['submit'])){
	if(isset($_GET['dsp_freight_mode'])){
		$_GET['dsp_freight_mode'] = 1;
		$frieght_mode = $_GET['dsp_freight_mode'];
	}else{
		$frieght_mode = '';
	}
	if(isset($_GET['dsp_shipment_mode'])){
		$_GET['dsp_shipment_mode'] = 1;
		$shipment_mode = $_GET['dsp_shipment_mode'];
	}else{
		$shipment_mode = '';
	}
	if(isset($_GET['dsp_fob_ex_works'])){
		$_GET['dsp_fob_ex_works'] = 1;
		$fob_ex_works = $_GET['dsp_fob_ex_works'];
	}else{
		$fob_ex_works = '';
	}
	if(isset($_GET['dsp_load_port'])){
		$_GET['dsp_load_port'] = 1;
		$load_port = $_GET['dsp_load_port'];
	}else{
		$load_port = '';
	}
	if(isset($_GET['dsp_disch_port'])){
		$_GET['dsp_disch_port'] = 1;
		$disch_port = $_GET['dsp_disch_port'];
	}else{
		$disch_port = '';
	}
	if(isset($_GET['dsp_container_number'])){
		$_GET['dsp_container_number'] = 1;
		$container_number = $_GET['dsp_container_number'];
	}else{
		$container_number = '';
	}
	if(isset($_GET['dsp_cont_count_type'])){
		$_GET['dsp_cont_count_type'] = 1;
		$cont_count_type = $_GET['dsp_cont_count_type'];
	}else{
		$cont_count_type = '';
	}
	if(isset($_GET['dsp_number_pcs'])){
		$_GET['dsp_number_pcs'] = 1;
		$number_pcs = $_GET['dsp_number_pcs'];
	}else{
		$number_pcs = '';
	}
	if(isset($_GET['dsp_type_packaging'])){
		$_GET['dsp_type_packaging'] = 1;
		$type_packaging = $_GET['dsp_type_packaging'];
	}else{
		$type_packaging = '';
	}
	if(isset($_GET['dsp_departure_vessel'])){
		$_GET['dsp_departure_vessel'] = 1;
		$departure_vessel = $_GET['dsp_departure_vessel'];
	}else{
		$departure_vessel = '';
	}
	if(isset($_GET['dsp_intended_arrival_vessel'])){
		$_GET['dsp_intended_arrival_vessel'] = 1;
		$intended_arrival_vessel = $_GET['dsp_intended_arrival_vessel'];
	}else{
		$intended_arrival_vessel = '';
	}
	if(isset($_GET['dsp_arrival_voyage'])){
		$_GET['dsp_arrival_voyage'] = 1;
		$arrival_voyage = $_GET['dsp_arrival_voyage'];
	}else{
		$arrival_voyage = '';
	}
	if(isset($_GET['dsp_etd'])){
		$_GET['dsp_etd'] = 1;
		$etd = $_GET['dsp_etd'];
	}else{
		$etd = '';
	}
	if(isset($_GET['dsp_eta'])){
		$_GET['dsp_eta'] = 1;
		$eta = $_GET['dsp_eta'];
	}else{
		$eta = '';
	}
	if(isset($_GET['dsp_estimated_delivery'])){
		$_GET['dsp_estimated_delivery'] = 1;
		$estimated_delivery = $_GET['dsp_estimated_delivery'];
	}else{
		$estimated_delivery = '';
	}
	if(isset($_GET['dsp_master_house_bill_number'])){
		$_GET['dsp_master_house_bill_number'] = 1;
		$bill_number = $_GET['dsp_master_house_bill_number'];
	}else{
		$bill_number = '';
	}
	if(isset($_GET['dsp_order_status_description'])){
		$_GET['dsp_order_status_description'] = 1;
		$order_status = $_GET['dsp_order_status_description'];
	}else{
		$order_status = '';
	}
	if(isset($_GET['dsp_comments'])){
		$_GET['dsp_comments'] = 1;
		$comments = $_GET['dsp_comments'];
	}else{
		$comments = '';
	}
}
$user = wp_get_current_user();
$userDataArray = array(
    "user_id" => $user->ID,
	 "dsp_frieght_mode" => $frieght_mode,
	 "dsp_shipment_mode" => $shipment_mode,
	 "dsp_fob_ex_works" => $fob_ex_works,
	 "dsp_load_port" => $load_port,
	 "dsp_disch_port" => $disch_port,
	 "dsp_container_number" => $container_number,
	 "dsp_cont_count_type" => $cont_count_type,
	 "dsp_number_pcs" => $number_pcs,
	 "dsp_type_packaging" => $type_packaging,
	 "dsp_departure_vessel" => $departure_vessel,
	 "dsp_intended_arrival_vessel" => $intended_arrival_vessel,
	 "dsp_arrival_voyage" => $arrival_voyage,
	 "dsp_etd" => $etd,
	 "dsp_eta" => $eta,
	 "dsp_estimated_delivery" => $estimated_delivery,
	 "dsp_master_house_bill_number" => $bill_number,
	 "dsp_order_status_description" => $order_status,
	 "dsp_comments" => $comments
);
$wp_session['userData'] = $userDataArray;
if(isset($wp_session['userData'])) {
    $value = $wp_session['userData'];
} else {
    $value = '';
}
if ( in_array( 'subscriber', (array) $user->roles ) || in_array( 'administrator', (array) $user->roles )  || in_array( 'editor', (array) $user->roles )) {
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
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	}else{
		$count_query = "SELECT COUNT(id) FROM $table_name ";
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	}
	$postData['pagenum'] = $pagenum;
	$limit = 60;
	$postData['limit'] = $limit;
	$offset = ( $pagenum - 1 ) * $limit;
	$postData['offset'] = $offset;	
	if(isset($_GET['search_by_title']) && $_GET['search_by_title']!=''){
		$search_query = "SELECT * FROM $table_name ".$where." ORDER BY id asc LIMIT $offset, $limit" ;
	}else{
		$search_query = "SELECT * FROM {$wpdb->prefix}tl_csv_import LIMIT $offset, $limit" ;
	}
	$results = $wpdb->get_results($search_query , OBJECT );
	$total = $wpdb->get_var($count_query);
	$postData['total'] = $total;
	$num_of_pages = ceil( $total / $limit );
	$postData['num_of_pages'] = $num_of_pages;
	$rowcount = $wpdb->num_rows;
	$postData['rowcount'] = $rowcount;
	$postData['returnData'] = $results;	
?>
<div class="wrapper">	
<form name="searchCsv" id="searchCsv" action="<?php echo $_SERVER['PHP_SELF'];//echo $formUrl;?>" method="GET">
<ul class="nav table">

	<li class="nav-item">
	<input type="checkbox" id="dsp_freight_mode" name="dsp_freight_mode" value="1" <?php if ($value['dsp_frieght_mode']==1): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Freight Mode</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_shipment_mode" name="dsp_shipment_mode" value="1" <?php if ($value['dsp_shipment_mode']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Shipment Mode</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_fob_ex_works" name="dsp_fob_ex_works" value="1" <?php if ($value['dsp_fob_ex_works']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Fob Ex Works</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_load_port" name="dsp_load_port" value="1" <?php if ($value['dsp_load_port']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Load Port</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_disch_port" name="dsp_disch_port" value="1" <?php if ($value['dsp_disch_port']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Disch Port</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_container_number" name="dsp_container_number" value="1" <?php if ($value['dsp_container_number']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Container Number</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_cont_count_type" name="dsp_cont_count_type" value="1" <?php if ($value['dsp_cont_count_type']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Cont Count Type</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_number_pcs" name="dsp_number_pcs" value="1" <?php if ($value['dsp_number_pcs']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Number Pcs</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_type_packaging" name="dsp_type_packaging" value="1" <?php if ($value['dsp_type_packaging']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Type Packaging</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_departure_vessel" name="dsp_departure_vessel" value="1" <?php if ($value['dsp_departure_vessel']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Departure Vessel</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_intended_arrival_vessel" name="dsp_intended_arrival_vessel" value="1" <?php if ($value['dsp_intended_arrival_vessel']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Intended Arrival Vessel</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_arrival_voyage" name="dsp_arrival_voyage" value="1" <?php if ($value['dsp_arrival_voyage']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Arrival Voyage</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_etd" name="dsp_etd" value="1" <?php if ($value['dsp_etd']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Etd</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_eta" name="dsp_eta" value="1" <?php if ($value['dsp_eta']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Eta</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_estimated_delivery" name="dsp_estimated_delivery" value="1" <?php if ($value['dsp_estimated_delivery']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Estimated Delivery</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_master_house_bill_number" name="dsp_master_house_bill_number" value="1" <?php if ($value['dsp_master_house_bill_number']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Bill Number</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_order_status_description" name="dsp_order_status_description" value="1" <?php if ($value['dsp_order_status_description']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Order Status</label>
	</li>

	<li class="nav-item">
	<input type="checkbox" id="dsp_comments" name="dsp_comments" value="1" <?php if ($value['dsp_comments']!=''): ?> checked="checked"<?php endif; ?>><label for="frieghtmode">Comments</label>
	</li>

</ul>
	<div class="row col-sm-12">
		<div class="span5">
			<div class="control-group">
				<div class="controls">
					<input class="span12" placeholder="Search here" name="search_by_title" id="search_by_title" type="text" value="<?php if(isset($_POST['search_by_title']) && $_POST['search_by_title']!=''){echo $_POST['search_by_title'];}?>" />
					<!--<input type="hidden" name="reffer" value="<?php /*echo $_SERVER['REQUEST_URI']*/?>" />-->
					<input type="submit" name="submit" class="btn btn-primary" value="Update Columns OR Search" />
					<a href="<?php echo site_url();?>" class="btn btn-primary">Clear All</a>					
				</div>
			</div>
		</div>
	</div><!--end of row-->
</form><!--end of form-->
<div id="table_preview" class="table-responsive">
	<table id="ajax_table" class="table table-striped">
		<thead class="thead-dark">
		<tr>
			<th scope="col">S No.</th>
			<th scope="col">Elite Job Number</th>
			<th scope="col">Order Date</th>
			<th scope="col">Supplier</th>
			<th scope="col">Supplier Inv No.</th>
			<th scope="col">Order #</th>
			<?php if($value['dsp_frieght_mode']==1){ ?><th scope="col">Frieght Mode</th><?php } ?>
			<?php if($value['dsp_shipment_mode']==1){ ?><th scope="col">Shipment Mode</th><?php } ?>
			<?php if($value['dsp_fob_ex_works']==1){ ?><th scope="col">Fob Ex Works</th><?php } ?>
			<?php if($value['dsp_load_port']==1){ ?><th scope="col">Load Port</th><?php } ?>
			<?php if($value['dsp_disch_port']==1){ ?><th scope="col">Disch Port</th><?php } ?>
			<?php if($value['dsp_container_number']==1){ ?><th scope="col">Container Number</th><?php } ?>
			<?php if($value['dsp_cont_count_type']==1){ ?><th scope="col">Cont Count Type</th><?php } ?>
			<?php if($value['dsp_number_pcs']==1){ ?><th scope="col">Number Pcs</th><?php } ?>
			<?php if($value['dsp_type_packaging']==1){ ?><th scope="col">Type Packaging</th><?php } ?>
			<?php if($value['dsp_departure_vessel']==1){ ?><th scope="col">Departure Vessel</th><?php } ?>
			<?php if($value['dsp_intended_arrival_vessel']==1){ ?><th scope="col">Intended Arrival Vessel</th><?php } ?>
			<?php if($value['dsp_arrival_voyage']==1){ ?><th scope="col">Arrival Voyage</th><?php } ?>
			<?php if($value['dsp_etd']==1){ ?><th scope="col">Etd</th><?php } ?>
			<?php if($value['dsp_eta']==1){ ?><th scope="col">Eta</th><?php } ?>
			<?php if($value['dsp_estimated_delivery']==1){ ?><th scope="col">Estimated Delivery</th><?php } ?>
			<?php if($value['dsp_master_house_bill_number']==1){ ?><th scope="col">Bill Number</th><?php } ?>
			<?php if($value['dsp_order_status_description']==1){ ?><th scope="col">Order Status</th><?php } ?>
			<?php if($value['dsp_comments']==1){ ?><th scope="col">Comments</th><?php } ?>
			<th>Pdf File</th>
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
				<?php if($value['dsp_frieght_mode']==1){ ?><td><?php echo $result->freight_mode; ?></td><?php } ?>
				<?php if($value['dsp_shipment_mode']==1){ ?><td><?php echo $result->shipment_mode; ?></td><?php } ?>
				<?php if($value['dsp_fob_ex_works']==1){ ?><td><?php echo $result->fob_ex_works; ?></td><?php } ?>
				<?php if($value['dsp_load_port']==1){ ?><td><?php echo $result->load_port; ?></td><?php } ?>
				<?php if($value['dsp_disch_port']==1){ ?><td><?php echo $result->disch_port; ?></td><?php } ?>
				<?php if($value['dsp_container_number']==1){ ?><td><?php echo $result->container_number; ?></td><?php } ?>
				<?php if($value['dsp_cont_count_type']==1){ ?><td><?php echo $result->cont_count_type; ?></td><?php } ?>
				<?php if($value['dsp_number_pcs']==1){ ?><td><?php echo $result->number_pcs; ?></td><?php } ?>
				<?php if($value['dsp_type_packaging']==1){ ?><td><?php echo $result->type_packaging; ?></td><?php } ?>
				<?php if($value['dsp_departure_vessel']==1){ ?><td><?php echo $result->departure_vessel; ?></td><?php } ?>
				<?php if($value['dsp_intended_arrival_vessel']==1){ ?><td><?php echo $result->intended_arrival_vessel; ?></td><?php } ?>
				<?php if($value['dsp_arrival_voyage']==1){ ?><td><?php echo $result->arrival_voyage; ?></td><?php } ?>
				<?php if($value['dsp_etd']==1){ ?><td><?php echo $result->etd; ?></td><?php } ?>
				<?php if($value['dsp_eta']==1){ ?><td><?php echo $result->eta; ?></td><?php } ?>
				<?php if($value['dsp_estimated_delivery']==1){ ?><td><?php echo $result->estimated_delivery; ?></td><?php } ?>
				<?php if($value['dsp_master_house_bill_number']==1){ ?><td><?php echo $result->master_house_bill_number; ?></td><?php } ?>
				<?php if($value['dsp_order_status_description']==1){ ?><td><?php echo $result->order_status_description; ?></td><?php } ?>
				<?php if($value['dsp_comments']==1){ ?><td><?php echo $result->comments; ?></td><?php } ?>
				<?php 
					$pdf_file_path = $_SERVER['DOCUMENT_ROOT'].'/truckline/wp-content/uploads/allpdf/';
					$pdfpath = $pdf_file_path.$result->elite_job_number;
					$filename = $result->elite_job_number.'.pdf';
				?>				
				<td><?php if(file_exists($pdf_file_path.$filename)){ ?><a href="<?php echo site_url(); ?>/wp-content/uploads/allpdf/<?php echo $filename ?>" target="_blank"><img src="<?php echo site_url(); ?>/wp-content/plugins/tl-csv-import/assets/images/icon-pdf.png" /></a><?php }elseif($result->pdf_url != ''){ ?><a href="<?php echo $result->pdf_url; ?>" target="_blank"><img src="<?php echo site_url(); ?>/wp-content/plugins/tl-csv-import/assets/images/icon-pdf.png" /></a><?php } ?></td>
				
			</tr>						
			<?php $c++;
			}	?>						
		</tbody>
	</table>
</div>
<?php	  
	$paginationVar = paginate_links(array(
			'base' 	 => add_query_arg( 'pagenum', '%#%' ) ,
			'format' => '',
			'current' => $pagenum,
			'total' => $postData['num_of_pages'],
			'prev_next' => false,
			'type' => 'list',
			)
	);					
	if(isset($paginationVar) && $paginationVar!='')
	{
		echo '<div class="table-toolbar"><div class="pull-right"><div class="dataTables_paginate paging_bootstrap pagination">';
		echo $paginationVar;
		echo '</div></div></div>';	
	}				
?>
</div>
<?php
}
?>
<style>.table-responsive{overflow-x: inherit;}</style>