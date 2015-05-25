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
        <script src="js/jquery.cookie.js"></script>
        <title>Info-Pulse</title>

    </head>
    <script>
        $( document ).ready(function() {
            try{
                
                var el_data_id = $('#newsContent').children().first().attr('data-post-id');
                var old_el_data_id = $.cookie("last_post_id"); 
                
                $.cookie("last_post_id",el_data_id,{expires: 3, path: '/', domain: 'user1187254.atservers.net'});
                //if(old_el_data_id != el_data_id){
                    destination = $('[data-post-id="'+old_el_data_id+'"]').offset().top;
                    $('body,html').animate({scrollTop:destination},800);
                //}
                var offset = ($('#newsContent').children().length) -11;
                $.cookie("offset",offset);
                
            }
            catch(ex){
                console.log(ex.message);
            }
           


        });
    </script>
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
        <div class="bar">

        <aside class="sidebar">
                        
            <div class="personal">
                <a href="?ctrl=user&act=MyProfile">Личный кабинет(<?php
                    echo "{$this->view->current_user->getLogin()}"
                    ?>)</a> / 
                <a href="?ctrl=user&act=leave">Выйти</a>
            </div>
            <div class="absent">
                <p>Пока вас не было появилось<br/> <?php echo $this->view->count_of_new_news; ?> новостей, <?php echo $this->view->count_of_new_tasks; ?> задач</p>
            </div>
            <h1 class="h1">Сервисы поиска</h1>
            <div class="side-post">
                <a href="vk_queries.php" target="_blank">Вконтакте поиск <?php echo "{$this->view->vk_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="tw_queries.php" target="_blank">Twitter поиск <?php echo "{$this->view->tw_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="google_queries.php" target="_blank">Google-web поиск <?php echo "{$this->view->google_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="google_news_queries.php" target="_blank">Google-news поиск <?php echo "{$this->view->google_news_posts}"; ?></a>
            </div>            
            <div class="side-post">
                <a  href="ya_queries_fb.php" target="_blank">Facebook поиск <?php echo "{$this->view->fb_posts}"; ?></a>
            </div>
            <div class="side-post">
                <a  href="ya_queries_lj.php" target="_blank">Livejournal поиск <?php echo "{$this->view->lj_posts}"; ?></a>
            </div>
        
        </aside>
        <aside class="sidebar" style="margin-top: 6px;padding:0px">
                        
            <div id="add_bw">
                <input id="" type="text" class="add_bw chng_distr_inp pers-input" placeholder="Добавить минус-слово">
                <span class="add_bw_ok word chnd_distr_ok ok" title="Подтвердить изменения">N</span>

            </div>
            <?php 
                $checker = $this->view->ShowAllHiddenNews;
                if($checker == NULL){
                    $checker = 'true';
                }//if
            ?>
            <div id="show_hidden_news">
                <span id="minimize_add_bw" title="Скрыть все">─</span>
                <span id="show_add_bw" title="Показать все">+</span>                
                <?php if($checker == 'true'){?>
                    <input type="checkbox" id="ShowAllNews" checked/> <span class="fs10">Показывать скрытые записи</span>
                <?php } else {?>
                    <input type="checkbox" id="ShowAllNews" /> <span class="fs10">Показывать скрытые записи</span>
                <?php }?>
            </div>
            <div id="loader_dis" class="loader"><img src="../img/loader3.GIF" alt=""></div>
        </aside>
        <aside class="hidden sidebar new_news " id="new_news_count">
            <div class="absent">
                <p>Пока вас не было появилось<br/> <span id="new_count"><?php echo $this->view->count_of_new_news; ?></span> новостей, <?php echo $this->view->count_of_new_tasks; ?> задач</p>
            </div>            
        </aside>
        </div>

        <section class="news-section" id="news-section">

            <h1 class="h1">Все новости по дате</h1>
            <div id="newsContent">
                <?php
                    $colors = $this->view->all_news[0]->getColors();
                    
                    foreach($this->view->all_news as $news){
                        $defaul_tag = $news->Tag;
                        $hidden = $news->IsHide;
                        
                        if($checker == 'false' && $hidden == 1){
                            continue;
                        }
                        echo '<div class="post" data-post-id="'.$news->getId().'" data-post-tag="'.$defaul_tag.'">';

                        $more = false;
                        $d_id = $news->getId();
                        $ch_social = $news->getSearchType();
                        $post_distr = $news->getDistrict_str();
                        $post_sw = $news->getStop_words();
                        $title = str_replace('\n', '', $news->getTitle());

                        $source = $news->getSource();
                        $source = str_replace("'", "", $source);
                        $source = str_replace("%20", "", $source);
                        
                        $description = stripslashes($description);
                        $description = $news->getDescription();
                        
                        
                        if(iconv_strlen($description, 'UTF-8') > 300){

                            //$last_part = iconv_substr($description, 300, strlen($description), 'UTF-8');
                            $last_part = $description;
                            
                            $description = iconv_substr($description,0, 300, 'UTF-8');
                            $description .= "...";
                            
                            $more = true;

                        }//if
                        $description = str_replace("\\n", " ", $description); 
                        $description = str_replace("\\r", " ", $description); 
                        
                        ?>
                        <div class="circles">
                        <?php if($defaul_tag != NULL){ ?>
                            <div class="<?php echo $defaul_tag; ?>"></div>
                        <?php } ?>
                        <?php foreach($colors as $color){ if($color != $defaul_tag){?>
                            <div class="<?php echo $color ;?>"></div>
                        <?php }//if
                        
                        }//foreach ?>
                        </div>
<!--                        <div class="circles"><div class="grey"></div><div class="red"></div><div class="blue"></div></div>-->
                        <?php

                        $date = $news->getDate();

                        $image = $news->getImage();
                        if($ch_social == 't'){
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a>";
                        }//if
                        else if($ch_social == 'v'){
                                echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a>";
                        }//else if
                        else if($ch_social == 'f'){
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a>";
                        }//else if
                        else if($ch_social == 'g' || $ch_social == 'n'){
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a>";
                        }//else if
                        else if($ch_social == 'y'){
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a>";
                        }//else if
                        else if($ch_social == 'i'){
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a>";
                        }//else if
                        else if($ch_social == 'lj'){
                            echo "<a href=\"$source\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a>";
                        }//else if

                        if($hidden==0){
                        
                        echo "<span  class=\"hide_post post-icon\" title=\"Скрыть запись\">O</span>";
                                                   //qr
                        echo "<span  class=\"post-date2\" title=\"Время публикации\">$date</span>";   

                            if($image != null){

                                 if( strstr($image, "http") == FALSE){
                                    $varible = explode(',',$image);
                                    $images_array = (array_shift ($varible));

                                    echo "<img  class=\"post-img\" src=\"files/$images_array\" alt=\"\"/>";
                                    echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\"class=\"post-h2 h2\">$title</h2></a>";
                                    echo "<p id=\"postContent\" class=\"post-text\">$description</p>";

                                }//if
                                else{
                                    echo "<img  class=\"post-img\" src=\"$image\" alt=\"\"/>";
                                    echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\"class=\"post-h2 h2\">$title</h2></a>";
                                    echo "<p id=\"postContent\" class=\"post-text\">$description</p>";
                                }
                            }//if
                            else{
                                echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\" class=\"post-h2 h2\" style=\"max-width:60%\">$title</h2></a>";
                                echo "<p id=\"postContent\" class=\"post-text\">$description</p>";
                            }//else
                            if($more){
                                echo "<div class=\"show_all\">Показать все</div>";
                            }
                        } 
                        else{
                            echo "<span  class=\"hide_post post-icon\" title=\"Скрыть запись\">E</span>";
                            echo "<span  class=\"post-date2\" title=\"Время публикации\">$date</span>";   

                            if($image != null){

                                if( strstr($image, "http") == FALSE){
                                    $varible = explode(',',$image);
                                    $images_array = (array_shift ($varible));

                                        echo "<img  class=\"hide post-img\" src=\"files/$images_array\" alt=\"\"/>";
                                        echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\"class=\"title_hidden post-h2 h2\">$title</h2></a>";
                                        echo "<p id=\"postContent\" class=\"hide post-text\">$description</p>";
                                }//if
                                else{
                                        echo "<img  class=\"hide post-img\" src=\"$image\" alt=\"\"/>";
                                        echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\"class=\"title_hidden post-h2 h2\">$title</h2></a>";
                                        echo "<p id=\"postContent\" class=\"hide post-text\">$description</p>";
                                }

                            }//if
                            else{
                                echo "<a href=\"?ctrl=news&act=SpecificPostHome&id={$d_id}\"><h2 id=\"postTitle\" class=\"title_hidden post-h2 h2\">$title</h2></a>";
                                echo "<p id=\"postContent\" class=\"hide post-text\">$description</p>";
                            }//else
                        }

                        echo "<p class=\"last_part\">$last_part</p>";
                        
                        echo "<p  class=\"post_bottom\">Район: $post_distr, cтоп-слово: $post_sw</p>";                          
                        echo '</div>';   
                    }//foreach
                        
                    
                ?>
            </div>
            <script>

                if($("#newsContent div.post").length != 0){
                    $("#newsContent").append('<input class="My-posts-button submit" id="more_news" value="Следующие новости" type="button">');
                }//if
                    
            </script>


        </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-pulse 2015</h2>
    </footer>
    <div class="addeed_new_news" class="hidden" title="Добавлены новые записи, нажмите для просмотра">!</div>
<!--    <div id='dialog' class="submit"  title='Оповещение'>
        Добавленны новые записи
    </div>-->
    <div id="toTop" class="hidden">E</div>
</body>
</html>
