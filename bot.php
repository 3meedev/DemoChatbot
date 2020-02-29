<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'vendor/autoload.php';



include 'bot_settings.php';

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;



$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


$content = file_get_contents('php://input');



$events = json_decode($content, true);
// if (!is_null($events)) {
    
//     $replyToken = $events['events'][0]['replyToken'];
//     $typeMessage = $events['events'][0]['message']['type'];
//     $userMessage = $events['events'][0]['message']['text'];
//     $userMessage = strtolower($userMessage);
//     switch ($typeMessage) {
//         case 'text':
//             switch ($userMessage) {
//                 case "ตอบกลับ":
//                     $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
//                     $textMessage = new TextMessageBuilder($textReplyMessage);
//                     break;
//                 case "โลเคชั่น":
//                     $placeName = "ที่ตั้งร้าน";
//                     $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
//                     $latitude = 13.780401863217657;
//                     $longitude = 100.61141967773438;
//                     $locationMessage = new LocationMessageBuilder($placeName, $placeAddress, $latitude, $longitude);
//                     break;
//                 case "แจ้งปัญหา":                   
//                     $actionBuilder = array(
//                         new MessageTemplateActionBuilder(
//                             'ลืมรหัสผ่าน',
//                             'กรอกชื่อผู้ใช้ 1'
//                         ),
//                         new MessageTemplateActionBuilder(
//                             'ยอดเงินไม่เข้า',
//                             'กรอกชื่อผู้ใช้ 2'
//                         ),
//                         new UriTemplateActionBuilder(
//                             'รายละเอียดเพิ่มเติม',
//                             'https://www.google.com/?hl=th'
//                         ),   
//                         new MessageTemplateActionBuilder(
//                             'อื่นๆ',
//                             'กรอกชื่อผู้ใช้ 3'
//                         ),
                                          
//                     );
//                     $imageUrl = 'https://i2.wp.com/sagaming168.com/wp-content/uploads/2018/12/sa-game-casino.jpg?resize=578%2C337&ssl=1';
//                     $replyData = new TemplateMessageBuilder(
//                         'แจ้งปัญหา',
//                         new ButtonTemplateBuilder(
//                             'แจ้งปัญหา',
//                             'กรุณาเลือกหัวข้อที่ต้องการ',
//                             $imageUrl,
//                             $actionBuilder 
//                         )
//                     );
//                     break;
//                 case "ติดต่อ":
//                     $replyData = new TemplateMessageBuilder(
//                         'Confirm Template',
//                         new ConfirmTemplateBuilder(
//                             'Confirm template builder',
//                             array(
//                                 new MessageTemplateActionBuilder(
//                                     'Yes',
//                                     'Text Yes'
//                                 ),
//                                 new MessageTemplateActionBuilder(
//                                     'No',
//                                     'Text NO'
//                                 )
//                             )
//                         )
//                     );
//                     break;
//                 default:
//                     $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
//                     $replyData = new TextMessageBuilder($textReplyMessage);
//                     break;
//             }
//             break;
//         default:
//             $textReplyMessage = json_encode($events);
//             $replyData = new TextMessageBuilder($textReplyMessage);
//             break;
//     }
// }

// $response = $bot->replyMessage($replyToken, $replyData);


// echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

$events = json_decode($content, true);
if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $userID = $events['events'][0]['source']['userId'];
    $sourceType = $events['events'][0]['source']['type'];
    $is_postback = NULL;
    $is_message = NULL;
    if(isset($events['events'][0]) && array_key_exists('message',$events['events'][0])){
        $is_message = true;
        $typeMessage = $events['events'][0]['message']['type'];
        $userMessage = $events['events'][0]['message']['text'];     
        $idMessage = $events['events'][0]['message']['id']; 
    }
    if(isset($events['events'][0]) && array_key_exists('postback',$events['events'][0])){
        $is_postback = true;
        $dataPostback = NULL;
        parse_str($events['events'][0]['postback']['data'],$dataPostback);;
        $paramPostback = NULL;
        if(array_key_exists('params',$events['events'][0]['postback'])){
            if(array_key_exists('date',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['date'];
            }
            if(array_key_exists('time',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['time'];
            }
            if(array_key_exists('datetime',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['datetime'];
            }                       
        }
    }   
    if(!is_null($is_postback)){
        $textReplyMessage = "ข้อความจาก Postback Event Data = ";
        if(is_array($dataPostback)){
            $textReplyMessage.= json_encode($dataPostback);
        }
        if(!is_null($paramPostback)){
            $textReplyMessage.= " \r\nParams = ".$paramPostback;
        }
        $replyData = new TextMessageBuilder($textReplyMessage);     
    }
    if(!is_null($is_message)){
        switch ($typeMessage){
            case 'text':
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                    case "p":
                        // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
                            // ดึงค่ามาแบบเป็น JSON String โดยใช้คำสั่ง getRawBody() กรณีเป้นข้อความ text
                            $textReplyMessage = $response->getRawBody(); // return string            
                            $replyData = new TextMessageBuilder($textReplyMessage);         
                            break;              
                        }
                        // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;              
                    case "สวัสดี":
                        // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
                            // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array 
                            $userData = $response->getJSONDecodedBody(); // return array     
                            // $userData['userId']
                            // $userData['displayName']
                            // $userData['pictureUrl']
                            // $userData['statusMessage']
                            $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];             
                            $replyData = new TextMessageBuilder($textReplyMessage);         
                            break;              
                        }
                        // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;                                                                                                                                                                                                                                          
                    default:
                        $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                        $replyData = new TextMessageBuilder($textReplyMessage);         
                        break;                                      
                }
                break;                                      
            default:
                $textReplyMessage = json_encode($events);
                $replyData = new TextMessageBuilder($textReplyMessage);         
                break;  
        }
    }
}
$response = $bot->replyMessage($replyToken,$replyData);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}
 
// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
?>
