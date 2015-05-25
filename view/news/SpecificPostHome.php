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
            
            <div id="newsContent">
                <?php if(isset($_GET['PersonId'])){ 
                    echo '<div class="specific-post post" data-person-id="'. $this->view->global_news->id .'">';
                } else{
                    echo '<div class="specific-post post" data-news-id="'. $this->view->global_news->id .'">';
                } ?>
                
                    <?php 
                        $colors = $this->view->global_news->getColors();
                        $defaul_tag = $this->view->global_news->Tag;
                        $post_distr = $this->view->global_news->getDistrict_str();
                        $post_distr = trim($post_distr);
                        $post_sw = trim($this->view->global_news->getStop_words());
                        $date = $this->view->global_news->getDate();
                        $ch_social = $this->view->global_news->getSearchType();
                        $tit = $this->view->global_news->getTitle();
                        $tit = preg_replace("/[^а-яa-z\\\\.,;\\/!@#$%^&*()_+-=\\\'\\\"«»]/ius",' ',$tit);
                        $tit = str_replace("\\n", " ", $tit);

                        $source = $this->view->global_news->getSource(); 
                        $source = str_replace("'", "", $source);
                        $source = str_replace("%20", "", $source);   
                        
                        
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
                        
                    <span class="export_btn" id="ExportToPDF" title="Экспорт в PDF">b</span>

                    <?php
                 
                    
                    
                    
                        echo "<span class=\"post-date2\" title=\"Время публикации\">$date</span>";
                        echo "<h2 class=\"sp_h2 post-h2 h2\">$tit</h2><br />";
                        echo "<p  class=\"post_bottom\" >Район: $post_distr, cтоп-слово: $post_sw <br/><span style=\"font-size:10pt; \">Зеленым/желтым цветом выделены районы/стоп-слова, вошедшие в текущую запись</span></p>";
                        echo "<div style=\"margin-bottom:10px;\"></div>";
                        $img = $this->view->global_news->getImage();
                        
                        if ($img){
                            if(strstr($img,'http')){
                                echo "<div class=\"top-3\"><img id=\"post_image\" src=\"{$img}\" alt=\"\"></div>";                        
                            }
                            else{
                                $img_array = explode(',', $img);
                                $count = count($img_array);
                                unset($img_array[$count-1]);
                                
                                foreach ($img_array as $spec_image) {
                                    echo "<img class=\"specificIMG\" id=\"post_image\" src=\"files/{$spec_image}\" alt=\"\">";        
                                }
                            }
                        }

                        
                        $descr = $this->view->global_news->getDescription();
                        
                        $descr = str_replace("\n", "<br/> ", $descr);
                        $descr = str_replace("\\n", "<br/> ", $descr);
                        
                        //$descr = stripslashes($descr);
                       

                        
                        
                        //выделение района 
                            $words = strtok($descr,' ,.!;)({}@\'\":^$<>');
                            $chapters = explode(' ', $post_distr);
                            $chapters_count = count($chapters);
                            $text_arr = [];

                            
                            //разделение текста на массив слов
                            while($words !== false){

                                $text_arr[] = $words;
                                $words = strtok(' ,.!;)({}@\'\":^$<>');

                            }//while        


                            $final_distr = false;
                            $proc;

                            for($i=0; $i<count($text_arr); $i++){

                                $txt_lower = mb_strtolower($text_arr[$i], "Utf-8");
                                $word_lower = mb_strtolower($chapters[0], "Utf-8");

                                $lev = similar_text($txt_lower, $word_lower,$proc);
                                $for_select = '';
                                
                                if ($proc >= 60 ){//нашли первое соответствие

                                    //начало проверки по частям
                                    if (iconv_strlen($text_arr[$i], 'Utf-8')<=5){
                                        $text_len = 3;
                                    }else{
                                        $text_len = ceil(iconv_strlen($text_arr[$i], 'Utf-8')/2);
                                    }

                                    if (iconv_strlen($chapters[0], 'Utf-8')<=5){
                                        $word_len = 3;
                                    }else{
                                        $word_len = ceil(iconv_strlen($chapters[0], 'Utf-8')/2);
                                    }                


                                    $first_part_text = iconv_substr($text_arr[$i], 0, $text_len+1, 'Utf-8');
                                    $first_part_word = iconv_substr($chapters[0], 0, $word_len+1, 'Utf-8');

                                    $first_part_text = mb_strtolower($first_part_text,'Utf-8');
                                    $first_part_word = mb_strtolower($first_part_word,'Utf-8');          

                                    //проверка на соответствие 2ух частей
                                    if ($first_part_text == $first_part_word){
                                        //равны, проверка на количество чаптеров
                                        if($chapters_count == 1){
                                            
                                            //стоп слово одно, нашли, конец
                                            //$descr = str_ireplace("$text_arr[$i]", "$text_arr[$i] ($chapters[0])", $descr);
                                            $descr = str_ireplace("$text_arr[$i]", "<span class=\"bold-distr\" title=\"Район\" >$text_arr[$i]</span>", $descr);
                                            //$descr = str_ireplace("$text_arr[$i]", "<span class=\"bold-distr\" title=\"Район\">$text_arr[$i]</span>", $descr);
                                        }else{
                                                
                                            //стоп слово НЕ одно, нашли первое, ищем дальше
                                            //$descr = str_ireplace("$text_arr[$i]", "<span class=\"bold-distr\" title=\"Район\" >$text_arr[$i]</span>", $descr);
                                            for ($j=0; $j<$chapters_count; $j++){//идем дальше по чаптерам

                                                
                                                $ji = $j + $i ;
                                                $lev2 = similar_text($text_arr[$ji], $chapters[$j],$proc);

                                                if ($proc >= 60){//если следующий чаптер не левенштейн выход
                                                    //$descr = str_ireplace("$text_arr[$ji]", "<span class=\"bold-distr\" title=\"Район\">$text_arr[$ji]</span>", $descr);
                                                    if (iconv_strlen($text_arr[$ji], 'Utf-8')<=5){
                                                        $text_len = 3;
                                                    }else{
                                                        $text_len = ceil(iconv_strlen($text_arr[$ji], 'Utf-8')/2);
                                                    }

                                                    if (iconv_strlen($chapters[$j], 'Utf-8')<=5){
                                                        $word_len = 3;
                                                    }else{
                                                        $word_len = ceil(iconv_strlen($chapters[$j], 'Utf-8')/2);
                                                    }       




                                                    //начало проверки по частям
                                                    $first_part_text = iconv_substr($text_arr[$ji], 0, $text_len+1, 'Utf-8');
                                                    $first_part_word = iconv_substr($chapters[$j], 0, $word_len+1, 'Utf-8');

                                                    $first_part_text = mb_strtolower($first_part_text,'Utf-8');
                                                    $first_part_word = mb_strtolower($first_part_word,'Utf-8');

                                                    //проверка на соответствие 2ух частей
                                                    if ($first_part_text == $first_part_word){
                                                        $for_select = $for_select.' '.$text_arr[$ji];
                                                        //$descr = str_ireplace("$text_arr[$ji]", "<span class=\"bold-distr\" title=\"Район\">$text_arr[$ji]</span>", $descr);
                                                        $final_distr = true;
                                                    }else{
                                                        
                                                        //половины следующего не равны
                                                        $final_distr = false;
                                                        break;
                                                        
                                                    }
                                                }
                                                else{
                                                    //процент следующего не равны
                                                    $final_distr = false;
                                                }

                                            }//for j
                                            
                                        }


                                    }else{
                                        //не соответствуют половины
                                        $final_distr = false;
                                    }




                                }//if for i проверка на первое соответствие процент
                                
                                if($final_distr){
                                    
                                    //echo "к выделению $for_select<br/>";
                                    $for_select = trim($for_select);
                                    $descr = str_ireplace("$for_select", " <span class=\"bold-distr\" title=\"Район\"> $for_select </span> ", $descr);
                                    
                                }

                            }//for i                         
                        
                        

                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
       
                        //модное выделение стоп-слов 

                        //$descr=iconv("KOI8-U","utf-8",$descr);
