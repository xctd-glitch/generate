<?php
include_once('../connection.config.php');
require_once 'Base64URL.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    function randomName() {
    $names = array(
    'ashley','jessica','emily','sarah','samantha','brittany','amanda','elizabeth','taylor','megan','stephanie','kayla','lauren','jennifer','rachel','hannah','nicole','amber','alexis','courtney','victoria','danielle','alyssa','rebecca','jasmine','katherine','melissa','alexandra','brianna','chelsea','michelle','morgan','kelsey','tiffany','kimberly','christina','madison','heather','shelby','anna','mary','maria','allison','sara','laura','andrea','olivia','erin','haley','abigail','kaitlyn','jordan','natalie','vanessa','kelly','brooke','erica','kristen','julia','crystal','amy','katelyn','marissa','lindsey','paige','cassandra','sydney','katie','caitlin','kathryn','emma','shannon','angela','gabrielle','jacqueline','jenna','jamie','mariah','alicia','briana','alexandria','destiny','miranda','monica','brittney','catherine','savannah','sierra','sabrina','breanna','whitney','caroline','molly','madeline','erika','grace','diana','leah','angelica','lindsay','christine','kaitlin','cynthia','meghan','cheyenne','mackenzie','margaret','veronica','melanie','bailey','kristin','bianca','lisa','holly','kristina','alexa','ariel','bethany','hailey','leslie','april','casey','brenda','kathleen','julie','patricia','autumn','karen','gabriela','brandi','ana','rachael','kendra','karina','dominique','valerie','desiree','kara','carly','claire','tara','adriana','kaylee','natasha','michaela','chloe','jocelyn','kylie','krystal','hayley','caitlyn','alison','nancy','sophia','daisy','rebekah','dana','jillian','cassidy','alejandra','raven','jade','angel','summer','audrey','gabriella','chelsey','sandra','ariana','katrina','claudia','monique','meagan','joanna','kirsten','faith','mikayla','brandy','kiara','makayla','mallory','krista','deanna','yesenia','ashlee','cindy','mercedes','alisha','gina','lydia','felicia','mckenzie','zoe','bridget','marisa','priscilla','karla','kassandra','denise','jasmin','tori','isabella','selena','diamond','evelyn','anne','amelia','cristina','allyson','tabitha','abby','ashleigh','lacey','jazmin','isabel','asia','candace','ciara','cierra','colleen','jaclyn','carolyn','hope','linda','naomi','ellen','mia','teresa','meredith','guadalupe','hanna','renee','nichole','kendall','jazmine','tamara','britney','justine','tessa','susan','tatiana','tiara','daniela','maya','adrianna','genesis','rosa','mayra','kelli','kasey','candice','clarissa','aubrey','arianna','nina','theresa','carrie','wendy','raquel','marina','carmen','katelynn','maggie','ruby','heidi','jenny','jessie','katlyn','angelina','carolina','jacquelyn','camille','gloria','virginia','kiana','jordyn','cecilia','ebony','alexus','cara','kelsie','alissa','janet','charlotte','ashlyn','esmeralda','miriam','elise','martha','hillary','tia','melinda','jada','marie','carla','barbara','esther','stacey','kate','natalia','sharon','carissa','toni','alana','pamela','ruth','valeria','robin','rose','cassie','shayla','lillian','paola','riley','arielle','celeste','helen','alondra','brenna','sasha','alexia','logan','janelle','savanna','ann','lily','tanya','stacy','elena','vivian','kyra','tiana','tina','nikki','adrienne','ashton','anastasia','dakota','madeleine','tyler','callie','kylee','serena','devin','chelsie','kellie','sadie','annie','eva','imani','mckenna','tierra','marisol','christian','frances','elisabeth','aimee','devon','kyla','deborah','liliana','nadia','sonia','deja','jane','kennedy','paula','shawna','brooklyn','sophie','kira','karissa','kierra','madelyn','kristine','peyton','regina','sylvia','skylar','aaliyah','melody','alice','brittani','kali','lorena','bria','nicolette','francesca','sofia','larissa','alaina','tracy','delaney','kari','brianne','cortney','macy','leticia','shayna','taryn','jeanette','robyn','joy','tania','chasity','mikaela','stefanie','shanice','sidney','tayler','juliana','kailey','makenzie','kaitlynn','bryanna','kristi','carol','randi','breana','tasha','india','irene','kayleigh','emilee','elisa','josephine','corinne','mariana','payton','alma','maribel','simone','clara','cristal','yvonne','johanna','katharine','kristy','alyson','isabelle','julianna','kaila','yvette','christy','ciera','kourtney','christa','harley','rachelle','meaghan','abbey','destinee','tanisha','elaine','michele','kenya','perla','precious','blanca','jaime','donna','marilyn','marlene','nora','haylee','josie','cheyanne','angelique','ericka','giselle','misty','noelle','lucy','carley','iris','lyndsey','tiffani','cameron','kelley','daniella','judith','maritza','talia','sandy','diane','shana','trisha','anita','kelsi','lori','carina','allie','chantel','charity','gianna','julianne','shania','avery','chanel','kaley','aliyah','luz','hunter','hilary','stephany','leanna','nia','mollie','bonnie','paris','yasmin','yolanda','margarita','yasmine','alexandrea','janae','janice','alanna','alysha','julissa','kaleigh','shaina','casandra','darian','iesha','ivy','jill','kiersten','moriah','sheila','dawn','norma','skyler','kiera','paulina','thalia','viviana','antoinette','genevieve','keisha','tatyana','lizbeth','roxanne','alisa','desirae','beatriz','kacie','maranda','emilie','bridgette','edith','kassidy','suzanne','susana','tianna','chandler','rochelle','ryan','hallie','lucia','aisha','cayla','tyra','laurel','dorothy','keri','antonia','eileen','rocio','eliza','kaylin','baylee','haleigh','karly','tonya','kaylie','kelsea','mckayla','kaylyn','breanne','gretchen','micaela','alina','lacy','fabiola','rosemary','annette','kathy','keely','justice','lena','sally','shaniqua','shakira','latoya','selina','shauna','ashlie','eleanor','mara','shirley','jacklyn','jodi','carlie','katelin','noemi','reagan','brittni','joyce','charlene','khadijah','shantel','dallas','katarina','heaven','jena','madalyn','araceli','fatima','kerri','blair','reyna','tess','angie','katy','leanne','tammy','darlene','maegan','celina','christie','shanna','clare','justina','nathalie','karlee','yadira','hailee','kristie','lexus','bailee','jalisa','rhiannon','abbie','georgia','kori','lea','mindy','shelbi','skye','alex','corina','katlin','sonya','beverly','mariela','regan','celia','dulce','juanita','kasandra','ella','lesley','stacie','connie','karli','savanah','ashlynn','sade','shea','cheryl','rylee','essence','kaela','kiley','tabatha','aurora','karlie','latasha','ali','anissa','gwendolyn','jennie','kacey','lauryn','brook','brandie','elaina','kala','kalyn','kianna','kirstin','octavia','aileen','alycia','jana','jesse','jessika','lizette','mandy','carli','demi','ellie','leigh','beth','joanne','lara','loren','ayanna','phoebe','silvia','bobbie','debra','halie','jami','janessa','jaimie','latisha','melina','rita','shelbie','ashly','devan','eden','jayme','breann','kaci','christen','kirstie','athena','fiona','jaqueline','jenifer','kailee','lucero','patrice','staci','alysia','christiana','corey','sheena','brielle','constance','arlene','ava','elyse','makenna','adrian','gillian','kerry','kortney','kristyn','elissa','janette','traci','cecelia','celine','elisha','joselyn','maureen','nadine','roxana','allyssa','cori','macey','maura','racheal','rikki','sarai','brittanie','dianna','graciela','iliana','jackie','maddison','rebeca','shyanne','trinity','betty','gladys','judy','keishla','stevie','dayna','kristal','nikita','alayna','cora','darby','lorraine','yessenia','ayana','mya','sage','sherry','tracey','britany','montana','belinda','chantal','giovanna','juliet','micah','myra','addison','aspen','ayla','helena','hollie','ingrid','krysta','lakeisha','infant','lacie','maricela','myranda','bobbi','carlee','nataly','destini','domonique','itzel','karley','shelly','christin','destiney','terri','beatrice','tricia','danica','kristian','trista','bryana','dalia','leeann','magdalena','mireya','berenice','estefania','hali','joann','juana','leann','melisa','alessandra','irma','kenia','terra','tiffanie','baby','damaris','jazmyn','rhonda','hazel','janie','martina','sarina','tiera','yasmeen','ivette','jolene','aja','alesha','billie','jerrica','joan','liana','marcella','nayeli','noel','olga','tyesha','catalina','kia','lynn','tatum','alysa','asha','audra','britni','janay','janine','kallie','kimberley','pauline','alecia','annika','kaycee','scarlett','vanesa','bernadette','devyn','elyssa','lourdes','marisela','chaya','daphne','emerald','joelle','kassie','katerina','katheryn','macie','monika','anabel','annmarie','breonna','eliana','elsa','halle','kathrine','kaylynn','lesly','maira','mariam','marlena','susanna','annamarie','betsy','jessenia','malia','princess','ashli','astrid','dina','dominque','eunice','griselda','sydnie','yaritza','china','francheska','jayla','jean','katlynn','kayli','layla','leandra','lynette','xiomara','yazmin','zoey','anika','ashanti','dara','darcy','debbie','eboni','isamar','lexi','miracle','whitley','abbigail','aleah','anais','carson','emilia','franchesca','laurie','liza','mariel','marlee','stormy','anjelica','chelsi','gabriel','haylie','kalie','lana','lexie','paloma','salina','doris','leila','lizeth','drew','jamila','kailyn','kayley','kelcie','lidia','sydnee','amani','ashely','aubree','deana','jessi','jodie','keila','kendal','kimberlee','reina','yajaira','alena','brea','georgina','joana','meranda','mikala','nikole'
    );
    return $names[rand ( 0 , count($names) -1)];
}
function get_branch_io($url, $access_token, $bit_tkn, $pick, $fb, $shimlink, $bnc) {
            if($bnc == 2){
        return get_bitly_short_url($url, $bit_tkn, $pick, $fb, $shimlink);
    }
    else{
            	$connectURL = 'https://api2.branch.io/v1/url';
	    $payload = json_encode([
        'branch_key'=> $access_token,
        'channel' => 'facebook',
        'campaign' => 'facebook',
        'data' => [
            '$always_deeplink'=> true,
            '$uri_redirect_mode'=> 1,
            '$og_app_id'=> '111411968896241',
            '$og_url'=> 'https://www.facebook.com/',
            '$canonical_url'=> 'https://www.facebook.com/',
            '$desktop_url'=> $url,
            '$ios_url'=> $url,
            '$ipad_url'=> $url,
            '$android_url'=> $url,
            '$og_title' => 'Facebook',
            '$og_description' => 'Facebook helps you connect and share with the people in your life.',
            '$og_image_url' => 'https://static.xx.fbcdn.net/rsrc.php/y8/r/dF5SId3UHWd.svg',
            '$og_type' => 'video.movie',
            '$og_image_width' => '1200',
            '$og_image_height' => '600',
            'custom_boolean'=> true
            ]
        ]);
	return branch_result($connectURL, $payload, $bit_tkn, $pick, $fb, $shimlink, $bnc);
}

}
function branch_result($url, $load, $bit_tkn, $pick, $fb, $shimlink, $bnc) {
	$ch = curl_init();
	$timeout = 15;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $load);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	$response = json_decode($data, true);
	curl_close($ch);
	if($response['error']['message'] == "Invalid or missing app id, Branch key, or secret"){
    $short = null;    
    }
    else{
    if(empty($bnc)){
        $short = 'https://bnc.lt' . parse_url($response['url'], PHP_URL_PATH);
    }
    else{
        $short = $response['url'];
    }

    }
	//return $short;
	return get_bitly_short_url($short, $bit_tkn, $pick, $fb, $shimlink);
}


