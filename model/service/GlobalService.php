<?php

namespace model\service;

use model\entity\district;
use model\entity\global_news;
use model\entity\stopword;
use model\entity\statistic_stop_word;
use model\entity\SocialInfo;
use model\entity\CronProperties;
use model\entity\bad_word;
use model\entity\VKGroups;
use model\entity\VkUser;
use model\entity\DistrictTree;
use model\entity\user;
use model\entity\PasswordToRecovery;
use model\entity\PostComments;

class GlobalService{
    
    public function sendNewsToUser($news_id,$user_id){
        
        $news = $this->GetGlobalNewsById($news_id);
        if(is_object($news)){
            $description = $news->getDescription();
        
            $user_service = new UserService();
            $user = $user_service->getUserById($user_id);
            
            if(is_object($user)){
                $user_login = $user->getLogin();
                $description .= "{@$user_login}";

                $stmt = \util\MySQL::$db->prepare("UPDATE global_news SET SearchType = 'i', description = :description WHERE id = :nid");
                $stmt->bindParam(':description',$description,\PDO::PARAM_STR);
                $stmt->bindParam(':nid',$news_id);

                return $stmt->execute();
            }//if
            else {return NULL;}
        }
        else {return NULL;}
        
    }
    
    public function setPostMeta($post_id,$metakey,$metavalue){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM post_meta WHERE PostId = :pid and MetaKey = :key ");
        $stmt->bindParam(':pid',$post_id);
        $stmt->bindParam(':key',$metakey);
        $stmt->execute();
        
        $user_meta = $stmt->fetch(\PDO::FETCH_OBJ);
        if( $user_meta != NULL){
            
            $stmt = \util\MySQL::$db->prepare("UPDATE post_meta SET MetaValue = :val WHERE PostId = :pid and MetaKey = :key");
            
            $stmt->bindParam(':val',$metavalue);
            $stmt->bindParam(':pid',$post_id);
            $stmt->bindParam(':key',$metakey);
            
        }//if
        else{
            
            $stmt = \util\MySQL::$db->prepare("INSERT INTO post_meta(id,PostId,MetaKey,MetaValue) VALUES(NULL,:pid,:key,:val)");
            $stmt->bindParam(':pid',$post_id);
            $stmt->bindParam(':key',$metakey);
            $stmt->bindParam(':val',$metavalue);
            
        }
        $res = $stmt->execute();
        
        return $res;
        
    }//setPostMeta
    
    public function getPostMeta($post_id,$metakey){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM post_meta WHERE PostId = :pid and MetaKey = :key ");
        $stmt->bindParam(':pid',$post_id);
        $stmt->bindParam(':key',$metakey);
        
        $stmt->execute();
        
        $post_meta = $stmt->fetch(\PDO::FETCH_OBJ);
        
        if(is_object($post_meta)){
            
            return $post_meta->MetaValue;
            
        }//if
        else{
            return NULL;
            
        }//else
        
    }//getPostMeta
    
    public function GetCommentsToPost($post_id){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT `PostComments`.`id`, `UserId`, `PostId`, `Comment`,`users`.`Login`, `PostComments`.`Date` FROM `PostComments`  INNER JOIN `users` on `users`.`id` = `PostComments`.`UserId` WHERE PostId = :pid ORDER BY `PostComments`.`id` desc");
        $stmt->bindParam(':pid',$post_id);
        $comments = [];
        
        $stmt->execute();
        
        while($comment = $stmt->fetchObject('model\entity\PostComments')){
            $comments[] = $comment;
        }//while
        
        return $comments;
        
    }//GetCommentsToPost
    
    public function AddCommentToPost($post_id,$user_id,$comment){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO `PostComments`(`id`, `UserId`, `PostId`, `Comment`, `Date`) VALUES (NULL,:uid,:pid,:comm, now())");
        $stmt->bindParam(':uid',$user_id);
        $stmt->bindParam(':pid',$post_id);
        $stmt->bindParam(':comm',$comment);
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT Date FROM `PostComments` WHERE `Comment` LIKE :comm");
        $stmt->bindParam(':comm',$comment);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
    }
    
    public function GetNewsByShortDescription($start_text){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `global_news` WHERE `description` LIKE ? ORDER BY id desc LIMIT 0,5");
        
        $stmt->execute(array("%$start_text%"));
        $all_news = [];
        
        while($finded_news = $stmt->fetchObject(global_news::class)){
            
            $all_news[] = $finded_news;
            
        }//while
        
        return $all_news;
        
    }
    
    public function GetNewNewsCount(){
        $first_id = $_COOKIE['last_post_id'];
        
        $stmt = \util\MySQL::$db->prepare("SELECT MAX(id) FROM `global_news`");
        $stmt->execute();
        $last_id = $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
        $stmt = \util\MySQL::$db->prepare("SELECT COUNT(*) FROM `global_news` WHERE id > $first_id and id <= $last_id");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
    }
    
    public function GetLastGlobalNews($id){
        $id = $id - 10;
                
        $global_news_array = [];
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `global_news` WHERE id >= $id ORDER BY id desc");
        $stmt->execute();

        while($glob_news = $stmt->fetchObject(global_news::class)){
            $tag = $this->getPostMeta($glob_news->id,'post_tag');
            if($tag != NULL){
                $glob_news->Tag = $tag;
            }//if
            else{
                $glob_news->Tag = NULL;
            }
            
            $global_news_array[] = $glob_news;
        }//while
            
        return $global_news_array;
    }
    
    public function GetPOINews($user){
       
        $global_news_array = [];
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `PersonOfInterest` WHERE SearchType LIKE ? ORDER BY id desc");
        $stmt->execute(array("%$user%"));

        while($glob_news = $stmt->fetchObject(global_news::class)){
            $global_news_array[] = $glob_news;
        }//while
            
        return $global_news_array;
        
    }
    
    public function GetAllNewsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT COUNT(*) FROM `global_news`");
        $stmt->execute();
        
