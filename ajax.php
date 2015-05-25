<?php
require_once './word/src/PhpWord/Autoloader.php';
include("PDF/mpdf.php");

    
function findClass($class) {
    $class = str_replace('\\', '/', $class) . '.php';
    if (file_exists($class)) {
        require_once "$class";
    }
}

spl_autoload_register('findClass');

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

\util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);

use model\entity\bad_word;
use model\entity\user;
use model\entity\news;
use model\entity\global_news;
use model\entity\district;
use model\service\GlobalService;
use model\entity\stopword;
use model\entity\PasswordRecovery;
use model\entity\VKGroups;
use model\entity\VkUser;
use model\service\UserService;
use model\service\InfoPulseService;
use model\entity\statistic_search;
use model\entity\PostComments;
use util\Request;

if(!empty($_POST['mainregister'])){
    
    //Главная регистрация
 
 $newLogin = (new \util\Request())->getPostValue('newUserLogin');
 $newMail = (new \util\Request())->getPostValue('newMail');
 
 //Если не пуст новый логин
 if(!empty($newLogin)){
     
     $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :login");
     $stmt->bindParam(":login",$newLogin);
     $stmt->execute();
     $user = $stmt->fetchObject(user::class);
     
     if(is_a($user,'model\entity\user')){
         
         echo "used_login";
         
     }//if
     //Если логин не используется и пароль на форме не пуст
     else if(!empty($newMail)){
         
            $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Email = :mail");
            $stmt->bindParam(":mail",$newMail);
            $stmt->execute();
            $user = $stmt->fetchObject(user::class);

            if(is_a($user,'model\entity\user')){

                echo "used_email";

            }//if
            else{
                
                echo $newMail;
                
            }//else
            
     }//if !empty($newMail)
     else {echo "no";}
     
 }//login
 
}
else if(!empty($_POST['fastregister'])){
    
    $login = (new \util\Request())->getPostValue('userLogin');
    $email = (new \util\Request())->getPostValue('userEmail');
        
 //Регистрация

 if(!empty($login) && !empty($email)){
     
    $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :login");
    $stmt->bindParam(":login",$login);
    $stmt->execute();
    $user = $stmt->fetchObject(user::class);
    
    if(is_a($user, 'model\entity\user')){
        
        echo "used_login";
        
    }//if
    else{
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Email = :email");
        $stmt->bindParam(":email",$email);
        $stmt->execute();
        $user = $stmt->fetchObject(user::class);
        
        if(is_a($user, 'model\entity\user')){
        
            echo "used_email";
        
        }//if
        else{
            echo "acc_free";
        }
    }
    
    
 }
 
}
else if(!empty($_POST['authorize'])){
     
    $userPS = (new \util\Request())->getPostValue('userPS');
    $userLE = (new \util\Request())->getPostValue('userLE');
    
    $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE ( (Login = :login or Email =:login) and Password = :pass)");
    $stmt->bindParam(":login",$userLE);
    $stmt->bindParam(":pass",$userPS);
    $stmt->execute();
    
    $user = $stmt->fetchObject(user::class);
    
       if(is_a($user, 'model\entity\user')){
               echo "yes";
       }//if
       else{
            echo "no_authorize";
       }//else
     
 }//if
