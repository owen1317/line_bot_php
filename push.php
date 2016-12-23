<?php
// parameters
$hubVerifyToken = 'owen_cheng_test';
$accessToken = "EAACT5g7ZAIJkBANIfkCnJOZAiMyiNX276uk4qGDVUqNZCqgSPyGIgpuAD1S4WzkdWM8EdmZC8CVRFSZC6pL7rSumumXpBnFdlBJrrvARVjOZCbpWjcph9W0qQiVB0NbQvu6XZCGhiXGQOqonWU88YtP3PhFqhEGArJqHfVAsxZAEqAZDZD";

$answer = 'push text';

//1251305908273117  morris
//1133016060085386  owen
//1595200317172733

$response = [
    'recipient' => [ 'id' => 1595200317172733 ],
    'message' => [ 'text' => '1.message:'.$_POST['message'].'       '.
    						 '2.device id:'.$_POST['device_id']]
];
$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);
//based on http://stackoverflow.com/questions/36803518







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

