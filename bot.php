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
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;


// ----------------------------------------------------------------------------------------------------- แบบ Template Message

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


$content = file_get_contents('php://input');
$count = 0;


$events = json_decode($content, true);


$replyToken = $events['events'][0]['replyToken'];
$typeMessage = $events['events'][0]['message']['type'];
$typeMessageImage = $events['events'][0]['image']['image'];
$userImage = $events['events'][0]['image'];
$userMessage = $events['events'][0]['message']['text'];
$userID = $events['events'][0]['source']['userId'];
$userMessage = strtolower($userMessage);

// ----------------------------------------------------------------------------------------- TextAll

$textToPromotion = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
                        โปรโมชั่น

พิมพ์ p ตามด้วยหัวข้อที่ต้องการ เช่น p1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. มีโปรโมชั่นอะไรบ้าง
2. ถ้ารับโปรโมชั่น ต้องทำเทิร์นเท่าไหร่
3. ถ้าไม่รับโบนัส จะต้องทำเทิร์นมั้ย
4. มีเครดิตฟรีมั้ย
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textPromotion1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
มีโปรโมชั่นอะไรบ้าง ?
___________________________________

ตอนนี้มีโปรโมชั่น 30% จากยอดฝาก 
หรือเลือกรับโปรโมชัั่นพร้อมของแถม 

1. สมัคร 1000 บาท ได้รับ หูฟังบลูทูธ TRUT WIRELESS 5.0 TWS 
2. สมัคร 1000 บาท ได้รับ พาวเวอร์แบ๊ง ELOOP E-12 
3. สมัคร 1000 บาท ได้รับ ลำโพง BLUETOOTH IRON MAN
4. สมัคร 1000 บาท ได้รับ บุหรี่ไฟฟ้า DRAG 
5. สมัคร 1000 บาท ได้รับ โทรศัพท์จิ๋ว 
6. สมัคร 500 บาท ได้รับ เสื้อบอล EURO 
7. สมัคร 500 บาท ได้รับ เสื้อฮูด Nike 
8. สมัคร 500 บาท ได้รับ Smart Watch 
9. สมัคร 500 บาท ได้รับ ลำโพง Bluetooth Mini 
10. สมัคร 500 บาท ได้รับ หูฟัง Bluetooth 
11. สมัคร 300 บาท ได้รับ ลำโพงสโมสรฟุตบอลโลก 
12. สมัคร 300 บาท ได้รับ กระเป๋าสะพายข้างลายสโมสรฟุตบอลโลก 
13. สมัคร 300 บาท ได้รับ Game Handle 
14. สมัครฝาก 200 รับโบนัส 30 %
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textPromotion2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
ถ้ารับโปรโมชั่นต้องทำเทิร์นเท่าไหร่ ?
___________________________________

ทุกโปรทำเทิร์น 1.5 ค่ะ เช่น ฝาก200 
(ต้องมียอดเล่นได้หรือเสียประมาณ 
300) ก็ถอนได้แล้วค่ะ เล่นได้ทุก
อย่าง เช่น คาสิโน เกมส์ แทง บอล
อื่นๆ เป็นต้นค่ะ
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textPromotion3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
ถ้าไม่รับโบนัสจะต้องทำเทิร์นมั้ย ?
___________________________________

ถ้าไม่รับโบนัสก้ทำเทริน 1.5 เหมือนกันคะ
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textPromotion4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
มีเครดิตฟรีมั้ย ?
___________________________________

เงินที่สมัครสามารถนำไปเล่นในเว็บได้
เลยและได้ของแถมด้วยนะคะ 
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textToRecmomend = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
                        แนะนำ

พิมพ์ r ตามด้วยหัวข้อที่ต้องการ เช่น r1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. ใส่คนแนะนำว่าอะไร
2. ถ้าชวนเพื่อนมาสมัครจะได้อะไรมั้ย
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textRecommend1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
ใส่คนแนะนำว่าอะไร ?
___________________________________

SL99 แนะนำให้สมัครคะ 
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

$textRecommend2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "
ถ้าชวนเพื่อนมาสมัครพี่จะได้อะไรมั้ย ?
___________________________________

ทางเรามีโปรโมชั่นชวนเพื่อนให้คะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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

// ----------------------------------------------------------------------------------------- TextAll
// ----------------------------------------------------------------------------------------- MainMenu

if ($userMessage != null) {
    if ($userMessage == "สอบถาม" || $userMessage == "q" || $userMessage == "Q") {
        $textReplyMessage = new BubbleContainerBuilder(
            "ltr",
            NULL,
            NULL,
            new BoxComponentBuilder(
                "horizontal",
                array(
                    new TextComponentBuilder(
                        "พิมพ์ q ตามด้วยหัวข้อที่ต้องการ เช่น q1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. โปรโมชั่น
2. คำแนะนำ
3. กลุ่ม/สูตร
4. ฝาก/ถอน
5. การสมัครสมาชิก
6. บัญชีผู้ใช้
7. เกี่ยวกับเว็บไซต์
___________________________________

Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
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
    }
    if ($userMessage == "สมัคร") {
        $textReplyMessage = new BubbleContainerBuilder(
            "ltr",
            NULL,
            NULL,
            new BoxComponentBuilder(
                "horizontal",
                array(
                    new TextComponentBuilder(
                        "ต้องการ",
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
    }

// ----------------------------------------------------------------------------------------- MainMenu
// ----------------------------------------------------------------------------------------- Promotion

if (strstr($userMessage,"q") == true && strstr($userMessage,"1") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textToPromotion);
}
if (strstr($userMessage,"Q") == true && strstr($userMessage,"1") == true) {
    $replyData = new FlexMessageBuilder("Flex", $textToPromotion);
}
if (strstr($userMessage,"p") == true && strstr($userMessage,"1") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion1);
}
if (strstr($userMessage,"P") == true && strstr($userMessage,"1") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion1);
}
if (strstr($userMessage,"p") == true && strstr($userMessage,"2") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion2);
}
if (strstr($userMessage,"P") == true && strstr($userMessage,"2") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion2);
}
if (strstr($userMessage,"p") == true && strstr($userMessage,"3") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion3);
}
if (strstr($userMessage,"P") == true && strstr($userMessage,"3") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion3);
}
if (strstr($userMessage,"p") == true && strstr($userMessage,"4") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion4);
}
if (strstr($userMessage,"P") == true && strstr($userMessage,"4") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textPromotion4);
}

// ----------------------------------------------------------------------------------------- Promotion
// ----------------------------------------------------------------------------------------- Recommend

if (strstr($userMessage,"q") == true && strstr($userMessage,"2") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textToRecommend);
}
if (strstr($userMessage,"Q") == true && strstr($userMessage,"2") == true) {
    $replyData = new FlexMessageBuilder("Flex", $textToRecommend);
}
if (strstr($userMessage,"r") == true && strstr($userMessage,"1") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textRecommend1);
}
if (strstr($userMessage,"R") == true && strstr($userMessage,"1") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textRecommend1);
}
if (strstr($userMessage,"r") == true && strstr($userMessage,"2") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textRecommend2);
}
if (strstr($userMessage,"R") == true && strstr($userMessage,"2") == true) {    
    $replyData = new FlexMessageBuilder("Flex", $textRecommend2);
}

// ----------------------------------------------------------------------------------------- Recommend








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

}

$response = $bot->replyMessage($replyToken, $replyData);




echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
