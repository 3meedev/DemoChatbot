<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'vendor/autoload.php';



include 'bot_settings.php';

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Event;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\AccountLinkEvent;
use LINE\LINEBot\Event\MemberJoinEvent; 
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
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
use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;

// ----------------------------------------------------------------------------------------------------- แบบ Template Message

// $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
// $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


// $content = file_get_contents('php://input');
// $count = 0;


// $events = json_decode($content, true);


// $replyToken = $events['events'][0]['replyToken'];
// $typeMessage = $events['events'][0]['message']['type'];
// $typeMessageImage = $events['events'][0]['image']['image'];
// $userImage = $events['events'][0]['image'];
// $userMessage = $events['events'][0]['message']['text'];
// $userID = $events['events'][0]['source']['userId'];
// $userMessage = strtolower($userMessage);

  
  
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
   
$content = file_get_contents('php://input');
   
$hash = hash_hmac('sha256', $content, LINE_MESSAGE_CHANNEL_SECRET, true);
$signature = base64_encode($hash);
   
$events = $bot->parseEventRequest($content, $signature);
$eventObj = $events[0];
   
$eventType = $eventObj->getType();
   

$userId = NULL;
// $groupId = NULL;
// $roomId = NULL;

$sourceId = NULL;
$sourceType = NULL;

$replyToken = NULL;
$replyData = NULL;

$eventMessage = NULL;
$eventPostback = NULL;
$eventJoin = NULL;
$eventLeave = NULL;
$eventFollow = NULL;
$eventUnfollow = NULL;
$eventBeacon = NULL;
$eventAccountLink = NULL;
$eventMemberJoined = NULL;
$eventMemberLeft = NULL;
// เงื่อนไขการกำหนดประเภท Event 
switch($eventType){
    case 'message': $eventMessage = true; break;    
    case 'postback': $eventPostback = true; break;  
    case 'join': $eventJoin = true; break;  
    case 'leave': $eventLeave = true; break;    
    case 'follow': $eventFollow = true; break;  
    case 'unfollow': $eventUnfollow = true; break;  
    case 'beacon': $eventBeacon = true; break;     
    case 'accountLink': $eventAccountLink = true; break;       
    case 'memberJoined': $eventMemberJoined = true; break;       
    case 'memberLeft': $eventMemberLeft = true; break;                                           
}
// สร้างตัวแปรเก็บค่า userId กรณีเป็น Event ที่เกิดขึ้นใน USER
if($eventObj->isUserEvent()){
    $userId = $eventObj->getUserId();  
    $sourceType = "USER";
}
// สร้างตัวแปรเก็บค่า groupId กรณีเป็น Event ที่เกิดขึ้นใน GROUP
// if($eventObj->isGroupEvent()){
//     $groupId = $eventObj->getGroupId();  
//     $userId = $eventObj->getUserId();  
//     $sourceType = "GROUP";
// }
// // สร้างตัวแปรเก็บค่า roomId กรณีเป็น Event ที่เกิดขึ้นใน ROOM
// if($eventObj->isRoomEvent()){
//     $roomId = $eventObj->getRoomId();        
//     $userId = $eventObj->getUserId();      
//     $sourceType = "ROOM";
// }
// เก็บค่า sourceId ปกติจะเป็นค่าเดียวกันกับ userId หรือ roomId หรือ groupId ขึ้นกับว่าเป็น event แบบใด
$sourceId = $eventObj->getEventSourceId();
// ดึงค่า replyToken มาไว้ใช้งาน ทุกๆ Event ที่ไม่ใช่ Leave และ Unfollow Event และ  MemberLeft
// replyToken ไว้สำหรับส่งข้อความจอบกลับ 
if(is_null($eventLeave) && is_null($eventUnfollow) && is_null($eventMemberLeft)){
    $replyToken = $eventObj->getReplyToken();    
}

// ----------------------------------------------------------------------------------------- TextAll

// $textToPromotion = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        โปรโมชั่น

// พิมพ์ p ตามด้วยหัวข้อที่ต้องการ เช่น p1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. มีโปรโมชั่นอะไรบ้าง
// 2. ถ้ารับโปรโมชั่น ต้องทำเทิร์นเท่าไหร่
// 3. ถ้าไม่รับโบนัส จะต้องทำเทิร์นมั้ย
// 4. มีเครดิตฟรีมั้ย
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textPromotion1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "มีโปรโมชั่นอะไรบ้าง ?
// ___________________________________

// ตอนนี้มีโปรโมชั่น 30% จากยอดฝาก 
// หรือเลือกรับโปรโมชัั่นพร้อมของแถม 

// 1. สมัคร 1000 บาท ได้รับ หูฟังบลูทูธ TRUT WIRELESS 5.0 TWS 
// 2. สมัคร 1000 บาท ได้รับ พาวเวอร์แบ๊ง ELOOP E-12 
// 3. สมัคร 1000 บาท ได้รับ ลำโพง BLUETOOTH IRON MAN
// 4. สมัคร 1000 บาท ได้รับ บุหรี่ไฟฟ้า DRAG 
// 5. สมัคร 1000 บาท ได้รับ โทรศัพท์จิ๋ว 
// 6. สมัคร 500 บาท ได้รับ เสื้อบอล EURO 
// 7. สมัคร 500 บาท ได้รับ เสื้อฮูด Nike 
// 8. สมัคร 500 บาท ได้รับ Smart Watch 
// 9. สมัคร 500 บาท ได้รับ ลำโพง Bluetooth Mini 
// 10. สมัคร 500 บาท ได้รับ หูฟัง Bluetooth 
// 11. สมัคร 300 บาท ได้รับ ลำโพงสโมสรฟุตบอลโลก 
// 12. สมัคร 300 บาท ได้รับ กระเป๋าสะพายข้างลายสโมสรฟุตบอลโลก 
// 13. สมัคร 300 บาท ได้รับ Game Handle 
// 14. สมัครฝาก 200 รับโบนัส 30 %
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textPromotion2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้ารับโปรโมชั่นต้องทำเทิร์นเท่าไหร่ ?
// ___________________________________

