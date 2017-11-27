<link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.js"></script>

<?php
if(!session_id()){
    session_start();
}

// Get album id from url
$album_id = isset($_GET['album_id'])?$_GET['album_id']:header("Location: facebook.php");
$album_name = isset($_GET['album_name'])?$_GET['album_name']:header("Location: facebook.php");

// Get access token from session
$access_token = $_SESSION['facebook_access_token'];

// Get photos of Facebook page album using Facebook Graph API
$graphPhoLink = "https://graph.facebook.com/v2.9/{$album_id}/photos?fields=source,images,name&access_token={$access_token}";
$jsonData = file_get_contents($graphPhoLink);
$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook photos content
$fbPhotoData = $fbPhotoObj['data'];
?>

<?php echo "<h2>".$album_name."</h2>"; ?>

<div id="owl-demo" class="owl-carousel owl-theme">
<?php
// Render all photos    
foreach($fbPhotoData as $data){
    $imageData = end($data['images']);
    $imgSource = isset($imageData['source'])?$imageData['source']:'';
    $name = isset($data['name'])?$data['name']:'';

    echo "<div class='item'>";
    echo "<img src='{$imgSource}' alt=''>";
 echo "<p>{$name}</p>";
    echo "</div>";
}
?>
</div>




<script>
    $(document).ready(function() {
      $("#owl-demo").owlCarousel({
      autoPlay: 3000,
      items : 5,
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3]

      });

    });
</script>


<style>

#owl-demo .item{
  margin: 3px;
}
#owl-demo .item img{
  display: block;
  width: 100%;
  height: 350px;
}

</style>


