<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/nanoscroller.css">
        <link rel="stylesheet" href="css/jqtree.css">
        <link rel="shortcut icon" href="img/info-puls1.png">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script> 
        <script src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/script.js"></script> 
        <script type="text/javascript" src="js/jquery.nanoscroller.min.js"></script> 
        
        <script src="js/tree.jquery.js"></script>
        <script src="js/tree.js"></script>
        
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
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
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
                        
        <h1 class="h1">Всего найдено новостей - <span id="count"><?php echo $this->view->all_count ?></span></h1>

        <div class="side-post">
            <h2 class="h2"><a id="get_all_vk_news" href="#">Список новостей по Вконтакте</a></h2>
        </div>

        <div class="side-post">
             <h2 class="h2"><a id="get_all_tw_news" href="#">Список новостей по Twitter</a></h2>
        </div>
               
        <div class="side-post">
            <h2 class="h2"><a id="get_all_google_news_news" href="#">Список новостей по Google news</a></h2>
        </div>
                
        <div class="side-post">
            <h2 class="h2"><a id="get_all_google_web_news" href="#">Список новостей по Google web</a></h2>
        </div>
        
         <div class="side-post">
            <h2 class="h2"><a id="get_all_fb_news" href="#">Список новостей по Facebook</a></h2>
        </div>
        
        <div class="side-post">
            <h2 class="h2"><a id="get_all_ya_news" href="#">Список новостей по Livejournal</a></h2>
        </div>
        
        </aside>
    </div>
    <section class=" news-section">
         
        <div class="srch">
            <h1 class="h1">Панель поиска <span id="minimize" title="Скрыть панель">─</span></h1>
            <div id="search-panel" class="post">          

                    <a href="?ctrl=news&act=Setting"><input class="distr-button submit mr" id="" value="Редактировать" type="button"></a>
                    <input class="distr-button submit " id="search_news_by_stop_words" value="Найти" type="button">

                <form id="start_search_news" method="POST" action="?ctrl=news&act=getNewsByStopWords">
                    <div class="distr_tree">
                        <h2 class="h2-distr">Районы</h2>
                        <div  class="nano has-scrollbar ">
                            <div class="nano-content">
                                <div id="tree1">

                                </div>
                            </div>
                        </div>
                    </div>                     


                    <div id="stopWords" class="right selectDistrict">
                        <input style="display: none" id="District" name="District" />
                        <h2 id ="STOP_WORD" class="h2-distr">Стоп-слова</h2>
                        <div  class="nano has-scrollbar ">
                            <div class="nano-content">
                        <ul class="district">
                            <?php   
                                $cnt = round(count($this->view->stop_words)/2);
                                $i = 0;                            
                                foreach ($this->view->stop_words as $stop_word){
                                    echo "<li data-stop-id = \"{$stop_word->getId()}\">{$stop_word->getWord()}</li>";
                                    $i++;
                                    if($i == $cnt){
                                        break;
                                    }
                                }//foreach
                            ?>               
                        </ul>
                        <ul class="district">
                            <?php
                                $i=0;
                                foreach ($this->view->stop_words as $stop_word){
                                    if($i>=$cnt){
                                        echo "<li data-stop-id = \"{$stop_word->getId()}\">{$stop_word->getWord()}</li>";
                                    }
                                    $i++;
                                }//foreach
                            ?>               
                        </ul>
                                </div>  
                        </div>        
                    </div>
                    
                </form>       
                    <input class="distr-button submit " id="SaveMyTreeDistricts" value="Сохранить изменения в дереве" type="button">
                    <input class="distr-button submit " id="ClearTree" style="margin-left: 20px" value="Сбросить иерархию дерева" type="button">
                    <div id="tree_succ" class="srch_success pers-success"><h2 class="h2">Изменения успешно сохранены</h2></div> 
                    <div id="tree_arch" class="srch_success pers-success"><h2 class="h2">Иерархия дерева - сброшена</h2></div>                   

            </div>
            
            <div id="mytree">
                
            </div>
            
            
        </div>
        <div id="loader_dis" class="loader"><img src="../img/loader3.GIF" alt=""></div>
        <div id="ForMsg"></div>
        <div id="newsContent">
            
        </div>
            <input class="My-posts-button submit" style="display: none" id="more_news_by_stop_words" value="Следующие новости" type="button">
    </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-pulse 2015</h2>
    </footer>
    <div class="addeed_new_news" class="hidden" title="Добавлены новые записи, нажмите для просмотра">!</div>
    <div id="toTop" class="hidden">E</div>
</body>

</html>
