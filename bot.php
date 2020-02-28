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
if (!is_null($events)) {
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];

    $imageMapUrl = 'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTEqZxvgDbb-NmneU6ym3CTBNmoPKbvVWbKy1IFm6wox3NUbrXB';
    $replyData = new ImagemapMessageBuilder(
        $imageMapUrl, // ส่วนของการกำหนด url รูป
        'This is Imagemap', // ส่วนของการกำหนดหัวเรื่องว่าเกี่ยวกับอะไร
        new BaseSizeBuilder(699, 1040), // กำหนดขนาดของรูป (สูง,กว้าง)
        array(
            new ImagemapMessageActionBuilder(
                'test image map',
                new AreaBuilder(0, 0, 520, 699)
            ),
            new ImagemapUriActionBuilder(
                'http://www.ninenik.com',
                new AreaBuilder(520, 0, 520, 699)
            )
        )
    );
    switch ($typeMessage) {
        case 'text':
            switch ($userMessage) {
                case "A":
                    $textReplyMessage = "คุณพิมพ์ A";
                    break;
                case "B":
                    $textReplyMessage = "คุณพิมพ์ B";
                    break;

                default:
                    $textReplyMessage = " คุณไม่ได้พิมพ์ A และ B";
                    break;
            }
            break;
        default:
            $textReplyMessage = json_encode($events);
            break;
    }
}


$textMessageBuilder = new TextMessageBuilder($textReplyMessage);

//l ส่วนของคำสั่งตอบกลับข้อความ
$response = $bot->replyMessage($replyToken, $textMessageBuilder);

// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