else if(!empty($_POST['DeleteMyNews'])){
    
    $current_user = (new Request())->getSessionValue('user_info_plus');
    
    $post_id = (new Request())->getPostValue('post_id');
    $post_g_id = (new Request())->getPostValue('post_id');
    \util\MySQL::$db = new PDO('mysql:host=localhost;dbname=user1187254_u304199710_info', 'u304199710_alex', '1qaz2wsx');
    
    $gl = new GlobalService();
    $gl->DeleteGlobalNewsById($post_g_id);
    



    
     echo "1";
}//else if
else if(!empty($_POST['EmailSuccess'])){
    
    $r = new Request();
    
    $db_user = $r->getPostValue('Owner');
    
    $NewMail = $r->getPostValue('NewPersonalMail');
    
    $stmt = \util\MySQL::$db->prepare("UPDATE users SET Email = :email WHERE Login = :owner");
    $stmt->bindParam(":email",$NewMail);
    $stmt->bindParam(":owner",$db_user);
    
    $result = $stmt->execute();
    
    if($result == 1){
        echo "ok";
    }
    else{
        echo "$result";
    }
   
}
else if(!empty($_POST['CheckPassword'])){
    
    $r = new Request();
    $owner = $r->getPostValue('Owner');
    $input_password = $r->getPostValue('UserPassword');
    
    $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :owner");
    $stmt->bindParam(":owner",$owner);
    
    $stmt->execute();
    
    $user = $stmt->fetchObject(user::class);
    
    if(is_a($user,'model\entity\user')){
        
        $pass = $user->getPassword();
        if($pass == $input_password){
            echo "password_correct";
        }//if
        else{
            echo "password_incorrect";
        }//else
    }//if
    else{echo "owner_problem($owner)";}//else
    
}
else if(!empty($_POST['ChangePassword'])){
    
    $r = new Request();
    $NewPass = $r->getPostValue('NewPassword');
    $Owner = $r->getPostValue('Owner');
    
    $stmt = \util\MySQL::$db->prepare("UPDATE users SET Password = :pass WHERE Login = :owner");
    $stmt->bindParam(":pass",$NewPass);
    $stmt->bindParam(":owner",$Owner);
    
    $res = $stmt->execute();
    
    if($res == 1){
            
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :owner");
        $stmt->bindParam(":owner",$Owner);
        $stmt->execute();
        $user = $stmt->fetchObject(user::class);
        
        if(is_a($user,'model\entity\user')){
            
            $id = $user->getId();
            $stmt = \util\MySQL::$db->prepare("UPDATE passwordinfo SET LastChangePassword = NOW() WHERE user = :owner");
            $stmt->bindParam(":owner",$id);
            $stmt->execute();
            
            echo "ok";
            
        }//if
        
    }//if
    else{
        echo "$res";
    }//else
    
}//else if
else if(!empty($_POST['ChangeFirstName'])){
    
    $r = new Request();
    $NewFirstName = $r->getPostValue('NewFirstName');
    $Owner = $r->getPostValue('Owner');
    $stmt = \util\MySQL::$db->prepare('SET NAMES utf8');
    $stmt->execute();
    
    $stmt = \util\MySQL::$db->prepare("UPDATE users SET FirstName = :firstName WHERE Login = :owner");
    $stmt->bindParam(":firstName",$NewFirstName);
    $stmt->bindParam(":owner",$Owner);
    
    $res = $stmt->execute();
    
    if($res == 1){
        echo "ok";
    }//if
    else{
        echo "res = $res, Owner = $Owner, new name = $NewFirstName";
        
    }//else
}
else if(!empty($_POST['ChangeLastName'])){
    
    $r = new Request();
    $NewLastName = $r->getPostValue('NewLastName');
    $Owner = $r->getPostValue('Owner');
    
    $stmt = \util\MySQL::$db->prepare('SET NAMES utf8');
    $stmt->execute();
    
    $stmt = \util\MySQL::$db->prepare("UPDATE users SET LastName = :lastName WHERE Login = :owner");
    $stmt->bindParam(":lastName",$NewLastName);
    $stmt->bindParam(":owner",$Owner);
    
    $res = $stmt->execute();
    
    if($res == 1){
        echo "ok";
    }//if
    else{
        echo "res = $res, Owner = $Owner, new last name = $NewLastName";
        
    }//else
}
else if(!empty ($_POST['GetCountOfNews'])){
    
    $stmt = \util\MySQL::$db->prepare("SELECT COUNT(id) FROM global_news");
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_BOTH);
    
    if($count >= 0){
        echo "$count[0]";
    }//if
    else{
        echo "$count";
    }//else
}//else if
else if(!empty ($_POST['GET_VK_NEW_COUNT'])){
    
    $glob_serv = new GlobalService();
    $count_vk_news = $glob_serv->GetVkPostsCount();
    
    echo $count_vk_news;
     
}//else if
else if(!empty ($_POST['ADD_DISTRICT'])){
    
    $glob_serv = new GlobalService();
    $r = new Request();
    $new_district = $r->getPostValue('District');
    
    $distr =  $glob_serv->GetDistrictByName($new_district);
    
    if(!is_a($distr,'model\entity\district')){
        
        $result_insert = $glob_serv->AddDistrict($new_district);
        
        if($result_insert){
            $distr = $glob_serv->GetDistrictByName($new_district);
            echo "{$distr->getId()}";
        }//if
        else{
            echo "not inserted";
        }//else
    }//if
    else{
        echo "exist";
    }//else
}//else
else if(!empty ($_POST['ADD_STOP_WORD'])){
    
    $glob_serv = new GlobalService();
    $r = new Request();
    $new_stop_word = $r->getPostValue('stop_word');
    
    $ex_stop_word =  $glob_serv->GetStopWordByTitle($new_stop_word);
    
    if(!is_a($ex_stop_word,'model\entity\district')){
        
        $result_insert = $glob_serv->AddStopWord($new_stop_word);
        
        if($result_insert){
            $result_insert = $glob_serv->GetStopWordByTitle($new_stop_word);
            
            echo "{$result_insert->getId()}";
        }//if
        else{
            echo "exist";
        }//else
    }//if
    else{
        echo "exist";
    }//else
    
}
else if(!empty ($_POST['SET_COOKIE_OFFSET'])){
    
    $request = new Request();
    $request->setCookiesWithKey('offset', 0);
    
}
else if(!empty ($_POST['UPDATE_STOP_WORD'])){
    
    $request = new Request();
    $id_to_update = $request->getPostValue('stop_id');
    $update_stop_word = $request->getPostValue('new_word');
    $glob_news_service = new GlobalService();
    
    $result = $glob_news_service->UpdateStopWord($id_to_update, $update_stop_word);
    
    if($result){
        echo "ok";
    }//if
    else{
        echo "error";
    }//else
}
else if(!empty ($_POST['CHECK_STOP_WORD'])){
    
    $request = new Request();
    $word_to_update = $request->getPostValue('stop_word');
    
    $glob_news_service = new GlobalService();
    
    $word = $glob_news_service->GetStopWordByTitle($word_to_update);
    
    if(is_a($word,'model\entity\stopword')){
        echo "exist";
    }
    else{
        echo "ok";
    }
}
else if(!empty ($_POST['UPDATE_DISTRICT'])){
    
    $request = new Request();
    $id_to_update = $request->getPostValue('district_id');
    $new_title = $request->getPostValue('new_district_title');
    $glob_news_service = new GlobalService();
    
    $result = $glob_news_service->UpdateDistrict($id_to_update, $new_title);
    
    if($result){
        echo "ok";
    }//if
    else{
        echo "error";
    }//else
}
else if(!empty ($_POST['CHECK_DISTRICT'])){
    
    $request = new Request();
    $distr = $request->getPostValue('district');
    
    $glob_news_service = new GlobalService();
    
    $word = $glob_news_service->GetDistrictByName($distr);
    
    if(is_a($word,'model\entity\district')){
        echo "exist";
    }
    else{
        echo "ok";
    }
}
else if(!empty ($_POST['GET_VK_POST_ACTION'])){
        $gl_service = new GlobalService();
        
        $count = $gl_service->GetVkPostsCount();
        
        echo "$count";
}//else if
else if(!empty ($_POST['GET_TW_POST_ACTION'])){
    
        $gl_service = new GlobalService();
        
        $count = $gl_service->GetTwitterPostsCount();
        
        echo "$count";
}//else if
else if(!empty ($_POST['ADD_VK_GROUP'])){
    
        $r = new Request();
        
        $group = $r->getPostValue('GROUP');
        
        $newGroup = new VKGroups();
        
        $newGroup->setGroupTitleId($group);
        
        $gl_service = new GlobalService();
        
        if(is_a($gl_service->GroupExist($group),VKGroups::class)){
            echo "exist";
        }
        else{
           if($gl_service->AddGroup($newGroup)){
               
               $gr = $gl_service->GroupExist($group);
               if(is_a($gr,VKGroups::class)){
                    echo "{$gr->getId()}";
               }
          
           }
           else{
               echo "not inserted";
           }
            
        }
        
        
}//else if
else if(!empty ($_POST['UPDATE_VK_GROUP'])){
    
        $r = new Request();
        $gl_service = new GlobalService();
        
        $group_id = $r->getPostValue('group_id');
        $new_group_title = $r->getPostValue('group_new_title');
        
        if(is_a($gl_service->GroupExist($new_group_title),VKGroups::class)){
            echo "exist";
        }
        else{
            if($gl_service->UpdateGroup($group_id,$new_group_title)){
                echo "ok";
            }
        }
}//else if
else if(!empty ($_POST['ADD_INFO_USER'])){
    
        $r = new Request();
        
        $user = $r->getPostValue('USER');
        
        $InfoPulseService = new InfoPulseService();
        $result = $InfoPulseService->AddInfoPulseUser($user);
        
        if(empty($result)){
            echo "exist";
        }
        else{
           echo json_encode($result);
        }
        
        
}//else if
else if(!empty ($_POST['UPDATE_INFO_USER'])){
    
    $r = new Request();
    $info_service = new InfoPulseService();
    
    $newUserName = $r->getPostValue('newUserName');
    $userID = $r->getPostValue('user_id');
    
    if($info_service->UpdateInfoUser($userID,$newUserName) != NULL){
        echo 'ok';
    }
    else{
        echo 'exist';
    }
        
}//else if
else if(!empty ($_POST['GET_SPECIFIC_NEWS'])){
    
        $r = new Request();
        $gl_service = new GlobalService();
        
        $st = $r->getPostValue('search_type');
        
        $news = $gl_service->GetNewsBySearchType($st);
        
        if(count($news) == 0){
            echo "nothing";
        }
        else{
            echo json_encode($news);
        }
        
}//else if
else if(!empty ($_POST['UPDATE_WORD'])){
        
        $r = new Request();
        $gl_service = new GlobalService();
        
        $bad_id = $r->getPostValue('word_id');
        $bad_title = $r->getPostValue('new_word');
        
        $res = $gl_service->UpdateWord($bad_id,$bad_title);
        
        if($res != 0){
            echo "ok";
        }
        else{
            echo "not inserted";
        }
        
}//else if
else if(!empty ($_POST['CKECK_WORD'])){
    
        $r = new Request();
        $gl_service = new GlobalService();
        
        $st = $r->getPostValue('word');
        
        $bad = $gl_service->CheckWord($st);
        
        if($bad != NULL){
            echo "exist";
        }
        else {
            echo "ok";
        }
        
}//else if
else if(!empty ($_POST['ADD_WORD'])){
    
        $r = new Request();
        $gl_service = new GlobalService();
        
        $st = $r->getPostValue('new_word');
        
        $bad = $gl_service->AddBadWord($st);
        
        if($bad != NULL){
            echo $bad->getId();
        }
        else {
            echo "bad";
        }
        
}//else ifADD_WORD
else if(!empty ($_POST['GET_ALL_USERS'])){
    
    $r = new Request();
    $gl_service = new GlobalService();
    
    $users = $gl_service->GetAllUsers();
    
    echo json_encode($users);
    
}
else if(!empty ($_POST['GET_ALL_DISTRICTS'])){
    
    $r = new Request();
    $gl_service = new GlobalService();
    
    $districts = $gl_service->GetDistricts();
    
    echo json_encode($districts);
    
}
else if(!empty ($_POST['HIDE_ALL_NEWS'])){
    
    $r = new Request();
    
    $word_to_hide = $r->getPostValue('word');
    
    $gl_service = new GlobalService();
    $gl_service->GetBadWordByTitle($word_to_hide);
    
    $result = $gl_service->HideGlobalNews($word_to_hide);
    
    if($result != FALSE){
        echo "ok";
    }//if
    else{
        echo "fail";
    }//else
}
else if(!empty ($_POST['HIDE_ALL_NEWS_FIRST'])){
    
    $gl_service = new GlobalService();
     
    $words = $gl_service->GetBadWords();
    
    foreach ($words as $current_word) {
        
        $gl_service->HideGlobalNews($current_word->getWord());
        
    }//foreach

}
else if(!empty ($_POST['SHOW_ALL_NEWS_FIRST'])){
    
    $gl_service = new GlobalService();
     
    $gl_service->ShowGlobalNews();
    

}
else if(!empty ($_POST['HIDE_SPECIFIC_NEWS'])){
    
    $r = new Request();
    
    $gl_service = new GlobalService();
    
    $p_id = $r->getPostValue('post_id');
    
    $gl_service->HideSpecificNews($p_id);
    

}
else if(!empty ($_POST['SHOW_SPECIFIC_NEWS'])){
    
    $r = new Request();
    
    $gl_service = new GlobalService();
     
    $p_id = $r->getPostValue('post_id');
    
    $gl_service->ShowSpecificNews($p_id);
    
    
    
}
else if(!empty ($_POST['GET_SPECIFIC_GLOBAL_NEWS'])){
    
    $r = new Request();
    
    $gl_service = new GlobalService();
     
    $p_id = $r->getPostValue('post_id');
    
    $res = $gl_service->GetGlobalNewsById($p_id);
    
    echo json_encode($res);
    
    
}
else if(!empty ($_POST['DELETE_BAD_WORD'])){
    
    $r = new Request();
    
    $gl_service = new GlobalService();
     
    $p_id = $r->getPostValue('word_id');
    
    $res = $gl_service->DeleteBadWord($p_id);
    
    if($res){
        echo "ok";
    }
    else{
        echo "fail";
    }
    
}
else if(!empty ($_POST['REMOVE_GROUP'])){
    
    $r = new Request();
    
    $gl_service = new GlobalService();
     
    $p_id = $r->getPostValue('group_id');
    
    $res = $gl_service->DeleteVkGroupById($p_id);
    
    if($res){
        echo "ok";
    }
    else{
        echo "fail";
    }
    
}
else if(!empty ($_POST['REMOVE_INFO_USER'])){
    
    $r = new Request();
    $service = new InfoPulseService();
    
    $p_id = $r->getPostValue('user_id');
    
    $res = $service->DeleteInfoPulseUser($p_id);
    
    if($res){
        echo "ok";
    }
    else{
        echo "fail";
    }
    
}
else if(!empty($_POST['REMOVE_SOCIAL'])){
    
    $r = new Request();
    $service = new InfoPulseService();
    
    $social_id = $r->getPostValue('social_id');
    
    $res = $service->DeleteSocial($social_id);
    
    if($res){
        echo "ok";
    }
    else{
        echo "fail";
    
    }
    
}
else if(!empty($_POST['UPDATE_SOCIAL'])){
    
    $r = new Request();
    $service = new InfoPulseService();
    
    $social_id = $r->getPostValue('social_id');
    $social_title_new = $r->getPostValue('new_title');
    
    $res = $service->UpdateSocial($social_id,$social_title_new);
    
    if($res){
        echo "ok";
    }
    else{
        echo "fail";
    
    }
    
}
else if(!empty($_POST['ADD_SOCIAL_TO_USER'])){
    
    $r = new Request();
    $service = new InfoPulseService();
    
    $user_id = $r->getPostValue('user_id');
    $social_id = $r->getPostValue('social_id');
    $accs_name = $r->getPostValue('accs_name');
    
    $res = $service->AddSocialToUser($user_id,$social_id,$accs_name);
    
    if($res){
        echo 'ok';
    }
    else{
        echo 'fail';
    }
    
}
else if(!empty($_POST['GET_INFO_USERS'])){
    
    $info_service = new InfoPulseService();
    $users = $info_service->GetAllInfoUsers();
    echo json_encode($users);
    
}//else if
else if(!empty($_POST['ADD_NEW_SOCIAL'])){
    
    $r = new Request();
    $service = new InfoPulseService();
    
    $social_new_name = $r->getPostValue('SocialName');
    
    $res = $service->AddSocialType($social_new_name);
    
    if($res != NULL){
        echo json_encode($res);
    }
    else{
        echo "fail";
    
    }
    
}
else if(!empty($_POST['GetUsersStartWith'])){
    
    $r = new Request();
    $startWith = $r->getPostValue('start'); 
    
    $gl_service = new GlobalService();
    
    $users = $gl_service->GetUsersStartWith($startWith);
    
    echo json_encode($users);
    
}
else if(!empty($_POST['GetDistrictsStartWith'])){
    
    $r = new Request();
    $startWith = $r->getPostValue('start'); 
    
    $gl_service = new GlobalService();
    
    $districts = $gl_service->GetDistrictsStartWith($startWith);
    
    echo json_encode($districts);
    
}
else if(!empty($_POST['REMOVE_DISTRICT'])){
    
    $r = new Request();
    $startWith = $r->getPostValue('districtID'); 
    
    $gl_service = new GlobalService();
    
    $districts = $gl_service->RemoveDistrict($startWith);
    if($districts){
        echo "ok";
    }
    else{
        echo "fail";
    }
    
}
else if(!empty($_POST['REMOVE_STOPWORD'])){
    
    $r = new Request();
    $startWith = $r->getPostValue('wordID'); 
    
    $gl_service = new GlobalService();
    
    $word = $gl_service->RemoveStopWord($startWith);
    if($word){
        echo "ok";
    }
    else{
        echo "fail";
    }
    
}
else if(!empty($_POST['GET_ALL_NEWS_COUNT'])){
    
    $r = new Request();
    $gl_service = new GlobalService();
    
    echo $gl_service->GetAllNewsCount();
    
}
else if(!empty($_POST['GENERATE_PASSWORD'])){

    $r = new Request();
    
    $mail = $r->getPostValue('user_mail');
    
    $user_service = new UserService();
    $user = $user_service->getUser($mail);
    
    if(is_a($user, 'model\entity\user')){
          
        $result = $user_service->GenerateNewPassword($user->getId());
        
        if($result){
            echo "ok";
        }
        else {
            echo "already";
        }
        
    }//if
    else {
            echo "fail";
    }
    
}
else if(!empty($_POST['CHANGE_PASSWORD'])){

    $r = new Request();
    
    $accessToken =  $r->getPostValue('access_token');
    $user_id = $r->getCookieValue('user_recovery');
    $user_service = new UserService();
    
    $at = $user_service->GetAccessToken($user_id);
    
    if($at == $accessToken){
        
        $user_pass = $r->getPostValue('user_password');
        $res = $user_service->UpdateUserPassword($user_id,$user_pass);
        
        if($res){
            echo 'ok';
        }//if
        else{
            echo 'fail';
        }//else
        
    }//if
    else{
        echo "fali access";
    }//else
    
    
    
    
}
else if(!empty ($_POST['GET_NEWS_BY_POI'])){
    
        
        $r = new Request();
        
        $user = $r->getPostValue('POI_USER');

        $gl_service = new GlobalService();

        $res = $gl_service->GetPOINews($user);
        
        echo json_encode($res);
        
        
}//else if
else if(!empty($_POST['SHOW_ALL_HIDDEN'])){
    
    $user_service = new UserService();
    $r = new Request();
    $user_id = $r->getPostValue('user_id');
    $res = $user_service->setUserMetaValue('show_all_hidden_news',$user_id, 'true');
    if($res){
        echo 'true';
    }
    else{
        echo 'false';
    }
    
}
else if(!empty($_POST['HIDE_ALL_HIDDEN'])){
    
    $user_service = new UserService();
    $r = new Request();
    $user_id = $r->getPostValue('user_id');
    $res = $user_service->setUserMetaValue('show_all_hidden_news',$user_id, 'false');
    if($res){
        echo 'true';
    }
    else{
        echo 'false';
    }

}
else if(!empty ($_POST['DETE_SEARCH'])){
    
    $r = new Request();
    $glob = new GlobalService();
    
    $left = $r->getPostValue('left');
    $right = $r->getPostValue('right');
    
    $news = $glob->GetGlobalNewsBetweenDate($left,$right);
    
    echo json_encode($news);
    
    
}
else if(!empty ($_POST['GET_FB_USERS'])){
    
    $glob = new GlobalService();
    
    $fb_users = $glob->GetFbUsers();
   
    echo json_encode($fb_users);
    
}
else if(!empty($_POST['GET_VK_USERS'])){
    
    $glob = new GlobalService();
    
    $vk_users = $glob->GetVkUsers();
    
    echo json_encode($vk_users);
    
    
}
else if(!empty($_POST['GET_TW_USERS'])){
    
    $glob = new GlobalService();
    $tw_users = $glob->GetTwitterUsers();
    echo json_encode($tw_users);
    
}
else if(!empty ($_POST['GET_VK_GROUPS'])){
    
    $glob = new GlobalService();
    $vk_groups = $glob->GetVkGroups();
    echo json_encode($vk_groups);
    
}
else if(!empty ($_POST['GET_OUR_USER'])){
    
    $user_service = new UserService();
    
    $our_users = $user_service->getAllUsers();
    
    echo json_encode($our_users);
    
}
else if(!empty ($_POST['GET_NEWS_START_WITH'])){
    
    $glob = new GlobalService();
    $r = new Request(); 
    
    $start_text = $r->getpostValue('start_text');
    
    $news = $glob->GetNewsByShortDescription($start_text);
    
    echo json_encode($news);
    
}
else if(!empty ($_POST['GET_DOWNLOAD_LINK'])){
    
    $r = new Request();
    
    $glob = new GlobalService();
    
    $left = $r->getPostValue('left');
    $right = $r->getPostValue('right');
    
    $news = $glob->GetGlobalNewsBetweenDate($left,$right);
    
    
    \PhpOffice\PhpWord\Autoloader::register();

    $php_word = new \PhpOffice\PhpWord\PhpWord();
    $setcion = $php_word->addSection();
    $setcion->addText("Отчет с $left по $right");
    
    $tableStyle = array(
        'borderColor' => '000000',
        'borderSize' => 6,
        'cellMargin' => 20,
    );

    $php_word->addTableStyle('myTable', $tableStyle);

    $table = $setcion->addTable('myTable');

    $first_row = $table->addRow();
    $first_row->addCell(2500)->addText('Район');
    $first_row->addCell(2500)->addText('Количество записей');
    $first_row->addCell(2500)->addText('Стоп слова');
    
    foreach($news as $spec_news){
        
        $first_row = $table->addRow();
        $first_row->addCell(2500)->addText($spec_news->District_str);
        $first_row->addCell(2500)->addText($spec_news->Count);
        $first_row->addCell(2500)->addText($spec_news->Stop_words);
        
    }
    
    $word_writer = \PhpOffice\PhpWord\IOFactory::createWriter($php_word,'Word2007');
    
    $file_name = md5(rand(-100000, 10000));
    
    $word_writer->save("documents/$file_name.docx");
    header('Content-Type: docx; charset=utf-8');
    header("Content-Disposition: attachment; filename=$file_name.docx");
    ob_end_clean();
    ob_start();
    $content = file_get_contents("documents/$file_name.docx");
    echo $file_name.'.docx';
    
    ob_end_flush();
    
}
else if(!empty ($_POST['GET_DOWNLOAD_DISTRICTS'])){
    
    $r = new Request();
    
    $glob = new GlobalService();
    
    
    $news = $glob->GetStatisticByDistricts();
    
    
    \PhpOffice\PhpWord\Autoloader::register();

    $php_word = new \PhpOffice\PhpWord\PhpWord();
    $setcion = $php_word->addSection();
    $setcion->addText("Статистика по районам за все время");
    
    $tableStyle = array(
        'borderColor' => '000000',
        'borderSize' => 6,
        'cellMargin' => 20,
    );

    $php_word->addTableStyle('myTable', $tableStyle);

    $table = $setcion->addTable('myTable');

    $first_row = $table->addRow();
    $first_row->addCell(2500)->addText('Район');
    $first_row->addCell(2500)->addText('Количество записей');
    
    foreach($news as $spec_news){
        
        $first_row = $table->addRow();
        $first_row->addCell(2500)->addText($spec_news->District_str);
        $first_row->addCell(2500)->addText($spec_news->Count);
        
    }
    
    $word_writer = \PhpOffice\PhpWord\IOFactory::createWriter($php_word,'Word2007');
    
    $file_name = md5(rand(-100000, 10000));
    
    $word_writer->save("documents/$file_name.docx");
    header('Content-Type: docx; charset=utf-8');
    header("Content-Disposition: attachment; filename=$file_name.docx");
    ob_end_clean();
    ob_start();
    $content = file_get_contents("documents/$file_name.docx");
    echo $file_name.'.docx';
    
    ob_end_flush();
    
}
else if(!empty ($_POST['GET_DOWNLOAD_STOP_WORDS'])){
    
    $r = new Request();
    
    $glob = new GlobalService();
    
    $news = $glob->GetStatisticByStopWords();
    
    
    \PhpOffice\PhpWord\Autoloader::register();

    $php_word = new \PhpOffice\PhpWord\PhpWord();
    $setcion = $php_word->addSection();
    $setcion->addText("Статистика по стоп-словам за все время");
    
    $tableStyle = array(
        'borderColor' => '000000',
        'borderSize' => 6,
        'cellMargin' => 20,
    );

    $php_word->addTableStyle('myTable', $tableStyle);

    $table = $setcion->addTable('myTable');

    $first_row = $table->addRow();
    $first_row->addCell(2500)->addText('Стоп-слово');
    $first_row->addCell(2500)->addText('Количество записей');
    
    foreach($news as $spec_news){
        
        $first_row = $table->addRow();
        $first_row->addCell(2500)->addText($spec_news->Stop_words);
        $first_row->addCell(2500)->addText($spec_news->Count);
        
    }
    
    $word_writer = \PhpOffice\PhpWord\IOFactory::createWriter($php_word,'Word2007');
    
    $file_name = md5(rand(-100000, 10000));
    
    $word_writer->save("documents/$file_name.docx");
    header('Content-Type: docx; charset=utf-8');
    header("Content-Disposition: attachment; filename=$file_name.docx");
    ob_end_clean();
    ob_start();
    $content = file_get_contents("documents/$file_name.docx");
    echo $file_name.'.docx';
    
    ob_end_flush();
    
}
else if(!empty ($_POST['GET_DOWNLOAD_LINK_PDF'])){
    
    $r = new Request();
    
    $glob = new GlobalService();
    $news_id = $r->getPostValue('news_id');
    $news = $glob->GetGlobalNewsById($news_id);
    $text = $news->getDescription();
    $title = $news->getTitle();
    $date  = $news->getDate();
    $post_distr = $news->getDistrict_str();
    $post_distr = trim($post_distr);
    
    $mpdf=new mPDF('utf-8');
    $mpdf->WriteHTML('<h5> Дата и время публикации: '.$date.'</h5>');
    $mpdf->WriteHTML('<h6> Район: '.$post_distr.'</h6>');
    $mpdf->WriteHTML('<h6> Стоп-слово: '.$news->getStop_words().'</h6>');
    
    if($news->Images != NULL){
        $mpdf->WriteHTML('<h6>Прилагаются фото:</h6>');
        
        $img = $news->Images;
                        
        if ($img){
            if(strstr($img,'http')){
               $mpdf->WriteHTML('<div style="max-width: 30%;"><img src="'.$img.'" alt="'.$img.'"></div>');                       
            }//if
            else{
                $img_array = explode(',', $img);
                $count = count($img_array);
                unset($img_array[$count-1]);

                foreach ($img_array as $spec_image) {
                    $mpdf->WriteHTML('<img alt="" style="display: inline-block; max-width: 30%; margin: 10px;" src="files/'.$spec_image.'" />');        
                }//foreach
            }//else
        }//if img

    }
    $mpdf->WriteHTML('<h4>'.$title.'</h4>');
    $mpdf->WriteHTML('<p>'.$text.'</p>');
    
    $filename = md5($news_id.rand(-10000,10000));
    
    $mpdf->Output("documents/$filename.pdf");
    
    echo $filename.'.pdf';
    
}//else if

