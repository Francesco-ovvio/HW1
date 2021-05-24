<?php 
    require_once 'connection.php';
    
    $appid = 'c3b1c165';
    $appkey = 'f580e7ae4bbf4739c53ea431460e3702';

    $sql = "SELECT * FROM tipoprodotto";
    $result = mysqli_query($con, $sql);
    $arrayNutr = array();
    while($row = mysqli_fetch_assoc($result)){
        //array_push($arrayNomi, $row['nomeProdotto']);
        $urlApi = 'https://api.nutritionix.com/v1_1/search/'.$row['nomeTrad'].'?results=0:1&fields=item_name,brand_name,item_id,nf_calories,nf_total_fat,nf_protein,nf_total_carbohydrate&appId='.$appid.'&appKey='.$appkey;
        $ch = curl_init($urlApi);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $json = json_decode($data, true);
        array_push($arrayNutr, $json);
        curl_close($ch);
    }
    echo json_encode($arrayNutr);
?>