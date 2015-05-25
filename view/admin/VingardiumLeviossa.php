<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/vingardium.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
        <script type="text/javascript" src="js/vingardium.js"></script>
        <link rel="shortcut icon" href="img/info-puls1.png">
        <title>Info-Pulse</title>
    </head>
    <body i="body" class="news-bg"> 
        
    <heder>
        <div class="top-head">
            <div class="content">
                <a href="?ctrl=news&act=news"><div class="logo">
                    <img src="img/info-puls1.png" alt="">
                    <h1 class="logo-h1">PULSE</h1>
                </div></a>
                <div class="div-menu">
                    <ul class="menu">
                        <a href="?ctrl=news&act=news"><li class=" menu-li">ГЛАВНАЯ</li></a>
                        <a href="?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                        <a href="?ctrl=news&act=MyTasks"><li class="menu-li">ЗАДАЧИ</li></a>
                        <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                        <a href="?ctrl=news&act=MyPosts"><li class="active menu-li">МОИ ЗАПИСИ</li></a>
                    </ul>

                    <div class="search">
                        <input id="search" type="search" class="isearch" placeholder="Поиск">
                        <span class="search-icon">A</span>
                    </div>

                </div>

            </div>
        </div>
        <div class="bottom-head">
            <div class="content">
                <ul class="menu">
                    <a href="?ctrl=news&act=news"><li class="active menu-li">ГЛАВНАЯ</li></a>
                    <a href="?ctrl=news&act=Districts"><li class="menu-li">РАЙОНЫ</li></a>
                    <a href=""><li class="menu-li">ЗАДАЧИ</li></a>
                    <a href="index?ctrl=news&act=PersonOfInterest"><li class="menu-li">УЧАСНИКИ</li></a>
                    <a href="?ctrl=news&act=MyPosts"><li class="menu-li">МОИ ЗАПИСИ</li></a>
                </ul>


            </div>
        </div>
    </heder>



    <div class="content">
        <section class=" news-section"  style="width: 95%; margin: 1 auto">
            
            <div class="RemoveNewsSection">
                <h2>Удаление новости</h2>
                <div>Введите id новости для её удаления</div>
                <input type="text" id="NewsId" placeholder="id новости"><br>
                <input type="button" id="FindGlobalNews" value="Найти"><br>
            </div>
            
            <div class="SpecialNews">
                <h2>Запрашиваемая новость:</h2>
                <div id="NewsTitle">Заголовок</div>
                <div id="NewsDescription">Описание</div>
            </div>
            
        </section>
    </div>
    <footer class="footer">
        <h2 class="foot copyright">© Info-pulse 2015</h2>
    </footer>
    <div id="toTop" class="hidden">E</div>
</body>
</html>