else if(!empty ($_POST['GET_DOWNLOAD_LINK_PDF_PERSON'])){
    
    $r = new Request();
    
    $glob = new GlobalService();
    $news_id = $r->getPostValue('news_id');
    $news = $glob->GetPersonNewsById($news_id);
    $text = $news->getDescription();
    $title = $news->getTitle();
    $date  = $news->getDate();
    $post_distr = $news->getDistrict_str();
    $post_distr = trim($post_distr);
    
    $mpdf=new mPDF('utf-8');
    $mpdf->WriteHTML('<h5> Дата и время публикации: '.$date.'</h5>');
    $mpdf->WriteHTML('<h6> Район: '.$post_distr.'</h6>');
    $mpdf->WriteHTML('<h6> Стоп-слово: '.$news->getStop_words().'</h6>');
    
    if($news->Images != NULL){
        $mpdf->WriteHTML('<h6>Прилагаются фото:</h6>');
        
        $img = $news->Images;
                        
        if ($img){
            if(strstr($img,'http')){
               $mpdf->WriteHTML('<div style="max-width: 30%;"><img src="'.$img.'" alt="'.$img.'"></div>');                       
            }//if
            else{
                $img_array = explode(',', $img);
                $count = count($img_array);
                unset($img_array[$count-1]);

                foreach ($img_array as $spec_image) {
                    $mpdf->WriteHTML('<img alt="" style="display: inline-block; max-width: 30%; margin: 10px;" src="files/'.$spec_image.'" />');        
                }//foreach
            }//else
        }//if img

    }
    $mpdf->WriteHTML('<h4>'.$title.'</h4>');
    $mpdf->WriteHTML('<p>'.$text.'</p>');
    
    $filename = md5($news_id.rand(-10000,10000));
    
    $mpdf->Output("documents/$filename.pdf");
    
    echo $filename.'.pdf';
    
}//else if
else if(!empty ($_POST['GET_DOWNLOAD_LINK_PDF_BY_DISTRICTS'])){
    
    $glob = new GlobalService();
    $mpdf = new mPDF('utf-8');
    
    foreach($_POST['districts'] as $district_title){
         
         if(trim($district_title) != NULL){
             
            $mpdf->WriteHTML('<center><h2>'.$district_title.'</h2></center>');
            $fineded_district = $glob->GetDistrictByName($district_title);
            if(is_a($fineded_district,'model\entity\district')){
                $dis_id = $fineded_district->getId();
                $news = $glob->GetGlobalNewsByDisrtictId($dis_id);

                foreach($news as $spec_news){

                    $post_distr = $spec_news->getDistrict_str();
                    $post_distr = trim($post_distr);
                    $date  = $spec_news->getDate();
                    $text = $spec_news->getDescription();
                    $title = $spec_news->getTitle();
                    $mpdf->WriteHTML('<h3> Запись номер: '.$spec_news->getId().'</h3>');
                    $mpdf->WriteHTML('<h5> Дата и время публикации: '.$date.'</h5>');
                    $mpdf->WriteHTML('<h6> Район: '.$post_distr.'</h6>');
                    $mpdf->WriteHTML('<h6> Стоп-слово: '.$spec_news->getStop_words().'</h6>');

                    if($spec_news->Images != NULL){
                        $mpdf->WriteHTML('<h6>Прилагаются фото:</h6>');

                        $img = $spec_news->Images;

                        if ($img){
                            if(strstr($img,'http')){
                               $mpdf->WriteHTML('<div style="max-width: 30%;"><img src="'.$img.'" alt="'.$img.'"></div>');                       
                            }//if
                            else{
                                $img_array = explode(',', $img);
                                $count = count($img_array);
                                unset($img_array[$count-1]);

                                foreach ($img_array as $spec_image) {
                                    $mpdf->WriteHTML('<img alt="'.$img.'" style="display: inline-block; max-width: 30%; margin: 10px;" src="files/'.$spec_image.'" />');        
                                }//foreach
                            }//else
                        }//if img

                    }//if images

                    $mpdf->WriteHTML('<h4>'.$title.'</h4>');
                    $mpdf->WriteHTML('<p>'.$text.'</p>');


                }//foreach
            }
            
             
         }//if
         
    }//foreach
    
    $filename = md5(rand(-10000,10000) . rand(-10000,10000));
    
    $mpdf->Output("documents/$filename.pdf");
    
    echo $filename.'.pdf';
    
}
else if(!empty ($_POST['DELETE_ACC'])){
    
    $info = new InfoPulseService();
    $r = new Request();
     
    $acc_id = $r->getPostValue('acc_id');
    
    $res = $info->DeleteUserAcc($acc_id);
    
    if($res){
        echo "ok";
    }
    else{
        echo "$res";
    }
    
}//else if