        return  $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    
    public function RemoveStopWord($id){
        
       
        $stmt = \util\MySQL::$db->prepare("DELETE FROM `stop_words` WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $result = $stmt->execute();
        
        if($result > 0){
            return TRUE;
        }
        else{
            return FALSE;
        }
        
    
    }
    
    public function RemoveDistrict($id){
        
       
        $stmt = \util\MySQL::$db->prepare("DELETE FROM `districts` WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $result = $stmt->execute();
        
        if($result > 0){
            return TRUE;
        }
        else{
            return FALSE;
        }
        
    
    }
    
    public function GetDistrictsStartWith($start){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `districts` WHERE Title LIKE ?");
        $stmt->execute(array("%$start%"));
        
        $districts = [];
        
        while($district = $stmt->fetchObject(district::class)){
            
            $districts[] = $district;
            
        }
        return $districts;
        
    }
    
    public function GetUsersStartWith($start){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Login FROM `users` WHERE Login LIKE ?");
        $stmt->execute(array("$start%"));
        
        $users = [];
        
        while($user = $stmt->fetchObject(user::class)){
            
            $users[] = $user;
            
        }
        return $users;
        
    }
    
    public function get_twitter_profile_info(){
                    
                    
                    define('CONSUMER_KEY', '3R3ABxZeNEvm1gLQzDbqD6q6s');
                    define('CONSUMER_SECRET', 'kpCLADH6XDsWNJ7fpFBTBucSbxqaMggAJlZuLQBwC9AJm97EfB');
                    // адрес получения токена запроса
                    define('REQUEST_TOKEN_URL', 'https://api.twitter.com/oauth/request_token');
                    // адрес аутентификации
                    define('AUTHORIZE_URL', 'https://api.twitter.com/oauth/authorize');
                    // адрес получения токена доступа
                    define('ACCESS_TOKEN_URL', 'https://api.twitter.com/oauth/access_token');
                    // адрес API получения информации о пользователе
                    define('ACCOUNT_DATA_URL', 'https://api.twitter.com/1.1/users/show.json');
                    // колбэк, адрес куда должен будет перенаправлен пользователь, после аутентификации
                    define('CALLBACK_URL', 'http://poolloop.ru/');
                    define('URL_SEPARATOR', '&');
                    
                    
                    
                    $oauth_nonce = md5(uniqid(rand(), true));
                    $oauth_timestamp = time();
                    $oauth_token = $_POST['oauth_token'];
                    $oauth_verifier = $_POST['oauth_verifier'];

                    
                    
                    $oauth_base_text = "GET&";
                    $oauth_base_text .= urlencode(ACCESS_TOKEN_URL)."&";

                    $params = array(
                        'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
                        'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
                        'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
                        'oauth_token=' . $oauth_token . URL_SEPARATOR,
                        'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
                        'oauth_verifier=' . $oauth_verifier . URL_SEPARATOR,
                        'oauth_version=1.0'
                    );

                    $key = CONSUMER_SECRET . URL_SEPARATOR . $oauth_token_secret;
                    $oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(ACCESS_TOKEN_URL) . URL_SEPARATOR . implode('', array_map('urlencode', $params));
                    $oauth_signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

                    // получаем токен доступа
                    $params = array(
                        'oauth_nonce=' . $oauth_nonce,
                        'oauth_signature_method=HMAC-SHA1',
                        'oauth_timestamp=' . $oauth_timestamp,
                        'oauth_consumer_key=' . CONSUMER_KEY,
                        'oauth_token=' . urlencode($oauth_token),
                        'oauth_verifier=' . urlencode($oauth_verifier),
                        'oauth_signature=' . urlencode($oauth_signature),
                        'oauth_version=1.0'
                    );
                    $url = ACCESS_TOKEN_URL . '?' . implode('&', $params);

                    $response = file_get_contents($url);
                    parse_str($response, $response);


                    // формируем подпись для следующего запроса
                    $oauth_nonce = md5(uniqid(rand(), true));
                    $oauth_timestamp = time();

                    $oauth_token = $response['oauth_token'];
                    $oauth_token_secret = $response['oauth_token_secret'];
                    $screen_name = $response['screen_name'];

                    $params = array(
                        'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
                        'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
                        'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
                        'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
                        'oauth_token=' . $oauth_token . URL_SEPARATOR,
                        'oauth_version=1.0' . URL_SEPARATOR,
                        'screen_name=' . $screen_name
                    );
                    $oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(ACCOUNT_DATA_URL) . URL_SEPARATOR . implode('', array_map('urlencode', $params));

                    $key = CONSUMER_SECRET . '&' . $oauth_token_secret;
                    $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

                        // получаем данные о пользователе
                    $params = array(
                        'oauth_consumer_key=' . CONSUMER_KEY,
                        'oauth_nonce=' . $oauth_nonce,
                        'oauth_signature=' . urlencode($signature),
                        'oauth_signature_method=HMAC-SHA1',
                        'oauth_timestamp=' . $oauth_timestamp,
                        'oauth_token=' . urlencode($oauth_token),
                        'oauth_version=1.0',
                        'screen_name=' . $screen_name
                    );

                    $url = ACCOUNT_DATA_URL . '?' . implode(URL_SEPARATOR, $params);

                    $response = file_get_contents($url);
                    $userInfo = json_decode($response, true);
                    
                    return $userInfo;
                    
                }
    
    public function get_twitter_oauth_link(){
                    
                    define('CONSUMER_KEY', '3R3ABxZeNEvm1gLQzDbqD6q6s');
                    define('CONSUMER_SECRET', 'kpCLADH6XDsWNJ7fpFBTBucSbxqaMggAJlZuLQBwC9AJm97EfB');
                    // адрес получения токена запроса
                    define('REQUEST_TOKEN_URL', 'https://api.twitter.com/oauth/request_token');
                    // адрес аутентификации
                    define('AUTHORIZE_URL', 'https://api.twitter.com/oauth/authorize');
                    // адрес получения токена доступа
                    define('ACCESS_TOKEN_URL', 'https://api.twitter.com/oauth/access_token');
                    // адрес API получения информации о пользователе
                    define('ACCOUNT_DATA_URL', 'https://api.twitter.com/1.1/users/show.json');
                    // колбэк, адрес куда должен будет перенаправлен пользователь, после аутентификации
                    define('CALLBACK_URL', 'http://user1187254.atservers.net/index?ctrl=news&act=TwitterUsersSettings');
                    define('URL_SEPARATOR', '&');

                    $oauth_nonce = md5(uniqid(rand(), true));
                    $oauth_timestamp = time();
                    
                    
                    // формируем набор параметров
                    $params = array(
                        'oauth_callback=' . urlencode(CALLBACK_URL) . URL_SEPARATOR,
                        'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
                        'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
                        'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
                        'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
                        'oauth_version=1.0'
                    );
                    // склеиваем все параметры, применяя к каждому из них функцию urlencode
                    $oauth_base_text = implode('', array_map('urlencode', $params));
                    // специальный ключ
                    $key = CONSUMER_SECRET . URL_SEPARATOR;
                    // формируем общий текст строки
                    $oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(REQUEST_TOKEN_URL) . URL_SEPARATOR . $oauth_base_text;
                    // хэшируем с помощью алгоритма sha1
                    $oauth_signature = base64_encode(hash_hmac('sha1', $oauth_base_text, $key, true));

                    // готовим массив параметров
                    $params = array(
                        URL_SEPARATOR . 'oauth_consumer_key=' . CONSUMER_KEY,
                        'oauth_nonce=' . $oauth_nonce,
                        'oauth_signature=' . urlencode($oauth_signature),
                        'oauth_signature_method=HMAC-SHA1',
                        'oauth_timestamp=' . $oauth_timestamp,
                        'oauth_version=1.0'
                    );
                    // склеиваем параметры для формирования url
                    $url = REQUEST_TOKEN_URL . '?oauth_callback=' . urlencode(CALLBACK_URL) . implode('&', $params);
                    // Отправляем GET запрос по сформированному url
                    $response = file_get_contents($url);
                    // Парсим ответ
                    parse_str($response, $response);
                    // записываем ответ в переменные
                    $oauth_token = $response['oauth_token'];
                    $oauth_token_secret = $response['oauth_token_secret'];
                    $link = AUTHORIZE_URL . '?oauth_token=' . $oauth_token;


                    //echo ("error");
                    return $link;
                    
                }
                
    public function DeleteFacebookUser($id) {
        
        $stmt = \util\MySQL::$db->prepare("DELETE FROM FbUsers WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;
        }
        return FALSE;
        
        
    }
    
    public function DeleteTwitterUser($id) {
        
        $stmt = \util\MySQL::$db->prepare("DELETE FROM TwitterUsers WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;
        }
        return FALSE;
        
        
    }
    
    public function GetNewsByInterests(){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        $global_news_array = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `global_news` WHERE SearchType = 'i' ORDER BY id desc");
        $stmt->execute();

        while($glob_news = $stmt->fetchObject(global_news::class)){
            $global_news_array[] = $glob_news;
        }//while
        return $global_news_array;
    }
    
    public function GetALLNewsByInterests(){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        $global_news_array = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `PersonOfInterest` ORDER BY id desc");
        $stmt->execute();

        while($glob_news = $stmt->fetchObject(global_news::class)){
            $global_news_array[] = $glob_news;
        }//while
        return $global_news_array;
    }
    
    public function GetAllChild($id){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM DistrictTree WHERE id = :id");
        $stmt->bindParam(':id',$id);
        
        $stmt->execute();
        
        $tree = [];
        
        while($node = $stmt->fetchObject('model\entity\DistrictTree')){
            
            $tree[] = $node;
            
        }
        
        return $tree;
        
    }
    
    public function GetDistrictTree(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM DistrictTree");
        
        $stmt->execute();
        
        $tree = [];
        
        while($node = $stmt->fetchObject('model\entity\DistrictTree')){
            
            $tree[] = $node;
            
        }
        
        return $tree;
        
    }
    
    public function AddDistrictParrendAndChild($parrent,$child) {
        
        if($child == NULL){
            $stmt = \util\MySQL::$db->prepare("INSERT INTO DistrictTree(DistrictId,ChildDistictId) VALUES(:parrent,NULL)");
            $stmt->bindParam(':parrent',$parrent);
        }
        else{
            $stmt = \util\MySQL::$db->prepare("INSERT INTO DistrictTree(DistrictId,ChildDistictId) VALUES(:parrent,:child)");
            $stmt->bindParam(':parrent',$parrent);
            $stmt->bindParam(':child',$child);
        }
        
        $stmt->execute();
        
    }
    
    public function ClearDistrictTree(){
        
        $stmt = \util\MySQL::$db->prepare("TRUNCATE TABLE DistrictTree");
       
        $stmt->execute();
    }
    
    public function GetVkUsers(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM VkUsers");

        $stmt->execute();
        
        $users = [];
        
        while($user = $stmt->fetchObject('model\entity\VkUser')){
            $users[] = $user;
        }
        
        return $users;
    }

    public function GetFbUsers(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM FbUsers");

        $stmt->execute();
        
        $users = [];
        
        while($user = $stmt->fetchObject('model\entity\VkUser')){
            $users[] = $user;
        }
        
        return $users;
    }
    
    public function GetTwitterUsers(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM TwitterUsers");

        $stmt->execute();
        
        $users = [];
        
        while($user = $stmt->fetchObject('model\entity\VkUser')){
            $users[] = $user;
        }
        
        return $users;
    }    
    
    public function ShowGlobalNews() {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE global_news SET IsHide = 0");
       
        $stmt->execute();
        
    }
    
    public function HideSpecificNews($id) {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE global_news SET IsHide = 1 WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res > 0){
            return TRUE;
        }//if
        else{
            return FALSE;
        }//else
        
        
    }
    
    public function ShowSpecificNews($id) {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE global_news SET IsHide = 0 WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res > 0){
            return TRUE;
        }//if
        else{
            return FALSE;
        }//else
        
    }
    
