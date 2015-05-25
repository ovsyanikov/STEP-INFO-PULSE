<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/nanoscroller.css">        
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/tree.js"></script>
        <script type="text/javascript" src="js/jquery.nanoscroller.min.js"></script>         
        <title>Info-Pulse</title>
    </head>
    <script>
        
        $( document ).ready(function() {
            $(".nano").nanoScroller();
        });
    </script>
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
                        <a href="index?ctrl=news&act=Districts"><li class="active menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
                <div class="div-menu">
                  
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class=" menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="active menu-li">РАЙОНЫ</li></a>
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
    </div>
    <section class=" news-section">

        <h1 class="h1">Список всех районов и стоп-слов:</h1>
        <div id="DistrictSectionConfirm" class="distr_section_input">
            <h2 class="srch-h2 pers-h2 h2">Добавить район</h2><input id="NewDistrict" name="Stop_word_inp" type="text" class="srch_panel pers-input" placeholder="Введите новый район"><span id="AddNewDistrictSettings" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>
        <div id="StopWordSectionConfirm" class="distr_section_input">
            <h2 class="srch-h2 pers-h2 h2">Добавить стоп-слова</h2><input id="NewStopWord" name="Stop_word_inp" type="text" class="srch_panel pers-input" placeholder="Введите новый район"><span id="AddStopWordSettings" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>
        <div id="BadWordSectionConfirm" class="distr_section_input">
            <h2 class="srch-h2 pers-h2 h2">Добавить плохое слово</h2><input id="NewBadWord" name="Stop_word_inp" type="text" class="srch_panel pers-input" placeholder="Введите новый слово"><span id="AddNewWord" class="srch_ok ok" title="Подтвердить изменения">N</span>
        </div>
        <div id="search-panel" class="post">
            <form id="start_search_news" method="POST" action="?ctrl=news&act=getNewsByStopWords">
                <div id="districts" class="chng_distr">
                    <div class="distr_col">
                        <h2 class="h2-distr">Районы</h2>
                        <div style="text-align: center">
                        <input id="SortDistrictsByTitle" class="sort distr-button submit "  data-sorttype="asc" type="button" value="Сортировать по алфавиту" >
                        <input id="SortDistrictsByDate" class="sort distr-button submit "  data-sorttype="asc" type="button" value="Сортировать по дате" >
                        </div>
                        
                        <div  class="nano has-scrollbar ">
                            <div class="nano-content">
                                <ul id="districts_order" class="chng_distr_div">
                                    <?php   
                                        foreach ($this->view->districts as $district){
                                            echo "<div><li data-DistrictDateTime=\"{$district->Date}\" data-district-id=\"{$district->getId()}\" data-district-title=\"{$district->getTitle()}\" class=\"chng_distr_li\">{$district->getTitle()}<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li>";
                                            echo "<div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\">"
                                                . "<span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>";

                                        }//foreach
                                    ?>                
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="ml distr_col">
                        <h2 class="h2-distr">Cтоп-слова</h2>
                        <div style="text-align: center">
                        <input id="SortStopWordsByTitle" class="sort distr-button submit " data-sorttype="asc" type="button" value="Сортировать по алфавиту" >
                        <input id="SortStopWordsByDate" class="sort distr-button submit " data-sorttype="asc" type="button" value="Сортировать по дате" >
                        </div>
                        
                        <div  class="nano has-scrollbar ">
                            <div class="nano-content">
                                <ul id="StopWordsOrder" class="chng_distr_div">
                                    <?php   
                                        foreach ($this->view->stop_words as $stopWord){
                                            echo "<div><li data-stopworddatetime=\"{$stopWord->Date}\" data-stopwordtitle=\"{$stopWord->getWord()}\" data-stop-id=\"{$stopWord->getId()}\" class=\"chng_distr_li\">{$stopWord->getWord()}<span class=\"remove_stop_word chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li>";
                                            echo "<div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование стоп слова\">"
                                                . "<span class=\"chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>";

                                        }//foreach
                                    ?>               
                                </ul>
                            </div>
                        </div>                            
                    </div>
                    <div class="distr_col">
                        <h2 class="h2-distr">Плохие слова</h2>
                        <div  class="nano has-scrollbar ">
                            <div class="nano-content">
                                <ul id="BadWordsOrder" class="chng_distr_div">
                                    <?php   
                                        foreach ($this->view->bad_words as $badWord){
                                            echo "<div><li data-word-id=\"{$badWord->getId()}\" class=\"chng_distr_li\">{$badWord->getWord()}<span class=\"remove_word chng_distr_correct correct\" title=\"Удалить\">J</span>"
                                            . "<span class=\"chng_distr_correct correct\" title=\"Изменить\" style=\"margin:4px \">M</span></li>";
                                            
                                            echo "<div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование слова\">"
                                                . "<span class=\"word chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>";

                                        }//foreach
                                    ?>               
                                </ul>
                            </div>
                        </div>                            
                    </div>                    
                </div>    

            </form>   


    
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
