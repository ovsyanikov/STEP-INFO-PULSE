<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script> 
        <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
        <title>Info-Pulse</title>

    </head>
    <body class="news-bg"> 
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
                        <a href="index?ctrl=news&act=news"><li class="active menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
                <div class="div-menu">
                  
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class="active menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
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
            
            <h1 class="h1">Сервисы поиска сайта</h1>
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
                <a  href="fb_queries.php">Facebook поиск <?php echo "{$this->view->fb_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="ya_queries.php">Yandex поиск <?php echo "{$this->view->ya_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="?ctrl=news&act=VkGroupSettings">Закрытые группы ВК поиск</a>
            </div>            
<!--            <div class="side-post">
                <div style="cursor: pointer" id="GroupsVk">Закрытые группы ВК поиск </div>
            </div>            -->
        </aside>
        
        <section class="news-section" id="news-section">
            <?php
                if(count($this->view->finded_news) == 0){
                    echo '<h1 class="h1">Результаты по запросу "' . $this->view->user_word . '"не дал результатов</h1>';
                }
                else{
                    echo '<h1 class="h1">Результаты по запросу "' . $this->view->user_word . '"</h1>';
                }        
            ?>
            
            <div id="newsContent">
                <?php
                        ini_set("max_execution_time", "500");
                        foreach($this->view->finded_news as $news){
                            echo '<div class="post">';
                            $d_id = $news->getId();
                            $ch_social = $news->getSearchType();
                            $post_distr = $news->getDistrict_str();
                            $post_sw = $news->getStop_words();
                            $title = str_replace('\n', '', $news->getTitle());
                            $source = $news->getSource();
                            
                            $description = $news->getDescription();
                            
                            if(strlen($description) > 300){
                                
                                $description = iconv_substr($description,0, 300, 'UTF-8');
                                $description .= "...";
                                
                            }//if
                            $description = str_replace($post_distr, "<span class=\"bold\">$post_distr</span>", $description);
                            $description = str_replace($post_sw, "<span class=\"bold\">$post_sw</span>", $description);
                            
                            $description = str_replace("\\n", " ", $description);
                            $description = stripslashes($description);
                            
                            $date = $news->getDate();
                            
                            $image = $news->getImage();
                            
                            if($ch_social == 't'){
                                echo "<a href=\"$source\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a>";
                            }//if
                            else if($ch_social == 'v'){
                                    echo "<a href=\"$source\" title=\"Ссылка на первоисточник\" target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a>";
                            }//else if
                            else if($ch_social == 'f'){
                                echo "<a href=\"$source\" title=\"Ссылка на первоисточник\" target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a>";
                            }//else if
                            else if($ch_social == 'g' || $ch_social == 'n'){
                                echo "<a href=\"$source\" title=\"Ссылка на первоисточник\" target=\"_blank\"><span  class=\"google post-icon\">V</span></a>";
                            }//else if
                            else if($ch_social == 'y'){
                                echo "<a href=\"$source\" title=\"Ссылка на первоисточник\" target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a>";
                            }//else if
                            
                            //qr
                            echo "<span  class=\"post-date2\" title=\"Время публикации\">$date</span>";    
                            if($image != null){
                                
                                echo "<img  class=\"post-img\" src=\"$image\" alt=\"\"/>";
                                echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\" class=\"post-h2 h2\">$title</h2></a>";
                                echo "<p id=\"postContent\" class=\"post-text\">$description</p>";
                                
                            }//if
                            else{
                                echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\" class=\"post-h2 h2\">$title</h2></a>";
                                echo "<p id=\"postContent\" class=\"post-text\">$description</p>";
                            }//else
                            

                            echo "<p  class=\"post_bottom\">Район: $post_distr, cтоп-слово:$post_sw</p>";                            
                            echo '</div>';
                            
                        }//foreach
                    
                    
                ?>
            </div>
            <script>
            
//                    if($("#newsContent div.post").length != 0){
//                        $("#newsContent").append('<input class="My-posts-button submit" id="more_news" value="Следующие новости" type="button">');
//                    }//if
                    
            </script>
<!--            <div class="post">
                <h2 id="postTitle" class="post-h2 h2"></h2>
                <p id="postContent" class="post-text"></p>
            </div>-->

        </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-pulse 2015</h2>
    </footer>
    <div class="addeed_new_news" class="hidden" title="Добавлены новые записи, нажмите для просмотра">!</div>    
    <div id="toTop" class="hidden">E</div>
</body>
</html>
