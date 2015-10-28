<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Remove Product</h4>
      </div>
      <div class="modal-body">
		<h3 class="text-danger">Are you sure you want to remove this Product?</h3>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="yesButton" class="btn btn-danger">Yes</button>
      </div>
<script>
	$('#yesButton').on('click', function() {
		var myPanel = $(this).closest('.container').find('#pid<?php echo $b; ?>');
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			data: {'action': 'removeProd', 'prodID': '<?php echo $b; ?>'},
			success: function(data, status) {
				console.log(data);
				console.log('<?php echo $b; ?>');
				myPanel.remove();
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