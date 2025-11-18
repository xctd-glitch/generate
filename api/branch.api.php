<?php
include_once('../connection.config.php');
require_once '../../Base64URL.php';
header ("Content-type: application/json");

if($_SERVER["REQUEST_METHOD"] == "POST"){
function randomName() {
    $names = array(
    'ashley','jessica','emily','sarah','samantha','brittany','amanda','elizabeth','taylor','megan','stephanie','kayla','lauren','jennifer','rachel','hannah','nicole','amber','alexis','courtney','victoria','danielle','alyssa','rebecca','jasmine','katherine','melissa','alexandra','brianna','chelsea','michelle','morgan','kelsey','tiffany','kimberly','christina','madison','heather','shelby','anna','mary','maria','allison','sara','laura','andrea','olivia','erin','haley','abigail','kaitlyn','jordan','natalie','vanessa','kelly','brooke','erica','kristen','julia','crystal','amy','katelyn','marissa','lindsey','paige','cassandra','sydney','katie','caitlin','kathryn','emma','shannon','angela','gabrielle','jacqueline','jenna','jamie','mariah','alicia','briana','alexandria','destiny','miranda','monica','brittney','catherine','savannah','sierra','sabrina','breanna','whitney','caroline','molly','madeline','erika','grace','diana','leah','angelica','lindsay','christine','kaitlin','cynthia','meghan','cheyenne','mackenzie','margaret','veronica','melanie','bailey','kristin','bianca','lisa','holly','kristina','alexa','ariel','bethany','hailey','leslie','april','casey','brenda','kathleen','julie','patricia','autumn','karen','gabriela','brandi','ana','rachael','kendra','karina','dominique','valerie','desiree','kara','carly','claire','tara','adriana','kaylee','natasha','michaela','chloe','jocelyn','kylie','krystal','hayley','caitlyn','alison','nancy','sophia','daisy','rebekah','dana','jillian','cassidy','alejandra','raven','jade','angel','summer','audrey','gabriella','chelsey','sandra','ariana','katrina','claudia','monique','meagan','joanna','kirsten','faith','mikayla','brandy','kiara','makayla','mallory','krista','deanna','yesenia','ashlee','cindy','mercedes','alisha','gina','lydia','felicia','mckenzie','zoe','bridget','marisa','priscilla','karla','kassandra','denise','jasmin','tori','isabella','selena','diamond','evelyn','anne','amelia','cristina','allyson','tabitha','abby','ashleigh','lacey','jazmin','isabel','asia','candace','ciara','cierra','colleen','jaclyn','carolyn','hope','linda','naomi','ellen','mia','teresa','meredith','guadalupe','hanna','renee','nichole','kendall','jazmine','tamara','britney','justine','tessa','susan','tatiana','tiara','daniela','maya','adrianna','genesis','rosa','mayra','kelli','kasey','candice','clarissa','aubrey','arianna','nina','theresa','carrie','wendy','raquel','marina','carmen','katelynn','maggie','ruby','heidi','jenny','jessie','katlyn','angelina','carolina','jacquelyn','camille','gloria','virginia','kiana','jordyn','cecilia','ebony','alexus','cara','kelsie','alissa','janet','charlotte','ashlyn','esmeralda','miriam','elise','martha','hillary','tia','melinda','jada','marie','carla','barbara','esther','stacey','kate','natalia','sharon','carissa','toni','alana','pamela','ruth','valeria','robin','rose','cassie','shayla','lillian','paola','riley','arielle','celeste','helen','alondra','brenna','sasha','alexia','logan','janelle','savanna','ann','lily','tanya','stacy','elena','vivian','kyra','tiana','tina','nikki','adrienne','ashton','anastasia','dakota','madeleine','tyler','callie','kylee','serena','devin','chelsie','kellie','sadie','annie','eva','imani','mckenna','tierra','marisol','christian','frances','elisabeth','aimee','devon','kyla','deborah','liliana','nadia','sonia','deja','jane','kennedy','paula','shawna','brooklyn','sophie','kira','karissa','kierra','madelyn','kristine','peyton','regina','sylvia','skylar','aaliyah','melody','alice','brittani','kali','lorena','bria','nicolette','francesca','sofia','larissa','alaina','tracy','delaney','kari','brianne','cortney','macy','leticia','shayna','taryn','jeanette','robyn','joy','tania','chasity','mikaela','stefanie','shanice','sidney','tayler','juliana','kailey','makenzie','kaitlynn','bryanna','kristi','carol','randi','breana','tasha','india','irene','kayleigh','emilee','elisa','josephine','corinne','mariana','payton','alma','maribel','simone','clara','cristal','yvonne','johanna','katharine','kristy','alyson','isabelle','julianna','kaila','yvette','christy','ciera','kourtney','christa','harley','rachelle','meaghan','abbey','destinee','tanisha','elaine','michele','kenya','perla','precious','blanca','jaime','donna','marilyn','marlene','nora','haylee','josie','cheyanne','angelique','ericka','giselle','misty','noelle','lucy','carley','iris','lyndsey','tiffani','cameron','kelley','daniella','judith','maritza','talia','sandy','diane','shana','trisha','anita','kelsi','lori','carina','allie','chantel','charity','gianna','julianne','shania','avery','chanel','kaley','aliyah','luz','hunter','hilary','stephany','leanna','nia','mollie','bonnie','paris','yasmin','yolanda','margarita','yasmine','alexandrea','janae','janice','alanna','alysha','julissa','kaleigh','shaina','casandra','darian','iesha','ivy','jill','kiersten','moriah','sheila','dawn','norma','skyler','kiera','paulina','thalia','viviana','antoinette','genevieve','keisha','tatyana','lizbeth','roxanne','alisa','desirae','beatriz','kacie','maranda','emilie','bridgette','edith','kassidy','suzanne','susana','tianna','chandler','rochelle','ryan','hallie','lucia','aisha','cayla','tyra','laurel','dorothy','keri','antonia','eileen','rocio','eliza','kaylin','baylee','haleigh','karly','tonya','kaylie','kelsea','mckayla','kaylyn','breanne','gretchen','micaela','alina','lacy','fabiola','rosemary','annette','kathy','keely','justice','lena','sally','shaniqua','shakira','latoya','selina','shauna','ashlie','eleanor','mara','shirley','jacklyn','jodi','carlie','katelin','noemi','reagan','brittni','joyce','charlene','khadijah','shantel','dallas','katarina','heaven','jena','madalyn','araceli','fatima','kerri','blair','reyna','tess','angie','katy','leanne','tammy','darlene','maegan','celina','christie','shanna','clare','justina','nathalie','karlee','yadira','hailee','kristie','lexus','bailee','jalisa','rhiannon','abbie','georgia','kori','lea','mindy','shelbi','skye','alex','corina','katlin','sonya','beverly','mariela','regan','celia','dulce','juanita','kasandra','ella','lesley','stacie','connie','karli','savanah','ashlynn','sade','shea','cheryl','rylee','essence','kaela','kiley','tabatha','aurora','karlie','latasha','ali','anissa','gwendolyn','jennie','kacey','lauryn','brook','brandie','elaina','kala','kalyn','kianna','kirstin','octavia','aileen','alycia','jana','jesse','jessika','lizette','mandy','carli','demi','ellie','leigh','beth','joanne','lara','loren','ayanna','phoebe','silvia','bobbie','debra','halie','jami','janessa','jaimie','latisha','melina','rita','shelbie','ashly','devan','eden','jayme','breann','kaci','christen','kirstie','athena','fiona','jaqueline','jenifer','kailee','lucero','patrice','staci','alysia','christiana','corey','sheena','brielle','constance','arlene','ava','elyse','makenna','adrian','gillian','kerry','kortney','kristyn','elissa','janette','traci','cecelia','celine','elisha','joselyn','maureen','nadine','roxana','allyssa','cori','macey','maura','racheal','rikki','sarai','brittanie','dianna','graciela','iliana','jackie','maddison','rebeca','shyanne','trinity','betty','gladys','judy','keishla','stevie','dayna','kristal','nikita','alayna','cora','darby','lorraine','yessenia','ayana','mya','sage','sherry','tracey','britany','montana','belinda','chantal','giovanna','juliet','micah','myra','addison','aspen','ayla','helena','hollie','ingrid','krysta','lakeisha','infant','lacie','maricela','myranda','bobbi','carlee','nataly','destini','domonique','itzel','karley','shelly','christin','destiney','terri','beatrice','tricia','danica','kristian','trista','bryana','dalia','leeann','magdalena','mireya','berenice','estefania','hali','joann','juana','leann','melisa','alessandra','irma','kenia','terra','tiffanie','baby','damaris','jazmyn','rhonda','hazel','janie','martina','sarina','tiera','yasmeen','ivette','jolene','aja','alesha','billie','jerrica','joan','liana','marcella','nayeli','noel','olga','tyesha','catalina','kia','lynn','tatum','alysa','asha','audra','britni','janay','janine','kallie','kimberley','pauline','alecia','annika','kaycee','scarlett','vanesa','bernadette','devyn','elyssa','lourdes','marisela','chaya','daphne','emerald','joelle','kassie','katerina','katheryn','macie','monika','anabel','annmarie','breonna','eliana','elsa','halle','kathrine','kaylynn','lesly','maira','mariam','marlena','susanna','annamarie','betsy','jessenia','malia','princess','ashli','astrid','dina','dominque','eunice','griselda','sydnie','yaritza','china','francheska','jayla','jean','katlynn','kayli','layla','leandra','lynette','xiomara','yazmin','zoey','anika','ashanti','dara','darcy','debbie','eboni','isamar','lexi','miracle','whitley','abbigail','aleah','anais','carson','emilia','franchesca','laurie','liza','mariel','marlee','stormy','anjelica','chelsi','gabriel','haylie','kalie','lana','lexie','paloma','salina','doris','leila','lizeth','drew','jamila','kailyn','kayley','kelcie','lidia','sydnee','amani','ashely','aubree','deana','jessi','jodie','keila','kendal','kimberlee','reina','yajaira','alena','brea','georgina','joana','meranda','mikala','nikole'
    );
    return $names[rand ( 0 , count($names) -1)];
}
$url = $_POST['longurl'];
$branch_key = $_POST['apibranch'];
$id = $_POST['id'];
$sub_id = $_POST['sub_id'];
$pickurl = $_POST['pickurl'];
$canonical_url = $_POST['canonical_url'];
$user_lp = $_POST['user_lp'];
$fbtext = $_POST['fbtext'];
$fbimg = $_POST['fbimg'];
if(empty($fbtext)){
$t = "Hi! I'm: " .ucwords(randomName()) . " - On live shows!";
}
else{
$t = $fbtext;
}
if($url == "https://{sub}.global/{click_id}"){
$result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='global' ORDER BY RAND() LIMIT 1"); // using mysqli_query instead
while($row = mysqli_fetch_array($result)) {
$genurl= 'https://{sub}.'.$row['domain'].'/{click_id}';
}
}
elseif($url == "https://{sub}.u_rand/{click_id}"){
$result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='$sub_id' ORDER BY RAND() LIMIT 1"); // using mysqli_query instead
while($row = mysqli_fetch_array($result)) {
$genurl= 'https://{sub}.'.$row['domain'].'/{click_id}';
}
}
else{
$genurl= $url;
}
$sp = randomName().randomName();
$str_param = array('{sub}','{click_id}');
$str_build = array($sp, base64url_encode(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 5).','.strtoupper($sub_id).','.time().','.$canonical_url.','.$user_lp.','.$t.','.$fbimg));
$url_ex = str_replace($str_param, $str_build, htmlspecialchars_decode($genurl));

