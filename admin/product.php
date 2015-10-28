<?php
	session_start();
	if (isset($_SESSION['loggedInAs']))
	{
		include("header.php");
		$a = $_GET["a"];
		$grp = new product;
		$pName = $grp->getProduct($a);
		$pItems = $grp->getItems($a);
		echo "<h1>". $pName . "</h1>";
		if ($pItems)
		{
			$myGalleryBtns = array();
			foreach ($pItems as $itm)
			{
				if ($itm["gallery"] == 0)
				{
					$gBtnColor = "btn-default";
				} else {
					$gBtnColor = "btn-info";
				}
	?>
    <div id="pid<?php echo $itm["ID"]; ?>" style="position: relative; box-sizing: content-box; display: inline; width: 25%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;">
    	<div class="panel panel-primary">
        	<div class="panel-heading">
            	<h3 class="panel-title"><?php echo $itm["itmName"]; ?></h3>
            </div>
            <div class="panel-body">
            	<img id="prodImg" class="img-responsive" src="../<?php echo $itm["image"]; ?>">
            </div>
            <div class="panel-footer">
                <a href="product_edit.php?b=<?php echo $itm["ID"]; ?>" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                <a href="product_gallery.php?b=<?php echo $itm["ID"]; ?>" class="btn <?php echo $gBtnColor; ?> btn-xs"><span class="glyphicon glyphicon-picture"></span> Gallery</a>
                <a href="product_remove.php?b=<?php echo $itm["ID"]; ?>" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#removeModal"><span class="glyphicon glyphicon-remove-sign"></span> Remove</a>
            </div>
         </div>
    </div>
    <?php
			}
		}
	?>
	<div id="addProduct" style="position: relative; box-sizing: content-box; display: inline; width: 25%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;">
    	<div class="panel panel-primary">
        	<div class="panel-heading">
            	<h3 class="panel-title">Add New Product</h3>
            </div>
            <div class="panel-body text-center">
            	<a href="product_edit.php?b=0&g=<?php echo $a; ?>" data-toggle="modal" data-target="#editModal"><img class="img-responsive" src="../images/add-sign.png"></a>
            </div>
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
	$('#editModal').on('hidden.bs.modal', function () {
	  $(this).data('bs.modal', null);
	  prodImage = undefined;
	});
	$('#removeModal').on('hidden.bs.modal', function() {
		$(this).data('bs.modal', null);
	});
</script>
</body>
</html>
<?php
	}
?>