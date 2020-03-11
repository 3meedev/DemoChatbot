<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'vendor/autoload.php';



include 'bot_settings.php';

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;

$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

$content = file_get_contents('php://input');

$hash = hash_hmac('sha256', $content, LINE_MESSAGE_CHANNEL_SECRET, true);
$signature = base64_encode($hash);

$events = $bot->parseEventRequest($content, $signature);
$eventObj = $events[0];

$eventType = $eventObj->getType();


$userId = NULL;
$sourceId = NULL;
$sourceType = NULL;
$replyToken = NULL;
$replyData = NULL;
$userImage = null;
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

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

switch ($eventType) {
    case 'message':
        $eventMessage = true;
        break;
    case 'postback':
        $eventPostback = true;
        break;
    case 'join':
        $eventJoin = true;
        break;
    case 'leave':
        $eventLeave = true;
        break;
    case 'follow':
        $eventFollow = true;
        break;
    case 'unfollow':
        $eventUnfollow = true;
        break;
    case 'beacon':
        $eventBeacon = true;
        break;
    case 'accountLink':
        $eventAccountLink = true;
        break;
    case 'memberJoined':
        $eventMemberJoined = true;
        break;
    case 'memberLeft':
        $eventMemberLeft = true;
        break;
}

if ($eventObj->isUserEvent()) {
    $userId = $eventObj->getUserId();
    $sourceType = "USER";
}

$sourceId = $eventObj->getEventSourceId();

if (is_null($eventLeave) && is_null($eventUnfollow) && is_null($eventMemberLeft)) {
    $replyToken = $eventObj->getReplyToken();
}

// ----------------------------------------------------------------------------------------- QuickReply

$textReplyToQuestion = new MessageTemplateActionBuilder(
    'สอบถาม',
    'สอบถาม'
);
$textReplyToRegister = new MessageTemplateActionBuilder(
    'สมัคร',
    'สมัคร'
);
$textReplyBackRegister = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'สมัคร'
);
$textReplyToContact = new MessageTemplateActionBuilder(
    'ติดต่อ',
    'ติดต่อ'
);
$textBackQuestion = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'สอบถาม'
);
$textBackPromotion = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'โปรโมชั่น'
);
$textBackRecommend = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'คำแนะนำ'
);
$textBackGroup = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'กลุ่ม/สูตร'
);
$textBackDeposit = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'ฝาก/ถอน'
);
$textBackRegister = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'การสมัครสมาชิก'
);
$textBackAccount = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'บัญชีผู้ใช้'
);
$textBackWebsite = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'เกี่ยวกับเว็บไซต์'
);
$textAddress = new MessageTemplateActionBuilder(
    'กรอกที่อยู่',
    'ต้องการ'
);
$textNotAddress = new MessageTemplateActionBuilder(
    'ไม่ต้องการ',
    'ไม่ต้องการ'
);
$textEditUser = new MessageTemplateActionBuilder(
    'แก้ไขหมายเลขยูส',
    'แจ้งเลขยูส'
);
$textBackToAddress = new MessageTemplateActionBuilder(
    'ย้อนกลับ',
    'BAddress'
);
$textEditAddress = new MessageTemplateActionBuilder(
    'แก้ไขที่อยู่',
    'ย้อนกลับAddress'
);

$quickReplyMain = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyPromotion = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyRecommend = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyGroup = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyDeposit = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyRegister = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyAccount = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplyWebsite = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackQuestion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);

$quickReplySubPromotion = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackPromotion),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubRecommend = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackRecommend),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubGroup = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackGroup),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubDeposit = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackDeposit),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubRegister = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackRegister),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact),
    )
);
$quickReplySubAccount = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackAccount),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplySubWebsite = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackWebsite),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplyBackRegister = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textReplyBackRegister),
        new QuickReplyButtonBuilder(new CameraTemplateActionBuilder('กล้องถ่ายรูป')),
        new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('คลังรูปภาพ'))
    )
);
$quickReplyEditSlip = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('แก้ไขสลิป'))
    )
);
$quickReplyUser = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textAddress),
        new QuickReplyButtonBuilder($textNotAddress),
        new QuickReplyButtonBuilder($textEditUser),
    )
);
$quickReplyAddress = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textBackToAddress),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);
$quickReplyDetailUser = new QuickReplyMessageBuilder(
    array(
        new QuickReplyButtonBuilder($textEditAddress),
        new QuickReplyButtonBuilder($textReplyToQuestion),
        new QuickReplyButtonBuilder($textReplyToRegister),
        new QuickReplyButtonBuilder($textReplyToContact)
    )
);