// ทุกโปรทำเทิร์น 1.5 ค่ะ เช่น ฝาก200 
// (ต้องมียอดเล่นได้หรือเสียประมาณ 
// 300) ก็ถอนได้แล้วค่ะ เล่นได้ทุก
// อย่าง เช่น คาสิโน เกมส์ แทง บอล
// อื่นๆ เป็นต้นค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textPromotion3 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าไม่รับโบนัสจะต้องทำเทิร์นมั้ย ?
// ___________________________________

// ถ้าไม่รับโบนัสก้ทำเทริน 1.5 เหมือนกันคะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textPromotion4 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "มีเครดิตฟรีมั้ย ?
// ___________________________________

// เงินที่สมัครสามารถนำไปเล่นในเว็บได้
// เลยและได้ของแถมด้วยนะคะ 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToRecommend = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        คำแนะนำ

// พิมพ์ r ตามด้วยหัวข้อที่ต้องการ เช่น r1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ใส่คนแนะนำว่าอะไร
// 2. ถ้าชวนเพื่อนมาสมัครจะได้อะไรมั้ย
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRecommend1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ใส่คนแนะนำว่าอะไร ?
// ___________________________________

// SL99 แนะนำให้สมัครคะ 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRecommend2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าชวนเพื่อนมาสมัครพี่จะได้อะไรมั้ย ?
// ___________________________________

// ทางเรามีโปรโมชั่นชวนเพื่อนให้คะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToGroup = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        กลุ่ม/สูตร

// พิมพ์ g ตามด้วยหัวข้อที่ต้องการ เช่น g1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. มีสูตรโกงบาคาร่าให้มั้ย
// 2. มีกลุ่มวิเคราะบอลด้วยมั้ย
// 3. เล่นบาคาร่ายังไง
// 4. แทงบอลยังไง
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textGroup1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "มีสูตรโกงบาคาร่าให้มั้ย ?
// ___________________________________

// มีค่ะ แจ้งยูส+สลิปการโอน นะคะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("สูตรบาคาร่า", "https://www.google.com/?hl=th"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textGroup2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "มีกลุ่มวิเคราะบอลด้วยมั้ย ?
// ___________________________________

// กลุ่มวิเคราะบอล คลิ้กเข้าลิ้งเลยนะคะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("วิเคราะห์บอล", "https://line.me/ti/g2/fbDC6OmeUzJua6pFerS7"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textGroup3 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เล่นบาคาร่ายังไง ?
// ___________________________________

// คลิกลิ้งเพื่อเข้าดูวิธีเข้าเล่นบาคาร่าค่ะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("การเล่นบาคาร่า", "https://youtu.be/8O8M8R2Kffg"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textGroup4 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "แทงบอลยังไง ?
// ___________________________________

