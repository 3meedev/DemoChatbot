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
//                         new ButtonComponentBuilder(
//                             new UriTemplateActionBuilder("Primary style button","http://niik.in"),
//                             NULL,NULL,NULL,"primary"
//                         ),
//                         new ButtonComponentBuilder(
//                             new UriTemplateActionBuilder("Secondary  style button","http://niik.in"),
//                             NULL,NULL,NULL,"secondary"
//                         ),          
//                         new ButtonComponentBuilder(
//                             new UriTemplateActionBuilder("Link  style button","http://niik.in"),
//                             NULL,NULL,NULL,"link"
//                         ),                                  
//                         0,"md"                        
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

// ----------------------------------------------------------------------------------------------------------------------------

// -------------------------------------------------------------------------------------------------------------------------- แบบ Flex Message

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
                
                case "ติดต่อ":
                    $textReplyMessage = new BubbleContainerBuilder(
                        "ltr",  
                        NULL,NULL,                        
                        new BoxComponentBuilder(
                            "vertical",
                            array(
                                new ButtonComponentBuilder(
                                    new TextComponentBuilder("ติดต่อที่ 1","รายละเอียดที่ 1"),
                                    NULL,NULL,NULL,"primary"
                                ),
                                new ButtonComponentBuilder(
                                    new TextComponentBuilder("ติดต่อที่ 2","รายละเอียดที่ 2"),
                                    NULL,NULL,NULL,"secondary"
                                ),          
                                new ButtonComponentBuilder(
                                    new UriTemplateActionBuilder("รายละเอียดเพิ่มเติม","https://www.google.com/"),
                                    NULL,NULL,NULL,"link"
                                ),                                  
                            ),
                            0,"md"
                        ) 
                    );      
             
            $replyData = new FlexMessageBuilder("Flex",$textReplyMessage);
                    break;

                case "a":
                    $textReplyMessage = new BubbleContainerBuilder(
                        "ltr",  // กำหนด NULL หรือ "ltr" หรือ "rtl"
                        NULL,NULL,
                        new BoxComponentBuilder(
                            "vertical",
                            array(                                
                                new TextComponentBuilder("Primary style button","http://niik.in"),                                   
                                
                                new ButtonComponentBuilder(
                                    new UriTemplateActionBuilder("Secondary  style button","http://niik.in"),
                                    NULL,NULL,NULL,"secondary"
                                ),          
                                new ButtonComponentBuilder(
                                    new UriTemplateActionBuilder("Link  style button","http://niik.in"),
                                    NULL,NULL,NULL,"link"
                                ),                                  
                            ),
                            0,"md"
                        )
                    );      
             
            $replyData = new FlexMessageBuilder("Flex",$textReplyMessage);
        break;
        case "b":
            $textReplyMessage = new BubbleContainerBuilder(
                "ltr",
                NULL,NULL,
                new BoxComponentBuilder(
                    "vertical",
                    array(
                        new ButtonComponentBuilder(
                            new TextComponentBuilder("Primary style button","dsfsdfsdf"),
                            NULL,NULL,NULL,"primary"
                        ),
                        new ButtonComponentBuilder(
                            new TextComponentBuilder("Secondary  style button","sdfsdfsdf"),
                            NULL,NULL,NULL,"secondary"
                        ),         
                    ),
                    0,"md",                    
                ),
                new BoxComponentBuilder(
                    "vertical",                    
                    array(                         
                        new ButtonComponentBuilder(
                            new UriTemplateActionBuilder("Link  style button","http://niik.in"),
                            NULL,NULL,NULL,"link"
                        ),                                  
                    ),
                    0,"md"
                )
            );      
     
    $replyData = new FlexMessageBuilder("Flex",$textReplyMessage);
break;
                default:
                    $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    $replyData = new TextMessageBuilder($textReplyMessage);
                    break;
            }
            break;
        default:
        $textReplyMessage = new BubbleContainerBuilder(
            "ltr",NULL,NULL,
            new BoxComponentBuilder(
                "vertical",
                array(
                    new TextComponentBuilder("hello"),
                    new TextComponentBuilder("world")
                )
            )
        );
        $replyData = new FlexMessageBuilder("This is a Flex Message",$textReplyMessage);
    }
}

$response = $bot->replyMessage($replyToken, $replyData);


echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

// ----------------------------------------------------------------------------------------------------------------------------
