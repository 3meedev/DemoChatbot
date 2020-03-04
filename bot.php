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
                'Copa69 สวัสดีครับ',
                'สนใจสมัครสมาชิกขั้นต่ำ 200 บาท รับโบนัส30%จากยอดฝากครั้งแรกสูงสุด 500 บาทครับ หรือจะเลือกรับโปรโมชั่นสุดฮอตจากทางเว็บ เช่น
                1.หูฟังบลูทูธ TRUT WIRELESS 5.0 TWS สมัคร1000 บาท
                2.พาวเวอร์แบ๊ง ELOOP E-12  สมัคร1000 บาท
                3.ลำโพง BLUETOOTH IRON MAN สมัคร1000 บาท
                4.บุหรี่ไฟฟ้า DRAG สมัคร1000 บาท
                5.โทรศัพท์จิ๋ว สมัคร 1000 บาท
                6.เสื้อบอล EURO สมัคร 500 บาท
                7.เสื้อฮูด Nike สมัคร 500 บาท
                8.Smart Watch สมัคร500 บาท
                9.ลำโพง Bluetooth Mini สมัคร 500 บาท
                10.หูฟัง Bluetooth สมัคร 500 บาท
                11.ลำโพงสโมสรฟุตบอลโลก สมัคร 300 บาท
                12.กระเป๋าสะพายข้างลายสโมสรฟุตบอลโลก สมัคร 300 บาท
                13.Game Handle สมัคร 300 บาท
                14.สมัครฝาก200 รับโบนัส 30 %
                เล่นได้ทุกอย่างในยูสเดียว บอล หวย มวย คาสิโน เกม ฝากอัตโนมัติ 30 วินาที ถอนไม่เกิน 1 นาที ทำเทิร์นเดียว 1.5 ก็สามารถถอนได้เลย ขั้นต่ำ 100 บาท',
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
