<?php
	require("../codelib.php");
	session_start();
	if (isset($_SESSION['loggedInAs'])) {
		if($_POST['action'] == "addGroup") {
			$SQL = new mSQL;
			$grpName = $_POST['grpName'];
			if ($SQL->fselectQ("groups", "ID", "groupname = '".$grpName."'") == false) {
				$destFile = "../images/groups/";
				$newSize = explode("X", $_POST['newSize']);
				if($newSize[0] < 300) {
					$dstX = (300 - $newSize[0]) / 2;
				} else {
					$dstX = 0;
				}
				if ($newSize[1] < 300) {
					$dstY = (300 - $newSize[1]) / 2;
				} else {
					$dstY = 0;
				}
				$fileName = SaveImage($_FILES['imageSrc']['tmp_name'], $_POST['imageType'], $destFile, explode("X", $_POST['origSize']), array( 0,0 ), $newSize, array( $dstX, $dstY ), array(300,300));
				$myID = $SQL->insertQ("groups", array("groupname", "image"), array($grpName,$fileName));
				echo "    <div id=\"gid" . $myID . "\" style=\"position: relative; box-sizing: content-box; display: inline; width: 25%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;\">
    	<div class=\"panel panel-primary\">
        	<div class=\"panel-heading\">
            	<h3 class=\"panel-title\">" . $grpName . "</h3>
            </div>
            <div class=\"panel-body\">
            	<a href=\"product.php?a=" . $myID . "\"><img class=\"img-responsive\" src=\"../" . $fileName . "\"></a>
            </div>
            <div class=\"panel-footer\">
                <a href=\"groups_edit.php?b=" . $myID . "\" class=\"btn btn-warning btn-xs\" data-toggle=\"modal\" data-target=\"#editModal\"><span class=\"glyphicon glyphicon-edit\"></span> Edit Group</a>
                <a href=\"groups_remove.php?b=" . $myID . "\" class=\"btn btn-danger btn-xs\" data-toggle=\"modal\" data-target=\"#removeModal\"><span class=\"glyphicon glyphicon-remove-sign\"></span> Remove Group</a>
            </div>
         </div>
    </div>";
			} else {
				echo "GROUP NAME EXISTS";
			}
		} elseif($_POST['action'] == "editGroup") {
			$groupID = $_POST['groupID'];
			$SQL = new mSQL;
			$origData = $SQL->rselectQ("groups", "ID = '".$groupID."'");
			$grpName = $_POST['grpName'];
			if ($origData['groupname'] != $grpName) {
				if ($SQL->fselectQ("groups", "ID", "groupname = '".$grpName."'") == false) {
					$SQL->updateQ("groups", "ID = '" . $groupID . "'", array('groupname'), array($grpName));
				} else {
					$grpName = "GROUP NAME EXISTS";
				}
			} else {
				$grpName = "GROUP NAME UNCHANGED";
			}
			if ($_POST['imageType'] != "NO IMAGE") {
				if ($origData['image'] != "images/testimage.png" && $origData['image'] != "images/missingImage.png") {
					unlink("../".$origData['image']);
				}
				$newSize = explode("X", $_POST['newSize']);
				if($newSize[0] < 300) {
					$dstX = (300 - $newSize[0]) / 2;
				} else {
					$dstX = 0;
				}
				if ($newSize[1] < 300) {
					$dstY = (300 - $newSize[1]) / 2;
				} else {
					$dstY = 0;
				}
				$fileName = SaveImage($_FILES['imageSrc']['tmp_name'], $_POST['imageType'], "../images/groups/", explode("X", $_POST['origSize']), array( 0,0 ), $newSize, array( $dstX, $dstY ), array(300,300));
				$SQL->updateQ("groups", "ID = '" . $groupID . "'", array('image'), array($fileName));
			} else {
				$fileName = "IMAGE UNCHANGED";
			}
			echo $grpName . ";" . $fileName;
		} elseif($_POST['action'] == "removeGroup") {
			$myID = $_POST['groupID'];
			$hasItems = false;
			if ($_POST['itmCount'] > 0) {
				$hasItems = true;
			}
			$SQL = new mSQL;
			$myData = $SQL->rselectQ("groups", "ID = '".$myID."'");
			if ($myData['image'] != "images/testimage.png" && $myData['image'] != "images/missingImage.png") {
				unlink("../".$myData['image']);
			}
			$SQL->deleteQ("groups", "ID = '".$myID."'");
			if ($hasItems) {
				$myItems = $SQL->selectQ("items", "*", "itmGroup = '".$myID."'");
				foreach ($myItems as $item) {
					deleteProduct($item["ID"]);
				}
			}
		} elseif($_POST['action'] == "addProd") {
			$SQL = new mSQL;
			$prdName = $_POST['prdName'];
			$prdGroup = $_POST['pGroup'];
			if ($SQL->fselectQ("items", "ID", "itmName = '".$prdName."' AND itmGroup = '".$prdGroup."'") == false) {
				$destFile = "../images/products/";
				$newSize = explode("X", $_POST['newSize']);
				if($newSize[0] < 300) {
					$dstX = (300 - $newSize[0]) / 2;
				} else {
					$dstX = 0;
				}
				if ($newSize[1] < 300) {
					$dstY = (300 - $newSize[1]) / 2;
				} else {
					$dstY = 0;
				}
				$fileName = saveImage($_FILES['imageSrc']['tmp_name'], $_POST['imageType'], $destFile, explode("X", $_POST['origSize']), array( 0,0 ), $newSize, array( $dstX, $dstY ), array(300,300));
				$myID = $SQL->insertQ("items", array('itmName', 'itmGroup', 'itmType', 'sizeFinish', 'variation', 'itmDesc', 'location', 'image'), array($prdName, $prdGroup, $_POST['pType'], $_POST['pSizes'], $_POST['pVar'], $_POST['pDesc'], $_POST['pLoc'], $fileName));
				echo "    <div id=\"pid". $myID . "\" style=\"position: relative; box-sizing: content-box; display: inline; width: 25%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;\">
    	<div class=\"panel panel-primary\">
        	<div class=\"panel-heading\">
            	<h3 class=\"panel-title\">". $prdName . "</h3>
            </div>
            <div class=\"panel-body\">
            	<img id=\"prodImg\" class=\"img-responsive\" src=\"../". $fileName . "\">
            </div>
            <div class=\"panel-footer\">
                <a href=\"product_edit.php?b=". $myID . "\" class=\"btn btn-warning btn-xs\" data-toggle=\"modal\" data-target=\"#editModal\"><span class=\"glyphicon glyphicon-edit\"></span> Edit</a>
                <a href=\"product_gallery.php?b=". $myID . "\" class=\"btn btn-default btn-xs\"><span class=\"glyphicon glyphicon-picture\"></span> Gallery</a>
                <a href=\"product_remove.php?b=". $myID . "\" class=\"btn btn-danger btn-xs\" data-toggle=\"modal\" data-target=\"#removeModal\"><span class=\"glyphicon glyphicon-remove-sign\"></span> Remove</a>
            </div>
         </div>
    </div>";
			} else {
				echo "PRODUCT NAME EXISTS";
			}
		} elseif($_POST['action'] == "editProd") {
			$prodID = $_POST['prodID'];
			$SQL = new mSQL;
			$origData = $SQL->rselectQ("items", "ID = '".$prodID."'");
			$prdName = $_POST['prdName'];
			$prdGroup = $_POST['pGroup'];
			if ($origData['itmName'] != $prdName) {
				if ($SQL->fselectQ("items", "ID", "itmName = '".$prdName."' AND itmGroup = '".$prdGroup."'") == false) {
					$SQL->updateQ("items", "ID = '" . $prodID . "'", array('itmName'), array($prdName));
				} else {
					$prdName = "PRODUCT NAME EXISTS";
				}
			}
			if ($origData['itmGroup'] != $prdGroup) {
				$SQL->updateQ("items", "ID = '" . $prodID . "'", array('itmGroup'), array($prdGroup));
				$prdGroup = "PRODUCT GROUP CHANGED";
			}
			if ($_POST['imageType'] != "NO IMAGE") {
				if ($origData['image'] != "images/testimage.png" && $origData['image'] != "images/missingImage.png") {
					unlink("../".$origData['image']);
				}
				$newSize = explode("X", $_POST['newSize']);
				if($newSize[0] < 300) {
					$dstX = (300 - $newSize[0]) / 2;
				} else {
					$dstX = 0;
				}
				if ($newSize[1] < 300) {
					$dstY = (300 - $newSize[1]) / 2;
				} else {
					$dstY = 0;
				}
				$fileName = SaveImage($_FILES['imageSrc']['tmp_name'], $_POST['imageType'], "../images/products/", explode("X", $_POST['origSize']), array( 0,0 ), $newSize, array( $dstX, $dstY ), array(300,300));
				$SQL->updateQ("items", "ID = '" . $prodID . "'", array('image'), array($fileName));
			} else {
				$fileName = $origData['image'];
			}
			$SQL->updateQ("items", "ID = '" . $prodID . "'", array('itmType', 'sizeFinish', 'variation', 'itmDesc', 'location'), array($_POST['pType'], $_POST['pSizes'], $_POST['pVar'], $_POST['pDesc'], $_POST['pLoc']));
			echo $prdName . ";" . $prdGroup . ";" . $fileName;
		} elseif($_POST['action'] == "removeProd") {
			deleteProduct($_POST['prodID']);
		} elseif($_POST['action'] == "editImageTitle") {
			$newTitle = $_POST['newTitle'];
			$SQL = new mSQL;
			$SQL->updateQ("gallery", "ID = '" . $_POST['imgID'] . "'", array( "title" ), array( $newTitle ));
			echo $newTitle;
		} elseif($_POST['action'] == "removeGalleryImage") {
			$imgID = $_POST['imgID'];
			$SQL = new mSQL;
			$myImage = $SQL->rselectQ("gallery", "ID = '" . $imgID . "'");
			$imgPID = $myImage["pID"];
			$imgURL = $myImage["imgurl"];
			$SQL->deleteQ("gallery", "ID = '" . $imgID . "'");
			unlink("../".$imgURL);
			unlink($imgURL);
			$imagesExist = "Images Exist";
			$myResult = $SQL->selectQ("gallery", "*", "pID = '" . $imgPID . "'");
			if ($myResult == false) {
				$SQL->updateQ("items", "ID = '" . $imgPID . "'", array( "gallery" ), array( "0" ));
				$imagesExist = "No Images";
			}
			echo $imagesExist;
		} elseif($_POST['action'] == "addGalleryImage") {
			$SQL = new mSQL;
			$prodID = $_POST['prodID'];
			$imgTitle = $_POST['imgTitle'];
			$imgSource = $_FILES['imageSrc']['tmp_name'];
			$imgType = $_POST['imageType'];
			$origSize = explode("X", $_POST['origSize']);
			$newSize = resizeImage($origSize, array( 768, 432 ));
			$XY = array ( 0, 0 );
			$destFile = "../gallery/";
			$fileName = SaveImage($imgSource, $imgType, $destFile, $origSize, $XY, $newSize, $XY, $newSize);
			$tFile = substr($fileName, 8, strlen($fileName) - 12);
			$newSize = resizeImage($origSize, array( 128, 72 ));
			$destFile = "gallery/";
			if($newSize[0] < 128) {
				$dstX = (128 - $newSize[0]) / 2;
			} else {
				$dstX = 0;
			}
			if ($newSize[1] < 72) {
				$dstY = (72 - $newSize[1]) / 2;
			} else {
				$dstY = 0;
			}
			SaveImage($imgSource, $imgType, $destFile, $origSize, $XY, $newSize, array( $dstX, $dstY ), array( 128, 72 ), $tFile);
			$myID = $SQL->insertQ("gallery", array("pID", "title", "imgurl"), array($prodID,$imgTitle,$fileName));
			if ($SQL->fselectQ("items", "gallery", "ID = '" . $prodID . "'") == 0) {
				$SQL->updateQ("items", "ID = '" . $prodID . "'", array( "gallery" ), array( 1 ));
			}
			echo "	<div id=\"imID" . $myID . "\" style=\"position: relative; box-sizing: content-box; display: inline; width: 16.67%; min-width: 200px; max-width: 300px; min-height: 1px; padding-left: 15px; padding-right: 15px; float: left;\">
    	<div class=\"panel panel-primary\">
        	<div class=\"panel-heading\">
            	<span class=\"panel-title h3\" style=\"overflow: hidden; white-space: nowrap; text-overflow: ellipsis;\">" . $imgTitle . "</span>
            </div>
            <div class=\"panel-body\">
            	<a href=\"gallery_view.php?b=" . $myID . "\" data-toggle=\"modal\" data-target=\"#viewModal\">
	            	<img src=\"" . $fileName . "\" class=\"img-responsive\" style=\"max-height: 180px;\" />
                </a>
            </div>
            <div class=\"panel-footer\">
	            <button type=\"button\" id=\"removeImg\" data-iid=\"" . $myID . "\" class=\"btn btn-xs btn-danger\"><span class=\"glyphicon glyphicon-remove-circle\"></span> Remove</button>
            </div>
        </div>
    </div>";
		} elseif($_POST['action'] == "addCarouselImage") {
			$SQL = new mSQL;
			$caption = $_POST['caption'];
			$imgSource = $_FILES['imageSrc']['tmp_name'];
			$imgType = $_POST['imageType'];
			$sourceX = $_POST['sX'];
			$sourceY = $_POST['sY'];
			$sourceWidth = $_POST['sW'];
			$sourceHeight = $_POST['sH'];
			$destWidth = $_POST['dW'];
			$destHeight = $_POST['dH'];	
			$destFile = "../images/home/";
			$fileName = SaveImage($imgSource, $imgType, $destFile, array( $sourceWidth, $sourceHeight ), array( $sourceX, $sourceY ), array( $destWidth, $destHeight ), array ( 0,0 ), array( $destWidth, $destHeight ));
			$myID = $SQL->insertQ("carousel", array("caption", "imgurl"), array($caption, $fileName));
			echo "    <div class=\"panel panel-primary\">
    	<div class=\"panel-heading\">
        	<h3 class=\"panel-title\">Image</h3>
        </div>
        <div class=\"panel-body\">
        	<div class=\"row\">
	        	<img class=\"img-responsive center\" src=\"../" . $fileName . "\">
            </div>
            <div class=\"lead\">" . $caption . "</div>
        </div>
        <div class=\"panel-footer\">
			<a href=\"caption_edit.php?b=" . $myID . "\" class=\"btn btn-warning btn-xs\" data-toggle=\"modal\" data-target=\"#editModal\"><span class=\"glyphicon glyphicon-edit\"></span> Edit Caption</a>
            <a href=\"carousel_remove.php?b=" . $myID . "\" class=\"btn btn-danger btn-xs\" data-toggle=\"modal\" data-target=\"#removeModal\"><span class=\"glyphicon glyphicon-remove-sign\"></span> Remove Image</a>
        </div>
     </div>";
		} elseif ($_POST['action'] == "removeCarouselImage") {
			$imgID = $_POST['imgID'];
			$SQL = new mSQL;
			$myImage = $SQL->rselectQ("carousel", "ID = '" . $imgID . "'");
			$imgURL = $myImage["imgurl"];
			$SQL->deleteQ("carousel", "ID = '" . $imgID . "'");
			unlink("../".$imgURL);
			echo "Image Deleted";
		} elseif ($_POST['action'] == "editCarouselCaption") {
			$imgID = $_POST['imgID'];
			$caption = $_POST["caption"];
			$SQL = new mSQL;
			$SQL->updateQ("carousel", "ID = '" . $imgID . "'", array ( "caption" ), array ( $caption ));
			echo $caption;
		}
	}
	function resizeImage ($origSize, $maxSize) {
		$max_width = $maxSize[0];
		$max_height = $maxSize[1];
		$w = $origSize[0];
		$h = $origSize[1];
		if ($w > $max_width) {
			$h = ceil($h / $w * $max_width);
			$w = $max_width;
		}
		if ($h > $max_height) {
			$w = ceil($w / $h * $max_height);
			$h = $max_height;
		}
		return array( $w, $h );
	}
	function deleteProduct ($myID) {
		if (!isset($SQL)) { $SQL = new mSQL; }
		$myData = $SQL->rselectQ("items", "ID = '".$myID."'");
		if ($myData['image'] != "images/testimage.png" && $myData['image'] != "images/missingImage.png") {
			unlink("../".$myData['image']);
		}
		$SQL->deleteQ("items", "ID = '".$myID."'");
	}
	function SaveImage ($imgSource, $imgType, $destLoc, $sourceSize, $sourceXY, $destSize, $destXY, $finSize, $thumb = "no") {
		if ($imgType != "NO IMAGE") {
			if ($thumb == "no") {
				$rndName = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,50 ) ,1 ) .substr( md5( time() ), 1);
			} else {
				$rndName = $thumb;
			}
			$fileName = $destLoc . $rndName;
			$image = imagecreatefromstring(file_get_contents($imgSource));
			$newCanvas = imagecreatetruecolor($finSize[0], $finSize[1]);
			$white = imagecolorallocate($newCanvas, 255, 255, 255);
			imagefill($newCanvas, 0, 0, $white);
			if(imagecopyresampled($newCanvas, $image, $destXY[0], $destXY[1], $sourceXY[0], $sourceXY[1], $destSize[0], $destSize[1], $sourceSize[0], $sourceSize[1])) {
				switch(strtolower($imgType))
				{
					case 'image/png':
						$newFN = $fileName.".png";
						imagepng($newCanvas, $fileName.".png");
						break;
					case 'image/gif':
						$newFN = $fileName.".gif";
						imagegif($newCanvas, $fileName.".gif");
						break;
					case 'image/jpeg':
						$newFN = $fileName.".jpg";
						imagejpeg($newCanvas, $fileName.".jpg", 90);
						break;
				}
			}
			return substr($newFN,3);
		} else {
			return "images/missingImage.png";
		}
	}
?>