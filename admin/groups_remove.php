<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs']) && isset($_GET["b"]))
	{
		$b = $_GET["b"];
		$SQL = new mSQL;
		$pData = $SQL->selectQ("items", "*", "itmGroup = '" . $b . "'");
		if ($pData) {
			$itmCount = count($pData);
			$myMessage = "Are you sure you want to remove this product group<br>and the <strong>" . $itmCount . " product items</strong> attached to this group!!";
		} else {
			$itmCount = 0;
			$myMessage = "Are you sure you want to remove this product group!<br>There are no product items attached to this group.";
		}
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="myModalLabel">Remove Product Group</h4>
</div>
<div class="modal-body">
  <h4 id="removeMessage" class="text-danger"><?php echo $myMessage; ?></h4>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
  <button type="button" id="yesButton" class="btn btn-danger">Yes</button>
</div>
<script>
	$('#yesButton').on('click', function() {
		var myPanel = $(this).closest('.container').find('#gid<?php echo $b; ?>');
		$.ajax({
			url: 'adminCode.php',
			type: 'post',
			data: {'action': 'removeGroup', 'groupID': '<?php echo $b; ?>', 'itmCount': '<?php echo $itmCount; ?>'},
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