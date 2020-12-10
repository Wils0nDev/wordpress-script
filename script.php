<?php
define('WP_USE_THEMES', false);
require('./wp-blog-header.php');


if($_GET['idcliente']){
    $idcliente = $_GET['idcliente'];
}



$servername = "localhost";
$username = "root";
$password = '';


// Create connection
$conn = new mysqli($servername, $username, $password);
$database = mysqli_select_db($conn, 'app_nutricionplatinum');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$datoarray = get_option('fwdsuvp_data');

$sql_client = "SELECT * FROM p_cliente_video where idcliente = $idcliente";
$client = mysqli_query($conn, $sql_client); // First parameter is just return of "mysqli_connect()" function

$gallery_arr = array();

for($i = 0; $i < count($datoarray->main_playlists_ar); $i++){
    //var_dump($datoarray->main_playlists_ar[$i]['id']);
    if($datoarray->main_playlists_ar[$i]['id'] <= 2){
     $gallery = $datoarray->main_playlists_ar[$i]['playlists'];
     array_push($gallery_arr,count($gallery[0]['videos']));
    }

}

$total_videos = array();
$total_videos['idcliente'] = $idcliente;
if($client->num_rows > 0){

    while($videos = $client->fetch_assoc()){
        if($gallery_arr[0] >= (int)($videos['videos'])){
            $tvideos = $gallery_arr[0] - (int)($videos['videos']);
           $total_videos['videos'] = $tvideos;
        }else{
            $total_videos['videos'] = 0;
        }

        if($gallery_arr[1] >= (int)($videos['receta'])){
            $tvideos = $gallery_arr[1] - (int)($videos['receta']);
            $total_videos['receta'] = $tvideos;
        }else{
            $total_videos['receta'] = 0;  
        }

        if($gallery_arr[2] >= (int)($videos['exito'])){
            $tvideos = $gallery_arr[2] - (int)($videos['exito']);
            $total_videos['exito'] = $tvideos;
        }else{
            $total_videos['exito'] = 0;
        }
    }

    $sql_up = "UPDATE p_cliente_video SET videos = $gallery_arr[0], receta = $gallery_arr[1], exito = $gallery_arr[2]";
    $update = mysqli_query($conn, $sql_up);

    if($update){
        echo json_encode($total_videos);
    }
    
    /**/

}else{

    //insertar
  $sql_in =  "INSERT INTO p_cliente_video(idcliente,videos,receta,exito) VALUES ($idcliente,$gallery_arr[0],$gallery_arr[1],$gallery_arr[2])";
  $insert = mysqli_query($conn, $sql_in);

  $total_videos['videos'] = $gallery_arr[0];
  $total_videos['receta'] = $gallery_arr[1];
  $total_videos['exito'] =  $gallery_arr[2];
  if($insert){
        echo json_encode($total_videos);
  }
}


$total_videos = [];

?>
