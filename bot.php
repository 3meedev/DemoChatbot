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
use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\Constant\Flex\ComponentIconSize;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentButtonHeight;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentGravity;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BlockStyleBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\FillerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;


// ----------------------------------------------------------------------------------------------------- แบบ Template Message

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


$content = file_get_contents('php://input');
$count = 0;


$events = json_decode($content, true);


$replyToken = $events['events'][0]['replyToken'];
$typeMessage = $events['events'][0]['message']['type'];
$userMessage = $events['events'][0]['message']['text'];
$userID = $events['events'][0]['source']['userId'];
$userMessage = strtolower($userMessage);



if ($userMessage != null) {
    if ($userMessage == "เรียกดูโปรโมชั่น") {
        $textReplyMessage = new BubbleContainerBuilder(
            "ltr",
            NULL,
            NULL,
            new BoxComponentBuilder(
                "horizontal",
                array(
                    new TextComponentBuilder(
                        "Copa69 สวัสดีครับ 

สนใจสมัครสมาชิกขั้นต่ำ 200 บาท รับ
โบนัส 30% จากยอดฝากครั้งแรกสูงสุด
500 บาท หรือจะเลือกรับโปรโมชั่น
สุดฮอตจากทางเว็บ เช่น

1.หูฟังบลูทูธ TRUT WIRELESS 5.0 TWS สมัคร 1000 บาท
2.พาวเวอร์แบ๊ง ELOOP E-12 สมัคร 1000 บาท
3.ลำโพง BLUETOOTH IRON MAN สมัคร 1000 บาท
4.บุหรี่ไฟฟ้า DRAG สมัคร 1000 บาท
5.โทรศัพท์จิ๋ว สมัคร 1000 บาท
6.เสื้อบอล EURO สมัคร 500 บาท
7.เสื้อฮูด Nike สมัคร 500 บาท
8.Smart Watch สมัคร 500 บาท
9.ลำโพง Bluetooth Mini สมัคร 500 บาท
10.หูฟัง Bluetooth สมัคร 500 บาท
11.ลำโพงสโมสรฟุตบอลโลก สมัคร 300 บาท
12.กระเป๋าสะพายข้างลายสโมสรฟุตบอลโลก สมัคร 300 บาท
13.Game Handle สมัคร 300 บาท
14.สมัครฝาก 200 รับโบนัส 30 %

เล่นได้ทุกอย่างในยูสเดียวบอล หวย มวย คาสิโน เกม ฝากอัตโนมัติ 30 วินาที ถอนไม่เกิน 1 นาทีทำเทิร์นเดียว 1.5 ก็สามารถถอนได้เลย ขั้นต่ำ 100 บาท
",
                        NULL,
                        NULL,
                        "md",
                        NULL,
                        NULL,
                        true
                    )
                )
            )


        );
        
        
                 
        $replyData = new FlexMessageBuilder("Flex", $textReplyMessage);
        
        $actionBuilder = array(
            new MessageTemplateActionBuilder(
                'เรียกดูโปรโมชั่น',
                'เรียกดูโปรโมชั่น'
            ),
            new MessageTemplateActionBuilder(
                'สมัครโปรโมชั่น',
                'สมัครโปรโมชั่น'
            )
        );
        $replyData1 = new TemplateMessageBuilder(
            'เปิดบัญชี',
            new ButtonTemplateBuilder(
                'เปิดบัญชี',
                'กรุณาเลือกหัวข้อที่ต้องการ',
                $imageUrl,
                $actionBuilder
            )
        );       
        
    } else {
        $actionBuilder = array(
            new MessageTemplateActionBuilder(
                'เรียกดูโปรโมชั่น',
                'เรียกดูโปรโมชั่น'
            ),
            new MessageTemplateActionBuilder(
                'สมัครโปรโมชั่น',
                'สมัครโปรโมชั่น'
            )
        );
        $imageUrl = '';
        $replyData = new TemplateMessageBuilder(
            'เปิดบัญชี',
            new ButtonTemplateBuilder(
                'เปิดบัญชี',
                'กรุณาเลือกหัวข้อที่ต้องการ',
                $imageUrl,
                $actionBuilder
            )
        );
    }
}

      
             
$response = $bot->replyMessage($replyToken, $replyData, $replyData1);




echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