// คลิกลิ้งเพื่อดูการใช้งานและวิธีแทงหวย+บอล
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("การเล่นบอล/หวย", "https://www.youtube.com/channel/UC0j3s6xKcdOX9OFP05W82Bg"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textToDeposit = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        ฝาก/ถอน

// พิมพ์ d ตามด้วยหัวข้อที่ต้องการ เช่น d1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ฝาก/ถอนขั้นต่ำเท่าไหร่
// 2. ครั้งต่อไปฝาก/ถอนยังไง
// 3. ฝาก/ถอนจำกัดครั้งมั้บ ถอนได้เร็วมั้ย
// 4. ถ้าฝากไปแล้วไม่เล่นถอนได้เลยมั้ย
// 5. โอนเงินเสร็จแล้วทำไงต่อ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ฝาก/ถอนขั้นต่ำเท่าไหร่ ?
// ___________________________________

// หลังจากสมัครเป็นสมาชิกแล้วฝาก/ถอน
// ขั้นต่ำ 100 บาท ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ครั้งต่อไปฝาก/ถอนยังไง ?
// ___________________________________

// ฝาก/ถอนสามารถทำรายการผ่านหน้า
// เว็บได้เลยค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit3 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ฝาก/ถอน จำกัดครั้งมั้ย ถอนได้เร็วมั้ย ?
// ___________________________________

// ฝากถอนผ่านหน้าเว็บไม่จำกัดจำนวน
// ครั้งฝากถอนภายใน 5 วินาที
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit4 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าฝากไปแล้วไม่เล่นถอนได้เลยมั้ย ?
// ___________________________________

// ไม่ได้ค่ะ ต้องมียอดเล่นให้ครบเทริน
// ถึงถอนออกได้ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textDeposit5 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "โอนเงินเสร็จแล้วทำไงต่อ ?
// ___________________________________

// รอแอดมินตรวจสอบสักครู่นะคะ เสร็จ
// แล้วแอดมินจะส่งเลขยูสเวอร์ให้คะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToRegister = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        การสมัคร

// พิมพ์ u ตามด้วยหัวข้อที่ต้องการ เช่น u1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. เช้คได้ไหมว่าเคยสมัครไปหรือยัง
// 2. ถ้าเคยสมัครแล้ว แต่จะใช้บันชีแฟน
// สมัครอีกได้ไหม (แฟนนามสกุลเดียวกัน)
// 3. เคยสมัครสมาชิกแล้วสมัครใหม่ได้มั้ย
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เช้คได้ไหมว่าเคยสมัครไปหรือยัง ?
// ___________________________________

// ส่งข้อมูลให้แอดมินตรวจสอบได้เลยนะ
// คะถ้าเคยเป็นสมาชิกแล้วแอดมินจะแจ้ง
// เลขยูสให้คะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://www.google.com/"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textRegister2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ถ้าเคยสมัครแล้ว แต่จะใช้บัญชีแฟน
// สมัครอีกได้ไหม 
// (แฟนนามสกุลเดียวกัน) ?
// ___________________________________

// รอแอดมินตรวจสอบสักครู่นะคะ เสร็จ
// ได้คะพี่ขอแค่ชื่อคนสมัครกับชื่อบัญชี
// ที่ใช้โอนตรงกันและถ้าชื่อที่เคยสมัคร
// แล้วจะสมัครอีกไม่ได้ค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textRegister3 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เคยสมัครสมาชิกแล้วสมัครใหม่ได้มั้ย ?
// ___________________________________

// ไม่ได้ค่ะเพราะ 1 ชื่อสามารถสมัคร
// ได้แค่ 1 ยูสเซอร์เท่านั้นค่ะ
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToAccount = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        บัญชีผู้ใช้

// พิมพ์ a ตามด้วยหัวข้อที่ต้องการ เช่น a1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ลืมเลขบันชีต้องทำยังไง
// 2. ทำไมทำรายการฝากไม่ได้สักที 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textAccount1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ลืมเลขบันชีต้องทำยังไง ?
// ___________________________________

// คลิกลิ้งติดต่อขอเลขบัญชีกับแอดมินได้เลยค่ะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://www.google.com/"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );

// $textAccount2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ทำไมทำรายการฝากไม่ได้สักที ?
// ___________________________________

// กรอกข้อมูลให้ถูกต้องนะคะ ชื่อบัญชี
// ที่โอน เวลา และยอดเงิน 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textToWebsite = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "                        เกี่ยวกับเว็บไซต์

// พิมพ์ w ตามด้วยหัวข้อที่ต้องการ เช่น w1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. ในเว็บมีอะไรให้เล่นบ้าง
// 2. เข้าเล่นยังไง
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textWebsite1 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "ในเว็บมีอะไรให้เล่นบ้าง ?
// ___________________________________

// ในเว็บมี บอล มวย หวย บาส ไก่ชน 
// กีฬาให้แทงมี บาคาล่าเซ็กซี่ ไฮโล  
// และคาสิโนสดต่าง เกม  สลอต รูเลท
// ให้เล่น 
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                 NULL,
//                 NULL,
//                 "md",
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     )
// );

// $textWebsite2 = new BubbleContainerBuilder(
//     "ltr",
//     NULL,
//     NULL,
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new TextComponentBuilder(
//                 "เข้าเล่นยังไง ?
// ___________________________________

// คลิกลิ้งเพื่อเข้าหน้าเว็บได้เลยค่ะ
// ___________________________________",
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 NULL,
//                 true
//             )
//         )
//     ),
//     new BoxComponentBuilder(
//         "horizontal",
//         array(
//             new ButtonComponentBuilder(
//                 new UriTemplateActionBuilder("เข้าสู่เว็บไซต์", " https://www.copa69.com/"),
//                 NULL,
//                 NULL,
//                 NULL,
//                 "primary"
//             )
//         )
//     )
// );



// // ----------------------------------------------------------------------------------------- TextAll
// // ----------------------------------------------------------------------------------------- MainMenu

// if ($userMessage != null) {
//     if ($userMessage == "สอบถาม" || $userMessage == "q" || $userMessage == "Q") {
//         $textReplyMessage = new BubbleContainerBuilder(
//             "ltr",
//             NULL,
//             NULL,
//             new BoxComponentBuilder(
//                 "horizontal",
//                 array(
//                     new TextComponentBuilder(
//                         "พิมพ์ q ตามด้วยหัวข้อที่ต้องการ เช่น q1
// ___________________________________

// หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
// 1. โปรโมชั่น
// 2. คำแนะนำ
// 3. กลุ่ม/สูตร
// 4. ฝาก/ถอน
// 5. การสมัครสมาชิก
// 6. บัญชีผู้ใช้
// 7. เกี่ยวกับเว็บไซต์
// ___________________________________

// Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
//                         NULL,
//                         NULL,
//                         "md",
//                         NULL,
//                         NULL,
//                         true
//                     )
//                 )
//             )
//         );
//         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
//     }
//     if ($userMessage == "สมัคร") {
//         $textReplyMessage = new BubbleContainerBuilder(
//             "ltr",
//             NULL,
//             NULL,
//             new BoxComponentBuilder(
//                 "horizontal",
//                 array(
//                     new TextComponentBuilder(
//                         "ต้องการ",
//                         NULL,
//                         NULL,
//                         "md",
//                         NULL,
//                         NULL,
//                         true
//                     )
//                 )
//             )
//         );
//         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
//     }

//     // ----------------------------------------------------------------------------------------- MainMenu
//     // ----------------------------------------------------------------------------------------- Promotion

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToPromotion);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToPromotion);
//     }
//     if (strstr($userMessage, "p") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion1);
//     }
//     if (strstr($userMessage, "P") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion1);
//     }
//     if (strstr($userMessage, "p") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion2);
//     }
//     if (strstr($userMessage, "P") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion2);
//     }
//     if (strstr($userMessage, "p") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion3);
//     }
//     if (strstr($userMessage, "P") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion3);
//     }
//     if (strstr($userMessage, "p") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion4);
//     }
//     if (strstr($userMessage, "P") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textPromotion4);
//     }

