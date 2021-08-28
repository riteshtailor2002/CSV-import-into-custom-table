(function( $, window, undefined ) {
  $.danidemo = $.extend( {}, {
    
    addLog: function(id, status, str){
      var d = new Date();
      var li = $('<li />', {'class': 'demo-' + status});
       
      var message = '[' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds() + '] ';
      
      message += str;
     
      li.html(message);
      
      $(id).prepend(li);
    },
    
    addFile: function(id, i, file){
		var template = '<div class="demofileclass" id="demo-file' + i + '">' +		                   
		                   '<span class="demo-file-id">#' + i + '&nbsp;' + file.name + '</span>&nbsp;&nbsp;<span class="demo-file-size">(' + $.danidemo.humanizeSize(file.size) + ')</span><br />Status: <span class="demo-file-status">Waiting to upload</span>'+
		                   '<div class="progress progress-striped">'+
		                       '<div class="progress-bar" role="progressbar" style="width: 0%;">'+
		                           '<span class="sr-only">0% Complete</span>'+
		                       '</div>'+
		                   '</div>'+
		               '</div>';
		               
		var i = $(id).attr('file-counter');
		if (!i){
			$(id).empty();
			
			i = 0;
		}
		
		i++;
		
		$(id).attr('file-counter', i);
		
		$(id).prepend(template);
	},
	
	updateFileStatus: function(i, status, message,uploadedfilename,uploadedfieldid){
      var custommsg = '';
	  var newfilename = '';	
	  if(uploadedfilename !== undefined)
	  {
	  	  var newfilename_arr = uploadedfilename.toString().split('.');
	      var newfilename = newfilename_arr[0];
	  }
	  //jQuery(uploadedfilename).replace('.', '');
      custommsg = message+'&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="deleteCurrnetFile(\''+uploadedfilename+'\',\''+uploadedfieldid+'\',\'demo-file'+i+'\');">Delete file</a>';     
		$('#demo-file' + i).find('span.demo-file-status').html(custommsg).addClass('demo-file-status-' + status + ' current'+newfilename);
	},
	
	updateFileProgress: function(i, percent){
		$('#demo-file' + i).find('div.progress-bar').width(percent);
		
		$('#demo-file' + i).find('span.sr-only').html(percent + ' Complete');
	},
	
	humanizeSize: function(size) {
      var i = Math.floor( Math.log(size) / Math.log(1024) );
      return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    }

  }, $.danidemo);
})(jQuery, this);


function deleteCurrnetFile(filename,uploadedfieldid,deletedivid)
{
	
   var upload_url = tl_csv_imp_to_db_pass_js_vars.ajaxurl;
      	jQuery.ajax({
			type: 'POST', 
			url: upload_url,
			dataType: 'html',
			data: {action: "implementAjaxCallback",filename: filename,case:"delete"},  
			success: function(data){
				if(data ==1) { 
				//alert('file deleted');				
				 var currentinputbox = '';var addseparator = '';
				 currentinputbox = jQuery("#"+uploadedfieldid).val();  
				 var new_currentinputbox =  currentinputbox.split('|');   
				 var index = new_currentinputbox.indexOf(filename);
					if (index >= 0) {
					  new_currentinputbox.splice( index, 1 );
					} 
					
					new_currentinputbox = new_currentinputbox.join("|");
					jQuery("#"+uploadedfieldid).val(new_currentinputbox);

	            //var newfilename_arr = filename.toString().split('.');
	     			//var newfilename_str = newfilename_arr[0];
					//jQuery('.current'+newfilename_str).parent().html('');
					jQuery('#'+deletedivid).remove();
				}
			}
		});
}