else if(!empty($_POST['SEND_COMMENT'])){
    
    $r = new Request();
    $global = new GlobalService();
    $user_s = new UserService();
    
    $current_user = $r->getCookieValue('current_user');
    $comment = $r->getPostValue('comment');
    $post_id = $r->getPostValue('post_id');
    
    $comment_len = strlen($comment);
    
    if($comment_len > 500){
        echo "to long";
    }//if
    else if($comment_len == 0){
        echo "to short";
    }//else if
    else{
        
       $res = $global->AddCommentToPost($post_id,$current_user,$comment);
       
       if($res){
           $return = array('user' => $user_s->getUserById($current_user)->Login, 'date' => $res);
           echo json_encode($return);
       }//if ok
       else{
           echo 'fail';
           
       }//else
       
    }//else
    
}
else if(!empty($_POST['SEND_NEWS_TO_USER'])){
    
    $r = new Request();
    $global = new GlobalService();
    
    $news_id = $r->getPostValue('news_id');
    $user_id = $r->getPostValue('user_id');
    
    $res = $global->sendNewsToUser($news_id,$user_id);
    
    if($res){
        echo 'ok';
    }
    else{
        echo $res;
    }
    
}
else if(!empty($_POST['SET_POST_TAG'])){
    
    $r = new Request();
    $global = new GlobalService();
    
    $news_id = $r->getPostValue('news_id');
    $post_tag = $r->getPostValue('post_tag');
    
    $res = $global->setPostMeta($news_id,'post_tag',$post_tag);
    
    if($res){
        echo 'ok';
    }
    else{
        echo $res;
    }
    
}