    public function HideGlobalNews($word){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        $stmt = \util\MySQL::$db->prepare("UPDATE global_news SET IsHide = 1 WHERE description REGEXP ?");
        $params = array('[[:<:]]'.$word.'[[:>:]]');
        
        $res = $stmt->execute($params);
        
        if($res > 0){
            return TRUE;
        }
        return FALSE;
        
    }
    
    public function GetBadWordByTitle($word_title){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * `badwords` WHERE Word LIKE ?");
        
        $stmt->execute(array("%$word_title%"));
        
        if(is_a($stmt->fetchObject(bad_word::class),'model\entity\bad_word')){
            return true;
        }
        else{
            
            $stmt = \util\MySQL::$db->prepare("INSERT INTO badwords(id,Word) VALUES(NULL,?)");
            $stmt->execute(array("$word_title"));
        
            return false;
        }
    }
    
    public function GetUniquePercent(global_news $news){

           $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
           $stmt->execute();

           $descr = $news->getDescription();
           $SearchType = $news->getSearchType();

           $stmt = \util\MySQL::$db->prepare("SELECT MAX(levenshtein_ratio(`description`,:search_text)) FROM `global_news` WHERE SearchType = :st");
           $stmt->bindParam(':search_text',$descr);
           $stmt->bindParam(':st',$SearchType);
           $stmt->execute();

           return $stmt->fetch(\PDO::FETCH_BOTH)[0];

    }

      
    public function IsSimilar($source, $word){
        $strikt = FALSE;
        ini_set("max_execution_time", "2500");
        set_time_limit (2500);
        
        
        $descr = $source;
        $descr = str_ireplace("\n",'',$descr);
       
        if($word[0]=='!'){
            $strikt = true;
            $word = iconv_substr($word, 1);
        }
        $words = strtok($descr,' ,.!;)({}@\'\":^$<>');
        $chapters = explode(' ', trim($word));
        $chapters_count = count($chapters);
        $text_arr = [];
        
        
        //разделение текста на массив слов
        while($words !== false){

            $text_arr[] = $words;
            $words = strtok(' ,.!;)({}@\'\":^$<>');
            
        }//while        


        $final_distr = false;
        $proc;
        if($strikt){
            
            for($i=0; $i<count($text_arr); $i++){
                $final_distr = false;
                $txt_lower = mb_strtolower($text_arr[$i], "Utf-8");
                $word_lower = mb_strtolower($chapters[0], "Utf-8");
                
                if($txt_lower==$word_lower){
                    if($chapters_count == 1){
                        return true;
                    }else{
                        for ($j=1; $j<$chapters_count; $j++){//идем дальше по чаптерам
                            $ji = $j+$i;
                            $txt_lower = mb_strtolower($text_arr[$ji], "Utf-8");
                            $word_lower = mb_strtolower($chapters[$j], "Utf-8");
                            if($txt_lower==$word_lower){
                                $final_distr=true;
                            }  else {
                                $final_distr=false;
                                break;
                            }
                        }
                    }
                }
                if($final_distr){
                    return true;
                }
            }
            return false;
        }else{
            for($i=0; $i<count($text_arr); $i++){

            $txt_lower = mb_strtolower($text_arr[$i], "Utf-8");
            $word_lower = mb_strtolower($chapters[0], "Utf-8");

            $lev = similar_text($txt_lower, $word_lower,$proc);

            if ($proc >= 60 ){//нашли первое соответствие

                //начало проверки по частям
                if (iconv_strlen($text_arr[$i], 'Utf-8')<=5){
                    $text_len = 3;
                }else{
                    $text_len = ceil(iconv_strlen($text_arr[$i], 'Utf-8')/2);
                }

                if (iconv_strlen($chapters[0], 'Utf-8')<=5){
                    $word_len = 3;
                }else{
                    $word_len = ceil(iconv_strlen($chapters[0], 'Utf-8')/2);
                }                


                $first_part_text = iconv_substr($text_arr[$i], 0, $text_len+1, 'Utf-8');
                $first_part_word = iconv_substr($chapters[0], 0, $word_len+1, 'Utf-8');

                $first_part_text = mb_strtolower($first_part_text,'Utf-8');
                $first_part_word = mb_strtolower($first_part_word,'Utf-8');          

                //проверка на соответствие 2ух частей
                if ($first_part_text == $first_part_word){
                    //равны, проверка на количество чаптеров
                    if($chapters_count == 1){
                        
                        //стоп слово одно, нашли, конец
                        return true;
                        
                    }else{
                        //echo "Первое совпало<br/>";
                        //стоп слово НЕ одно, нашли первое, ищем дальше
                        
                        for ($j=1; $j<$chapters_count; $j++){//идем дальше по чаптерам
                            
                            $ji = $j + $i;
                            $lev2 = similar_text($text_arr[$ji], $chapters[$j],$proc);

                            if ($proc >= 60){//если следующий чаптер не левенштейн выход

                                if (iconv_strlen($text_arr[$ji], 'Utf-8')<=5){
                                    $text_len = 3;
                                }else{
                                    $text_len = ceil(iconv_strlen($text_arr[$ji], 'Utf-8')/2);
                                }

                                if (iconv_strlen($chapters[$j], 'Utf-8')<=5){
                                    $word_len = 3;
                                }else{
                                    $word_len = ceil(iconv_strlen($chapters[$j], 'Utf-8')/2);
                                }       

                                //echo "Длина $text_arr[$i]";


                                //начало проверки по частям
                                $first_part_text = iconv_substr($text_arr[$ji], 0, $text_len+1, 'Utf-8');
                                $first_part_word = iconv_substr($chapters[$j], 0, $word_len+1, 'Utf-8');

                                $first_part_text = mb_strtolower($first_part_text,'Utf-8');
                                $first_part_word = mb_strtolower($first_part_word,'Utf-8');
                                
                                //echo "проверка второго $first_part_text и $first_part_word";
                                //проверка на соответствие 2ух частей
                                if ($first_part_text == $first_part_word){
                                    
                                    $final_distr = true;
                                }else{
                                    //половины следующего не равны
                                    $final_distr = false;
                                    break;
                                }
                            }
                            else{
                                //процент следующего не равны
                                $final_distr = false;
                                break;
                            }

                        }//for j
                    }


                }else{
                    //не соответствуют половины
                    $final_distr = false;
                }




            }//if for i проверка на первое соответствие процент
            else{
                $final_distr = false;
            }

            if($final_distr == true){
                return true;
                break;
            }  


        }//for i  
        }

//        if($final_distr == true){
//            return true;
//        }  
//        if($final_distr == false){
//
//            return false;
//
//        }
        
    }
      
