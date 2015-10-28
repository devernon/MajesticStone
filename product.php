<?php
	include("header.php");
	$a = $_GET["a"];
	$grp = new product;
	$pName = $grp->getProduct($a);
	$pItems = $grp->getItems($a);
?>
	<?php
    	echo "<h1>". $pName . "</h1>";
		if ($pItems)
		{
			$myGalleryBtns = array();
			foreach ($pItems as $itm)
			{
	?>
    <div style="position: relative; box-sizing: content-box; display: inline; width: 25%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;">
    	<div class="panel panel-primary">
        	<div class="panel-heading">
            	<h3 class="panel-title"><?php echo $itm["itmName"]; ?></h3>
            </div>
            <div class="panel-body">
            	<img class="img-responsive" src="<?php echo $itm["image"]; ?>">
            </div>
            <div class="panel-footer">
                <a href="product_info.php?b=<?php echo $itm["ID"]; ?>" class="btn btn-info btn-xs" data-toggle="modal" data-target="#infoModal"><span class="glyphicon glyphicon-info-sign"></span> Info</a>
                <?php
					if ($itm["gallery"] == 1)
					{ 
						$myGalleryBtns[] = $itm["ID"];
						?>
          		<a href="product_gallery.php?b=<?php echo $itm["ID"]; ?>" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#galleryModal"><span class="glyphicon glyphicon-picture"></span> Gallery</a>
					<?php }
				?>
            </div>
         </div>
    </div>
    <?php
			}
		} else {
			echo "<h3>No Results</h3>";
		}
	?>
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content"></div>
  </div>
</div>
<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"></div>
  </div>
</div>
<?php
	include("footer.php");
	include("productjs.php");
?>
</body>
</html>