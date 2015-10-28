<?php
	session_start();
	if (isset($_SESSION['loggedInAs']))
	{
		include("header.php");
		$SQL = new mSQL;
		$myCarousel = $SQL->selectQ("carousel");
		echo "<h1>Home Page Image Carousel</h1>
<p>Image size is 1108 x 254. Other image sizes will be modified to correct size. Adjustments can be made to visible area when adding new image.</p>";
		if ($myCarousel)
		{
			$x = 1;
			foreach ($myCarousel as $itm)
			{
	?>
    <div id="cID<?php echo $itm["ID"]; ?>" class="panel panel-primary">
    	<div class="panel-heading">
        	<h3 class="panel-title">Home Page Carousel Image</h3>
        </div>
        <div class="panel-body">
        	<div class="row">
	        	<img class="img-responsive center" src="../<?php echo $itm["imgurl"]; ?>">
            </div>
            <div class="lead"><?php echo $itm["caption"]; ?></div>
        </div>
        <div class="panel-footer">
            <a href="caption_edit.php?b=<?php echo $itm["ID"]; ?>" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal"><span class="glyphicon glyphicon-edit"></span> Edit Caption</a>
            <a href="carousel_remove.php?b=<?php echo $itm["ID"]; ?>" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#removeModal"><span class="glyphicon glyphicon-remove-sign"></span> Remove Image</a>
        </div>
     </div>
     <?php
			}
		}
	?>
        <span id="addImgInp" class="fileinput-button">
	        <button id="newImage" class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span> Add New Image</button>
            <input id="fileinput" type="file" name="files">
        </span>
		<div id="addPanel" class="panel panel-info hidden">
			<div class="panel-heading">
				<h3 class="panel-title">New Image</h3>
            </div>
            <div class="panel-body">
            	<div class="row">
	                <center><canvas id="newImageCanvas" width="1108" height="254"></canvas></center>
                </div>
                <div class="row">
					<input class="form-control" id="inputCaption" placeholder="Enter Caption Here" type="text">
                </div>
			</div>
			<div class="panel-footer">
             	<button type="button" id="saveButton" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-floppy-save"></span> Save</button>
                <button type="button" id="cancelButton" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-floppy-remove"></span> Cancel</button>
	           	<button type="button" id="adjUpButton" class="btn btn-primary btn-xs hidden"><span class="glyphicon glyphicon-collapse-up"></span> Adjust Image Up</button>
                <button type="button" id="adjDnButton" class="btn btn-primary btn-xs hidden"><span class="glyphicon glyphicon-collapse-down"></span> Adjust Image Down</button>
            </div>
		</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"></div>
  </div>
</div>
<div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"></div>
  </div>
</div>
<?php
	include("footer.php");
	include("standardjs.php");
?>
<script>
	var prodImage;
	var max_width = 1108;
	var max_height = 254;
	var origSize;
	var imgType;
	var canvas = document.getElementById('newImageCanvas');
	var context = canvas.getContext('2d');;
	var sourceX = 0;
	var sourceY = 0;
	var sourceWidth = 1108;
	var sourceHeight = 254;
	var destWidth = 1108;
	var destHeight = 254;
	var destX = 0;
	var destY = 0;
	
	function readURL(input) {
           if (input.files && input.files[0]) {
			   var ftype = input.files[0].type;
			   if (ftype == "image/png" || ftype == "image/gif" || ftype == "image/jpeg") {
				 console.log(ftype);
				 imgType = ftype;
				 var reader = new FileReader();
				 reader.onload = function(e) {
					 prodImage = new Image();
					 prodImage.src = e.target.result;
					 prodImage.onload = function() {
					   origSize = this.width + "X" + this.height;
					   var w = this.width;
					   var h = this.height;
					   if(this.width != max_width) {
						   sourceWidth = this.width;
						   sourceHeight = Math.ceil( 254 / 1108 * this.width);
					   }
					   if(this.height > max_height) {
						   sourceY = Math.ceil((this.height - max_height) / 2);
						   $('#adjDnButton').removeClass('hidden');
						   $('#adjUpButton').removeClass('hidden');
					   }
					   context.drawImage(prodImage, sourceX, sourceY, sourceWidth, sourceHeight, destX, destY, destWidth, destHeight);
					 }
				 }
   
				 reader.readAsDataURL(input.files[0]);
				 $('#addImgInp').addClass('hidden');
				 $('#addPanel').removeClass('hidden');
			   } else {
				   alert("Unknow File Type");
			   }
           }
       }
	 $("input:file").change(function() {
		 readURL(this);
	 });
	 $('#adjUpButton').on('click', function() {
		if(sourceY + sourceHeight < prodImage.height) {
			if(sourceY + sourceHeight + 5 > prodImage.height) {
				sourceY = prodImage.height - sourceHeight;
			} else {
				sourceY = sourceY + 5;
			}
			context.drawImage(prodImage, sourceX, sourceY, sourceWidth, sourceHeight, destX, destY, destWidth, destHeight);
		}
	 });
	 $('#adjDnButton').on('click', function() {
		if(sourceY > 0) {
			if(sourceY - 5 < 0) {
				sourceY = 0;
			} else {
				sourceY = sourceY - 5;
			}
			console.log(sourceX + "::" + sourceY + "::" + sourceWidth + "::" + sourceHeight + "::" + destX + "::" + destY + "::" + destWidth + "::" + destHeight);
			context.drawImage(prodImage, sourceX, sourceY, sourceWidth, sourceHeight, destX, destY, destWidth, destHeight);
		}
	 });
	 $('#cancelButton').on('click', function() {
		$('#addPanel').addClass('hidden');
		context.clearRect(0, 0, destWidth, destHeight);
		$('#inputCaption').val('');
		$('#addImgInp').removeClass('hidden');
		prodImage = undefined;
		sourceX = 0;
		sourceY = 0;
		sourceWidth = 1108;
		sourceHeight = 254;
	 });
	 $('#saveButton').on('click', function() {
		var fileInput = document.getElementById('fileinput');
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append('action', 'addCarouselImage');
		formData.append('caption', $('#inputCaption').val()),
		formData.append('imageSrc', file);
		formData.append('imageType', imgType);
		formData.append('sX', sourceX);
		formData.append('sY', sourceY);
		formData.append('sW', sourceWidth);
		formData.append('sH', sourceHeight);
		formData.append('dW', destWidth);
		formData.append('dH', destHeight);
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			processData: false,
			contentType: false,
			data: formData,
			success: function(data, status) {
				console.log(data);
				var myIdx = 1;
				console.log(myIdx);
				if ($('#addImgInp').prev().attr('class') == "panel panel-primary") {
					myIdx = Number($('#addImgInp').prev().attr('id')) + 1;
					console.log(myIdx + "<<");
				}
				$('#addImgInp').before(data);
				$('#addPanel').addClass('hidden');
				context.clearRect(0, 0, destWidth, destHeight);
				$('#inputCaption').val('');
				$('#addImgInp').removeClass('hidden');
				prodImage = undefined;
				sourceX = 0;
				sourceY = 0;
				sourceWidth = 1108;
				sourceHeight = 254;
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
				alert("Details: " + desc + "\nError:" + err);
			}
		}); 
	 });
	$('#editModal').on('hidden.bs.modal', function () {
	  $(this).data('bs.modal', null);
	});
	$('#removeModal').on('hidden.bs.modal', function() {
		$(this).data('bs.modal', null);
	});
</script>
<?php
	}
?>