    public function GetAllUsers(){

      $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
      $stmt->execute();

      $stmt = \util\MySQL::$db->prepare("SELECT * FROM `users`");
      $stmt->execute();

      $users = [];

      while($user = $stmt->fetchObject('model\entity\user')){
          $users[] = $user;
      }

      return $users;

    }


    public function GetNewsBySerachWord($word) {

       $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
       $stmt->execute();
       $word = trim($word);

       $stmt = \util\MySQL::$db->prepare("SELECT * FROM `global_news` WHERE description LIKE ?");
       $param = array("%$word%");
       //$param = array("[[:<:]]".$word."[[:>:]]");
       $stmt->execute($param);
       $news = [];

       while($glob_news = $stmt->fetchObject('model\entity\global_news')){
           $news[] = $glob_news;
       }//while

       return $news;

    }


    public function GetVkGroups(){

    $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
    $stmt->execute();

    $groups = [];
    $stmt = \util\MySQL::$db->prepare("SELECT * FROM VkGroups");
    $stmt->execute();

    while($group = $stmt->fetchObject('model\entity\VKGroups')){
        $groups[] = $group;
    }//while

    return $groups;

    }
      
    public function AddGroup(VKGroups $group){
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO VkGroups(id,GroupTitleId) VALUES(NULL,:group)");
        $stmt->bindParam(':group',$group->GroupTitleId);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
    }