//     // ----------------------------------------------------------------------------------------- Promotion
//     // ----------------------------------------------------------------------------------------- Recommend

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToRecommend);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToRecommend);
//     }
//     if (strstr($userMessage, "r") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRecommend1);
//     }
//     if (strstr($userMessage, "R") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRecommend1);
//     }
//     if (strstr($userMessage, "r") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRecommend2);
//     }
//     if (strstr($userMessage, "R") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRecommend2);
//     }

//     // ----------------------------------------------------------------------------------------- Recommend
//     // ----------------------------------------------------------------------------------------- Group

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToGroup);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToGroup);
//     }
//     if (strstr($userMessage, "g") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup1);
//     }
//     if (strstr($userMessage, "G") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup1);
//     }
//     if (strstr($userMessage, "g") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup2);
//     }
//     if (strstr($userMessage, "G") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup2);
//     }
//     if (strstr($userMessage, "g") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup3);
//     }
//     if (strstr($userMessage, "G") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup3);
//     }
//     if (strstr($userMessage, "g") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup4);
//     }
//     if (strstr($userMessage, "G") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textGroup4);
//     }

//     // ----------------------------------------------------------------------------------------- Group
//     // ----------------------------------------------------------------------------------------- Deposit

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToDeposit);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToDeposit);
//     }
//     if (strstr($userMessage, "d") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit1);
//     }
//     if (strstr($userMessage, "D") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit1);
//     }
//     if (strstr($userMessage, "d") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit2);
//     }
//     if (strstr($userMessage, "D") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit2);
//     }
//     if (strstr($userMessage, "d") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit3);
//     }
//     if (strstr($userMessage, "D") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit3);
//     }
//     if (strstr($userMessage, "d") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit4);
//     }
//     if (strstr($userMessage, "D") == true && strstr($userMessage, "4") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit4);
//     }
//     if (strstr($userMessage, "d") == true && strstr($userMessage, "5") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit5);
//     }
//     if (strstr($userMessage, "D") == true && strstr($userMessage, "5") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textDeposit5);
//     }

//     // ----------------------------------------------------------------------------------------- Deposit
//     // ----------------------------------------------------------------------------------------- Register

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "5") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToRegister);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "5") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToRegister);
//     }
//     if (strstr($userMessage, "u") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRegister1);
//     }
//     if (strstr($userMessage, "U") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRegister1);
//     }
//     if (strstr($userMessage, "u") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRegister2);
//     }
//     if (strstr($userMessage, "U") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRegister2);
//     }
//     if (strstr($userMessage, "u") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRegister3);
//     }
//     if (strstr($userMessage, "U") == true && strstr($userMessage, "3") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textRegister3);
//     }

//     // ----------------------------------------------------------------------------------------- Register
//     // ----------------------------------------------------------------------------------------- Account

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "6") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToAccount);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "6") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToAccount);
//     }
//     if (strstr($userMessage, "a") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textAccount1);
//     }
//     if (strstr($userMessage, "A") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textAccount1);
//     }
//     if (strstr($userMessage, "a") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textAccount2);
//     }
//     if (strstr($userMessage, "A") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textAccount2);
//     }

//     // ----------------------------------------------------------------------------------------- Account
//     // ----------------------------------------------------------------------------------------- Website

