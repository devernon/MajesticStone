<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
		if ($b == 0) {
			$title = "Add New Product Group";
			$thisItem = "";
			$thisImage = "images/add-img.png";
		} else {
			$title = "Edit Product Group";
			$SQL = new mSQL;
			$myInfo = $SQL->rselectQ("groups", "ID = '".$b."'");
			$thisItem = $myInfo["groupname"];
			$thisImage = $myInfo["image"];
			unset($SQL);
		}
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
</div>
<div class="modal-body">
  <table class="table">
      <tr>
          <td rowspan="3" width="25%">
              <span class="fileinput-button">
                  <img src="../<?php echo $thisImage; ?>" id="holderImg" class="img-responsive" title="Click To Change">
                  <input id="fileinput" type="file" name="files[]" multiple>
              </span>
          </td>
          <td width="75%">&nbsp;</td>
      </tr>
      <tr>
          <td>
              <div class="form-group">
                <label class="control-label" for="groupNameInp">Group Name</label>
                <input class="form-control" id="groupNameInp" type="text" value="<?php echo $thisItem; ?>" />
              </div>
           </td>
      </tr>
      <tr>
          <td>&nbsp;</td>
      </tr>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="button" id="saveProduct" class="btn btn-primary" data-pid="0">Save changes</button>
</div>
<script>
	var prodImage;
	var max_size = 300;
	var origSize;
	var newSize;
	var imgType;
	$("#holderImg").on("click",function() {
		$("#fileinput").click();
	});
	function readURL(input) {
           if (input.files && input.files[0]) {
			   var ftype = input.files[0].type;
			   if (ftype == "image/png" || ftype == "image/gif" || ftype == "image/jpeg") {
				 imgType = ftype;
				 var reader = new FileReader();
				 reader.onload = function(e) {
					 prodImage = new Image();
					 prodImage.src = e.target.result;
					 prodImage.onload = function() {
					   imageHolder.attr('src', this.src);
					   origSize = this.width + "X" + this.height;
					   if(this.height > this.width) {
						   var h = max_size;
						   var w = Math.ceil(this.width / this.height * max_size);
					   } else {
						   var w = max_size;
						   var h = Math.ceil(this.height / this.width * max_size);
					   }
					   this.width = w;
					   this.height = h;
					   newSize = w + "X" + h;
					   imageHolder.css({ height: h, width: w });
					 }
				 }
   
				 reader.readAsDataURL(input.files[0]);
			   } else {
				   alert("Unknow File Type");
			   }
           }
       }
	 $("input:file").change(function() {
		 imageHolder = $(this).parent().find('img');
		 readURL(this);
	 });
	 $('#saveProduct').on('click',function() {
		if ($('#groupNameInp').val() == "") {
			$('#groupNameInp').parent().addClass('has-error');
			$('#groupNameInp').attr('placeholder','Required Field');
		} else {
			if (typeof prodImage == "undefined") {
				origSize = "NO IMAGE";
				newSize = "NO IMAGE";
				imgType = "NO IMAGE";
			}
			if (<?php echo $b; ?> == 0) {
				var addPanel = $(this).closest('.container').find('#addProduct');
				var fileInput = document.getElementById('fileinput');
				var file = fileInput.files[0];
				var formData = new FormData();
				formData.append('action', 'addGroup');
				formData.append('grpName', $('#groupNameInp').val());
				formData.append('imageSrc', file);
				formData.append('imageType', imgType);
				formData.append('origSize', origSize);
				formData.append('newSize', newSize);
				$.ajax({
					url: 'adminCode.php',
					type: 'post',
					processData: false,
					contentType: false,
					data: formData,
					success: function(data, status) {
						console.log(data);
						if (data != "GROUP NAME EXISTS") {
							addPanel.before(data);
						} else {
							$('#groupNameInp').parent().addClass('has-error');
							$('#groupNameInp').val('');
							$('#groupNameInp').attr('placeholder','Group Name Exists');	
						}
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
						alert("Details: " + desc + "\nError:" + err);
					}
				});
			} else {
				var myPanel = $(this).closest('.container').find('#gid<?php echo $b; ?>');
				var fileInput = document.getElementById('fileinput');
				var file = fileInput.files[0];
				var formData = new FormData();
				formData.append('action', 'editGroup');
				formData.append('groupID', <?php echo $b; ?>);
				formData.append('grpName', $('#groupNameInp').val());
				formData.append('imageSrc', file);
				formData.append('imageType', imgType);
				formData.append('origSize', origSize);
				formData.append('newSize', newSize);
				$.ajax({
					url: 'adminCode.php',
					type: 'post',
					processData: false,
					contentType: false,
					data: formData,
					success: function(data, status) {
						var myData = data.split(";");
						console.log(myData);
						if (myData[0] == "GROUP NAME EXISTS") {
							$('#groupNameInp').parent().addClass('has-error');
							$('#groupNameInp').val('');
							$('#groupNameInp').attr('placeholder','Group Name Exists');
						} else {
							if (myData[0] != "GROUP NAME UNCHANGED") {
								myPanel.find('.panel-title').text(myData[0]);
							}
							if (myData[1] != "IMAGE UNCHANGED") {
								myPanel.find('.panel-body').find('img').attr('src', '../' + myData[1]);
							}
						}
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
						alert("Details: " + desc + "\nError:" + err);
					}
				});
			}
			
			$(this).closest('#editModal').modal('hide');
		}
	 });
</script>
<?php
	}
?>