// ----------------------------------------------------------------------------------------- QuickReply
// ----------------------------------------------------------------------------------------- TextAll


$imageMain = '';
$imageQuestion = '';
$imageAddMember = '';
$imagePromotion = '';
$imageGroup = '';
$imageDeposit = '';
$imageRegister = '';
$imageRecommend = '';
$imageAccount = '';
$imageWebsite = '';

$Main = new ImagemapMessageBuilder(
    $imageMain,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(4, 113, 337, 281)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(348, 112, 340, 283)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(693, 111, 338, 283)
        ),                          
    )
);

$Question = new ImagemapMessageBuilder(
    $imageQuestion,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(7, 208, 509, 139)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(522, 208, 512, 135)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(4, 350, 512, 134)
        ),  
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,349 ,511 ,137 )
        ), 
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(5,491 ,511 ,134 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521,489 ,514 ,139 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(7,631 ,510 ,139 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(524,633 ,509 ,133 )
        ),                            
    )
);

$AddMember = new ImagemapMessageBuilder(
    $imageAddMember,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(10, 153, 508, 76)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(521, 153, 507, 75)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(9, 233, 509, 78)
        ), 
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,235 ,508 ,75)
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(11,313 ,506 ,75 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(523,314 ,507 ,76 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(9,396 ,507 ,77 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521,395 ,507 ,77 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(8,474 ,510 ,82 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,477 ,509 ,74 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(8,557 ,508 ,75 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(523,555 ,506 ,79 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(9,638 ,507 ,77 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,638 ,509 ,77 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(10,720 ,509 ,78 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521,721 ,508 ,75 )
        ),                            
    )
);

$Promotion = new ImagemapMessageBuilder(
    $imagePromotion,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(5, 144, 511, 110)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(522, 145, 511, 107)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(4, 257, 512, 109)
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521,256 ,513 ,107 )
        ),                          
    )
);

$Group = new ImagemapMessageBuilder(
    $imageGroup,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(6, 146, 510, 107)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(523, 146, 511, 105)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(5, 259, 510, 106)
        ),     
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,256 ,514 ,104 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(4,371 ,512 ,106 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(523,371 ,510 ,104 )
        ),                        
    )
);

$Deposit = new ImagemapMessageBuilder(
    $imageDeposit,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(5, 146, 512, 107)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(520, 145, 514, 105)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(4, 258, 512, 108)
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521,257 ,511 ,106 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(5,370 ,510 ,108 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,371 ,509 ,105 )
        ),                          
    )
);

$Register = new ImagemapMessageBuilder(
    $imagRegister,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(5, 145, 512, 109)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(520, 147, 514, 105)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(5, 259, 511, 105)
        ),      
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(520,259 ,514 ,104 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(5,371 ,511 ,105 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(522,369 ,512 ,106 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(6,484 ,509 ,105 )
        ),   
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521,483 ,511 ,105 )
        ),                       
    )
);

$Recommend = new ImagemapMessageBuilder(
    $imageRecommend,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(5, 146, 511, 105)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(524, 145, 510, 104)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(8, 258, 508, 105)
        ),       
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(255,259 ,510 ,104 )
        ),                      
    )
);

$Account = new ImagemapMessageBuilder(
    $imageAccount,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(5, 144, 511, 108)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(521, 143, 512, 110)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(4, 257, 514, 106)
        ),      
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(521, 259, 511, 104)
        ),                       
    )
);