function get_bitly_short_url($short, $bit_tkn, $pick, $fb, $shimlink) {
    if($pick == '1_jmp'){
    $app = 'https://tools.nofx.work/jmp/?longurl='.urlencode(trim(preg_replace('/\s\s+/', ' ', $short))).'&access_token='.$bit_tkn.'&domain=j.mp';
    }
    else if($pick === '2_shr'){
    $url = 'https://graph.sdk-ngix.me/shr/bulk.php?longurl='.$short;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    $arr_result = curl_exec($ch);
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$arr_result."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$arr_result."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($arr_result)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($arr_result)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$arr_result;
    }
    else{
        $short = $arr_result;
    }
    return $short;

    }
    else if($pick === 'iisgd'){
    $url = 'https://is.gd/create.php?format=simple&url='.$short;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    $arr_result = curl_exec($ch);
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$arr_result."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$arr_result."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($arr_result)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($arr_result)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$arr_result;
    }
    else{
        $short = $arr_result;
    }
    return $short;

    }
        else if($pick === 'ivgd'){
    $url = 'https://v.gd/create.php?format=simple&url='.$short;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    $arr_result = curl_exec($ch);
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$arr_result."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$arr_result."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($arr_result)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($arr_result)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$arr_result;
    }
    else{
        $short = $arr_result;
    }
    return $short;

    }
            else if($pick === 'ixgd'){
    $url = 'https://xgd.io/V1/shorten?url='.$short.'&key=e2a96e90c0fd17d467705e50993c54cd';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    $arr_result = curl_exec($ch);
    $array = json_decode($arr_result, true);
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$array["shorturl"]."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$array["shorturl"]."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($array["shorturl"])."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($array["shorturl"])."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$array["shorturl"];
    }
    else{
        $short = $array["shorturl"];
    }
    return $short;

    }
        else if($pick === 'ivht'){
    $url = 'https://v.ht/index.php?txt_url='.$short;
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$data = curl_exec($curl);

// Hide HTML warnings
libxml_use_internal_errors(true);
$dom = new DOMDocument;
if($dom->loadHTML($data, LIBXML_NOWARNING)){
    // echo Links and their anchor text
    foreach($dom->getElementsByTagName('a') as $link) {
        $href = $link->getAttribute('href');
        $anchor = $link->nodeValue;
        //echo $anchor,"\n";
if (strpos($anchor, 'http://v.ht/') !== false) {
$s = preg_replace('/~/', '', $anchor);
}
    }
}
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($s)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($s)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$s;
    }
    else{
        $short = $s;
    }
    return $short;

    }
        else if($pick === 'icutt'){
    $url = 'https://cutt.us/index.php?txt_url='.$short;
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$data = curl_exec($curl);

// Hide HTML warnings
libxml_use_internal_errors(true);
$dom = new DOMDocument;
if($dom->loadHTML($data, LIBXML_NOWARNING)){
    // echo Links and their anchor text
    foreach($dom->getElementsByTagName('a') as $link) {
        $href = $link->getAttribute('href');
        $anchor = $link->nodeValue;
        //echo $anchor,"\n";
if (strpos($anchor, 'https://cutt.us/') !== false) {
$s = $anchor;
}
    }
}
    
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($s)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($s)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$s;
    }
    else{
        $short = $s;
    }
    return $short;

    }

    else if($pick === 'ixg_i'){
        
        //$url = "https://graph.sdk-ngix.me/shr/bulk.php?longurl=" . $short;
        $url = "https://me.ixg.llc/?url=" . $short . "&format=text";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        $arr_result = curl_exec($ch);
        $s = $arr_result;
        
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($s)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($s)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$s;
    }
    else{
        $short = $s;
    }
    return $short;

    }
    else if($pick === 'ngixi'){
        
        //$url = "https://graph.sdk-ngix.me/shr/bulk.php?longurl=" . $short;
        $url = "https://ngix.cc/?url=" . $short . "&format=text";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        $arr_result = curl_exec($ch);
        $s = $arr_result;
        
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$s."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($s)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($s)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$s;
    }
    else{
        $short = $s;
    }
    return $short;

    }
    else if($pick === '4_branch'){
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$short."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$short."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($short)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($short)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$short;
    }
    else{
        $short = $short;
    }
    return $short;

    }
    else{
    $app = 'https://tools.nofx.work/bitly/?longurl='.urlencode(trim(preg_replace('/\s\s+/', ' ', $short))).'&access_token='.$bit_tkn;
    }
	$connectURL = $app;
	return curl_get_result($connectURL, $fb, $shimlink);
}
function curl_get_result($url, $fb, $shimlink) {
	$ch = curl_init();
	$timeout = 15;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	$response = json_decode($data);
	curl_close($ch);
	
    if($fb === '1'){
        $short = "https://l.facebook.com/l.php?u=".$data."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'p_fb'){
        $short = "https://p.facebook.com/l.php?u=".$data."&h=".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789") , 0 , 7)."&s=1";
    }
    else if($fb === 'w_fb'){
        $short = "https://web.facebook.com/flx/warn/?u=".urlencode($data)."&h=1";
    }
    else if($fb == 'ig_s'){
        $short = "https://l.instagram.com/?u=".urlencode($data)."&e=".$shimlink."&s=1";
    }
    else if($fb == 'lwl'){
        $short = "https://l.wl.co/l?u=".$data;
    }
    else{
        $short = $data;
    }
	return $short;
	
}

