jQuery(document).ready(function($) {

	// 
	// Function for blur event on file upload field (used for input field AND upload button)
	function blur_file_upload_field() {

		 file_upload_url = $('#csv_file').val();
		 extension = file_upload_url.substr((file_upload_url.lastIndexOf('.') + 1));

		 $pdfsj = $('.pdf_file').length;
		 var j;
		 for (j = 1; j <= $pdfsj; j++) {
			  pdf_file_upload_url = $('#pdf_file_' + j).val();
			  pdf_extension = file_upload_url.substr((file_upload_url.lastIndexOf('.') + 1));
		 }

		 // If the file upload does not contain a valid .csv file extension
		 if (extension !== 'csv') {

			  // File extension .csv popup error
			  $("#dialog_csv_file").dialog({
					modal: true,
					buttons: {
						 Ok: function() {
							  $(this).dialog("close");
						 }
					}
			  });
			  $('#return_csv_col_count').text('0');
			  return;
		 }

	}

	//
	// Set blur click function on file input field
	$('#csv_file').blur(function() {
		 blur_file_upload_field(); // Function to blur file upload field (gets column count from .csv file)
	});

	$pdfsk = $('.pdf_file').length;
	var k;
	for (k = 1; k <= $pdfsk; k++) {
		 $('#pdf_file_' + k).blur(function() {
			  blur_file_upload_field(); // Function to blur file upload field (gets column count from .csv file)
		 });
	}

	// *******  Begin WP Media Uploader ******* //
	$('#csv_file_button').click(function() { // Run WP media uploader
		 formfield = $('#csv_file').attr('name');
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 window.send_to_editor = function(html) {
			  url = $(html).attr('href');
			  $('#csv_file').val(url);
			  tb_remove();
			  blur_file_upload_field();
		 };
		 return false;
	});

	$pdfs = $('.pdf_file').length;
	var i;
	for (i = 1; i <= $pdfs; i++) {
		 btn = $('#pdf_file_button_' + i);
		 btn.click(function(e) {
			  if (e.target) {
					id = $('#' + e.target.id).closest(":has(td input)").find('.pdf_file').attr('id');
					pdf_formfield = $('#' + id).attr('name');
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					window.send_to_editor = function(html) {
						 url = $(html).attr('href');
						 $('#' + id).val(url);
						 tb_remove();
						 blur_file_upload_field();
					};
					return false;
			  }
		 });
	}
	// *******  End WP Media Uploader ******* //
	$(".pdf-tr").hide();
	//$(".pdf_file").val('');
	//console.log($(".pdf_file").val());
	$("#upload_pdf").click(function() {
		 if ($(this).is(":checked")) {
			  $(".pdf-tr").show();
		 } else {
			  $(".pdf_file").val('');
			  $(".pdf-tr").hide();
		 }
	});
});