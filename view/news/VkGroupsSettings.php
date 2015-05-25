<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script> 
        
        <title>Info-Pulse</title>

        
    </head>
    
    <body id="body" class="news-bg"> 
        
    <heder>
        <div class="top-head">
            <div class="content_menu content">
                <a href="?ctrl=news&act=news"><div class="logo">
                    <img src="img/info-puls1.png" alt="">
                    <h1 class="logo-h1">PULSE</h1>
                </div></a>
                <div id="div_min">
                    <div class="min_menu_btn" id="min_menu_btn">
                        a
                    </div>  
                    <ul class="hidden menu_min " id="menu_min">
                        <a href="index?ctrl=news&act=news"><li class=" menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="active menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
                <div class="div-menu">
                  
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class=" menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="active menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>
                    </ul>


                    <div id="search_list_div" class="user_list" style="display: none; margin-left: -150px; margin-top: 0px;">
                            <div class="nano has-scrollbar">
                                <div class="nano-content" tabindex="0" style="margin-right: -23px;"> 
                                    <div id="search_list_content">
                                    </div>
                                </div>
                                <div class="nano-pane" style="display: none;">
                                    <div class="nano-slider" style="height: 155px; transform: translate(0px, 0px);"></div>
                                </div>
                                
                            </div>
                    </div>                       
                </div>
                <div class="search">
                    <input id="search" type="search" class="isearch" placeholder="Поиск">
                    <span class="search-icon">A</span>
                </div>                
            </div>
        </div>
    </heder>
    <div class="content">
        <div class="bar">
        <aside class="sidebar">
                        
            <div class="personal">
                <a href="?ctrl=user&act=MyProfile">Личный кабинет(<?php
                    echo "{$this->view->current_user->getLogin()}"
                    ?>)</a> / 
                <a href="?ctrl=user&act=leave">Выйти</a>
            </div>
            
            <h1 class="h1">Сервисы поиска по пользователям</h1>
            <h2 class="h2"><a href="<?php 
                    echo "{$this->view->vk_auth}";
                ?>">Авторизация вк</a>
            </h2>
            <script>
        
                loc = new String(window.location.search);
                
                if(loc.indexOf('code') != -1){
                    
                    
                   vk_code = loc.split('code')[1].split('=')[1];
                   
                   $.get("vk_auth.php",{code: vk_code},function(data){
                       
                       window.location = "index.php?ctrl=news&act=VkGroupSettings";
                       
                   });
                   
                }//if
                
                
        </script>
            <div class="side-post">
                <h2 class="h2"><a href="?ctrl=news&act=VkGroupSettings">Поиск по группам/пользователям</a></h2>
            </div>  
            <h2 class="h2"><a href="#" id="Main">Показать все записи</a></h2>            
        </aside>
        <aside class="sidebar" style="margin-top: 6px;">
            <h1 class="h1">Пользователи системы:</h1> 
            <h2 class="h2"><a href="#" id="Opponents">Оппоненты</a></h2>
            <h2 class="h2"><a href="#" id="Main_users">Наши</a></h2>
        </aside>
    </div>
    <section class=" news-section">
        <div id="newsContent">
        <h1 class="h1">Поиск по группам/пользователям <br/>(Вы вошли как <?php 
            
            $at = $this->view->access_token;
            $u_id = $this->view->user;
            $params = array(
                'uids' => $u_id,
                'fields' => 'uid,first_name,last_name,screen_name',
                'access_token' => $at
                
            );
            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
            echo "{$userInfo['response'][0]['first_name']} {$userInfo['response'][0]['last_name']}";
            
        ?>)</h1>
        <div id="DistrictSectionConfirm" class="distr_section_input">
            <h2 class="srch-h2 pers-h2 h2">Добавить группу</h2><input id="NewGroup" name="NewGroup" type="text" class="srch_panel pers-input" placeholder="Введите id или Screenname группы"><span id="AddNewVkGroupSettings" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>
        <div id="UsersSectionConfirm" class="distr_section_input">
            <h2 class="srch-h2 pers-h2 h2">Добавить пользователя</h2><input id="NewVkUser" name="NewVkUser" type="text" class="srch_panel pers-input" placeholder="Введите имя пользователя системы"><span id="AddNewVkUser" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>
