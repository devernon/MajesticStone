<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"])) {
		$b = $_GET["b"];
		$SQL = new mSQL;
		$myInfo = $SQL->rselectQ("items","ID = '".$b."'");
		$thisItem = $myInfo["itmName"];
		$thisGroup = $myInfo["itmGroup"];
		$thisGallery = $myInfo["gallery"];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Majestic Stone Import, Inc.</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
  <div class="navbar navbar-default">
      <div class="container-fluid">
          <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a href="index.php" class="navbar-brand">Majestic Stone Import</a>
          </div>
          <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
                  <li><a runat="server" href="product.php?a=<?php echo $thisGroup; ?>">Return to Products <span class="glyphicon glyphicon-remove-sign"></span></a></li>
              </ul>
          </div>
      </div>
  </div>
  <h1>Image Gallery for <?php echo $thisItem; ?></h1>
  <div id="existImages" class="row">
<?php
	if ($thisGallery == 0) {
		echo "  <div id=\"noImages\" class=\"h3\">There currently are no gallery images for this product.</div>";
	} else {
		$pImages = $SQL->selectQ("gallery", "*", "pID = '".$b."'");
		foreach ($pImages as $pImg) {
?>
	<div id="imID<?php echo $pImg["ID"]; ?>" style="position: relative; box-sizing: content-box; display: inline; width: 16.67%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;">
    	<div class="panel panel-primary">
        	<div class="panel-heading">
            	<span class="panel-title h3" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><?php echo $pImg["title"]; ?></span>
            </div>
            <div class="panel-body">
            	<a href="gallery_view.php?b=<?php echo $pImg["ID"]; ?>" data-toggle="modal" data-target="#viewModal">
	            	<img src="<?php echo $pImg["imgurl"]; ?>" class="img-responsive" style="max-height: 180px;" />
                </a>
            </div>
            <div class="panel-footer">
	            <button type="button" id="removeImg" data-iid="<?php echo $pImg["ID"]; ?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-circle"></span> Remove</button>
            </div>
        </div>
    </div>
<?php
		}
	}
?>
	</div>
    <hr>
    <div id="addFilesBtn" class="row">
        <span class="fileinput-button">
            <button type="button" id="addImages" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Add Images...</button>
            <input id="fileinput" type="file" name="files[]" multiple>
        </span>
	</div>
    <div id="imagePlace"></div>
</div>
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"></div>
  </div>
