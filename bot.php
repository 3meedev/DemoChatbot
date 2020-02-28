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
if (!is_null($events)) {
    
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
                    break;
                case "m":
                    $placeName = "ที่ตั้งร้าน";
                    $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                    $latitude = 13.780401863217657;
                    $longitude = 100.61141967773438;
                    $locationMessage = new LocationMessageBuilder($placeName, $placeAddress, $latitude, $longitude);
                    break;
                case "แจ้งปัญหา":                   
                    $actionBuilder = array(
                        new MessageTemplateActionBuilder(
                            'ลืมรหัสผ่าน',
                            'กรอกชื่อผู้ใช้ 1'
                        ),
                        new MessageTemplateActionBuilder(
                            'ยอดเงินไม่เข้า',
                            'กรอกชื่อผู้ใช้ 2'
                        ),
                        new MessageTemplateActionBuilder(
                            'อื่นๆ',
                            'กรอกชื่อผู้ใช้ 3'
                        ),
                        new UriTemplateActionBuilder(
                            'รายละเอียดเพิ่มเติม',
                            'https://www.ninenik.com'
                        ),                     
                    );
                    $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                    $replyData = new TemplateMessageBuilder(
                        'แจ้งปัญหา',
                        new ButtonTemplateBuilder(
                            'แจ้งปัญหา',
                            'กรุณาเลือกหัวข้อที่ต้องการ',
                            $imageUrl,
                            $actionBuilder 
                        )
                    );
                    break;
                case "ติดต่อ":
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

$response = $bot->replyMessage($replyToken, $replyData);


echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