<!--        <div id="SocialSectionConfirm"> 
            <h2 class="srch-h2 pers-h2 h2">Добавить новую сеть</h2><input id="NewSocial" name="NewSocial" type="text" class="srch_panel pers-input" placeholder="Введите название социальной сети"><span id="NewSocialAction" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>-->
        <div id="AddSocialToUser">
            <span >Пользователь - </span>
            <select id="UsersList">
                <?php foreach ($this->view->info_users as $user) { ?>
                    <option data-user-id="<?php echo $user->id; ?>"> <?php echo $user->UserName; ?></option>
                <?php }?>
            </select>
            <span>Социальная сеть - </span>
            <select id="SocialList">
                <?php foreach ($this->view->social_types as $social) { ?>
                    <option data-social-id="<?php echo $social->id; ?>"> <?php echo $social->SocialName; ?></option>
                <?php }?>
            </select>
            <input id="AccName" type="text" placeholder="Добавить аккаунт"/>
            <input id="AddSocialToUserInput" class="submit" type="button" value="Добавить"/>
        </div>
        <div id="search-panel" class="post">

            <div id="UsersSet" class="selectGr">
                <h2 class="h2-distr">Пользователи для поиска <a href="vk_users_queries.php" title="Поиск"><span class="srch_set search-icon">A</span></a></h2>
                <ul id="UsersOrder" class="chng_distr_div">

                    <?php   
                    if(count($this->view->info_users) == 0){
                        echo "<h2 id='NotFoundAnyUsers' class=\"h2\" style=\"padding:15px 0px;\">Пользователи для поиска не найдены</h2>";
                    }//if
                    else{
                        foreach ($this->view->info_users as $info_user){
                            echo "<div><li data-user-id=\"{$info_user->id}\" class=\"chng_distr_li\">{$info_user->UserName}<span class=\"remove_user  chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li>";
                            echo "<div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_group_inp pers-input\" placeholder=\"Изменение пользователя\">"
                                . "<span class=\"chnd_vk_users_ok ok user\" title=\"Подтвердить изменения\">N</span></div><div class=\"soc_hubs\"><div>";//<span>Социальные сети:</span>
                            if(count($info_user->Socials) == 0){
                                 echo "<div style=\"padding:8px 30px;\">Не найдены</div>";
                            }//if count == 0
                            else{
                                foreach($info_user->Socials as $social){
                                    if($social->SocialId == 'Vkontakte'){
                                        $soc_acc = $social->AccsName;
                                        echo "<div class=\"hub\"><a href=\"https://vk.com/$soc_acc\">$social->AccsName($social->SocialId)</a><span data-acc-id=\"$social->id\" class=\"remove_user_hub chng_distr_correct correct\" title=\"Удалить\">J</span></div>";    
                                    }
                                    if($social->SocialId == 'Twitter'){
                                        $soc_acc = $social->AccsName;
                                        echo "<div class=\"hub\"><a href=\"https://twitter.com/$soc_acc\">$social->AccsName($social->SocialId)</a><span data-acc-id=\"$social->id\" class=\"remove_user_hub chng_distr_correct correct\" title=\"Удалить\">J</span></div>";
                                    }                                    
                                }//foreach
                            }//else
                            
                            echo "</div></div></div>";

                        }//foreach
                    }

                    ?>               
                </ul>
            </div>

            <div  class="selectGr"  style="float:right">
                <h2 class="h2-distr">Группы <a href="vk_groups_queries.php" title="Поиск"><span class="srch_set search-icon">A</span></a></h2>
                <ul id="GroupsOrder" class="chng_distr_div">
                    <?php   
                    if(count($this->view->groups) == 0){
                        echo "<h2 id='NotFoundAnyGroups' class=\"h2\" style=\"padding:15px\">Группы для поиска не найдены</h2>";
                    }//if
                    else{
                        foreach ($this->view->groups as $group){
                            echo "<div><li data-group-id=\"{$group->id}\" class=\"chng_distr_li\"><a href=\"http://vk.com/{$group->GroupTitleId}\" title=\"Ссылка на первоисточник\">{$group->GroupTitleId}</a><span class=\"remove_group chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li>";
                            echo "<div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_group_inp pers-input\" placeholder=\"Редактирование группы\">"
                                . "<span class=\"chnd_group_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>";

                        }//foreach
                    }

                    ?>               
                </ul>
            </div>            
            

            </div>
        </div>
        </div>
    </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-pulse 2015</h2>
    </footer>
    <div class="addeed_new_news" class="hidden" title="Добавлены новые записи, нажмите для просмотра">!</div>    
    <div id="toTop" class="hidden">E</div>
</body>

</html>
