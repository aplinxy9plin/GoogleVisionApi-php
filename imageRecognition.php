<?php
$countResult = 5;
$image = file_get_contents('outputBase64.txt');
function createJSON($type){
  global $image;
  $request = '{
    "requests":[
      {
        "image":{
          "content":"'.$image.'"
        },
        "features":[
          {
            "type":"'.$type.'",
            "maxResults": 5
          }
        ]
      }
    ]
  }';
  return $request;
}
function request($jsonRequest){
  $apikey = 'AIzaSyCy5byxzoigmevVSNOlQe0HSabmpxOsNOU';
                                                                                                                   
  $ch = curl_init('https://vision.googleapis.com/v1/images:annotate?key='.$apikey.'');               
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);                                                                  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                                
      'Content-Length: ' . strlen($jsonRequest))                                                                       
  );                                                                                                                   
                                                                                                                  
  $result = curl_exec($ch);
  $resultDecode = json_decode($result); 
  return $resultDecode;
}
function data_base(){
  $mysqli = new mysqli("127.0.0.1","top4ek","q2w3e4r5","shop");
  $mysqli->set_charset("utf8");
  $result = $mysqli->query("SELECT * FROM products");
  if ($result->num_rows > 0) {
    $x = 0;
      while($row = $result->fetch_assoc()) {
          $data[$x] = $row['name'];
          $x++;
      }
      return $data;
  }
}
$db_name = data_base();
$json = array(createJSON('LOGO_DETECTION'), createJSON('WEB_DETECTION'), createJSON('TEXT_DETECTION'), createJSON('DOCUMENT_TEXT_DETECTION'));
for ($i=0; $i < 4; $i++) { 
  $encoded = json_encode(request($json[$i]), JSON_UNESCAPED_UNICODE);
  foreach ($db_name as $key) {
    if(strpos($encoded, $key) !== false){
      echo "Название: ";echo $key;
    }
  }
}