//     if (strstr($userMessage, "q") == true && strstr($userMessage, "7") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToWebsite);
//     }
//     if (strstr($userMessage, "Q") == true && strstr($userMessage, "7") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textToWebsite);
//     }
//     if (strstr($userMessage, "w") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textWebsite1);
//     }
//     if (strstr($userMessage, "W") == true && strstr($userMessage, "1") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textWebsite1);
//     }
//     if (strstr($userMessage, "w") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textWebsite2);
//     }
//     if (strstr($userMessage, "W") == true && strstr($userMessage, "2") == true) {
//         $replyData = new FlexMessageBuilder("Flex", $textWebsite2);
//     }

    // ----------------------------------------------------------------------------------------- Website





    // if ($userMessage != null) {

    //     if ($userMessage == "เรียกดูโปรโมชั่น") {
    //         $textReplyMessage = new BubbleContainerBuilder(
    //             "ltr",
    //             NULL,
    //             NULL,
    //             new BoxComponentBuilder(
    //                 "horizontal",
    //                 array(
    //                     new TextComponentBuilder(
    //                         "โปรโมชั่นยอดนิยม

    // สนใจสมัครสมาชิกขั้นต่ำ 200 บาท รับ
    // โบนัส 30% จากยอดฝากครั้งแรก สูงสุด
    // 500 บาท หรือจะเลือกรับโปรโมชั่น
    // สุดฮอตจากทางเว็บ เช่น

    // 1. สมัคร 1000 บาท ได้รับ หูฟังบลูทูธ TRUT WIRELESS 5.0 TWS 
    // 2. สมัคร 1000 บาท ได้รับ พาวเวอร์แบ๊ง ELOOP E-12 
    // 3. สมัคร 1000 บาท ได้รับ ลำโพง BLUETOOTH IRON MAN
    // 4. สมัคร 1000 บาท ได้รับ บุหรี่ไฟฟ้า DRAG 
    // 5. สมัคร 1000 บาท ได้รับ โทรศัพท์จิ๋ว 
    // 6. สมัคร 500 บาท ได้รับ เสื้อบอล EURO 
    // 7. สมัคร 500 บาท ได้รับ เสื้อฮูด Nike 
    // 8. สมัคร 500 บาท ได้รับ Smart Watch 
    // 9. สมัคร 500 บาท ได้รับ ลำโพง Bluetooth Mini 
    // 10. สมัคร 500 บาท ได้รับ หูฟัง Bluetooth 
    // 11. สมัคร 300 บาท ได้รับ ลำโพงสโมสรฟุตบอลโลก 
    // 12. สมัคร 300 บาท ได้รับ กระเป๋าสะพายข้างลายสโมสรฟุตบอลโลก 
    // 13. สมัคร 300 บาท ได้รับ Game Handle 
    // 14. สมัครฝาก 200 รับโบนัส 30 %

    // **สนใจสมัครโปรโมชั้น คลิก 'สมัครโปรโมชั่น' หรือพิมพ์ 'สมัครโปรโมชั่น'
    // ",
    //                         NULL,
    //                         NULL,
    //                         "md",
    //                         NULL,
    //                         NULL,
    //                         true
    //                     )
    //                 )
    //             )


    //         );


    //         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
    //     } else if ($userMessage == "สมัครโปรโมชั่น" || $userMessage == "สนใจ" || strstr($userMessage,"โปรโมชั่น") == true) {
    //         $textReplyMessage = new BubbleContainerBuilder(
    //             "ltr",
    //             NULL,
    //             NULL,
    //             new BoxComponentBuilder(
    //                 "horizontal",
    //                 array(
    //                     new TextComponentBuilder(
    //                         "สนใจโปรโมชั่นใหนพิมพ์ 'โปร' พร้อมหมายเลขโปรโมชั่นที่ต้องการ

    // [ ตัวอย่าง : โปร2 ]",
    //                         NULL,
    //                         NULL,
    //                         "md",
    //                         NULL,
    //                         NULL,
    //                         true
    //                     )
    //                 )
    //             )


    //         );


    //         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
    //     } else if ($userMessage == "โปร 1" || $userMessage == "โปร1" || $userMessage == "โปรโมชั่น1" || $userMessage == "โปรโมชั่น 1") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 1000 บาท ได้รับ หูฟังบลูทูธ TRUT WIRELESS 5.0 
    // TWS "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 2" || $userMessage == "โปร2" || $userMessage == "โปรโมชั่น2" || $userMessage == "โปรโมชั่น 2") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 1000 บาท ได้รับ พาวเวอร์แบ๊ง ELOOP E-12  "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 3" || $userMessage == "โปร3" || $userMessage == "โปรโมชั่น3" || $userMessage == "โปรโมชั่น 3") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 1000 บาท ได้รับ ลำโพง BLUETOOTH IRON MAN "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 4" || $userMessage == "โปร4" || $userMessage == "โปรโมชั่น4" || $userMessage == "โปรโมชั่น 4") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 1000 บาท ได้รับ บุหรี่ไฟฟ้า DRAG "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 5" || $userMessage == "โปร5" || $userMessage == "โปรโมชั่น5" || $userMessage == "โปรโมชั่น 5") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 1000 บาท ได้รับ โทรศัพท์จิ๋ว "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 6" || $userMessage == "โปร6" || $userMessage == "โปรโมชั่น6" || $userMessage == "โปรโมชั่น 6") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 500 บาท ได้รับ เสื้อบอล EURO "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 7" || $userMessage == "โปร7" || $userMessage == "โปรโมชั่น7" || $userMessage == "โปรโมชั่น 7") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 500 บาท ได้รับ เสื้อฮูด Nike "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 8" || $userMessage == "โปร8" || $userMessage == "โปรโมชั่น8" || $userMessage == "โปรโมชั่น 8") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 500 บาท ได้รับ Smart Watch "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 9" || $userMessage == "โปร9" || $userMessage == "โปรโมชั่น9" || $userMessage == "โปรโมชั่น 9") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 500 บาท ได้รับ ลำโพง Bluetooth Mini "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 10" || $userMessage == "โปร10" || $userMessage == "โปรโมชั่น10" || $userMessage == "โปรโมชั่น 10") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 500 บาท ได้รับ หูฟัง Bluetooth "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 11" || $userMessage == "โปร11" || $userMessage == "โปรโมชั่น11" || $userMessage == "โปรโมชั่น 11") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 300 บาท ได้รับ ลำโพงสโมสรฟุตบอลโลก "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 12" || $userMessage == "โปร12" || $userMessage == "โปรโมชั่น12" || $userMessage == "โปรโมชั่น 12") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 300 บาท ได้รับ กระเป๋าสะพายข้างลายสโมสรฟุตบอล
    // โลก "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 13" || $userMessage == "โปร13" || $userMessage == "โปรโมชั่น13" || $userMessage == "โปรโมชั่น 13") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัคร 300 บาท ได้รับ Game Handle "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "โปร 14" || $userMessage == "โปร14" || $userMessage == "โปรโมชั่น14" || $userMessage == "โปรโมชั่น 14") {
    //         $replyData = new TemplateMessageBuilder(
    //             'Confirm Template',
    //             new ConfirmTemplateBuilder(
    //                 'โปรโมชั่นที่ลูกค้าเลือก คือ

    // " สมัครฝาก 200 รับโบนัส 30 % "

    // ยืนยันการสมัครใช่หรือไม่ ?',
    //                 array(
    //                     new MessageTemplateActionBuilder(
    //                         'ใช่',
    //                         'ใช่'
    //                     ),
    //                     new MessageTemplateActionBuilder(
    //                         'ไม่',
    //                         'ใม่'
    //                     )
    //                 )
    //             )
    //         );
    //     } else if ($userMessage == "ใช่") {
    //         $textReplyMessage = new BubbleContainerBuilder(
    //             "ltr",
    //             NULL,
    //             NULL,
    //             new BoxComponentBuilder(
    //                 "horizontal",
    //                 array(
    //                     new TextComponentBuilder("สมัครเสร็จแจ้งสลีปพร้อมเลขยูส..", NULL, NULL, NULL, NULL, NULL, true)
    //                 )
    //             ),
    //             new BoxComponentBuilder(
    //                 "horizontal",
    //                 array(
    //                     new ButtonComponentBuilder(
    //                         new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
    //                         NULL,
    //                         NULL,
    //                         NULL,
    //                         "primary"
    //                     )
    //                 )
    //             )
    //         );

    //         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
    //     } else if (strstr($userMessage, "user_") == true || strstr($userMessage, "User_") == true) {
    //         $textReplyMessage = new BubbleContainerBuilder(
    //             "ltr",
    //             NULL,
    //             NULL,
    //             new BoxComponentBuilder(
    //                 "horizontal",
    //                 array(
    //                     new TextComponentBuilder(
    //                         "ขอชื่อ-นามสกุล เบอร์โทรลูกค้าด้วยค่ะ

    // ***กรุณากรอกคำนำหน้าชื่อ นาย,นางสาว

    // [ ตัวอย่าง : นายกอ เบอร์ 0859658965 ]",
    //                         NULL,
    //                         NULL,
    //                         "md",
    //                         NULL,
    //                         NULL,
    //                         true
    //                     )
    //                 )
    //             )

    //         );
    //         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
    //     } else if (strstr($userMessage, "นาย") == true || strstr($userMessage, "นางสาว") == true || strstr($userMessage, "เบอร์") == true || strstr($userMessage, "นาง") == true || strstr($userMessage, "เบอร์โทร") == true) {
    //         $textReplyMessage = new BubbleContainerBuilder(
    //             "ltr",
    //             NULL,
    //             NULL,
    //             new BoxComponentBuilder(
    //                 "horizontal",
    //                 array(
    //                     new TextComponentBuilder(
    //                         "กรุณากรอกที่อยู่ด้วยค่ะ

    // *** กรุณากรอกจังหวัดและนำหน้าประโยคด้วย 'ที่อยู่' เพื่อง่ายต่อการจัดส่ง

    // [ ตัวอย่าง : ที่อยู่ 159 หมู่2 ตำบล อำเภอ จังหวัด รหัสไปรษณีย์]",
    //                         NULL,
    //                         NULL,
    //                         "md",
    //                         NULL,
    //                         NULL,
    //                         true
    //                     )
    //                 )
    //             )

    //         );
    //         $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
    //     } else if (strstr($userMessage,"ที่อยู่") == true || strstr($userMessage,"อำเภอ") == true || strstr($userMessage,"อ.") == true || strstr($userMessage,"ตำบล") == true || strstr($userMessage,"ต.") == true
    //     || strstr($userMessage,"จังหวัด") == true || strstr($userMessage,"จ.") == true) {       
    //                 $textReplyMessage = new BubbleContainerBuilder(
    //                     "ltr",
    //                     NULL,
    //                     NULL,
    //                     new BoxComponentBuilder(
    //                         "horizontal",
    //                         array(
    //                             new TextComponentBuilder(
    //                                 "ขอบคุณค่ะ เดี๋ยวทางเราจะดำเนินการส่งของตามที่อยู่นี้นะคะ..",
    //                                 NULL,
    //                                 NULL,
    //                                 "md",
    //                                 NULL,
    //                                 NULL,
    //                                 true
    //                             )
    //                         )
    //                     )


    //                 );
    //                 $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);       
    //     } 
    //     // else {
    //     //         $actionBuilder = array(
    //     //             new MessageTemplateActionBuilder(
    //     //                 'รายละเอียดโปรโมชั่น',
    //     //                 'เรียกดูโปรโมชั่น' 
    //     //             ), 
    //     //             new MessageTemplateActionBuilder(
    //     //                 'สมัครโปรโมชั่น',
    //     //                 'สมัครโปรโมชั่น'
    //     //             )             
    //     //         );
    //     //         $imageUrl = '';
    //     //         $replyData = new TemplateMessageBuilder('Button Template',
    //     //             new ButtonTemplateBuilder(
    //     //                     'เมนูหลัก',
    //     //                     'กรุณาเลือกหัวข้อที่ต้องการ..',
    //     //                     $imageUrl,
    //     //                     $actionBuilder
    //     //             )
    //     //         );              
    //     //     }
    //     } else if ($userImage == null) {
    //     $textReplyMessage = new BubbleContainerBuilder(
    //         "ltr",
    //         NULL,
    //         NULL,
    //         new BoxComponentBuilder(
    //             "horizontal",
    //             array(
    //                 new TextComponentBuilder(
    //                     "กรุณาแจ้งเลขยูสค่ะ..

    // [ รูปแบบ : user_เลขยูสของคุณ ]
    // [ ตัวอย่าง : user_svr96654248 ]",

    //                     NULL,
    //                     NULL,
    //                     "md",
    //                     NULL,
    //                     NULL,
    //                     true
    //                 )
    //             )
    //         )


    //     );


    //     $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
    // }

