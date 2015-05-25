<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script> 
        <title>Info-Pulse</title>
    </head>
    <body i="body" class="news-bg"> 
        
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
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="active menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
                <div class="div-menu">
                  
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="active menu-li">МОИ ЗАПИСИ</li></a>
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
            
            <h1 class="h1">Сервисы поиска</h1>
            <div class="side-post">
                <a href="vk_queries.php">Вконтакте поиск <?php echo "{$this->view->vk_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="tw_queries.php">Twitter поиск <?php echo "{$this->view->tw_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="google_queries.php">Google-web поиск <?php echo "{$this->view->google_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="google_news_queries.php">Google-news поиск <?php echo "{$this->view->google_news_posts}"; ?></a>
            </div>            
            <div class="side-post">
                <a  href="ya_queries_fb.php">Facebook поиск <?php echo "{$this->view->fb_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="ya_queries_lj.php">Livejournal поиск <?php echo "{$this->view->lj_posts}"; ?></a>
            </div>
        
<!--            <div class="side-post">
                <div style="cursor: pointer" id="GroupsVk">Закрытые группы ВК поиск </div>
            </div>            -->
        </aside>
        </div>
        <section class=" news-section">

            <a href="?ctrl=news&act=MakePost"><input class="My-posts-button submit" id="addPost" value="Добавить запись" type="button"></a>

            <div id="newsContent">
                  <?php 
                  
                       $count_current_posts = count($this->view->current_user_news);
                       if($count_current_posts == 0){
                           echo "<h2 class=\"post-h2 h2\" style=\"margin: 15px 0px\">У вас пока нет записей!</h2>";
                       }//if
                       else{
                           foreach($this->view->current_user_news as $specific_news){
                               
                           $post_title = $specific_news->getTitle();
                           $post_description = $specific_news->getDescription();
                           
                           if(strlen($post_title) > 50){
                              $post_title = iconv_substr($post_title, 0, 40,'UTF-8');
                           }//if
                           
                           $post_title .= "...";
                           
                           if(strlen($post_description) > 150){
                              $post_description = iconv_substr($post_description, 0, 140,'UTF-8');
                           }//if
                           $post_description = str_ireplace('\r', ' ', $post_description);
                           $post_description = str_ireplace('\n', ' ', $post_description);
                           $post_description .= "...";
                           $images = $specific_news->getImages();
                           
                           if($images != NULL){
                                if( stripos($images, "http") == FALSE){
                                    $varible = explode(',',$specific_news->getImages());
                                    $images_array = (array_shift ($varible));
                                    
                                        echo "<div data-user-login=\"{$specific_news->getSearchType()}\" class=\"post\"><div data-post-id=\"{$specific_news->getId()}\"  class=\"delete-post\">J</div><img class=\"post-img\" alt=\"\" src=\"files/$images_array\"/><a href=\"?ctrl=news&act=SpecificPostHome&id={$specific_news->getId()}\"><h2 class=\"post-h2 h2\">$post_title</h2></a><p class=\"post-text\">$post_description</p></div>";
                                }//if
                                else{
                                    echo "<div data-user-login=\"{$specific_news->getSearchType()}\" class=\"post\"><div data-post-id=\"{$specific_news->getId()}\"  class=\"delete-post\">J</div><img class=\"post-img\" alt=\"\" src=\"{$specific_news->getImages()}\"/><a href=\"?ctrl=news&act=SpecificPostHome&id={$specific_news->getId()}\"><h2 class=\"post-h2 h2\">$post_title</h2></a><p class=\"post-text\">$post_description</p></div>";
                                }
                           }//if
                           
                           else{
                                 echo "<div data-user-login=\"{$specific_news->getSearchType()}\" class=\"post\"><div  data-post-id=\"{$specific_news->getId()}\"  class=\"delete-post\">J</div><a href=\"?ctrl=news&act=SpecificPostHome&id={$specific_news->getId()}\"><h2 class=\"post-h2 h2\">$post_title</h2></a><p class=\"post-text\">$post_description</p></div>";
                                  
                           }//else
                           
                       }//foreach
                       }//else
                       
                  ?>
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
