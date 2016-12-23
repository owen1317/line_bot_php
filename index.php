<?php
if(! mysql_connect("localhost","startouch",""))
    die("connect false");

mysql_select_db("message_bot");


// parameters
$hubVerifyToken = 'owen_cheng_test';
$accessToken = "EAACT5g7ZAIJkBANIfkCnJOZAiMyiNX276uk4qGDVUqNZCqgSPyGIgpuAD1S4WzkdWM8EdmZC8CVRFSZC6pL7rSumumXpBnFdlBJrrvARVjOZCbpWjcph9W0qQiVB0NbQvu6XZCGhiXGQOqonWU88YtP3PhFqhEGArJqHfVAsxZAEqAZDZD";
// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
$answer = "I don't understand. Ask me 'hi'. Your fb id is ".$senderId;
if($messageText == "hi") {
    $answer = "Hello";
}
else if($messageText == "我是店家管理者"){
	$sql = 'Insert into admin (fb_id,fan_page_id) VALUES ('.$senderId.','.$input['entry'][0]['messaging'][0]['recipient']['id'].')';
	$result = mysql_query($sql);
	$answer = "已將您新增至店家管理者清單";
}

/*
    foreach(店家管理者清單){
        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => [ 'text' => $answer ]
        ];
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);
    }
*/

$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $answer ]
];
$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);
//based on http://stackoverflow.com/questions/36803518

$response = [
    'recipient' => [ 'id' => 1133016060085386 ],
    'message' => [ 'text' => '傳送者ID'.$senderId ]
];
$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);







function debug($data,$option=['IsReturnString'=>false,'append'=>'']){
	if(!isset($option['IsReturnString'])) $option['IsReturnString']=false;
	if(!isset($option['append']) || $option['append']==''){
		if(defined('CLI_MODE') && CLI_MODE){
			$option['append']='';
		}else{
			$option['append']='pre';
		}
	}
	switch($option['append']){
		case 'textarea':
			$append_start='<textarea cols="100" rows="10">';
			$append_end='</textarea>';
			break;
		case 'div':
			$append_start='<div style="width: 1000px; height: 300px; border: 1px solid #ccc; overflow: auto;">';
			$append_end='</div>';
			break;
		case 'pre':
			$append_start='<pre>';
			$append_end='</pre>';
			break;
		default:
			$append_start='';
			$append_end='';
			break;
	}

	$str="{$append_start}";
	foreach($data as $key=>$val){
		$str.="$key => ".var_export($val,true)."\n";
/*
		if($option['IsReturnString']){
			$str.="$key => ";
		}else{
			echo "$key => ";
		}
		if($option['IsReturnString']){
			$str.=print_r($val,true);
// 				$str.="<br>\n";
		}else{
			var_dump($val);
// 				echo "<br>\n";
		}
*/
	}
	$str.="{$append_end}";

	if($option['IsReturnString']){
		return $str;
	}else{
		echo $str;
	}
}

