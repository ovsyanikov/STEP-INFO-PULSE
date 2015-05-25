    <!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/PersonOfInterest.js"></script>         
        <title>Info-Pulse</title>

        
    </head>
    
    <body id="body" class="news-bg"> 
        
    <heder>
        <div class="top-head">
            <div class="content">
                <a href="?ctrl=news&act=news"><div class="logo">
                    <img src="img/info-puls1.png" alt="">
                    <h1 class="logo-h1">PULSE</h1>
                </div></a>
                <div class="div-menu">
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
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
    <aside class="sidebar"> 
        <div class="personal">
            <a href="?ctrl=user&act=MyProfile">Личный кабинет(<?php
                echo "{$this->view->current_user->getLogin()}"
                ?>)</a> / 
            <a href="?ctrl=user&act=leave">Выйти</a>
        </div>
                        
        <h1 class="h1">Лента новостей</br>Найдено новостей - <span id="count">0</span></h1>
            <div class="side-post">
                <h2 class="h2"><a href="<?php 
                    echo "{$this->view->tw_auth}";
                ?>">Авторизация twitter</a></h2>
            </div>
            <script>
        
                loc = new String(window.location.search);
                
                if(loc.indexOf('oauth_token') != -1){
                    
                    
                   oauth_token = loc.split('oauth_token')[1].split('=')[1].split('&')[0];
                   oauth_verifier = loc.split('oauth_verifier')[1].split('=')[1];
                   
                   $.post('tw_auth.php',{oauth_token: oauth_token, oauth_verifier: oauth_verifier},function(data){
                       
                       auth_info = $.parseJSON(data);
                       $('#userAuthInfo').html("Поиск по пользователям Twitter<br/>(Вы вошли как " +auth_info['name']+")");
                       
                   });
                   
                }//if
                
                
        </script>
            <div class="side-post">
                <h2 class="h2">Section 1.10.32 of "de Finibus Bonorum et Malorum" <span class="span-time">14:32</span></h2>
            </div>
            <div class="side-post last">
                <h2 class="h2">Section 1.10.32 of "de Finibus Bonorum et Malorum" <span class="span-time">14:32</span></h2>
            </div>
    </aside>
    <section class=" news-section">
        <h1 id ="userAuthInfo" class="h1">Поиск по пользователям Twitter<br/>(Вы не авторизированны)</h1>

        <div id="UsersSectionConfirm"> 
            <h2 class="srch-h2 pers-h2 h2">Добавить пользователя</h2><input id="NewTwitterUser" name="NewTwitterUser" type="text" class="srch_panel pers-input" placeholder="Введите Screenname Twitetr-пользователя"><span id="AddNewTwitterUser" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>
        
        <div id="search-panel" class="post">
            
            <div  class="selectGr" >
                <h2 class="h2-distr">Пользователи для поиска</h2>
                <ul id="UsersOrder" class="chng_distr_div">

                    <?php   
                    if(count($this->view->twitter_users) == 0){
                        echo "<h2 id='NotFoundAnyUsers' class=\"h2\" style=\"padding:15px 0px;\">Пользователи для поиска не найдены</h2>";
                    }//if
                    else{
                        foreach ($this->view->twitter_users as $vk_users){
                            echo "<div><li data-user-id=\"{$vk_users->id}\" class=\"chng_distr_li\"><a href=\"https://twitter.com/{$vk_users->ScreenName}\" title=\"Ссылка на первоисточник\">{$vk_users->ScreenName}</a><span class=\"remove_user_twitter  chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li>";
                            echo "<div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_group_inp pers-input\" placeholder=\"Изменение пользователя\">"
                                . "<span class=\"chnd_twitter_users_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>";

                        }//foreach
                    }

                    ?>               
                </ul>
                <a href="tw_users_queries.php"><input class="groups_button submit" value="Найти" type="button"></a>
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
