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

use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;

use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;


// ----------------------------------------------------------------------------------------------------- แบบ Template Message

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


$content = file_get_contents('php://input');



$events = json_decode($content, true);
if (!is_null($events)) {

    $replyToken = $events['events'][0]['replyToken'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = $events['events'][0]['message']['text'];
    $userMessage = strtolower($userMessage);

    if (isset($arrayJson['events'][0]['source']['userId'])) {
        $id = $arrayJson['events'][0]['source']['userId'];
    } else if (isset($arrayJson['events'][0]['source']['groupId'])) {
        $id = $arrayJson['events'][0]['source']['groupId'];
    } else if (isset($arrayJson['events'][0]['source']['room'])) {
        $id = $arrayJson['events'][0]['source']['room'];
    };

    switch ($typeMessage) {
        case 'text':
            switch ($userMessage) {
                case "สวัสดี":
                    $arrayPostData['to'] = $id;
                    $arrayPostData['messages'][0]['type'] = "text";
                    $arrayPostData['messages'][0]['text'] = "สวัสดีจ้าาา";
                    $arrayPostData['messages'][1]['type'] = "sticker";
                    $arrayPostData['messages'][1]['packageId'] = "2";
                    $arrayPostData['messages'][1]['stickerId'] = "34";
                    pushMsg($arrayHeader, $arrayPostData);
                    break;
                case "แจ้งปัญหา":
                    $actionBuilder = array(
                        new MessageTemplateActionBuilder(
                            'ปัญหาที่ 1',
                            'รายละเอียดที่ 1'
                        ),
                        new MessageTemplateActionBuilder(
                            'ปัญหาที่ 2',
                            'รายละเอียดที่ 2'
                        ),
                        new MessageTemplateActionBuilder(
                            'ปัญหาที่ 3',
                            'รายละเอียดที่ 3'
                        ),
                        new UriTemplateActionBuilder(
                            'รายละเอียดเพิ่มเติม',
                            'https://www.google.com/?hl=th'
                        ),
                    );
                    $imageUrl = 'https://writerlisamason.com/wp-content/uploads/2019/02/4.jpg';
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
                case "เปิดบัญชี":
                    $actionBuilder = array(
                        new MessageTemplateActionBuilder(
                            'รายละเอียดที่ 1',
                            'ข้อมูลที่ 1'
                        ),
                        new MessageTemplateActionBuilder(
                            'รายละเอียดที่ 2',
                            'ข้อมูลที่ 2'
                        ),
                        new MessageTemplateActionBuilder(
                            'รายละเอียดที่ 3',
                            'ข้อมูลที่ 3'
                        ),
                        new UriTemplateActionBuilder(
                            'รายละเอียดเพิ่มเติม',
                            'https://www.google.com/?hl=th'
                        ),
                    );
                    $imageUrl = 'https://lh3.googleusercontent.com/proxy/wn8c-FyKoyfCBsZ3uv5qVc79WzoqF3a8Kjy8P7SVLe_FPox9TQEdbYoEDP4Lac66hh4o2XIhLhP0vteCQOkZzeFgJId2h4NTtaDbiFHd48rLxGbbg0-PO_yw8gjdMIUyXCnf';
                    $replyData = new TemplateMessageBuilder(
                        'เปิดบัญชี',
                        new ButtonTemplateBuilder(
                            'เปิดบัญชี',
                            'กรุณาเลือกหัวข้อที่ต้องการ',
                            $imageUrl,
                            $actionBuilder
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

$response = $bot->replyMessage($id, $replyData);


echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

function pushMsg($arrayHeader, $arrayPostData)
{
    $strUrl = "https://api.line.me/v2/bot/message/push";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
}
exit;

// ----------------------------------------------------------------------------------------------------------------------------

// -------------------------------------------------------------------------------------------------------------------------- แบบ Flex Message

// $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
// $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


// $content = file_get_contents('php://input');



// $events = json_decode($content, true);
// if (!is_null($events)) {

//     $replyToken = $events['events'][0]['replyToken'];
//     $typeMessage = $events['events'][0]['message']['type'];
//     $userMessage = $events['events'][0]['message']['text'];
//     $userMessage = strtolower($userMessage);

//     switch ($typeMessage) {
//         case 'text':
//             switch ($userMessage) {

//                 case "ติดต่อ":
//                     $textReplyMessage = new BubbleContainerBuilder(
//                         "ltr",  // กำหนด NULL หรือ "ltr" หรือ "rtl"
//                         NULL,NULL,
//                         new BoxComponentBuilder(
//                             "horizontal",
//                             array(
//                                 new TextComponentBuilder("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed 
//                                 do eiusmod tempor incididunt ut labore et dolore magna aliqua.",NULL,NULL,NULL,NULL,NULL,true)
//                             )
//                         ),
//                         new BoxComponentBuilder(
//                             "horizontal",
//                             array(
//                                 new ButtonComponentBuilder(
//                                     new UriTemplateActionBuilder("GO","http://niik.in"),
//                                     NULL,NULL,NULL,"primary"
//                                 )
//                             )
//                         )
//                     );
             
//             $replyData = new FlexMessageBuilder("Flex",$textReplyMessage);
//                     break;

//                 default:
//                     $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
//                     $replyData = new TextMessageBuilder($textReplyMessage);
//                     break;
//             }
//             break;
//         default:
//             $textReplyMessage = new BubbleContainerBuilder(
//                 "ltr",
//                 NULL,
//                 NULL,
//                 new BoxComponentBuilder(
//                     "vertical",
//                     array(
//                         new TextComponentBuilder("hello"),
//                         new TextComponentBuilder("world")
//                     )
//                 )
//             );
//             $replyData = new FlexMessageBuilder("This is a Flex Message", $textReplyMessage);
//     }
// }

// $response = $bot->replyMessage($replyToken, $replyData);


// echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

// ----------------------------------------------------------------------------------------------------------------------------
