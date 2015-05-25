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
    <body class="news-bg"> 
    <heder>
        <div class="top-head">
            <div class="content">
                <a href="?ctrl=news&act=news"><div class="logo">
                    <img src="img/info-puls1.png" alt="">
                    <h1 class="logo-h1">PULSE</h1>
                </div></a>
                <div class="div-menu">
                    <ul class="menu">
                        <a href="?ctrl=news&act=news"><li class="active menu-li">ГЛАВНАЯ</li></a>
                        <a href="?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href=""><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                    </ul>

                    <div class="search">
                        <input id="search" type="search" class="isearch" placeholder="Поиск">
                        <span class="search-icon">A</span>
                    </div>
                <img src="img/loader2.gif" alt="" id="loader" class="hide">

                </div>

            </div>
        </div>
        <div class="bottom-head">
            <div class="content">
                <ul class="menu">
                    <a href="?ctrl=news&act=news"><li class="active menu-li">ГЛАВНАЯ</li></a>
                    <a href="?ctrl=news&act=districts"><li class="menu-li">РАЙОНЫ</li></a>
                    <a href=""><li class="menu-li">ЗАДАЧИ</li></a>
                    <a href=""><li class="menu-li">УЧАСНИКИ</li></a>
                    <a href="?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                </ul>


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
                <a href="?ctrl=social&act=GetVkNews"><h2 class="h2">Вконтакте поиск ( <?php echo "{$this->view->vk_posts}"; ?> )</h2></a>
            </div>
            <div class="side-post">
                <h2 class="h2">Twitter поиск ( <?php echo "{$this->view->tw_posts}"; ?> )</h2>
            </div>
            <div class="side-post">
                <h2 class="h2">Google поиск ( <?php echo "{$this->view->google_posts}"; ?> )</h2>
            </div>
            <div class="side-post">
                <h2 class="h2">Facebook поиск ( <?php echo "{$this->view->fb_posts}"; ?> )</h2>
            </div>
            
        </aside>
        
        
        <section class="news-section" id="news-section">
            <div class="main-img top-3">
                <img src="img/moscow_1.jpg" alt="">
            </div>
            <h1 class="h1">Все новости по дате</h1>
            <div id="newsContent">
                <?php
                    require_once 'util/Request.php';
                    use util\Request;
                    $request = new Request();                
                    $count = count($this->view->all_news);
                    $count_vk_records = $request->getCookieValue('vk_records');
                    
                    if($count_vk_records != 0){
                        
                        echo "<h2 id=\"postTitle\" class=\"post-h2 h2\">Новых записей из Вконтакте - $count_vk_records</h2>";
                        $request->unsetCookie('vk_records');
                        
                    }//if
                    
                    if($count == 0){
                        echo '<h2 id="postTitle" class="post-h2 h2">База данных пока что пуста!</h2>';
                    }//if
                    else{
                        foreach($this->view->all_news as $news){
                            echo '<div class="post">';
                            $d_id = $news->getId();
                            $ch_social = $news->getSource();
                           //$title = html_entity_decode($news->getTitle());
                            $title =  htmlspecialchars_decode($news->getTitle());
                            if(strlen($title) > 50){
                                
                                $title = iconv_substr($title,0, 47, 'UTF-8');
                                
                                $title .= "...";
                                
                            }//if
                            
                            $description = $news->getDescription();
                            
                            if(strlen($description) > 300){
                                
                                $description = iconv_substr($description,0, 300, 'UTF-8');
                                $description .= "...";
                                
                            }//if
                            $date = $news->getDate();
                            
                            $image = $news->getImage();
                            if(strripos($ch_social,'twitter') != false){
                                echo "<a href=\"$ch_social\" title=\"Ссылка на первоисточник\"><span  class=\"twitter post-icon\">R</span></a>";
                            }else if(strripos($ch_social,'vk.com') != false){
                                    echo "<a href=\"$ch_social\" title=\"Ссылка на первоисточник\"><span  class=\"vk post-icon\">Q</span></a>";
                                }else{
                                    echo "<a href=\"$ch_social\" title=\"Ссылка на первоисточник\"><span  class=\"google post-icon\">V</span></a>";
                                }
                            
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
                            
                            $post_distr = $news->getDistrict_str();
                            $post_sw = $news->getStop_words();
                            echo "<p  class=\"post_bottom\">Район: $post_distr, cтоп-слово:$post_sw</p>";
                            //echo "<p  class=\"post_bottom\">Стоп-слово: $post_sw</p>";                            
                            echo '</div>';
                            
                        }//foreach
                    }//else
                    
                    
                ?>
            </div>
            <script>
            
                    if($("#newsContent div.post").length != 0){
                        $("#newsContent").append('<input class="My-posts-button submit" id="more_news" value="Следующие новости" type="button">');
                    }//if
                    
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
    <div id="toTop" class="hidden">E</div>
</body>
</html>