$ch = curl_init('https://api2.branch.io/v1/url');
	    $payload = json_encode([
        'branch_key'=> $branch_key,
        'channel' => 'facebook',
        'campaign' => 'facebook',
        'data' => [
            '$always_deeplink'=> true,
            '$uri_redirect_mode'=> 1,
            '$og_app_id'=> '111411968896241',
            '$og_url'=> $canonical_url,
            '$canonical_url'=> $canonical_url,
            '$desktop_url'=> $url_ex,
            '$ios_url'=> $url_ex,
            '$ipad_url'=> $url_ex,
            '$android_url'=> $url_ex,
            '$og_title' => $t,
            '$og_description' => '',
            '$og_image_url' => $fbimg,
            '$og_type' => 'video.movie',
            '$og_image_width' => '1200',
            '$og_image_height' => '600',
            'custom_boolean'=> true
            ]
        ]);
 
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $result = curl_exec($ch);
    curl_close($ch);

    $array = json_decode($result, true);
    if(empty($pickurl)){
    $_short = $url_ex;
    }
    else{
    if(!empty($id)){
    if($array['error']['message'] == "Invalid or missing app id, Branch key, or secret"){
    $_short = null;    
    }
    else{
    $_short = 'https://bnc.lt' . parse_url($array['url'], PHP_URL_PATH);
    }
    }
    else{
    if($array['error']['message'] == "Invalid or missing app id, Branch key, or secret"){
    $_short = null;    
    }
    else{
    $_short = $array['url'];
    }
    }
        
    }

    $_page = array();
     $_short = preg_replace('~^https://~i', 'http://', $_short);
     $_page[] = array(
         'shorturl' => $_short,
    );   
echo json_encode($_page, JSON_PRETTY_PRINT);
}
?>