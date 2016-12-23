
<?php
	ini_set("display_errors", "On"); // 顯示錯誤是否打開( On=開, Off=關 )
	error_reporting(E_ALL & ~E_NOTICE);

	if(! mysql_connect("localhost","startouch",""))
    die("connect false");
mysql_query("SET NAMES 'utf8'"); 
 mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
 mysql_query("SET CHARACTER_SET_RESULTS=utf8"); 
mysql_select_db("message_bot");
	/* 輸入申請的Line Developers 資料  */
	$channel_id = "1492586591";
	$channel_secret = "2eac9c639aea7e50a844de8d891203d7";
	$channel_access_token = "HylxJGZOFpWGPWn+tUiCZCsPXK0z9Ymb13cP+f2p03lcGzg/Xl++4GEN6GPkCVeL94GrfjY06PI/ViwxKeToTUgOFiGxaxbqoR+nL2t3HdAliqdfUaMJ0GpyZijhPaJZ0yhRhVSWZCJtK5dI41OEHwdB04t89/1O/w1cDnyilFU=";
//  當有人發送訊息 bot收到的json
// 	{
// 	  "events": 
// 	  [
// 		  {
// 			"replyToken": "nHuyWiB7yP5Zw52FIkcQobQuGDXCTA",
// 			"type": "message",
// 			"timestamp": 1462629479859,
// 			"source": {
// 				 "type": "user",
// 				 "userId": "U206d25c2ea6bd87c17655609a1c37cb8"
// 			 },
// 			 "message": {
// 				 "id": "325708",
// 				 "type": "text",
// 				 "text": "Hello, world"
// 			  }
// 		  }
// 	  ]
// 	}
	 
	 
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
	reply($content_type, $text);
	
	/* 發送訊息 */	
	function reply($content_type, $message) {
	 
	 	global $header, $from, $receive;
	 	
		$url = "https://api.line.me/v2/bot/message/push";
				
// 		$profile = curlUserProfileFromLine($from);
// 		$userName = $profile['displayName'];
		
		
		$data = ["to" => $from, "messages" => array(["type" => "text", "text" => $message])];
		
		switch($content_type) {
		
			case "text" :
				$content_type = "文字訊息";
				$data = ["to" => $from, "messages" => array(["type" => "text", "text" => $from])];
				break;
			case "image" :
				$content_type = "圖片訊息";
				$message = getObjContent("jpeg");
				$data = ["to" => $from, "messages" => array(["type" => "image", "originalContentUrl" => $message, "previewImageUrl" => $message])];
				break;
			case "video" :
				$content_type = "影片訊息";
				$message = getObjContent("mp4");
				$data = ["to" => $from, "messages" => array(["type" => "video", "originalContentUrl" => $message, "previewImageUrl" => $message])];
				break;
			case "audio" :
				$content_type = "語音訊息";
				$message = getObjContent("mp3");
				$data = ["to" => $from, "messages" => array(["type" => "audio", "originalContentUrl" => $message[0], "duration" => $message[1]])];
				break;
			case "location" :
				$content_type = "位置訊息";
				$title = $receive->events[0]->message->title;
				$address = $receive->events[0]->message->address;
				$latitude = $receive->events[0]->message->latitude;
				$longitude = $receive->events[0]->message->longitude;
				$data = ["to" => $from, "messages" => array(["type" => "location", "title" => $title, "address" => $address, "latitude" => $latitude, "longitude" => $longitude])];
				break;
			case "sticker" :
				$content_type = "貼圖訊息";
				$packageId = $receive->events[0]->message->packageId;
				$stickerId = $receive->events[0]->message->stickerId;
				$data = ["to" => $from, "messages" => array(["type" => "sticker", "packageId" => $packageId, "stickerId" => $stickerId])];
				break;
			default:
				$content_type = "未知訊息";
				break;
	   	}
		if($from !='Ce894385c5641b06cc9fe688083bbba72'&&$from !='C07ff16d256299d2c2a9eadaa208d63ea'&&$from !='R2dc4ca60f8314371ef928531c0a44a44'&&$from !='R7d3178c96f47235085fb0cdb1ae9989f'){
			$context = stream_context_create(array(
			"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
			));
			file_get_contents($url, false, $context);
		}else if($from =='Ce894385c5641b06cc9fe688083bbba72'||$from =='C07ff16d256299d2c2a9eadaa208d63ea'||$from=='R7d3178c96f47235085fb0cdb1ae9989f'){
			if($content_type == '文字訊息'){
				if($message =='你給我滾'){
					if($from =='R7d3178c96f47235085fb0cdb1ae9989f'){
						$url="https://api.line.me/v2/bot/room/".$from."/leave";
						$context = stream_context_create(array(
							"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
							));
						file_get_contents($url, false, $context);
					}else{
						$url="https://api.line.me/v2/bot/group/".$from."/leave";
						$context = stream_context_create(array(
							"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
							));
						file_get_contents($url, false, $context);
					}
					
					
				}else{
					$message_arr = explode(',',$message);
					if($message_arr[0]=='星一波' && count($message_arr) == 3){
						$sql = 'Insert into message_answer (question,answer) VALUES ('."'".$message_arr[1]."'".','."'".$message_arr[2]."'".')';
						$result = mysql_query($sql);
						if($result){
							$data = ["to" => $from, "messages" => array(["type" => "text", "text" => "好了"])];
							$context = stream_context_create(array(
								"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
								));
							file_get_contents($url, false, $context);
						}else{
							$data = ["to" => $from, "messages" => array(["type" => "text", "text" => $sql])];
							$context = stream_context_create(array(
								"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
								));
							file_get_contents($url, false, $context);
						}
						
					}else{
						if($message =='智障'||$message=='北七'){
							$data = ["to" => $from, "messages" => array(["type" => "sticker", "packageId" => 1, "stickerId" => 401])];
							$context = stream_context_create(array(
							"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
							));
							file_get_contents($url, false, $context);
						}else{
							$message = addslashes($message);
							// $sql = 'Select * FROM message_answer WHERE question='."'".$message."'";
							$sql = 'Select * FROM message_answer WHERE question='."'".$message."'";
							$result = mysql_query($sql);
							$row = @mysql_fetch_row($result);
							if(!$row){
								$sql = 'Select * FROM message_answer WHERE instr('."'".$message."',"."message_answer.question)";
								$result = mysql_query($sql);
								$row = @mysql_fetch_row($result);
							}
							// $sql = 'Select * FROM message_answer WHERE instr('."'".$message."',"."message_answer.question)";
							// $result = mysql_query($sql);
							// $row = @mysql_fetch_row($result);
							$data = ["to" => $from, "messages" => array(["type" => "text", "text" => $row[2]])];
							$context = stream_context_create(array(
								"http" => array("method" => "POST", "header" => implode(PHP_EOL, $header), "content" => json_encode($data), "ignore_errors" => true)
								));
							file_get_contents($url, false, $context);
						}
						
					}
				}
			}
			
		}
	
	}
	
	function getObjContent($filenameExtension){
		
		global $channel_access_token, $receive;
	
		$objID = $receive->events[0]->message->id;
		$url = 'https://api.line.me/v2/bot/message/'.$objID.'/content';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer {' . $channel_access_token . '}',
		));
		
		$json_content = curl_exec($ch);
		curl_close($ch);
		if (!$json_content) {
			return false;
		}
		
		$fileURL = '/var/www/linebot/update/'.$objID.'.'.$filenameExtension;
		$fp = fopen($fileURL, 'w');
		fwrite($fp, $json_content);
		fclose($fp);
		
		if ($filenameExtension==".mp3"){
			require_once("getID3/getid3/getid3.php");
			$getID3 = new getID3;
			$fileData = $getID3->analyze($fileURL);
			$audioInfo = var_dump($fileData);
			$playSec = $audioInfo["playtime_seconds"];
			$re = array("https://linebot.andynote.com/update/".$objID.'.'.$filenameExtension, $playSec);
		}
		return "https://linebot.andynote.com/update/".$objID.'.'.$filenameExtension;
	}
	
	function curlUserProfileFromLine($mid) {
		
		global $channel_access_token;
	
		$url = 'https://api.line.me/v2/bot/profile/' . $mid;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer {' . $channel_access_token . '}',
		));
		
		$json_content = curl_exec($ch);
		curl_close($ch);
		if (!$json_content) {
			return false;
		}
// 		{
// 			"displayName":"LINE taro",
// 			"userId":"Uxxxxxxxxxxxxxx...",
// 			"pictureUrl":"http://obs.line-apps.com/...",
// 			"statusMessage":"Hello, LINE!"
// 		}
		$json = json_decode($json_content, true);
		$url = $json['pictureUrl'];
		$image_data = file_get_contents($url);
		$image = imagecreatefromstring($image_data);
		imagejpeg($image, '/var/www/linebot/update/profile/'.$json['userId'].'jpeg'); // 於此目錄下, 產生實體圖片
		$json['pictureUrl'] = "https://linebot.andynote.com/update/profile/".$json['userId'].'jpeg';
		
		if ($mid == $json['userId']) {
				return $json;
		}
		
		return false;
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