$Website = new ImagemapMessageBuilder(
    $imageWebsite,
    'test',
    new BaseSizeBuilder(500, 1040),
    array(
        new ImagemapMessageActionBuilder(
            'สอบถาม',
            new AreaBuilder(4, 145, 512, 108)
        ), 
        new ImagemapMessageActionBuilder(
            'สมัคร',
            new AreaBuilder(521, 145, 513, 108)
        ),  
        new ImagemapMessageActionBuilder(
            'ติดต่อ',
            new AreaBuilder(4, 259, 513, 107)
        ),      
        new ImagemapMessageActionBuilder(
            '',
            new AreaBuilder(520,257 ,513 ,106 )
        ),                       
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
                "มีโปรโมชั่นอะไรบ้าง ?
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
                "ถ้ารับโปรโมชั่นต้องทำเทิร์นเท่าไหร่ ?
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
                "ถ้าไม่รับโบนัสจะต้องทำเทิร์นมั้ย ?
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
                "มีเครดิตฟรีมั้ย ?
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

$textToRecommend = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "คำแนะนำ

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
                "ใส่คนแนะนำว่าอะไร ?
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
                "ถ้าชวนเพื่อนมาสมัครพี่จะได้อะไรมั้ย ?
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

$textToGroup = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กลุ่ม/สูตร

พิมพ์ g ตามด้วยหัวข้อที่ต้องการ เช่น g1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. มีสูตรโกงบาคาร่าให้มั้ย
2. มีกลุ่มวิเคราะบอลด้วยมั้ย
3. เล่นบาคาร่ายังไง
4. แทงบอลยังไง
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

$textGroup1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "มีสูตรโกงบาคาร่าให้มั้ย ?
___________________________________

มีค่ะ แจ้งยูส+สลิปการโอน นะคะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สูตรบาคาร่า", "https://www.google.com/?hl=th"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGroup2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "มีกลุ่มวิเคราะบอลด้วยมั้ย ?
___________________________________

กลุ่มวิเคราะบอล คลิ้กเข้าลิ้งเลยนะคะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("วิเคราะห์บอล", "https://line.me/ti/g2/fbDC6OmeUzJua6pFerS7"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGroup3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "เล่นบาคาร่ายังไง ?
___________________________________

คลิกลิ้งเพื่อเข้าดูวิธีเข้าเล่นบาคาร่าค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("การเล่นบาคาร่า", "https://youtu.be/8O8M8R2Kffg"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGroup4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "แทงบอลยังไง ?
___________________________________

คลิกลิ้งเพื่อดูการใช้งานและวิธีแทงหวย+บอล
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("การเล่นบอล/หวย", "https://www.youtube.com/channel/UC0j3s6xKcdOX9OFP05W82Bg"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textToDeposit = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ฝาก/ถอน

พิมพ์ d ตามด้วยหัวข้อที่ต้องการ เช่น d1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. ฝาก/ถอนขั้นต่ำเท่าไหร่
2. ครั้งต่อไปฝาก/ถอนยังไง
3. ฝาก/ถอนจำกัดครั้งมั้ย ถอนได้เร็วมั้ย
4. ถ้าฝากไปแล้วไม่เล่นถอนได้เลยมั้ย
5. โอนเงินเสร็จแล้วทำไงต่อ
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

$textDeposit1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ฝาก/ถอนขั้นต่ำเท่าไหร่ ?
___________________________________

หลังจากสมัครเป็นสมาชิกแล้วฝาก/ถอน
ขั้นต่ำ 100 บาท ค่ะ
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

$textDeposit2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ครั้งต่อไปฝาก/ถอนยังไง ?
___________________________________

ฝาก/ถอนสามารถทำรายการผ่านหน้า
เว็บได้เลยค่ะ
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

$textDeposit3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ฝาก/ถอน จำกัดครั้งมั้ย ถอนได้เร็วมั้ย ?
___________________________________

ฝากถอนผ่านหน้าเว็บไม่จำกัดจำนวน
ครั้งฝากถอนภายใน 5 วินาที
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

$textDeposit4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ถ้าฝากไปแล้วไม่เล่นถอนได้เลยมั้ย ?
___________________________________

ไม่ได้ค่ะ ต้องมียอดเล่นให้ครบเทริน
ถึงถอนออกได้ค่ะ
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

$textDeposit5 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โอนเงินเสร็จแล้วทำไงต่อ ?
___________________________________

รอแอดมินตรวจสอบสักครู่นะคะ เสร็จ
แล้วแอดมินจะส่งเลขยูสเวอร์ให้คะ
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

$textToRegister = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "การสมัคร

พิมพ์ u ตามด้วยหัวข้อที่ต้องการ เช่น u1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. เช้คได้ไหมว่าเคยสมัครไปหรือยัง
2. ถ้าเคยสมัครแล้ว แต่จะใช้บันชีแฟน
สมัครอีกได้ไหม (แฟนนามสกุลเดียวกัน)
3. เคยสมัครสมาชิกแล้วสมัครใหม่ได้มั้ย
4. สมัครง่ายมั้ย
5. สมัครขั้นต่ำเท่าไหร่
6. สมัครยังไง
7. สมัคร 100 ได้ไหม
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

$textRegister1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "เช้คได้ไหมว่าเคยสมัครไปหรือยัง ?
___________________________________

ส่งข้อมูลให้แอดมินตรวจสอบได้เลยนะ
คะถ้าเคยเป็นสมาชิกแล้วแอดมินจะแจ้ง
เลขยูสให้คะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textRegister2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ถ้าเคยสมัครแล้ว แต่จะใช้บัญชีแฟน
สมัครอีกได้ไหม 
(แฟนนามสกุลเดียวกัน) ?
___________________________________

รอแอดมินตรวจสอบสักครู่นะคะ เสร็จ
ได้คะพี่ขอแค่ชื่อคนสมัครกับชื่อบัญชี
ที่ใช้โอนตรงกันและถ้าชื่อที่เคยสมัคร
แล้วจะสมัครอีกไม่ได้ค่ะ
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

$textRegister3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "เคยสมัครสมาชิกแล้วสมัครใหม่ได้มั้ย ?
___________________________________

ไม่ได้ค่ะเพราะ 1 ชื่อสามารถสมัคร
ได้แค่ 1 ยูสเซอร์เท่านั้นค่ะ
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

$textRegister4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครง่ายมั้ย ?
___________________________________

สมัครง่าย เล่นง่าย เล่นในมือถือได้
ฝากถอนเงินได้ 24 ชม. เลยนะคะ
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

$textRegister5 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครขั้นต่ำเท่าไหร่ ?
___________________________________

เปิดยูสฝากครั้งแรก 200 บาท ค่ะ
ฝากครั้งต่อไป 100 บาท ค่ะ
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

$textRegister6 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัครยังไง ?
___________________________________

คลิกเมนูสมัครเพื่อสมัครสมาชิกค่ะ
สมัครสมาชิกขั้นต่ำ 200 บาท 
ได้รับโบโบนัสเพิ่ม 30% ค่ะ
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

$textRegister7 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "สมัคร100ได้มั้ย ?
___________________________________

ได้คะ แต่ว่าจะไม่ได้รับโบนัส30%นะคะ
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

$textToAccount = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "บัญชีผู้ใช้

พิมพ์ a ตามด้วยหัวข้อที่ต้องการ เช่น a1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. ลืมเลขบันชีต้องทำยังไง
2. ทำไมทำรายการฝากไม่ได้สักที 
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

$textAccount1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ลืมเลขบันชีต้องทำยังไง ?
___________________________________

คลิกลิ้งติดต่อขอเลขบัญชีกับแอดมินได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อแอดมิน", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textAccount2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ทำไมทำรายการฝากไม่ได้สักที ?
___________________________________

กรอกข้อมูลให้ถูกต้องนะคะ ชื่อบัญชี
ที่โอน เวลา และยอดเงิน 
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

$textToWebsite = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "เกี่ยวกับเว็บไซต์

พิมพ์ w ตามด้วยหัวข้อที่ต้องการ เช่น w1
___________________________________

หัวข้อปัญหาหรือเรื่องที่ต้องการสอบถาม
1. ในเว็บมีอะไรให้เล่นบ้าง
2. เข้าเล่นยังไง
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

$textWebsite1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ในเว็บมีอะไรให้เล่นบ้าง ?
___________________________________

ในเว็บมี บอล มวย หวย บาส ไก่ชน 
กีฬาให้แทงมี บาคาล่าเซ็กซี่ ไฮโล  
และคาสิโนสดต่าง เกม  สลอต รูเลท
ให้เล่น 
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

$textWebsite2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "เข้าเล่นยังไง ?
___________________________________

คลิกลิ้งเพื่อเข้าหน้าเว็บได้เลยค่ะ
___________________________________",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("เข้าสู่เว็บไซต์", "https://www.copa69.com/"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textDetailPromotion1 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 1000 บาท ได้รับหูฟังบลูทูธ
TRUT WIRELESS 5.0 TWS 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion2 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 1000 บาท ได้รับพาวเวอร์แบ๊ง ELOOP E-12 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion3 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 1000 บาท ได้รับลำโพง BLUETOOTH IRON MAN 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion4 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 1000 บาท ได้รับ บุหรี่ไฟฟ้า DRAG 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion5 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 1000 บาท ได้รับโทรศัพท์จิ๋ว 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion6 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 500 บาท ได้รับเสื้อบอล EURO 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion7 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 500 บาท ได้รับเสื้อฮูด Nike 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion8 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 500 บาท ได้รับSmart Watch

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion9 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 500 บาท ได้รับลำโพง Bluetooth Mini

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion10 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 500 บาท ได้รับหูฟัง Bluetooth 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion11 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 300 บาท ได้รับลำโพงสโมสรฟุตบอลโลก

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion12 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 300 บาท ได้รับกระเป๋าสะพาย
ข้างลายสโมสรฟุตบอลโลก 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion13 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัคร 300 บาท ได้รับGame Handle 

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);
$textDetailPromotion14 = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "โปรโมชั่นที่ลูกค้าเลือก คือ
___________________________________

สมัครฝาก 200 รับโบนัส 30%

คลิกลิ้งเพื่อสมัครได้เลยค่ะ

หลังสมัครเสร็จแนบสลิปพร้อมเลข
๊User ที่นี่ด้วยนะคะ
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("สมัครโปรโมชั่น", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

$textGetUser = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรุณากรอกหมายเลข User
___________________________________

ตัวอย่าง user_หมายเลขยูสของลูกค้า
เช่น user_sa894567415
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

$textToAddress = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรุณากรอกที่อยู่เพื่อทางเราจะทำ
การจัดส่งสินค้า โดยลูกค้าเลือกที่
จะกรอกหรือไม่กรอกก็ได้ค่ะ
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

$textNotAddress = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "Copa69 ขอขอบคุณที่ใช้บริการค่ะ....
___________________________________",
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

$textAddress = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรุณากรอกที่อยู่ให้ครบถ้วนสมบูรณ์
*** กรุณานำหน้าประโยคด้วย ที่อยู่

ตัวอย่าง: ที่อยู่ 148 หมู่1 ต.ตำบล
อ.อำเภอ จ.จังหวัด 16589
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

$textDetailUser = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "กรอกชื่อและเบอร์โทรเพื่อติดต่อ
*** กรุณานำหน้าประโยคด้วย เพิ่มเติม
ตัวอย่าง: เพิ่มเติม กอ ดี 089XXXXXXX
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
$textSendAddress = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "ขอบคุณค่ะ เดี๋ยวทางเราจะดำเนินการ
ส่งของตามที่อยู่นี้นะคะ..
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
$textNotKeyword = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "คุณพิมพ์ไม่ตรง Keyword ที่ต้องการค่ะ
กรุณาเลือกหัวข้อที่ต้องการและทำตาม
ขั้นตอนค่ะ
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
$textContact = new BubbleContainerBuilder(
    "ltr",
    NULL,
    NULL,
    new BoxComponentBuilder(
        "horizontal",
        array(
            new TextComponentBuilder(
                "หากมีข้อสงสัยนอกเหนือจากที่กล่าว
มาลูกค้าสามารถติดต่อกับ Admin ได้โดยตรงค่ะ

คลิกที่ลิ้งเพื่อติดต่อ Admin
___________________________________
Copa69 ขอขอบคุณที่ใช้บริการค่ะ....",
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                true
            )
        )
    ),
    new BoxComponentBuilder(
        "horizontal",
        array(
            new ButtonComponentBuilder(
                new UriTemplateActionBuilder("ติดต่อ Admin", "https://line.me/R/ti/p/%40519uqyhc"),
                NULL,
                NULL,
                NULL,
                "primary"
            )
        )
    )
);

// ----------------------------------------------------------------------------------------- TextAll

if (!is_null($events)) {
    $userMessage = strtolower($userMessage);
    if (!is_null($eventFollow)) {
        $textReplyMessage = "Copa69 สวัสดีค่ะ";
        $replyData = new TextMessageBuilder($textReplyMessage, $quickReplyMain);
    }
    if (!is_null($eventMessage)) {
        $typeMessage = $eventObj->getMessageType();
        $idMessage = $eventObj->getMessageId();
        if ($typeMessage == 'text') {
            $userMessage = $eventObj->getText();
        }
        if ($typeMessage == 'image') {
        }
    }


    // ----------------------------------------------------------------------------------------- MainMenu
    switch ($typeMessage) {
        case "text":
            if ($userMessage != null) {
                if ($userMessage == "สอบถาม" || $userMessage == "q" || $userMessage == "Q" || $userMessage == "ย้อนกลับเมนูสอบถาม") {
                    $imageMapUrl = 'https://www.dropbox.com/s/a5ioo7jeslj4hrq/Account.png?dl=0';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'test',
                        new BaseSizeBuilder(500, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปรโมชั่น',
                                new AreaBuilder(9, 214, 511, 138)
                            ),                         
                        )
                    );
//                     $textReplyMessage = new BubbleContainerBuilder(
//                         "ltr",
//                         NULL,
//                         NULL,
//                         new BoxComponentBuilder(
//                             "horizontal",
//                             array(
//                                 new TextComponentBuilder(
//                                     "พิมพ์ q ตามด้วยหัวข้อที่ต้องการ เช่น q1
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
//                                     NULL,
//                                     NULL,
//                                     "md",
//                                     NULL,
//                                     NULL,
//                                     true
//                                 )
//                             )
//                         )
//                     );
//                     $replyData = new FlexMessageBuilder("Flex", $textReplyMessage, $quickReplyMain);
                } else if ($userMessage == "สมัคร") {
                    $imageMapUrl = 'https://sv1.picz.in.th/images/2020/03/10/Q7Hc7l.png';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'test',
                        new BaseSizeBuilder(800, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปรโมชั่น',
                                new AreaBuilder(9, 214, 511, 138)
                            ),                         
                        )
                    );
//                     $textReplyMessage = new BubbleContainerBuilder(
//                         "ltr",
//                         NULL,
//                         NULL,
//                         new BoxComponentBuilder(
//                             "horizontal",
//                             array(
//                                 new TextComponentBuilder(
//                                     "พิมพ์ s ตามด้วยหัวข้อที่ต้องการ เช่น s1
// ___________________________________

// หัวข้อโปรโมชั่นต่างๆของทางเรา
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
//                                     NULL,
//                                     NULL,
//                                     "md",
//                                     NULL,
//                                     NULL,
//                                     true
//                                 )
//                             )
//                         )
//                     );
//                     $replyData = new FlexMessageBuilder("Flex", $textReplyMessage, $quickReplyMain);
                } else if ($userMessage == "ติดต่อ") {
                    $imageMapUrl = 'https://sv1.picz.in.th/images/2020/03/10/Q7Hi2v.png';
                    $replyData = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'test',
                        new BaseSizeBuilder(800, 1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'โปรโมชั่น',
                                new AreaBuilder(9, 214, 511, 138)
                            ),                         
                        )
                    );
                    // $replyData = new FlexMessageBuilder("Flex", $textContact, $quickReplyMain);
                }

                // ----------------------------------------------------------------------------------------- MainMenu
                // ----------------------------------------------------------------------------------------- Promotion

                else if (startsWith($userMessage, "q") && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToPromotion, $quickReplyPromotion);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToPromotion, $quickReplyPromotion);
                } else if ($userMessage == "โปรโมชั่น") {
                    $replyData = new FlexMessageBuilder("Flex", $textToPromotion, $quickReplyPromotion);
                } else if (startsWith($userMessage, "p") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion1, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "P") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion1, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "p") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion2, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "P") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion2, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "p") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion3, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "P") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion3, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "p") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion4, $quickReplySubPromotion);
                } else if (startsWith($userMessage, "P") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textPromotion4, $quickReplySubPromotion);
                }

                // ----------------------------------------------------------------------------------------- Promotion
                // ----------------------------------------------------------------------------------------- Recommend

                else if (startsWith($userMessage, "q") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToRecommend, $quickReplyRecommend);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToRecommend, $quickReplyRecommend);
                } else if ($userMessage == "คำแนะนำ") {
                    $replyData = new FlexMessageBuilder("Flex", $textToRecommend, $quickReplyRecommend);
                } else if (startsWith($userMessage, "r") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRecommend1, $quickReplySubRecommend);
                } else if (startsWith($userMessage, "R") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRecommend1, $quickReplySubRecommend);
                } else if (startsWith($userMessage, "r") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRecommend2, $quickReplySubRecommend);
                } else if (startsWith($userMessage, "R") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRecommend2, $quickReplySubRecommend);
                }

                // ----------------------------------------------------------------------------------------- Recommend
                // ----------------------------------------------------------------------------------------- Group

                else if (startsWith($userMessage, "q") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToGroup, $quickReplyGroup);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToGroup, $quickReplyGroup);
                } else if ($userMessage == "กลุ่ม/สูตร") {
                    $replyData = new FlexMessageBuilder("Flex", $textToGroup, $quickReplyGroup);
                } else if (startsWith($userMessage, "g") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup1, $quickReplySubGroup);
                } else if (startsWith($userMessage, "G") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup1, $quickReplySubGroup);
                } else if (startsWith($userMessage, "g") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup2, $quickReplySubGroup);
                } else if (startsWith($userMessage, "G") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup2, $quickReplySubGroup);
                } else if (startsWith($userMessage, "g") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup3, $quickReplySubGroup);
                } else if (startsWith($userMessage, "G") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup3, $quickReplySubGroup);
                } else if (startsWith($userMessage, "g") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup4, $quickReplySubGroup);
                } else if (startsWith($userMessage, "G") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGroup4, $quickReplySubGroup);
                }

                // ----------------------------------------------------------------------------------------- Group
                // ----------------------------------------------------------------------------------------- Deposit

                else if (startsWith($userMessage, "q") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToDeposit, $quickReplyDeposit);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToDeposit, $quickReplyDeposit);
                } else if ($userMessage == "ฝาก/ถอน") {
                    $replyData = new FlexMessageBuilder("Flex", $textToDeposit, $quickReplyDeposit);
                } else if (startsWith($userMessage, "d") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit1, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "D") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit1, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "d") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit2, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "D") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit2, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "d") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit3, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "D") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit3, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "d") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit4, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "D") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit4, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "d") == true && strstr($userMessage, "5") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit5, $quickReplySubDeposit);
                } else if (startsWith($userMessage, "D") == true && strstr($userMessage, "5") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDeposit5, $quickReplySubDeposit);
                }

                // ----------------------------------------------------------------------------------------- Deposit
                // ----------------------------------------------------------------------------------------- Register
                
                else if (startsWith($userMessage, "q") == true && strstr($userMessage, "5") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToRegister, $quickReplyRegister);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "5") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToRegister, $quickReplyRegister);
                } else if ($userMessage == "การสมัครสมาชิก") {
                    $replyData = new FlexMessageBuilder("Flex", $textToRegister, $quickReplyRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister1, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister1, $quickReplySubRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister2, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister2, $quickReplySubRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister3, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "3") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister3, $quickReplySubRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister4, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "4") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister4, $quickReplySubRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "5") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister5, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "5") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister5, $quickReplySubRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "6") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister6, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "6") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister6, $quickReplySubRegister);
                } else if (startsWith($userMessage, "u") == true && strstr($userMessage, "7") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister7, $quickReplySubRegister);
                } else if (startsWith($userMessage, "U") == true && strstr($userMessage, "7") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textRegister7, $quickReplySubRegister);
                }

                // ----------------------------------------------------------------------------------------- Register
                // ----------------------------------------------------------------------------------------- Account

                else if (startsWith($userMessage, "q") == true && strstr($userMessage, "6") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToAccount, $quickReplyAccount);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "6") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToAccount, $quickReplyAccount);
                } else if ($userMessage == "บัญชีผู้ใช้") {
                    $replyData = new FlexMessageBuilder("Flex", $textToAccount, $quickReplyAccount);
                } else if (startsWith($userMessage, "a") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textAccount1, $quickReplySubAccount);
                } else if (startsWith($userMessage, "A") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textAccount1, $quickReplySubAccount);
                } else if (startsWith($userMessage, "a") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textAccount2, $quickReplySubAccount);
                } else if (startsWith($userMessage, "A") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textAccount2, $quickReplySubAccount);
                }

                // ----------------------------------------------------------------------------------------- Account
                // ----------------------------------------------------------------------------------------- Website

                else if (startsWith($userMessage, "q") == true && strstr($userMessage, "7") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToWebsite, $quickReplyWebsite);
                } else if (startsWith($userMessage, "Q") == true && strstr($userMessage, "7") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textToWebsite, $quickReplyWebsite);
                } else if ($userMessage == "เกี่ยวกับเว็บไซต์") {
                    $replyData = new FlexMessageBuilder("Flex", $textToWebsite, $quickReplyWebsite);
                } else if (startsWith($userMessage, "w") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textWebsite1, $quickReplySubWebsite);
                } else if (startsWith($userMessage, "W") == true && strstr($userMessage, "1") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textWebsite1, $quickReplySubWebsite);
                } else if (startsWith($userMessage, "w") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textWebsite2, $quickReplySubWebsite);
                } else if (startsWith($userMessage, "W") == true && strstr($userMessage, "2") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textWebsite2, $quickReplySubWebsite);
                }

                // ----------------------------------------------------------------------------------------- Website
                // ----------------------------------------------------------------------------------------- DetailPromotion

                else if ((startsWith($userMessage, "s") == true && endsWith($userMessage, "s1") == true) || (startsWith($userMessage, "S") == true && endsWith($userMessage, "S1") == true)) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion1, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "2") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "2") == true) || strstr($userMessage, "s2") || strstr($userMessage, "S2")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion2, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "3") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "3") == true) || strstr($userMessage, "s3") || strstr($userMessage, "S3")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion3, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "4") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "4") == true) || strstr($userMessage, "s4") || strstr($userMessage, "S4")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion4, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "5") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "5") == true) || strstr($userMessage, "s5") || strstr($userMessage, "S5")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion5, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "6") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "6") == true) || strstr($userMessage, "s6") || strstr($userMessage, "S6")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion6, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "7") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "7") == true) || strstr($userMessage, "s7") || strstr($userMessage, "S7")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion7, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "8") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "8") == true) || strstr($userMessage, "s8") || strstr($userMessage, "S8")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion8, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "9") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "9") == true) || strstr($userMessage, "s9") || strstr($userMessage, "S9")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion9, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "10") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "10") == true) || strstr($userMessage, "s10") || strstr($userMessage, "S10")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion10, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "11") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "11") == true) || strstr($userMessage, "s11") || strstr($userMessage, "S11")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion11, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "12") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "12") == true) || strstr($userMessage, "s12") || strstr($userMessage, "S12")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion12, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "13") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "13") == true) || strstr($userMessage, "s13") || strstr($userMessage, "S13")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion13, $quickReplyBackRegister);
                } else if ((startsWith($userMessage, "s") == true && strstr($userMessage, "14") == true) || (startsWith($userMessage, "S") == true && strstr($userMessage, "14") == true) || strstr($userMessage, "s14") || strstr($userMessage, "S14")) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailPromotion14, $quickReplyBackRegister);
                }

                // ----------------------------------------------------------------------------------------- DetailPromotion 

                else if (strstr($userMessage, "แจ้งเลขยูส") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textGetUser, $quickReplyEditSlip);
                } else if (strstr($userMessage, "x") == true || strstr($userMessage, "X") == true || $userMessage == "BAddress") {
                    $replyData = new FlexMessageBuilder("Flex", $textToAddress, $quickReplyUser);
                } else if ($userMessage == "ไม่ต้องการ") {
                    $replyData = new FlexMessageBuilder("Flex", $textNotAddress, $quickReplyMain);
                } else if ($userMessage == "ต้องการ" || $userMessage == "ย้อนกลับAddress") {
                    $replyData = new FlexMessageBuilder("Flex", $textAddress, $quickReplyAddress);
                } else if (strstr($userMessage, "ที่อยู่") == true || strstr($userMessage, "อำเภอ") == true || strstr($userMessage, "อ.") == true || strstr($userMessage, "ตำบล") == true || strstr($userMessage, "ต.") == true || strstr($userMessage, "จังหวัด") == true || strstr($userMessage, "จ.") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textDetailUser, $quickReplyDetailUser);
                } else if (strstr($userMessage, "เพิ่มเติม") == true) {
                    $replyData = new FlexMessageBuilder("Flex", $textSendAddress, $quickReplyMain);
                } else {
                    $replyData = new FlexMessageBuilder("Flex", $textNotKeyword, $quickReplyMain);
                }
                break;
            }

            // ----------------------------------------------------------------------------------------- Image
        default:
            if (!is_null($replyData)) {
            } else {
                $replyData = new FlexMessageBuilder("Flex", $textGetUser, $quickReplyEditSlip);
            }
            break;
    }
    // ----------------------------------------------------------------------------------------- Image
    // ----------------------------------------------------------------------------------------- Respone

    $response = $bot->replyMessage($replyToken, $replyData);
    if ($response->isSucceeded()) {
        echo 'Succeeded!';
        return;
    }
    // Failed
    echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

    // ----------------------------------------------------------------------------------------- Respone
}
