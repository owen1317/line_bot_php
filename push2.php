<?php
// parameters
$hubVerifyToken = 'owen_cheng_test2';
$accessToken = "EAAP56rUF8xQBAFnKIny50YCtxqaToilfZA3xFMWTQdyQnaWxEZAn41nfJsF67JktfvF0INZAxPQDGkgb9EMr4Lx8MzowYlEAwVKQw9AmLWOKsXuOFTfXCd1O34B7GzMGEOUBwOwrxEuSwKF8sy5KdJJxTS3ubs5ZCX7MmJBOcAZDZD";

$answer = 'push text';


$response = [
    'recipient' => [ 'id' => 1386532204690280 ],
    'message' => [ 'text' => $_GET['push_text'] ]
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