</div>
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="viewModal" aria-hidden="true">
	<div class="modal-dialog modal-sm">
    	<div class="modal-content">
        	<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Upload Gallery Image</h4>
			</div>
			<div class="modal-body">
            	<div class="row"><img class="center-block img-responsive" style="max-height: 144px" /></div>
                <div class="row"><label class="control-label" for="inTitle">Image Title</label><input class="form-control" id="inTitle" type="text" /></div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" id="upload" class="btn btn-primary" data-iid="0">Upload</button>
			</div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
	var prodImage;
	var max_height = 72;
	var max_width = 128;
	var origSize;
	var newSize;
	var imgType;
	
	function readURL(input) {
           if (input.files && input.files[0]) {
			   $("#addFilesBtn").addClass("hidden");
				var myCount = input.files.length;
				prodImage = new Array(myCount);
				origSize = new Array(myCount);
				newSize = new Array(myCount);
				imgType = new Array(myCount);
				var newImgPanel = $("<div />");
			    newImgPanel.attr('style', 'position: relative; box-sizing: content-box; display: inline; width: 16.67%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;');
			    newImgPanel.append($('<div class=\"panel panel-primary\"></div>'));
			    newImgPanel.find(".panel").append($('<div class=\"panel-heading\"></div>'));
				newImgPanel.find(".panel-heading").append($('<button type=\"button\" id=\"uploadImg\" class=\"btn btn-xs btn-success\"><span class=\"glyphicon glyphicon-upload\"></span> Upload</button>'));
			    newImgPanel.find(".panel-heading").append($('<button type=\"button\" id=\"discardImg\" class=\"btn btn-xs btn-danger pull-right\"><span class=\"glyphicon glyphicon-remove-circle\"></span></button>'));
			    newImgPanel.find(".panel").append($('<div class=\"panel-body\"></div>'));
			    newImgPanel.find(".panel-body").append($('<img class=\"center img-responsive\" />'));
				var imageHolder = newImgPanel.find('img');
				for (var i = 0; i < myCount; i++) {
				   if (input.files[i].type == "image/png" || input.files[i].type == "image/gif" || input.files[i].type == "image/jpeg") {
					 (function(i) {
						 imgType[i] = input.files[i].type;
						 var reader = new FileReader();
						 reader.onload = function(e) {
							 prodImage[i] = new Image();
							 prodImage[i].src = e.target.result;
							 prodImage[i].onload = function() {
							   imageHolder.attr('src', this.src);
							   origSize[i] = this.width + "X" + this.height;
							   var w = this.width;
							   var h = this.height;
							   if(this.width > max_width) {
								   w = max_width;
								   h = Math.ceil(this.height / this.width * max_width);
							   }
							   if (h != max_height) {
								   w = Math.ceil(w / h * max_height);
								   h = max_height;
							   }
							   newSize[i] = w + "X" + h;
							   imageHolder.css({ height: h, width: w });
							   newImgPanel.find('button').attr('data-iid', i);
							   newImgPanel.attr('id', 'newI' + i);
							   $('#imagePlace').append(newImgPanel.clone());
							 }
						 }
						 reader.readAsDataURL(input.files[i]);
					 })(i);
				   } else {
					   imgType[i] = "unknown file type";
				   }
				}
           }
       }
	 $("input:file").change(function() {
		 readURL(this);
	 });
	$('#viewModal').on('hidden.bs.modal', function() {
		$(this).data('bs.modal', null);
	});
	$('#uploadModal').on('hidden.bs.modal', function() {
		$('#inTitle').val('');
		$(this).data('bs.modal', null);
	});
	$('#removeImg').on('click', function() {
		var imgID = $(this).data('iid');
		console.log(imgID);
		var myPanel = $(this).closest(".panel").parent();
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			data: {'action': 'removeGalleryImage', 'imgID': imgID },
			success: function(data, status) {
				console.log(data);
				myPanel.remove();
				if (data == "No Images")
				$('#existImages').append($('<div id=\"noImages\" class=\"h3\">There currently are no gallery images for this product.</div>'));
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
				alert("Details: " + desc + "\nError:" + err);
			}
		});	
	});
	$(document).on('click', '#discardImg', function() {
		var tmp = '#newI' + $(this).data('iid');
		$(document).find(tmp).remove();
	});
	$(document).on('click', '#uploadImg', function() {
		$('#uploadModal').data('iid', $(this).data('iid'));
		$('#upload').data('iid', $(this).data('iid'));
		$('#uploadModal').find('img').attr('src', prodImage[$(this).data('iid')].src);
		$('#uploadModal').modal('show');
	});
	$('#upload').on('click', function() {
		var tmp = '#newI' + $(this).data('iid');
		var fileInput = document.getElementById('fileinput');
		var file = fileInput.files[$(this).data('iid')];
		var formData = new FormData();
		formData.append('action', 'addGalleryImage');
		formData.append('prodID', <?php echo $b; ?>);
		formData.append('imgTitle', $('#inTitle').val());
		formData.append('imageSrc', file);
		formData.append('imageType', imgType[$(this).data('iid')]),
		formData.append('origSize', origSize[$(this).data('iid')]),
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			processData: false,
			contentType: false,
			data: formData,
			success: function(data, status) {
				console.log(data);
				$('#existImages').find('#noImages').remove();
				$('#existImages').append(data);
				$(document).find(tmp).remove();
				$('#uploadModal').modal('hide');
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
				alert("Details: " + desc + "\nError:" + err);
			}
		});	
	});
</script>
</body>
</html>
<?php
	}
?>