<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include composer autoload
include 'vendor/autoload.php';


// การตั้งเกี่ยวกับ bot
include 'bot_settings.php';

// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");

///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
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


// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');

// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);
$events = json_decode($content, true);
if (!is_null($events)) {
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];
    $userMessage = strtolower($userMessage);
    switch ($typeMessage) {
        case 'text':
            switch ($userMessage) {
                case "m":
                    $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                    $textMessage = new TextMessageBuilder($textReplyMessage);

                    $placeName = "ที่ตั้งร้าน";
                    $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                    $latitude = 13.780401863217657;
                    $longitude = 100.61141967773438;
                    $locationMessage = new LocationMessageBuilder($placeName, $placeAddress, $latitude, $longitude);

                    $multiMessage = new MultiMessageBuilder;
                    $multiMessage->add($textMessage);
                    $multiMessage->add($imageMessage);
                    $multiMessage->add($locationMessage);
                    $replyData = $multiMessage;
                    break;
                case "t_b":
                    // กำหนด action 4 ปุ่ม 4 ประเภท
                    $actionBuilder = array(
                        new MessageTemplateActionBuilder(
                            'Message Template', // ข้อความแสดงในปุ่ม
                            'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),
                        new UriTemplateActionBuilder(
                            'Uri Template', // ข้อความแสดงในปุ่ม
                            'https://www.ninenik.com'
                        ),
                        new DatetimePickerTemplateActionBuilder(
                            'Datetime Picker', // ข้อความแสดงในปุ่ม
                            http_build_query(array(
                                'action' => 'reservation',
                                'person' => 5
                            )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                            'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                            substr_replace(date("Y-m-d H:i"), 'T', 10, 1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                            substr_replace(date("Y-m-d H:i", strtotime("+5 day")), 'T', 10, 1), //วันที่ เวลา มากสุดที่เลือกได้
                            substr_replace(date("Y-m-d H:i"), 'T', 10, 1) //วันที่ เวลา น้อยสุดที่เลือกได้
                        ),
                        new PostbackTemplateActionBuilder(
                            'Postback', // ข้อความแสดงในปุ่ม
                            http_build_query(array(
                                'action' => 'buy',
                                'item' => 100
                            )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                            'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                        ),
                    );
                    $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                    $replyData = new TemplateMessageBuilder(
                        'Button Template',
                        new ButtonTemplateBuilder(
                            'button template builder', // กำหนดหัวเรื่อง
                            'Please select', // กำหนดรายละเอียด
                            $imageUrl, // กำหนด url รุปภาพ
                            $actionBuilder  // กำหนด action object
                        )
                    );
                    break;
                case "tm":
                    $replyData = new TemplateMessageBuilder(
                        'Confirm Template',
                        new ConfirmTemplateBuilder(
                            'Confirm template builder',
                            array(
                                new MessageTemplateActionBuilder(
                                    'Yes',
                                    'Text Yes'
                                ),
                                new MessageTemplateActionBuilder(
                                    'No',
                                    'Text NO'
                                )
                            )
                        )
                    );
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
//l ส่วนของคำสั่งตอบกลับข้อความ
$response = $bot->replyMessage($replyToken, $replyData);

// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