function deleteFile(filename,metakey,userid)
{
	var agree= confirm("Are you sure you want to delete this file?");
	if(agree)
	{	
	var upload_delete_url = siteurl + '/wp-content/plugins/front-end-management/site/upload.php?case=delete';
	var upload_meta_url = siteurl + '/wp-content/plugins/front-end-management/site/upload.php?case=update_meta';	 
   	jQuery.ajax({
			type: 'POST', 
			url: upload_delete_url,
			dataType: 'html',
			data: {"filename": filename},  
			success: function(data){
				if(data ==1) { 
				   jQuery.ajax({
						type: 'POST', 
						url: upload_meta_url,
						dataType: 'html',
						data: {"metakey": metakey,"userid":userid,"filename": filename},  
						success: function(data){
							var return_data = jQuery.parseJSON(data);
							
								if(return_data.status == 1)
								{
									var filename_arr = filename.toString().split('/');
									var newfilename = filename_arr[1];
									newfilename = newfilename.replace( '.','');
									jQuery('#'+newfilename).html('');
									var new_key = metakey.toString().split('/');
									var res_arr = new_key[1].toString().split('_');
									var hidden_id = res_arr[1];
									jQuery('#'+hidden_id).val(return_data.new_meta);
									alert('File deleted successfully.');
								}
								else
								{
									alert('Unable to update meta.');
								}
							} 
						}); 
				}else
				{
					alert('Unable to delete file!');
				}
				
				} 
			}); 
		}
	else
	{
		return false;
	}

}

jQuery(document).ready(function($) {
	var upload_url = tl_csv_imp_to_db_pass_js_vars.ajaxurl;
	$('#drag-and-drop-zone,#drag-and-drop-zone_1,#drag-and-drop-zone_2,#drag-and-drop-zone_3,#drag-and-drop-zone_4,#drag-and-drop-zone_5').dmUploader({
	  url: upload_url,
	  extraData: {action: "implementAjaxCallback"},
	  dataType: 'json',
	  allowedTypes: '*',
	  onInit: function(){
		 $.danidemo.addLog('#demo-debug', 'default', 'Plugin initialized correctly');
	  },
	  onBeforeUpload: function(id){
		 $.danidemo.addLog('#demo-debug', 'default', 'Starting the upload of #' + id);

		 $.danidemo.updateFileStatus(id, 'default', 'Uploading...');
	  },
	  onNewFile: function(id, file){
		 $.danidemo.addFile('#demo-files', id, file);
		 /*** Begins Image preview loader ***/
		 if (typeof FileReader !== "undefined"){            
			var reader = new FileReader();
			// Last image added
			/*var img = $('#demo-files').find('.demo-image-preview').eq(0);
			reader.onload = function (e) {
			  img.attr('src', e.target.result);
			}
			reader.readAsDataURL(file);*/
		 } else {
			// Hide/Remove all Images if FileReader isn't supported
			$('#demo-files').find('.demo-image-preview').remove();
		 }
		 /*** Ends Image preview loader ***/

	  },
	  onComplete: function(){
		 //$.danidemo.addLog('#demo-debug', 'default', 'All pending tranfers completed');
	  },
	  onUploadProgress: function(id, percent){
		 var percentStr = percent + '%';

		 $.danidemo.updateFileProgress(id, percentStr);
	  },
	  onUploadSuccess: function(id, data){
		
		/*added custom code starts here*/
		 var currentinputbox = '';var addseparator = '';
		 currentinputbox = $(this).find("input[type=hidden]").val();          
		 if (currentinputbox!='') {
				addseparator = '|';
			}
		 currentinputbox = currentinputbox+addseparator+data.filename;          
		 $(this).find("input[type=hidden]").val(currentinputbox);
		/*added custom code ends here*/ 
		 //$.danidemo.addLog('#demo-debug', 'success', 'Upload of file #' + id + ' completed');
		 //$.danidemo.addLog('#demo-debug', 'info', 'Server Response for file #' + id + ': ' + JSON.stringify(data));
		 $.danidemo.updateFileStatus(id, 'success', 'Upload Complete',data.filename,$(this).find("input[type=hidden]").attr('id'));
		 
		 $.danidemo.updateFileProgress(id, '100%');
	  },
	  onUploadError: function(id, message){
		 $.danidemo.updateFileStatus(id, 'error', message);
		 //$.danidemo.addLog('#demo-debug', 'error', 'Failed to Upload file #' + id + ': ' + message);
	  },
	  onFileTypeError: function(file){
		 //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' cannot be added: must be an image');
	  },
	  onFileSizeError: function(file){
		 //$.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' cannot be added: size excess limit');
	  },
	  onFallbackMode: function(message){
		 //$.danidemo.addLog('#demo-debug', 'info', 'Browser not supported(do something else here!): ' + message);
	  }
	});
});