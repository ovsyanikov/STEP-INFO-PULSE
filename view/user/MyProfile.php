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
                        <a href="index?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="index?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="index?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                        <a href="index?ctrl=news&act=Statistic"><li class="menu-li">СТАТИСТИКА</li></a>                        
                    </ul>                    
                </div>
                <div class="div-menu">
                  
                    <ul class="menu">
                        <a href="index?ctrl=news&act=news"><li class="menu-li">ГЛАВНАЯ</li></a>
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

            <h1 class="h1">Лента новостей</br>Выводятся новости 1ой рубрики, кратко</h1>
            <div class="side-post">
                <h2 class="h2">Section 1.10.32 of "de Finibus Bonorum et Malorum" <span class="span-time">14:32</span></h2>
            </div>
            <div class="side-post">
                <h2 class="h2">Section 1.10.32 of "de Finibus Bonorum et Malorum" <span class="span-time">14:32</span></h2>
            </div>
            <div class="side-post">
                <h2 class="h2">Section 1.10.32 of "de Finibus Bonorum et Malorum" <span class="span-time">14:32</span></h2>
            </div>
            <div class="side-post last">
                <h2 class="h2">Section 1.10.32 of "de Finibus Bonorum et Malorum" <span class="span-time">14:32</span></h2>
            </div>

        </aside>
        </div>
        <section class=" news-section">
            <div class="personal-block">
                <h1 class="pers-title-h1 h1">Личный кабинет</h1>
                <div><h2 class="pers-h2 h2">Логин</h2><p class="pers-text" id="login"><?php echo"{$this->view->current_user->getLogin()}" ;?></p></div>
                <div id="emailSection">
                    <h2 class="pers-h2 h2">E-mail</h2><p class="pers-text" id="email"><?php echo"{$this->view->current_user->getEmail()}"; ?><span class="correct_js correct" title="Изменить">M</span></p>
                    <div class="pers-input-block">
                        <form id="ChangeEmail">
                            <input id="NewMailInPersonal" name="NewMailInPersonal" type="text" class="pers-input" placeholder="Введите новый e-mail">
                            <span id="ConfirmEmail" class="ok" title="Подтвердить изменения">N</span>
                        </form>
                    </div>
                </div>
                <div id="PasswordSection" ><h2 class="pers-h2 h2">Пароль</h2><p class="pers-text" id="password">******<span class="correct_js correct" title="Изменить">M</span></p>
                    
                        <div class="pers-input-block password-chng">
                            <input id="CurrentPassword" type="password" class="pers-input" placeholder="Введите текущий пароль">
                            <input id="FirstPassword" type="password" class="pers-input" placeholder="Введите новый пароль">
                            <input  id="SecondPassword" type="password" class="pers-input" placeholder="Повторите новый пароль">
                            <span id="ConfirmPassword" class="ok" title="Подтвердить изменения">N</span>
                        </div>

                
                </div>

                <div id="FirstNameSection"><h2 class="pers-h2 h2">Имя</h2><p class="pers-text" id="FirstName"><?php echo"{$this->view->current_user->getFirstName()}"; ?><span class="correct_js correct" title="Изменить">M</span></p>
                    <div class="pers-input-block">
                        <input id="NewFirstName" type="text" class="pers-input" placeholder="Введите новое имя">
                        <span id="ConfirmName" class="ok" title="Подтвердить изменения">N</span>
                    </div>

                </div>
                
                <div id="LastNameSection"><h2 class="pers-h2 h2">Фамилия</h2><p class="pers-text" id="LastName"><?php echo"{$this->view->current_user->getLastName()}";?><span class="correct_js correct" title="Изменить">M</span></p>
                                    <div class="pers-input-block">
                        <input id="NewLastName" type="text" class="pers-input" placeholder="Введите новую фамилию">
                        <span id="ConfirmLastName" class="ok" title="Подтвердить изменения">N</span>
                    </div>

                </div>

                
            </div>
        </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-plus 2015</h2>
    </footer>
    <div id="toTop" class="hidden">E</div>
</body>
</html>
