Enter the page id: <input type="text" id="m" value="">

<button type="button" id="myButton">submit</button>

<script>

var n = document.getElementById("m");

document.getElementById("myButton").addEventListener("click", function() {

document.location.href='facebook.php?fb='+n.value;

 
});

</script>


<?php
if(!session_id()){
    session_start();
}

/*
 * Get access token using Facebook Graph API
 */
if(isset($_SESSION['access_token'])){
    // Get access token from session
    $access_token = $_SESSION['access_token'];
}else{
    // Facebook app id & app secret 
    $appId = '2030905173821685'; 
    $appSecret = 'ba30ca99bf0d3baeb191712e52614e6a';
    
    // Generate access token
    $graphActLink = "https://graph.facebook.com/oauth/access_token?client_id={$appId}&client_secret={$appSecret}&grant_type=client_credentials";
    
    // Retrieve access token
    $accessTokenJson = file_get_contents($graphActLink);
    $accessTokenObj = json_decode($accessTokenJson);
    $access_token = $accessTokenObj->access_token;
    
    // Store access token in session
    $_SESSION['facebook_access_token'] = $access_token;
}

// Get photo albums of Facebook page using Facebook Graph API
$fields = "id,name,description,link,cover_photo,count";
$fb_page_id = $_GET['fb'];
$graphAlbLink = "https://graph.facebook.com/v2.9/{$fb_page_id}/albums?fields={$fields}&access_token={$access_token}";

$jsonData = file_get_contents($graphAlbLink);
$fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook albums content
$fbAlbumData = $fbAlbumObj['data'];
?>


<?php
// Render all photo albums
echo "<br/><br/>";
foreach($fbAlbumData as $data){
    $id = isset($data['id'])?$data['id']:'';
    $name = isset($data['name'])?$data['name']:'';
    $description = isset($data['description'])?$data['description']:'';
    $link = isset($data['link'])?$data['link']:'';
    $cover_photo_id = isset($data['cover_photo']['id'])?$data['cover_photo']['id']:'';
    $count = isset($data['count'])?$data['count']:'';
    
    $pictureLink = "photos.php?album_id={$id}&album_name={$name}";
    

    echo "<a href='{$pictureLink}'>";
    $cover_photo_id = (!empty($cover_photo_id ))?$cover_photo_id : 123456;
    echo "<img width=100px height=100px src='https://graph.facebook.com/v2.9/{$cover_photo_id}/picture?access_token={$access_token}' alt=''>";
    echo "</a>";
    echo "<p>{$name}</p>";

    $photoCount = ($count > 1)?$count. 'Photos':$count. 'Photo';
    
    echo "<p><span style='color:#888;'>{$photoCount} / <a href='{$link}' target='_blank'>View on Facebook</a></span></p>";
    echo "<p>{$description}</p>";

}
?>