//                        $words = strtok($descr,' ,.!;)({}@\'\"/:^$<>');
//                        $text_arr = [];
//
//                        while($words !== false){
//
//                            $text_arr[] = $words;
//                            $words = strtok(' ,.!;)({}@\'\"/:^$<>');
//
//                        }//while                          
                        
                        $sw_title = '';
                        foreach ($this->view->stop_words as $sw){
                            $strikt = false;
                            $stop_word = $sw -> getWord();
                            $stop_word = trim($stop_word);
                            if($stop_word[0]=='!'){
                               $strikt = true;
                               $stop_word = iconv_substr($stop_word, 1);
                            }
                            

                            $words = strtok($descr,' ,.!;)({}@\'\":^/$<>');
                            
                            $chapters = explode(' ', $stop_word);
                            $chapters_count = count($chapters);
                            $text_arr = [];

                            //разделение текста на массив слов
                            while($words !== false){

                                $text_arr[] = $words;
                                $words = strtok(' ,.!;)({}@\'\":^/$<>');
                                //echo "разбиение $words<br/>";
                            }//while        
                            

                            $final_distr = false;
                            $proc;
                            
                            if($strikt){
                                
                                for($i=0; $i<count($text_arr); $i++){
                                    $for_select_more = '';
                                    $final_distr = false;
                                    $txt_lower = mb_strtolower($text_arr[$i], "Utf-8");
                                    $word_lower = mb_strtolower($chapters[0], "Utf-8");
                                    //echo "$chapters[0]<br/>";
                                    
                                    if($txt_lower==$word_lower){
                                        if($chapters_count == 1){
                                            $sw_title = $sw_title.' '.$chapters[0].';';
                                            $descr = str_ireplace("$text_arr[$i]", "<span class=\"bold\" title=\"Стоп-слово\">$text_arr[$i]</span>", $descr);
                                            break;
                                        }else{
                                            $for_select_more = $for_select_more.' '.$text_arr[$i];
                                            for ($j=1; $j<$chapters_count; $j++){//идем дальше по чаптерам
                                                $ji = $j+$i;
                                                $txt_lower = mb_strtolower($text_arr[$ji], "Utf-8");
                                                $word_lower = mb_strtolower($chapters[$j], "Utf-8");
                                                if($txt_lower==$word_lower){
                                                    $final_distr=true;
                                                    $for_select_more = $for_select_more.' '.$text_arr[$ji];
                                                }  else {
                                                    $final_distr=false;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    if($final_distr){
                                        $descr = str_ireplace("$for_select_more", "<span class=\"bold\" title=\"Стоп-слово\">$for_select_more</span>", $descr);
                                    }
                                    
                                }
                                //return false;
                            }
                            else{
                                for($i=0; $i<count($text_arr); $i++){

                                $txt_lower = mb_strtolower($text_arr[$i], "Utf-8");
                                $word_lower = mb_strtolower($chapters[0], "Utf-8");

                                $lev = similar_text($txt_lower, $word_lower,$proc);

                                if ($proc >= 60 ){//нашли первое соответствие
                                    
                                    //начало проверки по частям
                                    if (iconv_strlen($text_arr[$i], 'Utf-8')<=5){
                                        $text_len = 3;
                                    }else{
                                        $text_len = ceil(iconv_strlen($text_arr[$i], 'Utf-8')/2);
                                    }

                                    if (iconv_strlen($chapters[0], 'Utf-8')<=5){
                                        $word_len = 3;
                                    }else{
                                        //$word_real_len = iconv_strlen($chapters[0], 'Utf-8');
                                        $word_len = ceil(iconv_strlen($chapters[0], 'Utf-8')/2);
                                    }                

                                    //echo "<br/>до обрезки $text_arr[$i] и $chapters[0]<br/>";

                                    $first_part_text = iconv_substr($text_arr[$i], 0, $text_len+1, 'Utf-8');
                                    $first_part_word = iconv_substr($chapters[0], 0, $word_len+1, 'Utf-8');

                                    
                                    //echo "после обрезки $text_arr[$i] и $chapters[0]<br/>";

                                    $first_part_text = mb_strtolower($first_part_text,'Utf-8');
                                    $first_part_word = mb_strtolower($first_part_word,'Utf-8');          

                                    //проверка на соответствие 2ух частей
                                    if ($first_part_text == $first_part_word){
                                        //равны, проверка на количество чаптеров
                                        
                                        if($chapters_count == 1){    
                                            //стоп слово одно, нашли, конец
                                            //echo "<br/>к выделению $text_arr[$i] стоп слово $chapters[0]<br/>";
                                            $sw_title = $sw_title.' '.$chapters[0].';';
                                            $descr = str_ireplace("$text_arr[$i]", "<span class=\"bold\" title=\"Стоп-слово\">$text_arr[$i]</span>", $descr);
                                            break;
                                            //$descr = str_ireplace("$text_arr[$i]", "<span class=\"bold-distr\" title=\"Район\">$text_arr[$i]</span>", $descr);
                                        }else{
                                            //echo "сл не додно $text_arr[$i] $chapters_count";
                                            //стоп слово НЕ одно, нашли первое, ищем дальше
                                            
                                            for ($j=1; $j<$chapters_count; $j++){//идем дальше по чаптерам
                                                $ji = $j + $i;
                                                $first_word = $text_arr[$i];
                                                //echo "есть второе совпадение $text_arr[$ji] и $chapters[$j]<br/>";
                                                if(iconv_strlen($chapters[$j], 'Utf-8')<3){
                                                    $final_distr = false;
                                                    break;
                                                }
                                                
                                                $lev2 = similar_text($text_arr[$ji], $chapters[$j],$proc);
                                                
                                                if ($proc >= 60){//если следующий чаптер не левенштейн выход
                                                    
                                                    if (iconv_strlen($text_arr[$ji], 'Utf-8')<=5){
                                                        $text_len = 3;
                                                    }else{
                                                        $text_len = ceil(iconv_strlen($text_arr[$ji], 'Utf-8')/2);
                                                    }

                                                    if (iconv_strlen($chapters[$j], 'Utf-8')<=5){
                                                        $word_len = 3;
                                                    }else{
                                                        $word_len = ceil(iconv_strlen($chapters[$j], 'Utf-8')/2);
                                                    }       




                                                    //начало проверки по частям
//                                                    $first_part_text = iconv_substr($text_arr[$ji], 0, $text_len, 'Utf-8');
//                                                    $first_part_word = iconv_substr($chapters[$j], 0, $word_len, 'Utf-8');
                                                    

                                                    $first_part_text = iconv_substr($text_arr[$ji], 0, $text_len+1, 'Utf-8');
                                                    $first_part_word = iconv_substr($chapters[$j], 0, $word_len+1, 'Utf-8');

                                                    
                                                    
                                                    

                                                    $first_part_text = mb_strtolower($first_part_text,'Utf-8');
                                                    $first_part_word = mb_strtolower($first_part_word,'Utf-8');

                                                    //проверка на соответствие 2ух частей
                                                    if ($first_part_text == $first_part_word){
                                                        //echo "к выделению если больше одного $text_arr[$ji] стоп слово $chapters[$j]<br/>"; $first_word
                                                        $sw_title = $first_word.' '.$sw_title.' '.$chapters[$j].';';
                                                        $descr = str_ireplace("$first_word", "<span class=\"bold\" title=\"Стоп-слово\">$first_word</span>", $descr); 
                                                        $descr = str_ireplace("$text_arr[$ji]", "<span class=\"bold\" title=\"Стоп-слово\">$text_arr[$ji]</span>", $descr);                                                      
                                                        $final_distr = true;
                                                    }else{
                                                        //половины следующего не равны
                                                        $final_distr = false;
                                                    }
                                                }
                                                else{
                                                    //процент следующего не равны
                                                    $final_distr = false;                                
                                                }

                                            }//for j
                                            if($final_distr){
                                                break;
                                            }
                                        }


                                    }else{
                                        //не соответствуют половины
                                        $final_distr = false;
                                    }




                                }//if for i проверка на первое соответствие процент



                            }//for i  
                            }

                        }//foreach по стоп словам    
                        
                        
                        
                                                                  //выделение плохих слов    
                        foreach ($this->view->bw as $bw){
                            $bw_str = $bw->getWord();

                            //$descr = str_ireplace(" $bw_str", " <span class=\"bold\" title=\"Стоп-слово\" style=\"background:red\">$bw_str</span> ", $descr);
                            $descr = preg_replace("/( $bw_str |$bw_str.| $bw_str,| $bw_str;|-$bw_str |$bw_str-)/i", " <span class=\"bold\" title=\"Стоп-слово\" style=\"background:red\">$bw_str</span> ", $descr);
                            
                        }  

                        $descr = stripslashes($descr);
                        echo "<p class=\"post-text\">$descr</p>"; 
                        echo "<p  class=\"post_bottom\" style=\"width:100%\">Все cтоп-слова: $sw_title</p>";
                        
                        
                        
                        
                    ?>
                </div>
            
                <?php 

                    //echo "<a href=\"{$source}\" title=\"Ссылка на первоисточник\" target=\"_blank\">Ссылка на первоисточник</a>";
                ?>
                
            </div>
            
            
            <div id="SendMessage" class="comments">
<!--                    <h2 class="sp_h2 post-h2 h2">Комментарии</h2>-->
                <textarea id="CommentText" placeholder="Введите текст комментария"></textarea>
                <input id="Comment" type="button" value="Оставить комментария" class="comments_btn submit">
            </div>
            
            
            <?php if(count($this->view->post_comments) == 0){ ?>
<!--                <h2 id="com_empty" class="sp_h2 post-h2 h2">Комментарии текущей записи отсутствуют</h2>-->
            <?php } ?>
            <div class="comment_p" id="Comments">    
                <?php
                foreach($this->view->post_comments as $comment){?>
                    <p data-comment-id="<?php echo $comment->id;?>"><?php echo $comment->Login . ": $comment->Comment"; ?><span class="post-date2" title="Время публикации"><?php echo $comment->Date; ?></span></p>
                <?php } ?>
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