    public function AddVkUser(VkUser $user){
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO VkUsers(id,ScreenName) VALUES(NULL,:user)");
        $stmt->bindParam(':user',$user->ScreenName);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
    }
    
    public function AddFbUser(VkUser $user){
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO FbUsers(id,ScreenName) VALUES(NULL,:user)");
        $stmt->bindParam(':user',$user->ScreenName);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
    }
    
    public function AddTwitterUser(VkUser $user){
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO TwitterUsers(id,ScreenName) VALUES(NULL,:user)");
        $stmt->bindParam(':user',$user->ScreenName);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
    }    
    
    public function GroupExist($name) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
     
        $groups = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM VkGroups WHERE GroupTitleId = :title");
        $stmt->bindParam(':title',$name);
        $stmt->execute();
        $group = $stmt->fetchObject(VKGroups::class);
        
        if(is_a($group, VKGroups::class)){
            return $group;
        }
        else{
            return false;
        }
    }
    
    public function UserExist($name) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
     
        $groups = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM VkUsers WHERE ScreenName = :title");
        $stmt->bindParam(':title',$name);
        $stmt->execute();
        $user = $stmt->fetchObject(VkUser::class);
        
        if(is_a($user, VkUser::class)){
            return $user;
        }
        else{
            return false;
        }
    }   
    
    public function FbUserExist($name) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
     
        $groups = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM FbUsers WHERE ScreenName = :title");
        $stmt->bindParam(':title',$name);
        $stmt->execute();
        $user = $stmt->fetchObject(VkUser::class);
        
        if(is_a($user, VkUser::class)){
            return $user;
        }
        else{
            return false;
        }
    } 
    
    public function TwitterUserExist($name) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
     
        $groups = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM TwitterUsers WHERE ScreenName = :title");
        $stmt->bindParam(':title',$name);
        $stmt->execute();
        $user = $stmt->fetchObject(VkUser::class);
        
        if(is_a($user, VkUser::class)){
            return $user;
        }
        else{
            return false;
        }
    } 
    
    public function UpdateGroup($id , $new_title_id) {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE VkGroups SET GroupTitleId = :new_title WHERE id = :id");
        $stmt->bindParam(':new_title',$new_title_id);
        $stmt->bindParam(':id',$id);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
        
    }
    
    public function UpdateVkUser($id , $new_title_id) {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE VkUsers SET ScreenName = :new_title WHERE id = :id");
        $stmt->bindParam(':new_title',$new_title_id);
        $stmt->bindParam(':id',$id);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
        
    }

    public function UpdateFbUser($id , $new_title_id) {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE FbUsers SET ScreenName = :new_title WHERE id = :id");
        $stmt->bindParam(':new_title',$new_title_id);
        $stmt->bindParam(':id',$id);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
        
    }    
    
    public function UpdateTwitterUser($id , $new_title_id) {
        
        $stmt = \util\MySQL::$db->prepare("UPDATE TwitterUsers SET ScreenName = :new_title WHERE id = :id");
        $stmt->bindParam(':new_title',$new_title_id);
        $stmt->bindParam(':id',$id);
        
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;   
        }//if
        else{
            return FALSE;
        }
        
    }
    
    public function GetDistricts(){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $districts = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM districts");
        $stmt->execute();
        
        while($district = $stmt->fetchObject('model\entity\district')){
            $districts[] = $district;
        }//while
        
        
        return $districts;
        
    }
    
    public function GetNewsBySearchType($SearchType) {
        //echo "<br/><br/>$SearchType<br/><br/>";
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $bad_words = [];
        if($SearchType == 'v'){
            $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE SearchType = :st ORDER BY id desc LIMIT 0,500;");
        }
        else{
             $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE SearchType = :st ORDER BY id desc");
        }
       
       
        $stmt->bindParam(':st',$SearchType);
        $stmt->execute();
        
        $news = [];
        
        while($spec_news =  $stmt->fetchObject('model\entity\global_news')){
            
            $news[] = $spec_news;
            
        }//while
        
        
        return $news;
        
    }
    
    public function GetGoogleNewsPostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 'n'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    
    public function GetBadWords(){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $bad_words = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM badwords");
        $stmt->execute();
        
        while($word = $stmt->fetchObject('model\entity\bad_word')){
            $bad_words[] = $word;
        }//while
        
        
        return $bad_words;
        
    }
    
    public function AddBadWord($title){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO badwords(id,Word) VALUES(NULL,:word)");
        $stmt->bindParam(':word',$title);
        $stmt->execute();
            
        return $this->CheckWord($title);;
        
        
    }//AddBadWord
    
    public function UpdateWord($id,$new_title){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("UPDATE badwords SET Word = :word WHERE id = :id");
        
        $stmt->bindParam(':word',$new_title);
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        return $res;
        
    }

    public function CheckWord($new_title){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM badwords WHERE Word LIKE ?");
        
        $stmt->execute(array("%$new_title%"));
        
        $bw = $stmt->fetchObject(bad_word::class);
        
        if(is_a( $bw, 'model\entity\bad_word')){
            return $bw;
        }
        return NULL;
        
    }
    
    public function DeleteVkUserById($id){
        
        $stmt = \util\MySQL::$db->prepare("DELETE FROM VkUsers WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;
        }
        return FALSE;
        
    }
    
    public function DeleteVkGroupById($id){
        
        $stmt = \util\MySQL::$db->prepare("DELETE FROM VkGroups WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;
        }
        return FALSE;
        
    }
    
    public function DeleteGlobalNewsById($id){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $gn = $stmt -> fetchObject('model\entity\global_news');
        $gm = $gn -> getImages();
        
        if ($gm != NULL){
            if(strstr($gm, 'http') == false){
                
                $images = explode(',',$gm);
                $count = count($images);
                unset($images[$count-1]);
                
                foreach($images as $image){
                    unlink( "files/" . $image ); 
                }//foreach
           
            }
            
        }
        
       
        
        
        $stmt = \util\MySQL::$db->prepare("DELETE FROM global_news WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();
      
         
    }
    
    public function GetVkPostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 'v'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    
    public function GetYandexPostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 'y'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }

    public function GetLjPostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 'lj'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }    
    
    public function GetVkAuthLink(){
        
                    $client_id = '4843223'; // ID приложения
                    $client_secret = 'q3eRLmVDMXKCx467CjUv'; // Защищённый ключ
                    $redirect_uri = 'http://user1187254.atservers.net/'; // Адрес сайта
                    $url = 'http://oauth.vk.com/authorize';

                    $params = array(
                        'client_id'     => $client_id,
                        'redirect_uri'  => $redirect_uri,
                        'response_type' => 'code',
                        'scope'         => 'offline'
                    );
                    return $url . '?' . urldecode(http_build_query($params));
        
    }
    
    public function GetTwitterPostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 't'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    
    public function GetUserId(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT user_id FROM vk_token");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
     public function GetAccessToken(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT vk_token FROM vk_token");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    public function GetGooglePostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 'g'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    
     public function GetFaceBookPostsCount(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT Count(*) FROM global_news WHERE SearchType = 'f'");
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
    }
    
    public function GetCronProperties(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM cronproperties");
        $stmt->execute();
        
        $cron = $stmt->fetchObject('model\entity\CronProperties');
        
        if(is_a($cron, 'model\entity\CronProperties')){
            
            if($cron->getTimeStart() == NULL){
                return NULL;
            }//if
            else{
                return $cron;
            }//else
        }
        
    }
    
    public function IsCronEnable(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT NOW()");
        $stmt->execute();
        $date = $stmt->fetch(\PDO::FETCH_COLUMN);
        
        $stmt = \util\MySQL::$db->prepare("SELECT TimeEnd FROM cronproperties");
        $stmt->execute();
        $date_end = $stmt->fetch(\PDO::FETCH_COLUMN);
        
        return $date != $date_end;
        
    }
    
    public function GetLastIdTwitter(){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM social_info");
        $stmt->execute();
        
        $sf = $stmt->fetchObject('model\entity\SocialInfo');
        
        if(is_a($sf,'model\entity\SocialInfo')){
            $lastId = $sf->getLastRecordId();   
            if(!empty($lastId)){
                return $lastId;
            }//if
            else{
                return NULL;
            }//else
            
        }//if
        else{
                return NULL;
        }//else
        
        
    }
    
    public function SetLastIdTwitter($lastId){
        
        $stmt = \util\MySQL::$db->prepare("UPDATE social_info SET LastRecordId = :li");
        $stmt->bindParam(":li",$lastId);
        $res = $stmt->execute();
        
        if($res == 1){
            return true;
        }//if
        else{
            return false;
        }//else
        
    }//SetLastIdTwitter
    
    public function AddDistrict($title){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO districts(id,Title,Date) VALUES(NULL,:TITLE,now())");
        $stmt->bindParam(":TITLE",$title);
        $district = $stmt->execute();
        
        if($district == 1){
            return true;
        }//if
        else{
            return false;
        }//else
        
    }
    
    public function IsContainsNews($text){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE description Like ? and SearchType = 't'");
        $params = array("%$text%");
        $stmt->execute($params);
        
        $news = $stmt->fetchObject('model\entity\global_news');
        
        if(!is_a($news,'model\entity\global_news')){
            return FALSE;
        }//if
        
        return TRUE;
        
        
    }//IsContainsNews
    
    public function IsContainsStopWord($stopWord){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM stop_words where word = :word");
        $stmt->bindParam(':word',$stopWord);
        $stmt->execute();
        
        $stopWordContains = $stmt->fetchObject('model\entity\stopword');
        
        if(is_a($stopWordContains,'model\entity\stopword')){
            
            return true;
            
        }//if
        else{
            
            return false;
            
        }//else
        
    }
    
    public function UpdateStopWord($word_id,$word) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("UPDATE stop_words SET Word = :word WHERE id = :id");
        $stmt->bindParam(":word",$word);
        $stmt->bindParam(":id",$word_id);
        $res = $stmt->execute();
        
        if($res == 1){
            return true;
        }//if
        else{
            return false;
        }//else
        
    }
    
    public function UpdateDistrict($id_district,$new_title){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("UPDATE districts SET Title = :word WHERE id = :id");
        $stmt->bindParam(":word",$new_title);
        $stmt->bindParam(":id",$id_district);
        $res = $stmt->execute();
        
        if($res == 1){
            return true;
        }//if
        else{
            return false;
        }//else
        
    }
    
    public function GetDistrictById($id){
        
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM districts WHERE id = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        
        $district = $stmt->fetchObject('model\entity\district');
        
        if(is_a($district, 'model\entity\district')){
               return $district;
        }//if
        else{
            return NULL;
        }
    }//GetDistrict
    
    public function GetDistrictByName($name){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM districts WHERE Title LIKE ?");
        $param = array("%$name%");
        $stmt->execute($param);
        
        $final_district = $stmt->fetchObject('model\entity\district');
        
        if(is_a($final_district, 'model\entity\district')){
               return $final_district;
        }//if
        else{
            return NULL;
        }
    }//GetDistrict
    
    public function GetLastVkNews(){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $districts = [];
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE INSTR(Source,'vk.com') ORDER BY id desc");
        $stmt->execute();
        
        $last_news = $stmt->fetchObject(global_news::class);
        
        if(is_a($last_news,'model\entity\global_news')){
             return $last_news;
        }//if
        else{
            return NULL;
        }
       
        
    }
    
    public function GetGlobalNewsById($id) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");       
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE id = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        
        $globalNews = $stmt->fetchObject('model\entity\global_news');
        
        if(is_a($globalNews, 'model\entity\global_news')){
            
               $tag = $this->getPostMeta($globalNews->id,'post_tag');
               if($tag != NULL){
                    $globalNews->Tag = $tag;
               }//if
               else{
                    $globalNews->Tag = NULL;
               }
               return $globalNews;
        }//if
        else{
            return NULL;
        }//else
        
    }//GetGlobalNewsById
    
    
    public function GetRecoveryByUserId($userId) {
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `PasswordRecovery` WHERE UserID = :id");
        $stmt->bindParam(':id',$userId);
        $stmt->execute();
        
        $userInfo = $stmt->fetchObject(PasswordToRecovery::class);
        if(is_a($userInfo,PasswordToRecovery::class)){
            return $userInfo;
        }
        else {
            return NULL;
        }
        
    }
    
    public function GetPersonNewsById($id) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");       
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM PersonOfInterest WHERE id = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        
        $globalNews = $stmt->fetchObject('model\entity\global_news');
        
        if(is_a($globalNews, 'model\entity\global_news')){
               return $globalNews;
        }//if
        else{
            return NULL;
        }//else
        
    }//GetGlobalNewsById
    
    public function GetGlobalNews($offset=0,$limit=0){
       
        $global_news_array = [];
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `global_news` ORDER BY id desc LIMIT $offset,$limit");
        $stmt->execute();

        while($glob_news = $stmt->fetchObject(global_news::class)){
            
            $tag = $this->getPostMeta($glob_news->id,'post_tag');
            if($tag != NULL){
                $glob_news->Tag = $tag;
            }//if
            else{
                $glob_news->Tag = NULL;
            }
            $global_news_array[] = $glob_news;
            
        }//while
            
        return $global_news_array;
        
    }
    
    public function AddGlobalNews(global_news $news){
            
            $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
            $stmt->execute();
            $search_type = $news->getSearchType();

            $stmt = \util\MySQL::$db->prepare("INSERT INTO global_news(id,title,description,public_date,district,Source,Images,Date,Stop_words,District_str,SearchType)".
                    " VALUES(NULL,:title,:description,now(),:distr,:src,:img,:date,:s_w,:dis_str,'$search_type') ");
            
            $title = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"<>«»\n\t\r ]/ius",'',$news->getTitle());

            $stmt->bindParam(":title",$title);

            $description = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"<>«»\n\t\r ]/ius",'',$news->getDescription());

            $description = \util\MySQL::$db->quote($description,\PDO::PARAM_STR);

            $stmt->bindParam(":description",$description );

            $destr = $news->getDistrict();
            $stmt->bindParam(":distr",$destr);

            $source = \util\MySQL::$db->quote($news->getSource(),\PDO::PARAM_STR);
            
            $stmt->bindParam(":src",$source);

            $img = $news->getImage();
            $stmt->bindParam(":img",$img);

            $public_date = $news->getDate();
            $stmt->bindParam(":date",$public_date);

            $sw = $news->getStop_words();
            $stmt->bindParam(":s_w",$sw);

            $dis_str = $news->getDistrict_str();
            $stmt->bindParam(":dis_str",$dis_str);

            $res = $stmt->execute();
            
            $stmt = \util\MySQL::$db->prepare("DELETE FROM global_news WHERE description LIKE ?");
            
            $stmt->execute(array("% порно %"));
            
            
            
            if($res != 0){
                return true;
            }//if
            else{
                return $res;
            }//else
        
    }
    
    public function DeleteBadWord($id) {
        
         $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT Word FROM badwords WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        
        $word = $stmt->fetch(\PDO::FETCH_BOTH)[0];
        
        $stmt = \util\MySQL::$db->prepare("UPDATE global_news SET IsHide = 0 WHERE description LIKE ? ");
        $stmt->execute(array("% $word %"));
        
        $stmt = \util\MySQL::$db->prepare("DELETE FROM badwords WHERE id = :id");
        $stmt->bindParam(':id',$id);
        $res = $stmt->execute();
        
        if($res != 0){
            return TRUE;
        }
        
        return FALSE;
    }
    public function AddToPersonOfInterest(global_news $news){
        
 //       $percent = $this->GetUniquePercent($news->getDescription());
        
 //       if(intval($percent) < 96){
            
            $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
            $stmt->execute();
            
            $search_type = $news->getSearchType();

            $stmt = \util\MySQL::$db->prepare("INSERT INTO PersonOfInterest(id,title,description,public_date,district,Source,Images,Date,Stop_words,District_str,SearchType)".
                    " VALUES(NULL,?,?,now(),?,?,?,?,?,?,?) ");
            $title = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"<>«»\n\t\r ]/ius",'',$news->getTitle());
            $description = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"<>«»\n\t\r ]/ius",'',$news->getDescription());
            $destr = $news->getDistrict();
            $source = $news->getSource();
            $img = $news->getImage();
            $public_date = $news->getDate();
            $sw = $news->getStop_words();
            $dis_str = $news->getDistrict_str();
            
            $params = array($title,$description,$destr,$source,$img,$public_date,$sw,$dis_str,$search_type);
            
            $res = $stmt->execute($params);
            
            $stmt = \util\MySQL::$db->prepare("DELETE FROM PersonOfInterest WHERE description LIKE ?");
            
            $stmt->execute(array("% порно %"));
            
            if($res != 0){
                return true;
            }//if
            else{
                return $res;
            }//else

    }
    
    public function AddGlobalNews2(global_news $news){
            
            $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
            $stmt->execute();
            
            $search_type = $news->getSearchType();

            $stmt = \util\MySQL::$db->prepare("INSERT INTO global_news(id,title,description,public_date,district,Source,Images,Date,Stop_words,District_str,SearchType) VALUES(NULL,?,?,now(),?,?,?,?,?,?,?) ");
            $title = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"<>«»\n\t\r ]/ius",'',$news->getTitle());
            $description = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"<>«»\n\t\r ]/ius",'',$news->getDescription());
            $destr = $news->getDistrict();
            $source = $news->getSource();
            $img = $news->getImage();
            $public_date = $news->getDate();
            $sw = $news->getStop_words();
            $dis_str = $news->getDistrict_str();
            
            $params = array($title,$description,$destr,$source,$img,$public_date,$sw,$dis_str,$search_type);
            
            $res = $stmt->execute($params);
            
            $stmt = \util\MySQL::$db->prepare("DELETE FROM global_news WHERE description LIKE ?");
            
            $stmt->execute(array("% порно %"));
            
            if($res != 0){
                return true;
            }//if
            else{
                return $res;
            }//else

    }
    
    public function GetGlobalNewsSinceDate($date){
        $globalNews=[];
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE public_date > :date; ");
        $stmt->bindParam(":date",$date);
        $stmt->execute();
        
        while($news = $stmt->fetchObject(global_news::class)){
            
            $globalNews[] = $news;
            
        }//while
        
        
        return $globalNews;
    }
    
    public function GetGlobalNewsByStopWord($word,$district){
    
        $globalNews=[];
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE Stop_words Like ? and district = ? ORDER BY id desc LIMIT 0,500; ");
        $params = array("%$word%","$district");
        $stmt->execute($params);
        
        while($news = $stmt->fetchObject(global_news::class)){
            $globalNews[] = $news;
            
        }//while
        
        
        return $globalNews;
    
}
    
