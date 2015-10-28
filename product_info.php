<?php
	require("codelib.php");
	if (isset($_GET["b"]))
	{
		$b = $_GET["b"];
		$grp = new product;
		$myInfo = $grp->getItmInfo($b);
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $myInfo["itmName"]; ?></h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-hover">
          <tr>
            <td width="25%" rowspan="4"><img class="img-responsive" src="<?php echo $myInfo["image"]; ?>"></td>
            <td><p><?php echo $myInfo["itmDesc"]; ?></p></td>
          </tr>
          <tr>
            <td><em>Product Type: </em><?php echo $myInfo["itmType"]; ?></td>
          </tr>
          <tr>
            <td><em>Product Variation: </em><?php echo $myInfo["variation"]; ?></td>
          </tr>
          <tr>
          	<td>
            	<em>Available Size and Finish:</em>
                <ul>
<?php
	$sizes = explode(";", $myInfo["sizeFinish"]);
	foreach ($sizes as $indSize) {
?>
	               	<li><?php echo $indSize; ?></li>
<?php
    }
?>
                </ul>
             </td>
          </tr>
        </table>

      </div>
      <div class="modal-footer">
        &nbsp;<?php echo $myInfo["location"]; ?>
      </div>

<?php
	}
	else
	{
?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Item Retreival Error</h4>
      </div>
      <div class="modal-body">
        <h3>Item Information Unavailable</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
<?php	
	}
?>