<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link rel="stylesheet" href="css/nanoscroller.css">        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script> 
        <script type="text/javascript" src="js/jquery.nanoscroller.min.js"></script>         
        <title>Info-Pulse</title>
    </head>
    <script>
        
        $( document ).ready(function() {
            $(".nano").nanoScroller();
        });
    </script> 
    
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
                        <a href="index?ctrl=news&act=news"><li class=" menu-li">ГЛАВНАЯ</li></a>
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
        </div>
        <section class=" news-section">
            <div class="makepost">
                <form id="NewPostForm" action="?ctrl=news&act=ConfirmPost" method="POST"  enctype="multipart/form-data">
                    <h2 class="post-h2 h2">Введите заголовок записи</h2>
                    <input class="makepostInput input" name="postTitle" id="postTitle" placeholder="Заголовок">
                    <h2 class="post-h2 h2">Введите текст записи</h2>
                    <textarea name="makePostArea" id="makePostArea" placeholder="Текст"></textarea>
                    
<!--                    //<div  >-->
                
                        <div id="user_list_div" class="user_list">
                            <div  class=" nano has-scrollbar ">
                                <div class="nano-content"> 
                                    <div id="user_list_content">

                                    </div>
                                </div>
                            </div>
                        </div>    
<!--                    </div>     -->
                        
                        <div id="district_list_div" class="user_list">
                            <div  class=" nano has-scrollbar ">
                                <div class="nano-content"> 
                                    <div id="district_list_content" >

                                    </div>
                                </div>
                            </div>     
                        </div>      

                    <div class="user_files_submit submit">
                        Выбрать файлы
                        <input id="user_files" type="file" name="user_files[]" multiple=""/>
                    </div>
                    
                    <input class="makepost-button submit" id="addPost" value="Опубликовать" type="button">
                    
                    <input id="SelectedDistrict" name="SelectedDistrict" type="text" style="display: none">
                    <input id="SelectedDistrictId" name="SelectedDistrictId" type="text" style="display: none">
                </form>
                    
            </div>
            <pre id="first_str"></pre>
            <pre id="first_str_txt_area"></pre>
            <div id="error" class="invisible"></div>
        </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-pulse 2015</h2>
    </footer>
    <div class="addeed_new_news" class="hidden" title="Добавлены новые записи, нажмите для просмотра">!</div>    
    <div id="toTop" class="hidden">E</div>
</body>
</html>
