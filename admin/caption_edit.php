<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
		$SQL = new mSQL;
		$thisCaption = $SQL->fselectQ("carousel", "caption", "ID = '" . $b . "'");
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="myModalLabel">Carousel Image Caption Edit</h4>
</div>
<div class="modal-body">
    <div class="form-group">
      <label for="txtCaption" class="control-label">Description</label>
      <textarea style="width: 100%;" class="form-control" rows="3" id="txtCaption"><?php echo $thisCaption; ?></textarea>
    </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  <button type="button" id="chngCaptionButton" class="btn btn-success">Save</button>
</div>
<script>
	$('#chngCaptionButton').on('click', function() {
		console.log('<?php echo $b; ?>');
		var myPanel = $(this).closest('.container').find('#cID<?php echo $b; ?>');
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			data: {'action': 'editCarouselCaption', 'imgID': '<?php echo $b; ?>', 'caption': $('#txtCaption').val()},
			success: function(data, status) {
				console.log(data);
				myPanel.find('.lead').text(data);
				$('#editModal').modal('hide');
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
				alert("Details: " + desc + "\nError:" + err);
			}
		});	
		$(this).closest('#removeModal').modal('hide');
	});
</script>
<?php
	}
?>