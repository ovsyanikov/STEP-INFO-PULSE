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
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="active menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
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
        <div class="bar">
        <aside class="sidebar">
                        
            <div class="personal">
                <a href="?ctrl=user&act=MyProfile">Личный кабинет(<?php
                    echo "{$this->view->current_user->getLogin()}"
                    ?>)</a> / 
                <a href="?ctrl=user&act=leave">Выйти</a>
            </div>
            
            <h1 class="h1">Сервисы поиска по пользователям</h1>
            <div class="side-post">
                <h2 class="h2"><a href="?ctrl=news&act=VkGroupSettings">Поиск по группам/пользователям</a></h2>
            </div>  
            <h2 class="h2"><a href="#" id="Main">Показать все записи</a></h2>            
        </aside>
        <aside class="sidebar" style="margin-top: 6px;">
            <h1 class="h1">Пользователи системы:</h1> 
            <h2 class="h2"><a href="#" id="Opponents">Оппоненты</a></h2>
            <h2 class="h2"><a href="#" id="Main_users">Наши</a></h2>

<!--                    <input id="Opponents" class="distr-button submit mr" type="button" value="ОППОНЕНТЫ">
                    <input id="Main" class="distr-button submit"  type="button" value="НАШИ">-->
        </aside>
        </div>
        <section class=" news-section">
            <div id="loader_dis" class="loader"><img src="../img/loader3.GIF" alt=""></div>

            <div id="newsContent">
                
                 <?php
                 
                    if(count($this->view->news_by_interests) == 0){
                        
                        echo "<h2 class='h2'>Поиск по скрытым группам и пользователям пока не дал результатов</h2>";
                    }//if
                    else{
                        
                        foreach($this->view->news_by_interests as $news){
                            echo '<div class="post" data-post-id="'.$news->getId().'" >';
                            $hidden = $news->IsHide;
                            $d_id = $news->getId();
                            $ch_social = $news->getSearchType();
                            $post_distr = $news->getDistrict_str();
                            $post_sw = $news->getStop_words();
                            $title = str_replace('\n', '', $news->getTitle());
                            $source = $news->getSource();
                            $source = str_replace("'", "", $source);
                            $source = str_replace("%20", "", $source);

                            $description = $news->getDescription();

                            if(strlen($description) > 300){

                                $description = iconv_substr($description,0, 300, 'UTF-8');
                                $description .= "...";

                            }//if
                            $description = str_replace($post_distr, " <span class=\"bold\">$post_distr</span>", $description);
                            $description = str_replace($post_sw, " <span class=\"bold\">$post_sw</span>", $description);

                            $description = str_replace("\\n", " ", $description);
                            $description = stripslashes($description);

                            $date = $news->getDate();

                            $image = $news->getImage();
                            
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\" target=\"_blank\"><span  class=\"info post-icon\">Y</span></a>";
                                
                            if($hidden==0){
                                
                            echo "<span  class=\"hide_post post-icon\" title=\"Скрыть запись\">O</span>";
                                                       //qr
                            echo "<span  class=\"post-date2\" title=\"Время публикации\">$date</span>";   
                                
                                if($image != null){
                                     
                                    echo "<img  class=\"post-img\" src=\"$image\" alt=\"\"/>";
                                    echo "<a href=\"?ctrl=news&act=SpecificPostHome&PersonId={$d_id}\"><h2 id=\"postTitle\"class=\"post-h2 h2\">$title</h2></a>";
                                    echo "<p id=\"postContent\" class=\"post-text\">$description</p>";
                                    
                                }//if
                                else{
                                    echo "<a href=\"?ctrl=news&act=SpecificPostHome&PersonId={$d_id}\"><h2 id=\"postTitle\" class=\"post-h2 h2\">$title</h2></a>";
                                    echo "<p id=\"postContent\" class=\"post-text\">$description</p>";
                                }//else


                            } 
                            else{
                                echo "<span  class=\"hide_post post-icon\" title=\"Скрыть запись\">E</span>";
                                echo "<span  class=\"post-date2\" title=\"Время публикации\">$date</span>";   
                                
                                if($image != null){
                                    
                                    echo "<img  class=\"hide post-img\" src=\"$image\" alt=\"\"/>";
                                    echo "<a href=\"?ctrl=news&act=SpecificPostHome&PersonId={$d_id}\"><h2 id=\"postTitle\"class=\"title_hidden post-h2 h2\">$title</h2></a>";
                                    echo "<p id=\"postContent\" class=\"hide post-text\">$description</p>";
                                        
                                }//if
                                else{
                                    echo "<a href=\"?ctrl=news&act=SpecificPostHome&PersonId={$d_id}\"><h2 id=\"postTitle\" class=\"title_hidden post-h2 h2\">$title</h2></a>";
                                    echo "<p id=\"postContent\" class=\"hide post-text\">$description</p>";
                                }//else
                            }
                            echo "<p  class=\"post_bottom\">Район: $post_distr, cтоп-слово:$post_sw</p>";                          
                            echo '</div>';   
                        }//foreach
                        
                    }//else count != 0
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