function curl_get_final($url) {
	$ch = curl_init();
	$timeout = 15;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	$response = json_decode($data);
	curl_close($ch);
	return $response->data->url;
}


if(isset($_POST['b_subid'])){

    $sub_id = trim($_POST['b_subid']);
    $canonical_url = trim($_POST['b_canonical_url']);
    $branch_key = trim($_POST['branch_key']);
    $bit_key = trim($_POST['bit_key']);
    $b_pick = trim($_POST['branch_pick']);
    $fb_pick = trim($_POST['fb_pick']);
    $limit_i = trim($_POST['limit_i']);
    $net = trim($_POST['netpick']);
    $domain = trim($_POST['sel_dom_p']);
    $shimlink = $_POST['igshiml'];
    $bnc = $_POST['sel_bnctext'];
    
    if(!empty($domain)){
    $d = $sub_id;
    }
    else{
    $d = 'global';
    }
for ($x = 1; $x <= $limit_i; $x++) {
    // Fix: Check if shimlink is empty and provide default value
    if(empty($shimlink)){
        $shimlink = "default";
    }
    $textArt = explode("\n", $shimlink);
    $textArt = array_filter($textArt, 'trim');
    
    // Fix: Check if array is empty before array_rand
    if(empty($textArt)){
        $textArt = array("default");
    }
    
    $k = array_rand($textArt);
    $v = trim($textArt[$k]);

$result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='$d' ORDER BY RAND() LIMIT 1"); // using mysqli_query instead
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    $sp = randomName().randomName();

$genurl= 'http://{sub}.'.$row['domain'].'/{click_id}';
$str_param = array('{sub}','{click_id}');
$str_build = array($sp, base64url_encode(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 5).','.strtoupper($sub_id).','.time().','.$canonical_url.','.$net));
$url_ex = str_replace($str_param, $str_build, htmlspecialchars_decode($genurl));

    $text = trim($url_ex);
    $textAr = explode("\n", $text);
    $textAr = array_filter($textAr, 'trim');
    foreach ($textAr as $line){
        echo get_branch_io($line, $branch_key, $bit_key, $b_pick, $fb_pick, $v, $bnc) . ',';

        }

}
}

        }
}
?>
