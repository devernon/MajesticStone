<?php
	include("header.php");
	$SQL = new mSQL;
	$groups = $SQL->selectQ("groups");
   	echo "<h1>Products</h1>";
		if ($groups)
		{
			foreach ($groups as $itm)
			{
	?>
    <div style="position: relative; box-sizing: content-box; display: inline; width: 25%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;">
    	<div class="panel panel-primary">
        	<div class="panel-heading">
            	<h3 class="panel-title"><?php echo $itm["groupname"]; ?></h3>
            </div>
            <div class="panel-body">
            	<a href="product.php?a=<?php echo $itm["ID"]; ?>"><img class="img-responsive" src="<?php echo $itm["image"]; ?>"></a>
            </div>
         </div>
    </div>
    <?php
			}
		} else {
			echo "<h3>No Results</h3>";
		}
	?>
<?php
	include("footer.php");
	include("standardjs.php");
?>
</body>
</html>