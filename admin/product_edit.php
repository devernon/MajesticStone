<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
		if ($b == 0) {
			$g = $_GET["g"];
			$title = "Add New Product";
			$thisItem = "";
			$thisGroup = 0;
			$thisType = "";
			$thisSize = "";
			$thisVariation = "";
			$thisDesc = "";
			$thisLocation = "";
			$thisImage = "images/add-img.png";
		} else {
			$title = "Edit Product";
			$grp = new product;
			$myInfo = $grp->getItmInfo($b);
			$thisItem = $myInfo["itmName"];
			$thisGroup = $myInfo["itmGroup"];
			$thisType = $myInfo["itmType"];
			$thisSize = $myInfo["sizeFinish"];
			$thisVariation = $myInfo["variation"];
			$thisDesc = $myInfo["itmDesc"];
			$thisLocation = $myInfo["location"];
			$thisImage = $myInfo["image"];
			unset($myInfo);
		}
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-hover">
          <tr>
            <td colspan="2">
              <div class="form-group">
      			<label for="inputName" class="col-lg-2 control-label">Product Name</label>
  			    <div class="col-lg-10">
 			      <input class="form-control" id="inputName" placeholder="Product Name" type="text" value="<?php echo $thisItem; ?>">
 			    </div>
    		  </div>
            </td>
          </tr>
          <tr>
            <td width="25%" rowspan="3">
              <span class="fileinput-button">
                  <img src="../<?php echo $thisImage; ?>" id="holderImg" class="img-responsive" title="Click To Change">
                  <input id="fileinput" type="file" name="files">
              </span>
            </td>
            <td>
              <div class="form-group">
		        <label for="txtDesc" class="control-label">Description</label>
		        <textarea style="width: 100%;" class="form-control" rows="3" id="txtDesc"><?php echo $thisDesc; ?></textarea>
		      </div>
            </td>
          </tr>
          <tr>
            <td>
		      <div class="form-group">
		        <label for="prdGroup" class="control-label">Product Group</label>
                <select class="form-control" id="prdGroup">
<?php
	$SQL = new mSQL;
	$groups = $SQL->selectQ("groups");
	if ($b != 0) {
		$g = $thisGroup;
	}
	foreach ($groups as $itm) {
		if ($itm["ID"] == $g) {
			echo "
                  <option value=\"" . $itm["ID"] . "\" selected>" . $itm["groupname"] . "</option>";
		} else {
			echo "
                  <option value=\"" . $itm["ID"] . "\">" . $itm["groupname"] . "</option>";
		}
	}
?>
                </select>
		      </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="col-lg-6 form-group">
                <label class="control-label" for="inputType">Product Type</label>
                <input class="form-control" id="inputType" type="text" value="<?php echo $thisType; ?>">
              </div>
              <div class="col-lg-6 form-group">
                <label class="control-label" for="inputVariation">Product Variation</label>
                <input class="form-control" id="inputVariation" type="text" value="<?php echo $thisVariation; ?>">
              </div>
            </td>
          </tr>
          <tr>
          	<td colspan="2">
              <div class="col-lg-6 form-group">
		        <label for="txtSizes" class="control-label">Sizes and Finishes (Separate each with semi-colon)</label>
		        <textarea style="width: 100%;" class="form-control" rows="3" id="txtSizes"><?php echo $thisSize; ?></textarea>
		      </div>
              <div class="col-lg-6 form-group">
      			<label for="inputLocation" class="control-label">Availability</label>
 		        <input class="form-control" id="inputLocation" placeholder="Locations Available" type="text" value="<?php echo $thisLocation; ?>">
    		  </div>
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="saveButton" class="btn btn-primary">Save changes</button>
      </div>
<script>
	var prodImage;
	var max_size = 300;
	var origSize;
	var newSize;
	var imgType;
	var editPanel;
	var imageHolder;
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
	$('#saveButton').on('click', function() {
		if ($('#inputName').val() == '') {
			var inpGrp = $('#inputName').closest('.form-group');
			inpGrp.addClass('has-error');
			inpGrp.find('label').text('Product Name\nRequired');
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
				formData.append('action', 'addProd');
				formData.append('prdName', $('#inputName').val());
				formData.append('imageSrc', file);
				formData.append('imageType', imgType);
				formData.append('origSize', origSize);
				formData.append('newSize', newSize);
				formData.append('pDesc', $('#txtDesc').val());
				formData.append('pGroup', $('#prdGroup').val());
				formData.append('pType', $('#inputType').val());
				formData.append('pVar', $('#inputVariation').val());
				formData.append('pSizes', $('#txtSizes').val());
				formData.append('pLoc', $('#inputLocation').val());
				$.ajax({
					url: 'adminCode.php',
					type: 'post',
					processData: false,
					contentType: false,
					data: formData,
					success: function(data, status) {
						console.log(data);
						if (data != "PRODUCT NAME EXISTS") {
							if ($('#prdGroup').val() == <?php echo $g; ?>) {
								addPanel.before(data);
							}
							$('#addModal').modal('hide');
						} else {
							var inpGrp = $('#inputName').closest('.form-group');
							inpGrp.addClass('has-error');
							inpGrp.find('input').attr('placeholder', 'Name already existed');	
						}
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
						alert("Details: " + desc + "\nError:" + err);
					}
				});				
				
			} else {
				var myPanel = $(this).closest('.container').find('#pid<?php echo $b; ?>');
				var fileInput = document.getElementById('fileinput');
				var file = fileInput.files[0];
				var formData = new FormData();
				formData.append('action', 'editProd');
				formData.append('prodID', <?php echo $b; ?>);
				formData.append('prdName', $('#inputName').val());
				formData.append('imageSrc', file);
				formData.append('imageType', imgType);
				formData.append('origSize', origSize);
				formData.append('newSize', newSize);
				formData.append('pDesc', $('#txtDesc').val());
				formData.append('pGroup', $('#prdGroup').val());
				formData.append('pType', $('#inputType').val());
				formData.append('pVar', $('#inputVariation').val());
				formData.append('pSizes', $('#txtSizes').val());
				formData.append('pLoc', $('#inputLocation').val());
				$.ajax({
					url: 'adminCode.php',
					type: 'post',
					processData: false,
					contentType: false,
					data: formData,
					success: function(data, status) {
						console.log(data);
						if (data != "PRODUCT NAME EXISTS") {
							var myData = data.split(";");
							console.log(myData);
							console.log('<?php echo $b; ?>');
							
							if (myData[1] != 'PRODUCT GROUP CHANGED') {
								myPanel.find('.panel-title').text(myData[0]);
								myPanel.find('#prodImg').attr('src', '../' + myData[2]);
							} else {
								myPanel.remove();
							}
							$('#addModal').modal('hide');
						} else {
							var inpGrp = $('#inputName').closest('.form-group');
							inpGrp.addClass('has-error');
							inpGrp.find('input').attr('placeholder', 'Name already existed');	
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