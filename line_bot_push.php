
<?php
	ini_set("display_errors", "On"); // 顯示錯誤是否打開( On=開, Off=關 )
	error_reporting(E_ALL & ~E_NOTICE);
	/* 輸入申請的Line Developers 資料  */
	$channel_id = "1492586591";
	$channel_secret = "2eac9c639aea7e50a844de8d891203d7";
	$channel_access_token = "HylxJGZOFpWGPWn+tUiCZCsPXK0z9Ymb13cP+f2p03lcGzg/Xl++4GEN6GPkCVeL94GrfjY06PI/ViwxKeToTUgOFiGxaxbqoR+nL2t3HdAliqdfUaMJ0GpyZijhPaJZ0yhRhVSWZCJtK5dI41OEHwdB04t89/1O/w1cDnyilFU=";
	 
	 
	// 將收到的資料整理至變數
	$receive = json_decode(file_get_contents("php://input"));
	
	// 讀取收到的訊息內容
	$text = $receive->events[0]->message->text;
	
	// 讀取訊息來源的類型 	[user, group, room]
	$type = $receive->events[0]->source->type;
	
	// 由於新版的Messaging Api可以讓Bot帳號加入多人聊天和群組當中
	// 所以在這裡先判斷訊息的來源
	if ($type == "room")
	{
		// 多人聊天 讀取房間id
		$from = $receive->events[0]->source->roomId;
	} 
	else if ($type == "group")
	{
		// 群組 讀取群組id
		$from = $receive->events[0]->source->groupId;
	}
	else
	{
		// 一對一聊天 讀取使用者id
		$from = $receive->events[0]->source->userId;
	}
	
	// 讀取訊息的型態 [Text, Image, Video, Audio, Location, Sticker]
	$content_type = $receive->events[0]->message->type;
	
	/* 準備Post回Line伺服器的資料 */
	$header = ["Content-Type: application/json", "Authorization: Bearer {" . $channel_access_token . "}"];
	push($content_type, $text);
	
	function push(){
	    global $header;
	    $url = "https://api.line.me/v2/bot/message/push";
	    
	    $data = ["to" => $_POST['receiver'], "messages" => array(["type" => "text", "text" => $_POST['message']])];
	    $context = stream_context_create(array(
		"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
		));
		file_get_contents($url, false, $context);
	}
	
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