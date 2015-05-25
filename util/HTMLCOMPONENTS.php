<?php

namespace util;

class HTMLCOMPONENTS{
    
    public function GetLeftBar($user){
        
        echo "

         <aside class=\"sidebar\">
            
            
                <div class=\"personal\">
                    <a href=\"?ctrl=user&act=MyProfile\">Личный кабинет(".$user->getLogin().")</a> / 
                    <a href=\"?ctrl=user&act=leave\">Выйти</a>
                </div>
            
            
            
            
            <h1 class=\"h1\">Лента новостей</br>Выводятся новости 1ой рубрики, кратко</h1>
            <div class=\"side-post\">
                <h2 class=\"h2\">Section 1.10.32 of \"de Finibus Bonorum et Malorum\" <span class=\"span-time\">14:32</span></h2>
            </div>
            <div class=\"side-post\">
                <h2 class=\"h2\">Section 1.10.32 of \"de Finibus Bonorum et Malorum\" <span class=\"span-time\">14:32</span></h2>
            </div>
            <div class=\"side-post\">
                <h2 class=\"h2\">Section 1.10.32 of \"de Finibus Bonorum et Malorum\" <span class=\"span-time\">14:32</span></h2>
            </div>
            <div class=\"side-post last\">
                <h2 class=\"h2\">Section 1.10.32 of \"de Finibus Bonorum et Malorum\" <span class=\"span-time\">14:32</span></h2>
            </div>

        </aside>

        ";
        

    }
    
    public function GetHeader($location){
        echo "<heder>
        <div class=\"top-head\">
            <div class=\"content\">
                <a href=\"?ctrl=news&act=news\"><div class=\"logo\">
                    <img src=\"img/info-puls1.png\" alt=\"\">
                    <h1 class=\"logo-h1\">PULSE</h1>
                </div></a>
                <div class=\"div-menu\">
                    <ul class=\"menu\">
                        <a href=\"?ctrl=news&act=news\"><li class=\"active menu-li\">ГЛАВНАЯ</li></a>
                        <a href=\"\"><li class=\"menu-li\">РАЙОНЫ</li></a>
                        <a href=\"\"><li class=\"menu-li\">ЗАДАЧИ</li></a>
                        <a href=\"\"><li class=\"menu-li\">УЧАСНИКИ</li></a>
                        <a href=\"?ctrl=news&act=MyPosts\"><li class=\"menu-li\">МОИ ЗАПИСИ</li></a>
                    </ul>

                    <div class=\"search\">
                        <input id=\"search\" type=\"search\" class=\"isearch\" placeholder=\"Поиск\">
                        <span class=\"search-icon\">A</span>
                    </div>
                <img src=\"img/loader2.gif\" alt=\"\" id=\"loader\" class=\"show loader\">

                </div>

            </div>
        </div>
        <div class=\"bottom-head\">
            <div class=\"content\">
                <ul class=\"menu\">
                    <a href=\"?ctrl=news&act=news\"><li class=\"active menu-li\">ГЛАВНАЯ</li></a>
                    <a href=\"\"><li class=\"menu-li\">РАЙОНЫ</li></a>
                    <a href=\"\"><li class=\"menu-li\">ЗАДАЧИ</li></a>
                    <a href=\"\"><li class=\"menu-li\">УЧАСНИКИ</li></a>
                    <a href=\"?ctrl=news&act=MyPosts\"><li class=\"menu-li\">МОИ ЗАПИСИ</li></a>
                </ul>


            </div>
        </div>
    </heder>";
        
        
    }
    
}
