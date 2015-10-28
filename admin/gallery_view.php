<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
		$SQL = new mSQL;
		$pImage = $SQL->rselectQ("gallery", "ID = '".$b."'");	
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $pImage["title"]; ?></h4>
        <input class="form-control hidden" id="inputTitle" type="text" value="<?php echo $pImage["title"]; ?>">
      </div>
      <div class="modal-body">
      	<td colspan="2"><img class="img-responsive center" src="../<?php echo $pImage["imgurl"]; ?>" />
      </div>
      <div class="modal-footer">
        <button type="button" id="editButton" class="btn btn-primary">Edit Title</button>
        <button type="button" id="saveButton" class="btn btn-warning hidden">Save Changes</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
<script>
	$("#editButton").on("click", function() {
		$("#editButton").addClass("hidden");
		$("#saveButton").removeClass("hidden");
		$("#myModalLabel").addClass("hidden");
		$("#inputTitle").removeClass("hidden");
		$("#inputTitle").select();
	});
	$("#saveButton").on("click", function() {
		if ($("#inputTitle").val() != "<?php echo $pImage["title"]; ?>") {
			var myPanel = $(document).find("#imID<?php echo $pImage["ID"]; ?>");
			$.ajax({
				url: 'adminCode.php',
				type: 'post',
				data: {'action': 'removeGalleryImage', 'imgID': '<?php echo $pImage["ID"]; ?>', 'newTitle': $('#inputTitle').val() },
				success: function(data, status) {
					console.log(data);
					myPanel.find('.panel-title').text(data);
					$("#saveButton").addClass("hidden");
					$("#editButton").removeClass("hidden");
					$("#inputTitle").addClass("hidden");
					$("#myModalLabel").removeClass("hidden");
					$("#myModalLabel").text(data);
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
					alert("Details: " + desc + "\nError:" + err);
				}
			});	
		}
	});
</script>
<?php		
	}
?>