// }

if(!is_null($events)){
 
    // ถ้า bot ถูก invite เพื่อเข้า Join Event ให้ bot ส่งข้อความใน GROUP ว่าเข้าร่วม GROUP แล้ว
    // if(!is_null($eventJoin)){
    //     $textReplyMessage = "ขอเข้าร่วมด้วยน่ะ $sourceType ID:: ".$sourceId;
    //     $replyData = new TextMessageBuilder($textReplyMessage);                 
    // }
     
    // ถ้า bot ออกจาก สนทนา จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    // if(!is_null($eventLeave)){
 
    // }   
     
    // ถ้า bot ถูกเพื่มเป้นเพื่อน หรือถูกติดตาม หรือ ยกเลิกการ บล็อก
    if(!is_null($eventFollow)){
        $textReplyMessage = "ขอบคุณที่เป็นเพื่อน และติดตามเรา";        
        $replyData = new TextMessageBuilder($textReplyMessage);                 
    }
     
    // ถ้า bot ถูกบล็อก หรือเลิกติดตาม จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    // if(!is_null($eventUnfollow)){
 
    // }       
     
    // ถ้ามีสมาชิกคนอื่น เข้ามาร่วมใน room หรือ group 
    // room คือ สมมติเราคุยกับ คนหนึ่งอยู่ แล้วเชิญคนอื่นๆ เข้ามาสนทนาด้วย จะกลายเป็นห้องใหม่
    // group คือ กลุ่มที่เราสร้างไว้ มีชื่อกลุ่ม แล้วเราเชิญคนอื่นเข้ามาในกลุ่ม เพิ่มร่วมสนทนาด้วย
//     if(!is_null($eventMemberJoined)){
//             $arr_joinedMember = $eventObj->getEventBody();
//             $joinedMember = $arr_joinedMember['joined']['members'][0];
//             if(!is_null($groupId) || !is_null($roomId)){
//                 if($eventObj->isGroupEvent()){
//                     foreach($joinedMember as $k_user=>$v_user){
//                         if($k_user=="userId"){
//                             $joined_userId = $v_user;
//                         }
//                     }                       
//                     $response = $bot->getGroupMemberProfile($groupId, $joined_userId);
//                 }
//                 if($eventObj->isRoomEvent()){
//                     foreach($joinedMember as $k_user=>$v_user){
//                         if($k_user=="userId"){
//                             $joined_userId = $v_user;
//                         }
//                     }                   
//                     $response = $bot->getRoomMemberProfile($roomId, $joined_userId);    
//                 }
//             }else{
//                 $response = $bot->getProfile($userId);
//             }
//             if ($response->isSucceeded()) {
//                 $userData = $response->getJSONDecodedBody(); // return array     
//                 // $userData['userId']
//                 // $userData['displayName']
//                 // $userData['pictureUrl']
//                 // $userData['statusMessage']
//                 $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];     
//             }else{
//                 $textReplyMessage = 'สวัสดีครับ ยินดีต้อนรับ';
//             }
// //        $textReplyMessage = "ยินดีต้อนรับกลับมาอีกครั้ง ".json_encode($joinedMember);
//         $replyData = new TextMessageBuilder($textReplyMessage);                     
//     }
     
    // ถ้ามีสมาชิกคนอื่น ออกจากก room หรือ group จะไม่สามารถส่งข้อความกลับได้ เนื่องจากไม่มี replyToken
    // if(!is_null($eventMemberLeft)){
     
    // }   
 
    // ถ้ามีกาาเชื่อมกับบัญชี LINE กับระบบสมาชิกของเว็บไซต์เรา
    // if(!is_null($eventAccountLink)){
    //     // หลักๆ ส่วนนี้ใช้สำรหบัเพิ่มความภัยในการเชื่อมบัญตี LINE กับระบบสมาชิกของเว็บไซต์เรา 
    //     $textReplyMessage = "AccountLink ทำงาน ".$replyToken." Nonce: ".$eventObj->getNonce();
    //     $replyData = new TextMessageBuilder($textReplyMessage);                         
    // }
             
    // ถ้าเป็น Postback Event
    // if(!is_null($eventPostback)){
    //     $dataPostback = NULL;
    //     $paramPostback = NULL;
    //     // แปลงข้อมูลจาก Postback Data เป็น array
    //     parse_str($eventObj->getPostbackData(),$dataPostback);
    //     // ดึงค่า params กรณีมีค่า params
    //     $paramPostback = $eventObj->getPostbackParams();
    //     // ทดสอบแสดงข้อความที่เกิดจาก Postaback Event
    //     $textReplyMessage = "ข้อความจาก Postback Event Data = ";        
    //     $textReplyMessage.= json_encode($dataPostback);
    //     $textReplyMessage.= json_encode($paramPostback);
    //     $replyData = new TextMessageBuilder($textReplyMessage);     
    // }
    // ถ้าเป้น Message Event 
    if(!is_null($eventMessage)){
         
        // สร้างตัวแปรเก็ยค่าประเภทของ Message จากทั้งหมด 7 ประเภท
        $typeMessage = $eventObj->getMessageType();  
        //  text | image | sticker | location | audio | video | file  
        // เก็บค่า id ของข้อความ
        $idMessage = $eventObj->getMessageId();          
        // ถ้าเป็นข้อความ
        if($typeMessage=='text'){
            $userMessage = $eventObj->getText(); // เก็บค่าข้อความที่ผู้ใช้พิมพ์
        }
        // ถ้าเป็น image
        if($typeMessage=='image'){
 
        }               
        // ถ้าเป็น audio
 
        if($typeMessage=='audio'){
 
        }       
        // ถ้าเป็น video
        if($typeMessage=='video'){
 
        }   
        // ถ้าเป็น file
        if($typeMessage=='file'){
            $FileName = $eventObj->getFileName();
            $FileSize = $eventObj->getFileSize();
        }               
        // ถ้าเป็น image หรือ audio หรือ video หรือ file และต้องการบันทึกไฟล์
//         if(preg_match('/image|audio|video|file/',$typeMessage)){            
//             $responseMedia = $bot->getMessageContent($idMessage);
//             if ($responseMedia->isSucceeded()) {
//                 // คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary 
//                 // เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
//                 $dataBinary = $responseMedia->getRawBody(); // return binary
//                 // ดึงข้อมูลประเภทของไฟล์ จาก header
//                 $fileType = $responseMedia->getHeader('Content-Type');    
//                 switch ($fileType){
//                     case (preg_match('/^application/',$fileType) ? true : false):
// //                      $fileNameSave = $FileName; // ถ้าต้องการบันทึกเป็นชื่อไฟล์เดิม
//                         $arr_ext = explode(".",$FileName);
//                         $ext = array_pop($arr_ext);
//                         $fileNameSave = time().".".$ext;                            
//                         break;                  
//                     case (preg_match('/^image/',$fileType) ? true : false):
//                         list($typeFile,$ext) = explode("/",$fileType);
//                         $ext = ($ext=='jpeg' || $ext=='jpg')?"jpg":$ext;
//                         $fileNameSave = time().".".$ext;
//                         break;
//                     case (preg_match('/^audio/',$fileType) ? true : false):
//                         list($typeFile,$ext) = explode("/",$fileType);
//                         $fileNameSave = time().".".$ext;                        
//                         break;
//                     case (preg_match('/^video/',$fileType) ? true : false):
//                         list($typeFile,$ext) = explode("/",$fileType);
//                         $fileNameSave = time().".".$ext;                                
//                         break;                                                      
//                 }
//                 $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
//                 $botDataUserFolder = $botDataFolder.$userId; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
//                 if(!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
//                     mkdir($botDataUserFolder, 0777, true);
//                 }   
//                 // กำหนด path ของไฟล์ที่จะบันทึก
//                 $fileFullSavePath = $botDataUserFolder.'/'.$fileNameSave;
// //              file_put_contents($fileFullSavePath,$dataBinary); // เอา comment ออก ถ้าต้องการทำการบันทึกไฟล์
//                 $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว $fileNameSave";
//                 $replyData = new TextMessageBuilder($textReplyMessage);
// //              $failMessage = json_encode($fileType);              
// //              $failMessage = json_encode($responseMedia->getHeaders());
//                 $replyData = new TextMessageBuilder($failMessage);                      
//             }else{
//                 $failMessage = json_encode($idMessage.' '.$responseMedia->getHTTPStatus() . ' ' . $responseMedia->getRawBody());
//                 $replyData = new TextMessageBuilder($failMessage);          
//             }
//         }
//         // ถ้าเป็น sticker
//         if($typeMessage=='sticker'){
//             $packageId = $eventObj->getPackageId();
//             $stickerId = $eventObj->getStickerId();
//         }
//         // ถ้าเป็น location
//         if($typeMessage=='location'){
//             $locationTitle = $eventObj->getTitle();
//             $locationAddress = $eventObj->getAddress();
//             $locationLatitude = $eventObj->getLatitude();
//             $locationLongitude = $eventObj->getLongitude();
//         }       
         
         
        switch ($typeMessage){ // กำหนดเงื่อนไขการทำงานจาก ประเภทของ message
            case 'text':  // ถ้าเป็นข้อความ
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                        case "ot":
                            // ทำอื่นๆ 
                            break;
                        case "qr":
                            $postback = new PostbackTemplateActionBuilder(
                                'Postback', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    'action'=>'buy',
                                    'item'=>100
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                 'Buy'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            );
                            // $txtMsg = new MessageTemplateActionBuilder(
                            //     'ข้อความภาษาไทย',// ข้อความแสดงในปุ่ม
                            //     'thai' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            // );
                            // $datetimePicker = new DatetimePickerTemplateActionBuilder(
                            //     'Datetime Picker', // ข้อความแสดงในปุ่ม
                            //     http_build_query(array(
                            //         'action'=>'reservation',
                            //         'person'=>5
                            //     )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                            //     'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                            //     substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                            //     substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
                            //     substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
                            // );
 
                            $quickReply = new QuickReplyMessageBuilder(
                                array(
                                    new QuickReplyButtonBuilder(new LocationTemplateActionBuilder('Location')),
                                    new QuickReplyButtonBuilder(new CameraTemplateActionBuilder('Camera')),
                                    new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('Camera roll')),
                                    new QuickReplyButtonBuilder($postback),
                                    new QuickReplyButtonBuilder($datetimePicker),
                                    new QuickReplyButtonBuilder(
                                        $txtMsg,
                                        "https://www.ninenik.com/images/ninenik_page_logo.png"
                                    ),
                                )
                            );
                            $textReplyMessage = "ส่งพร้อม quick reply ";
                            $replyData = new TextMessageBuilder($textReplyMessage,$quickReply);                             
                            break;                                                                         
                    // default:
                    //     $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    //     $replyData = new TextMessageBuilder($textReplyMessage);         
                    //     break;                                      
                }
                break;                                                  
            default:
                if(!is_null($replyData)){
                     
                }else{
                    // กรณีทดสอบเงื่อนไขอื่นๆ ผู้ใช้ไม่ได้ส่งเป็นข้อความ
                    $textReplyMessage = 'สวัสดีครับ คุณ '.$typeMessage;         
                    $replyData = new TextMessageBuilder($textReplyMessage);         
                }
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


