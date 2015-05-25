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
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="active menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
                <div class="div-menu">
                  
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="active menu-li">СТАТИСТИКА</li></a>
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

        <section class="news-section" id="news-section">
            <h1 class="h1" style="margin-bottom: 10px">Статистика по датам <span class="export_btn" id="ExportToWord" title="Экспорт в Word">b</span></h1>

            <div id="statistic_menu">
                <span>От</span>
                <input id="leftDate" type="date" class="inp_stat mr" > 
                <span>До</span>
                <input id="rightDate" type="date" class="inp_stat">
                <span id="SearchByDate" class="search_stat search-icon" title="Поиск">A</span>
<!--                <input id="SearchByDate" type="button" value="Найти" class="stat_btn submit">-->
<!--                <input type="button" id="ExportToWord" value="Экспортировать в Word" class="stat_btn submit">-->
            </div>
            <div id="WrapTable" class="WrapTable">
                <table class="statistic_table">
                    <thead>
                        <tr>
                            <th >Район</th>
                            <th >Количество записей</th>
                            <th >Стоп слова</th>
                        </tr>
                    </thead>
                    <tbody id="SearchResultContent">
                        
                    </tbody>
                </table>
            </div>
            <h1 class="h1" style="margin-bottom: 10px">Статистика по районам за все время<span class="export_btn" id="ExportToWord_dstr" title="Экспорт в Word">b</span></h1>

            <div class="WrapTable">
                <table class="statistic_table">
                    <thead>
                        <tr>
                            <th >Район</th>
                            <th >Количество записей</th>
                        </tr>
                    </thead>
                    <tbody id="SearchResultContent_districts">
                        <?php foreach ($this->view->district_table as $stat) { ?>
                        <tr><td><?php echo $stat->District_str; ?></td><td><?php echo $stat->Count; ?></td></tr>
                        <? } ?>
                    </tbody>
                </table>
            </div>    
            
            <h1 class="h1" style="margin-bottom: 10px">Статистика по стоп-словам за все время<span class="export_btn" id="ExportToWord_sw" title="Экспорт в Word">b</span></h1>
            <div class="WrapTable">
                <table class="statistic_table">
                    <thead>
                        <tr>
                            <th >Район</th>
                            <th >Количество записей</th>
                        </tr>
                    </thead>
                    <tbody id="SearchResultContent_sw">
                        <?php foreach ($this->view->stop_words_table as $stat) { ?>
                        <tr><td><?php echo $stat->Stop_words; ?></td><td><?php echo $stat->Count; ?></td></tr>
                        <? } ?>
                    </tbody>
                </table>
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
