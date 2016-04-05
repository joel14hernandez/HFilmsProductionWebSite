<?
include '../head.php';
?>
<body id="models">
<? 
include '../top.php';
?>  

    <div id="body2">
        <div class="photos">
        <?
//path to directory to scan

//get all image files with a .jpg extension.
$images = glob( "*.jpg");

foreach($images as $image) {
echo '<a href="../photos/'.$image.'" data-lightbox="roadtrip" ><img src="'.$image.'" alt="photo"></a>';
    }
?>
        </div>
    </div>
        
 <script src="javascript.js"></script>
 
</body>
</html>