<?
include '../head.php';
?>
<body>
<? 
include '../top.php';
?>  
            <div id="bodyHome">
                <div class="photo">
                <?
//path to directory to scan

//get all image files with a .jpg extension.
$images = glob( "*.jpg");
shuffle($images);
foreach($images as $image) {
echo '<a href="../photos/'.$image.'" data-lightbox="roadtrip" ><img src="'.$image.'" alt="photo"></a>';
    }
?>
    </div>
            </div>

 <script src="javascript.js"></script>
 
</body>
</html>