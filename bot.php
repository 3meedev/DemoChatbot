<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'vendor/autoload.php';



include 'bot_settings.php';

use LINE\LINEBot;

use LINE\LINEBot\HTTPClient\CurlHTTPClient;

use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;


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

    // $findme   = 'บัญชี';
    // $pos = strpos($userMessage, "บัญชี");

    if (strpos($userMessage, "บัญชี") == true) {
        $actionBuilder = array(
            new UriTemplateActionBuilder(
                'รายละเอียดเพิ่มเติม',
                'https://www.google.com/?hl=th'
            )
        );
        $imageUrl = 'https://lh3.googleusercontent.com/proxy/wn8c-FyKoyfCBsZ3uv5qVc79WzoqF3a8Kjy8P7SVLe_FPox9TQEdbYoEDP4Lac66hh4o2XIhLhP0vteCQOkZzeFgJId2h4NTtaDbiFHd48rLxGbbg0-PO_yw8gjdMIUyXCnf';
        $replyData = new TemplateMessageBuilder(
            'Copa69',
            new ButtonTemplateBuilder(
                'เปิดบัญชี',
                'กรุณาเลือกหัวข้อที่ต้องการ',
                $imageUrl,
                $actionBuilder
            )
        );
    }

    

    // if (strpos($userMessage, "บัญชี") == true) {
    //     $actionBuilder = array(
    //         new MessageTemplateActionBuilder(
    //             'รายละเอียดที่ 1',
    //             'ข้อมูลที่ 1'
    //         ),
    //         new MessageTemplateActionBuilder(
    //             'รายละเอียดที่ 2',
    //             'ข้อมูลที่ 2'
    //         ),
    //         new MessageTemplateActionBuilder(
    //             'รายละเอียดที่ 3',
    //             'ข้อมูลที่ 3'
    //         ),
    //         new UriTemplateActionBuilder(
    //             'รายละเอียดเพิ่มเติม',
    //             'https://www.google.com/?hl=th'
    //         ),
    //     );
    //     $imageUrl = 'https://lh3.googleusercontent.com/proxy/wn8c-FyKoyfCBsZ3uv5qVc79WzoqF3a8Kjy8P7SVLe_FPox9TQEdbYoEDP4Lac66hh4o2XIhLhP0vteCQOkZzeFgJId2h4NTtaDbiFHd48rLxGbbg0-PO_yw8gjdMIUyXCnf';
    //     $replyData = new TemplateMessageBuilder(
    //         'เปิดบัญชี',
    //         new ButtonTemplateBuilder(
    //             'เปิดบัญชี',
    //             'กรุณาเลือกหัวข้อที่ต้องการ',
    //             $imageUrl,
    //             $actionBuilder
    //         )
    //     );
    // }
    if (strpos($userMessage, "ปัญหา") == true) {
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
    }
    if ($userMessage == "รายละเอียดที่ 1") {
        $actionBuilder = array(
            new MessageTemplateActionBuilder(
                'เมนูที่ 1',
                'เมนูที่ 1'
            ),
            new MessageTemplateActionBuilder(
                'เมนูที่ 2',
                'เมนูที่ 2'
            ),
        );
        $imageUrl = null;
        $replyData = new TemplateMessageBuilder(
            'รายละเอียดที่ 1',
            new ButtonTemplateBuilder(
                'รายละเอียดที่ 1',
                'รายละเอียดของรายละเอียดที่ 1',
                $imageUrl,
                $actionBuilder
            )
        );
    }
}

$response = $bot->replyMessage($replyToken, $replyData);


echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