public function GetGlobalNewsByStopWordWithoutDistrict($word){
    
        $globalNews=[];
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE Stop_words LIKE ? ORDER BY id desc LIMIT 0,500; ");
        $param = array("%$word%");
        $stmt->execute($param);
        
        while($news = $stmt->fetchObject(global_news::class)){
            $globalNews[] = $news;
            
        }//while
        
        
        return $globalNews;
    
}

    public function GetGlobalNewsByDisrtict($district){

           $globalNews=[];

           $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE District_str LIKE ? ORDER BY id desc LIMIT 0,500");
           $param = array("%$district%"); 
          
           $stmt->execute($param);

           while($news = $stmt->fetchObject('model\entity\global_news')){
               $globalNews[] = $news;

           }//while


           return $globalNews;

   }

    public function GetGlobalNewsByDisrtictId($id){

           $globalNews=[];

           $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE district = :id ORDER BY id desc ");
           $stmt->bindParam(':id',$id);

          
           $stmt->execute();

           while($news = $stmt->fetchObject('model\entity\global_news')){
               $globalNews[] = $news;

           }//while


           return $globalNews;

   }
   
   public function GetStatisticByDistricts() {
      
           $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
           $stmt->execute();
           $globalNews = [];
           $stmt = \util\MySQL::$db->prepare("SELECT COUNT( * ) as Count , District_str FROM global_news GROUP BY  `District_str`" );
           $stmt->execute();
            
           while($news = $stmt->fetchObject('model\entity\statistic_search')){
                 $globalNews[] = $news;
           }
            
            return $globalNews;
   }
   
   public function GetStatisticByStopWords() {
      
           $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
           $stmt->execute();
           $globalNews = [];
           $stmt = \util\MySQL::$db->prepare("SELECT COUNT( * ) as Count ,  `Stop_words` FROM  `global_news` GROUP BY  `Stop_words` " );
           $stmt->execute();
            
           while($news = $stmt->fetchObject('model\entity\statistic_search')){
                 $globalNews[] = $news;
           }
            
            return $globalNews;
   }
   
    public function GetGlobalNewsBetweenDate($date_left, $date_right){
        
            $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
            $stmt->execute();
            $globalNews = [];
            $stmt = \util\MySQL::$db->prepare("SELECT COUNT( * ) as Count , District_str,  `public_date` ,  `Stop_words`  FROM global_news WHERE public_date BETWEEN  '$date_left' AND  '$date_right' GROUP BY District_str");
            $stmt->execute();
            
            while($news = $stmt->fetchObject('model\entity\statistic_search')){
                $globalNews[] = $news;
            }
            
            return $globalNews;
    }
    
    public function GetCountOfNewsByStopWord($district){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        $stop_words = $this->GetStopWords();
        $count = 0;
        
        foreach($stop_words as $sw){
            
            $stmt = \util\MySQL::$db->prepare("SELECT COUNT(*) FROM global_news WHERE district = ? and description Like ?");
            $params = array("$district","%{$sw->getWord()}%");
            $stmt->execute($params);
            
            $count += intval($stmt->fetch(\PDO::FETCH_BOTH));
            
        }//foreach
        
        return $count;
        
    }

    public function GetStopWordById($id){

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM stop_words WHERE id = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();

        $stopword = $stmt->fetchObject('model\entity\stopword');

        if(is_a($stopword, 'model\entity\stopword')){
               return $stopword;
        }//if
        else{
            return NULL;
        }

    }

    public function GetStopWordByTitle($word){

        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM stop_words WHERE word LIKE ?");
        $param = array("%$word%");

        $stmt->execute($param);

        $stopword = $stmt->fetchObject('model\entity\stopword');

        if(is_a($stopword, 'model\entity\stopword')){
               return $stopword;
        }//if
        else{
            return NULL;
        }

    }

    public function AddStopWord($word){

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM stop_words WHERE word = :word");
        $stmt->bindParam(":word",$word);
        $stmt->execute();

        $exist_word = $stmt->fetchObject('model\entity\stopword');

        if(is_a($exist_word,'model\entity\stopword')){

            return false;

        }//if

        else{

            $stmt = \util\MySQL::$db->prepare("INSERT INTO stop_words(id,word,Date)  VALUES(NULL,:word,now())");
            $stmt->bindParam(":word",$word);
            $st = $stmt->execute();

            if($st == 1){
                return true;
            }//if
            else{
                return false;
            }//else
        }//else

    }//AddStopWord

    public function AddStatisticWord($user_id,$word_id) {

        $stmt = \util\MySQL::$db->prepare("INSERT INTO statistic_stop_words(user_id,word_id) VALUES(:ui,:wi)");
        $stmt->bindParam(":ui",$user_id);
        $stmt->bindParam(":wi",$word_id);
        $res = $stmt->execute();

        return ($res == 1) ? true : false;

    }//

    public function GetStatisticWordsByUserId($id) {

        $stat_wrd = [];

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM statistic_stop_words WHERE user_id = :uid");
        $stmt->bindParam(":uid",$id);
        $stmt->execute();

        while( $sw = $stmt->fetchObject('model\entity\statistic_stop_word')){

            $stat_wrd[] = $sw;

        }//while

        return $stat_wrd;

    }

    public function GetStatisticByStopWordId($id) {

        $stmt = \util\MySQL::$db->prepare("SELECT COUNT(id) FROM statistic_stop_words WHERE word_id = :wId");
        $stmt->bindParam(":wId",$id);
        $stmt->execute();

        $array = $stmt->fetchAll( \PDO::FETCH_COLUMN, 0 );

        return $array;

    }//public

    public function GetStopWords(){

        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM stop_words");
        $stmt->execute();

        $stop_words = [];

        while($stop_word = $stmt->fetchObject('model\entity\stopword')){

            $stop_words[] = $stop_word;

        }//while

        return $stop_words;

    }

}
