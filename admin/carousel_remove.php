<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="myModalLabel">Remove Carousel Image</h4>
</div>
<div class="modal-body">
  <h4 id="removeMessage" class="text-danger">Are you sure you want to remove this image from the homepage carousel?</h4>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
  <button type="button" id="yesButton" class="btn btn-danger">Yes</button>
</div>
<script>
	$('#yesButton').on('click', function() {
		var myPanel = $(this).closest('.container').find('#cID<?php echo $b; ?>');
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			data: {'action': 'removeCarouselImage', 'imgID': '<?php echo $b; ?>'},
			success: function(data, status) {
				console.log(data);
				myPanel.remove();
				$('#removeModal').modal('hide');
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