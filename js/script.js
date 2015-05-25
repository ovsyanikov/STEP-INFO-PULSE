if (!window.jQuery) {
    msg = 'Не загружен JQUERY';
    alert(msg);
}

function ShowAuthorizeMessage(message){
    
    var inp_class = $('#error_lp').attr("class");
    
    if(inp_class != 'error_block'){
        
        $('#authentication').animate({height: $('#authentication').height()+20},500);
        $('#error_lp').removeClass('invisible');
        $('#error_lp').addClass('error_block');

        $('#error_lp').text(message);
        
    }//if
    else{
        
        $('#error_lp').text(message);
        
    }
    
    
}//ShowAuthorizeMessage

function ShowRegisterMessage(message){
    
    var inp_class = $('#error').attr("class");
    
    if(inp_class != 'error_block'){
        
        $('#registration').animate({height: $('#registration').height()+20},500);
        $('#error').removeClass('invisible');
        $('#error').addClass('error_block');

        $('#error').text(message);
        
    }//if
    else{
        
        $('#error').text(message);
        
    }
    
    
}//ShowAuthorizeMessage

function ShowPostMessage(message){
    
    var inp_class = $('#error').attr("class");
    
    if(inp_class != 'error_block2'){
        
        $('section').animate({height: $('section').height()+20},500);
        $('#error').removeClass('invisible');
        $('#error').addClass('error_block2');
        $('#error').text(message);
        
    }//if
    else{
        
        $('#error').text(message);
    }
    
}

function ShowPersonalRoomMessage(controll,message,type){
   
    if(type == "success"){
        
        $("#" + $(controll).attr('id')).children(".pers-error").remove();
        
        if($("#" + $(controll).attr('id')).children().last().attr("class") == "pers-success"){
            $("#" + $(controll).attr('id')).children().last().html("<h2 class=\"h2\">"+message+"</h2>");;
        }
        
        else{
            $("#" + $(controll).attr('id')).append("<div class=\"pers-success\"><h2 class=\"h2\">"+message+"</h2></div>");
        }//else
        $(controll).children().last().fadeOut(0).fadeIn(300).delay(1000).fadeOut(500); 
    }//if
    else{
        
        $("#" + $(controll).attr('id')).children(".pers-success").remove();
        
        if($("#" + $(controll).attr('id')).children().last().attr("class") == "pers-error"){
            $("#" + $(controll).attr('id')).children().last().html("<h2 class=\"h2\">"+message+"</h2>");
        }//if
        else {
            $("#" + $(controll).attr('id')).append("<div class=\"pers-error\"><h2 class=\"h2\">"+message+"</h2></div>");
        }//else
        $(controll).children().last().fadeOut(0).fadeIn(300).delay(1000).fadeOut(500);
       
    }//if
}

$(function() {
    
        $(window).scroll(function() {
                
                if($(window).scrollTop() > 700) {
                        $('#toTop').removeClass("hidden");
                        $('#toTop').fadeIn(); 
                        if ($('#new_count').html()!=0){
                            $('#new_news_count').removeClass("hidden");
                            $('#new_news_count').fadeIn(200);
                        }
                        
                        //$('#news-section').fadeOut(100).css({'margin-left' : '-155px'},300).fadeIn(100);

                } else {
                        $('#toTop').fadeOut();
                        $('#new_news_count').fadeOut(0);
                    }
                });

                $('#toTop').click(function() {
                    $('body,html').animate({scrollTop:0},800);
                
        });
});

var a = true; 

function getSpecificNews(search_type){
    
    $.post("ajax.php",{GET_SPECIFIC_NEWS: 'SET', search_type: search_type},function(data){
        
            $("#newsContent").children().remove();
             
            if(data == "nothing"){
                $('#newsContent').append('<h2 class="post-h2 h2" style="margin: 15px 0px; max-width:100%;">Поиск не дал результатов</h2>');
            }//if
            else{
            
                $("*").blur();
                
                news = $.parseJSON(data);

                $.each(news, function(idx, glob_news) {

                d_id = glob_news.id;
                ch_social = new String(glob_news.Source);
                
                if(ch_social.indexOf('\'') != -1){
                    ch_social = ch_social.replace("'",'');
                }
                if(ch_social.indexOf('\'') != -1){
                    ch_social = ch_social.replace("'",'');
                }
                title =  new String(glob_news.title);
                description = new String(glob_news.description);
                image = glob_news.Images;
                date_public = glob_news.Date;
                
                distr_str = new String(glob_news.District_str);
                sw = new String(glob_news.Stop_words);

                if(title.length > 50){

                    title = title.substr(0,47);
                    title += "...";

                }//if

                if(description.length > 300){

                    description = description.substr(0,297);
                    description += "...";

                }//if
                description = description.replace(distr_str, " <span class=\"bold\">"+distr_str+"</span>");
                description = description.replace(sw, " <span class=\"bold\">"+sw+"</span>");
                description = description.replace(/\\n/g, " ");
                description = description.replace(/\\"/g, "\"");
                
                switch(search_type){
                    case 'v':
                        
                        if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//else
                        
                    break;
                        
                    case 't':
                        if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }
                            
                    break;
                    
                    case 'n':
                        
                        if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }
                    break;
                    
                    case 'g':
                        
                        if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }
                        break;
                        
                    case 'f':
                        if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }
                        break;
                    case 'y':
                        
                         if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }
                        
                        break;//yandex post-icon Я
                    case 'lj':
                        
                         if(image != null){
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }//if
                        else{
                            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                        }
                        
                        break;//yandex post-icon Я                        
                }
                
                
                
                
        });
                
            }//else
        $("#loader_dis").fadeOut(300);
    });
    
}
function AddNewsToNewsContent(data_news){
    
    news = $.parseJSON(data_news);
    
    $.each(news,function(idx,glob_news){
            d_id = glob_news.id;
            ch_social = new String(glob_news.Source);   
            title =  new String(glob_news.title);
            description = new String(glob_news.description);
            image = glob_news.Images;
            date_public = glob_news.Date;
            search_type = glob_news.SearchType;
            distr_str = new String(glob_news.District_str);
            sw = new String(glob_news.Stop_words);

            if(title.length > 50){

                title = title.substr(0,47);
                title += "...";

            }//if

        if(description.length > 300){

            description = description.substr(0,297);
            description += "...";

        }//if
        description = description.replace(distr_str, " <span class=\"bold\">"+distr_str+"</span>");
        description = description.replace(sw, " <span class=\"bold\">"+sw+"</span>");
        description = description.replace(/\\n/g, " ");
        description = description.replace(/\\"/g, "\"");
        if(image != null){
            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&PersonId="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
        }//if
        else{
            $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&PersonId="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
        }
//        switch(search_type){
//            case 'v':
//
//                if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//else
//
//            break;
//
//            case 't':
//                if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//
//            break;
//
//            case 'n':
//
//                if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//            break;
//
//            case 'g':
//
//                if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//                break;
//
//            case 'f':
//                if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//                break;
//            case 'y':
//
//                 if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//
//                break;//yandex post-icon Я
//            case 'i':
//
//                 if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//
//                break;//yandex post-icon Я
//            case 'lj':
//
//                 if(image != null){
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><img  class=\"post-img\" src=\""+image+"\" alt=\"\"/><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }//if
//                else{
//                    $("#newsContent").append("<div data-post_id="+d_id+" class=\"post\"><a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span><a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p><p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
//                }
//
//                break;//yandex post-icon Я                        
//        }

    });
    $("#loader_dis").fadeOut(300);
}//if


function GetNewsByDistrictOrStopWord(district, stop_word){
    


    district = new String(district);
    stop_word = new String(stop_word);
    if(district.length == 0){
        district = 'empty';
    }
    if(stop_word.length == 0 || stop_word == 'Стоп-слова'){
        stop_word = 'empty';
    }
    
    $("#ForMsg").css('display','block');
    
    $.post("get_news_by_stop_words.php",{District: district, STOP_W: stop_word},function(data){

        if(data != "end"){

            news = $.parseJSON(data);

            $.each(news,function(idx,glob_news){
                
                var fl=false;
                var last_part;
                d_id = glob_news.id;
                ch_social = new String(glob_news.Source);   
                title =  new String(glob_news.title);
                description = new String(glob_news.description);
                image = glob_news.Images;
                date_public = glob_news.Date;
                SearchType = glob_news.SearchType;
                distr_str = new String(glob_news.District_str);
                sw = new String(glob_news.Stop_words);

                if(title.length > 50){

                    title = title.substr(0,47);
                    title += "...";

                }//if

                if(description.length > 300){
                    
                    last_part = new String(description);
                    description = description.substr(0,297);
                    description += "...";
                    fl = true;

                }//if
                description = description.replace(distr_str, " <span class=\"bold\">"+distr_str+"</span>");
                description = description.replace(sw, " <span class=\"bold\">"+sw+"</span>");
                description = description.replace(/\\n/g, " ");
                description = description.replace(/\\"/g, "\"");

                $("#newsContent").append("<div data-post-id="+d_id+" class=\"post\">");

                    //цепляется иконка соц сети
                    if(SearchType == 'v'){
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a>");
                    }
                    else if(SearchType == 't'){
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a>");
                    }//if facebook
                    else if(SearchType == 'f'){
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a>");                 
                    }
                    else if(SearchType == 'i'){
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a>");
                    }
                    else if(SearchType == 'y'){
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a>");
                    }
                    else if(SearchType == 'lj'){
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a>");
                    }
                    else{
                        $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a>");                 
                    }

                //все остальное до картинки
                $("[data-post-id="+d_id+"]").append("<span class=\"hide_post post-icon\" title=\"Скрыть запись\">O</span><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span>");

                //картинка
                if(image != null){
                    $("[data-post-id="+d_id+"]").append("<img  class=\"post-img\" src=\""+image+"\" alt=\"\"/>");
                }

                //остаток после картинки
                $("[data-post-id="+d_id+"]").append("<a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p>");

                //показать все
                if(fl){
                    $("[data-post-id="+d_id+"]").append("<div class=\"show_all\">Показать все</div><p class=\"last_part\">"+last_part+"</p>");
                }

                //после показать все
                $("[data-post-id="+d_id+"]").append("<p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");


            });
            
            var dis_res = sessionStorage['selected_li'];
            dis_res = dis_res.replace(/;/g,'; ');
            $("#ForMsg").empty();
            if(stop_word != 'empty' && district != 'empty'){
                $("#ForMsg").append("<h2 class=\"distr_h2 post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по запросу район: \" "+dis_res+"\" стоп слово: \""+stop_word+"\"</h2><span class=\"export_btn_distr export_btn\" id=\"ExportToPDF_districts\" title=\"Экспорт в PDF\">b</span> ");
            }else if(stop_word == 'empty' && district != 'empty'){
                $("#ForMsg").append("<h2 class=\"distr_h2 post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по запросу район: \" "+dis_res+"\" </h2><span class=\"export_btn_distr export_btn\" id=\"ExportToPDF_districts\" title=\"Экспорт в PDF\">b</span>");                
            }else if(stop_word != 'empty' && district == 'empty'){
                $("#ForMsg").append("<h2 class=\"distr_h2 post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по запросу стоп-слово: \""+stop_word+"\"</h2><span class=\"export_btn_distr export_btn\" id=\"ExportToPDF_districts\" title=\"Экспорт в PDF\">b</span>");                
            }else{
                $("#ForMsg").append("<h2 class=\"distr_h2 post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по всем записям (район и стоп-слово не выбраны)</h2><span class=\"export_btn_distr export_btn\" id=\"ExportToPDF_districts\" title=\"Экспорт в PDF\">b</span>");                
            }
            
        }//if

        else{
            var dis_res = sessionStorage['selected_li'];
            dis_res = dis_res.replace(/;/g,'; ');           


            if($($("#ForMsg").text()).length == 0){
                if(stop_word != 'empty' && district != 'empty'){
                    $("#ForMsg").append("<h2 class=\"post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по запросу район: \" "+dis_res+"\" стоп слово: \""+stop_word+"\"  не дал результатов</h2>");
                }else if(stop_word == 'empty' && district != 'empty'){
                    $("#ForMsg").append("<h2 class=\"post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по запросу район: \" "+dis_res+"\"  не дал результатов</h2>");                
                }else if(stop_word != 'empty' && district == 'empty'){
                    $("#ForMsg").append("<h2 class=\"post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по запросу стоп-слово: \""+stop_word+"\"  не дал результатов</h2>");                
                }else{
                    $("#ForMsg").append("<h2 class=\"post-h2 h2\" style=\"margin: 15px 0px; max-width:100%;\">Поиск по всем записям (район и стоп-слово не выбраны)  не дал результатов</h2>");                
                }
            }
        }//else

        $("#loader_dis").fadeOut(100);

    });

}

start = " ";//Начальное слово перед @
isDog = false;//Был ли указан символ @
isSharp = false;//Был ли нажат #
FirstPosition = -1;//Начальная позиция последней @
count_symbols = 0;//Количество введенных символов пользователем после @

news_to_export = [];

function GetAllNewsCount(){
    
    $.post('ajax.php',{GetCountOfNews:'set'},function(data){
        
        if(sessionStorage['count_of_vk_news'] != undefined){
            var OldCount =  parseInt(sessionStorage['count_of_vk_news']);
            var NewCount = parseInt(data);
            
            if(NewCount > OldCount){
                
                sessionStorage['count_of_vk_news'] = data;
                
                $('.addeed_new_news').fadeIn(600);
                
            }
            
        }
        else{
            sessionStorage['count_of_vk_news']  = data;
        }
        
        
    });
     
}


function HideHiddenNews(){
    
    var user_id = $.cookie('current_user');
    
    
    $.post('ajax.php',{HIDE_ALL_HIDDEN : 'SET', 'user_id': user_id},function(data){
        if(data == 'true' || data == 'false'){ 
            window.location.reload();
        }
    });
    
}

function ShowHiddenNews(){
    
    var user_id = $.cookie('current_user');
    
    $.post('ajax.php',{SHOW_ALL_HIDDEN : 'SET', 'user_id': user_id},function(data){
        if(data == 'true' || data == 'false'){ 
            window.location.reload();
        }
    });
}

function GetNewsBetweenDates(left,right){
    
    $.post('ajax.php',{DETE_SEARCH: 'set', left: left, right: right},function(data){
        
        statistic_search = $.parseJSON(data);
        $('#SearchResultContent').children().remove();
        news_to_export = [];
        
        $.each(statistic_search,function(indx,news){
            //<td>'+news.Stop_words+'</td>
            $('#SearchResultContent').append('<tr><td style="text-align: center;">'+news.District_str+'</td><td style="text-align: center;">'+news.Count+'</td><td>'+news.Stop_words+'</td></tr>');
            
        });
        
    });
    
}

function anyCaseAsc(a, b) {
if (a.title.toLowerCase() > b.title.toLowerCase())
  return 1;
if (a.title.toLowerCase() < b.title.toLowerCase())
  return -1;
else
  return 0;
}

function anyCaseDesc(a, b) {
if (a.title.toLowerCase() < b.title.toLowerCase())
  return 1;
if (a.title.toLowerCase() > b.title.toLowerCase())
  return -1;
else
  return 0;
}

function anyDateDesc(a, b) {
    firstDate = Date.parse(a.date);
    secondDate = Date.parse(b.date);
    if (firstDate < secondDate)
      return 1;
    if (firstDate > secondDate)
      return -1;
    else
      return 0;
}

function anyDateAsc(a, b) {
    
    firstDate = Date.parse(a.date);
    secondDate = Date.parse(b.date);
    
    if (firstDate > secondDate)
      return 1;
    if (firstDate < secondDate)
      return -1;
    else
      return 0;
}

function SortDistrictsBtTitle(){
    
    SortType = $('#SortDistrictsByTitle').data('sorttype');

    if(SortType == 'asc'){//По убыванию
        $('#SortDistrictsByTitle').data('sorttype','desc');
        
        titles = new Array();
        
        $.each($('#districts_order div li[data-district-title]'),function(id,district){
            
            titles.push(
                    {
                        title: new String($(district).data('district-title')),
                        id   : new String($(district).data('district-id')),
                        date : new String($(district).data('districtdatetime'))
                    });
            
        });
        
        $('#districts_order').children().remove();
        
        titles.sort(anyCaseDesc);
        
        for(j = 0; j < titles.length; j++){
            
            $("#districts_order").append('<div><li data-districtdatetime="'+titles[j].date+'" data-district-title="'+titles[j].title+'" data-district-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
        
    }
    else{//По возростанию
        
        $('#SortDistrictsByTitle').data('sorttype','asc');
        titles = new Array();
        
        $.each($('#districts_order div li[data-district-title]'),function(id,district){
            
           titles.push(
                    {
                        title: new String($(district).data('district-title')),
                        id   : new String($(district).data('district-id')),
                        date : new String($(district).data('districtdatetime'))
                    });
            
        });
        
        titles.sort(anyCaseAsc);
        
        $('#districts_order').children().remove();
        
        for(j = 0; j < titles.length; j++){
            
            $("#districts_order").append('<div><li data-districtdatetime="'+titles[j].date+'" data-district-title="'+titles[j].title+'" data-district-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
    }
    
}

function SortDistrictsByDate(){
    
    SortType = $('#SortDistrictsByDate').data('sorttype');

    if(SortType == 'asc'){//По убыванию
        
        $('#SortDistrictsByDate').data('sorttype','desc');
        
        titles = new Array();
        
        $.each($('#districts_order div li[data-district-title]'),function(id,district){
            
           titles.push(
                    {
                        title: new String($(district).data('district-title')),
                        id   : new String($(district).data('district-id')),
                        date : new String($(district).data('districtdatetime'))
                    });
            
        });
        
        $('#districts_order').children().remove();
        
        titles.sort(anyDateDesc);
        
        for(j = 0; j < titles.length; j++){
            
            $("#districts_order").append('<div><li data-districtdatetime="'+titles[j].date+'" data-district-title="'+titles[j].title+'" data-district-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
        
    }
    else{//По возростанию
        
        $('#SortDistrictsByDate').data('sorttype','asc');
        titles = new Array();
        
        $.each($('#districts_order div li[data-district-title]'),function(id,district){
            
            titles.push(
                    {
                        title: new String($(district).data('district-title')),
                        id   : new String($(district).data('district-id')),
                        date : new String($(district).data('districtdatetime'))
                    });
            
        });
        
        titles.sort(anyDateAsc);
        
        $('#districts_order').children().remove();
        
        for(j = 0; j < titles.length; j++){
            
            $("#districts_order").append('<div><li data-districtdatetime="'+titles[j].date+'" data-district-title="'+titles[j].title+'" data-district-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
    }
    
}

function SortStopWordsByTitle(){
    
    SortType = $('#SortStopWordsByTitle').data('sorttype');

    if(SortType == 'asc'){//По убыванию
        $('#SortStopWordsByTitle').data('sorttype','desc');
        
        titles = new Array();
        
        $.each($('#StopWordsOrder div li[data-stopwordtitle]'),function(id,district){
            
            titles.push(
                    {
                        title: new String($(district).data('stopwordtitle')),
                        id   : new String($(district).data('stop-id')),
                        date : new String($(district).data('stopworddatetime'))
                    });
            
        });
        
        $('#StopWordsOrder').children().remove();
        
        titles.sort(anyCaseDesc);
        
        for(j = 0; j < titles.length; j++){
            
            $("#StopWordsOrder").append('<div><li data-stopworddatetime="'+titles[j].date+'" data-stopwordtitle="'+titles[j].title+'" data-stop-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
        
    }
    else{//По возростанию
        
        $('#SortStopWordsByTitle').data('sorttype','asc');
        titles = new Array();
        
        $.each($('#StopWordsOrder div li[data-stopwordtitle]'),function(id,district){
            
          titles.push(
                    {
                        title: new String($(district).data('stopwordtitle')),
                        id   : new String($(district).data('stop-id')),
                        date : new String($(district).data('stopworddatetime'))
                    });
            
        });
        
        titles.sort(anyCaseAsc);
        
        $('#StopWordsOrder').children().remove();
        
        for(j = 0; j < titles.length; j++){
            
            $("#StopWordsOrder").append('<div><li data-stopworddatetime="'+titles[j].date+'" data-stopwordtitle="'+titles[j].title+'" data-stop-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
    }
    
}

function SortStopWordsByDate(){
    
    SortType = $('#SortDistrictsByDate').data('sorttype');

    if(SortType == 'asc'){//По убыванию
        $('#SortDistrictsByDate').data('sorttype','desc');
        
        titles = new Array();
        
        $.each($('#StopWordsOrder div li[data-stopwordtitle]'),function(id,district){
            
            titles.push(
                    {
                        title: new String($(district).data('stopwordtitle')),
                        id   : new String($(district).data('stop-id')),
                        date : new String($(district).data('stopworddatetime'))
                    });
            
        });
        
        $('#StopWordsOrder').children().remove();
        
        titles.sort(anyDateDesc);
        
        for(j = 0; j < titles.length; j++){
            
            $("#StopWordsOrder").append('<div><li data-stopworddatetime="'+titles[j].date+'" data-stopwordtitle="'+titles[j].title+'" data-stop-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
        
    }
    else{//По возростанию
        
        $('#SortDistrictsByDate').data('sorttype','asc');
        titles = new Array();
        
        $.each($('#StopWordsOrder div li[data-stopwordtitle]'),function(id,district){
            
          titles.push(
                    {
                        title: new String($(district).data('stopwordtitle')),
                        id   : new String($(district).data('stop-id')),
                        date : new String($(district).data('stopworddatetime'))
                    });
            
        });
        
        titles.sort(anyDateAsc);
        
        $('#StopWordsOrder').children().remove();
        
        for(j = 0; j < titles.length; j++){
            
            $("#StopWordsOrder").append('<div><li data-stopworddatetime="'+titles[j].date+'" data-stopwordtitle="'+titles[j].title+'" data-stop-id=\"'+titles[j].id+'\" class=\"chng_distr_li\">'+titles[j].title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            
        }
    }
    
}

function GetOurUsers(){

    $.post('ajax.php',{GET_OUR_USER: 'set'},function(data){
          
          users = $.parseJSON(data);
          
          if(users.length != 0){

              $.each(users,function(indx,user){
                  
                  $('#newsContent').append('<div class="post"><b>Login:</b> '+user.Login+' <b>Фамилия/Имя:</b> '+user.LastName+'/'+user.FirstName+'</div>');
                  
              });
               
          }
          else{
              alert(data);
          }
          
    });
    
}

function GetOpponents(){
    
    $.post('ajax.php',{GET_INFO_USERS: 'set'},function(data){
          users = $.parseJSON(data);
          
          if(users.length != 0){
              $('#newsContent').children().remove();
              $.each(users,function(indx,user){
                  
                  $('#newsContent').append('<div class="post">'+user.UserName+'</div>');
                  $('#newsContent').append('<div>Социальные сети:</div>');
                  
                  $.each(user.Socials,function(indx,social){
                     $('#newsContent').children().last().append('<div>'+social.AccsName+' ('+social.SocialId+')</div>');
                  });
              });
              $.post('ajax.php',{GET_VK_GROUPS: 'set'},function(data){
        
          groups = $.parseJSON(data);
          
          if(groups.length != 0){

              $('#newsContent').append('<h3>Скрытые группы "вк":</h3>');
              
              $.each(groups,function(indx,group){
                  
                  $('#newsContent').append('<div class="post">'+group.GroupTitleId+'</div>');
                  
              });
              
          }
          else{
              $('#newsContent').append('<h3>Скрытые группы Vkontakte не найдены!</h3>');
          }
          
    });
          }
          else{
              alert(data);
          }
          
    });
    
}

function GetVkGroups(){
    
    $.post('ajax.php',{GET_VK_GROUPS: 'set'},function(data){
        
          groups = $.parseJSON(data);
          
          if(groups.length != 0){

              $('#newsContent').append('<h3>Скрытые группы "вк":</h3>');
              
              $.each(groups,function(indx,group){
                  
                  $('#newsContent').append('<div class="post">'+group.GroupTitleId+'</div>');
                  
              });
              
          }
          else{
              $('#newsContent').append('<h3>Скрытые группы Vkontakte не найдены!</h3>');
          }
          
    });
    
}

function GetGlobalNewsByShortDescription(description){
    
    $.post('ajax.php',{GET_NEWS_START_WITH: 'set' , start_text: description},function(data){
        news = $.parseJSON(data);
        $('#search_list_content').children().remove();
        $('#search_list_div').css('display','block');
        
        $.each(news,function(indx,spec_news){
            
            short_description = new String(spec_news.description).substring(0,20);
            
            $('#search_list_content').append('<a href="?ctrl=news&act=SpecificPostHome&id='+spec_news.id+'"><span class="user_list_span">'+short_description+'</span></a>');
            
        });
        
        
    });
    
}

function AddSocial(name){
    
    $.post('ajax.php',{ADD_NEW_SOCIAL:'set',SocialName:name},function(data){
        
        if(data != 'fail'){
            social = $.parseJSON(data);
            ShowPersonalRoomMessage($("#SocialSectionConfirm"),'Группа успешно добавлена','success');
            $("#SocialSectionConfirm").children().last().addClass("srch_success");
            $("#SocialSectionConfirm").children().last().delay(2000).fadeOut(500);
            $("#SocialOrder").append('<div><li data-social-id=\"'+social.id+'\" class=\"chng_distr_li\">'+social.SocialName+'<span class=\"remove_social chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование сети\"><span id=\"ConfirmName\" class=\"group chnd_group_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
            $('#SocialList').append('<option data-social-id="'+social.id+'">'+social.SocialName+'</option>');
            
        }
        else{

            ShowPersonalRoomMessage($("#SocialSectionConfirm"),'Такая сеть уже есть','error');
            $("#SocialSectionConfirm").children().last().addClass("srch_error");
            $("#SocialSectionConfirm").children().last().delay(2000).fadeOut(500);
            
        }
        
    });
    
}

function RemoveSocial(id,el){
    
    $.post('ajax.php',{REMOVE_SOCIAL:'set', social_id: id},function(data){
            if(data == "ok"){
                $(el).parent().parent().remove();
                
                $('#SocialList').children('[data-social-id="'+id+'"]').remove();
                
                if($('#SocialOrder').children().length == 0){
                    $('#SocialOrder').append("<h2 id='NotFoundAnyUsers' class=\"h2\">Не найдено ни одной социальной сети</h2>");
                    
                }
                else{
                    ShowPersonalRoomMessage($("#SocialSet"),'Сеть удалена','success'); 
                    $("#SocialSet").children().last().addClass("srch_success");
                    $("#SocialSet").children().last().delay(2000).fadeOut(500);
                }
            }
            else{
                ShowPersonalRoomMessage($("#SocialSet"),'Сеть не удалена','error'); 
                $("#SocialSet").children().last().addClass("srch_error");
                $("#SocialSet").children().last().delay(2000).fadeOut(500);
            }
    });
    
}

function UpdateSocial(id,newName){
    
    $.post('ajax.php',{UPDATE_SOCIAL:'set',social_id:id,new_title: newName},function(data){

          if(data == 'ok'){
            $('li[data-social-id='+id+']').text(newName);
            ShowPersonalRoomMessage($("#SocialSet"),'Сеть изменена','success'); 
            $("#SocialSet").children().last().addClass("srch_success");
            $("#SocialSet").children().last().delay(2000).fadeOut(500);
          }//if
          else{
              
            ShowPersonalRoomMessage($("#SocialSet"),'Сеть не изменена','error'); 
            $("#SocialSet").children().last().addClass("srch_error");
            $("#SocialSet").children().last().delay(2000).fadeOut(500);
            
          }//else
    });
    
}

function AddInfoPulseUser(new_user_title){
    
    $.post("ajax.php",{ADD_INFO_USER: 'SET',USER: new_user_title},function(data){

                    if(data != "exist"){
                        user = $.parseJSON(data);
                        $('#NotFoundAnyUsers').remove();
                        ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Пользователь добавлен','success');
                        $("#UsersSectionConfirm").children().last().addClass("srch_success");
                        $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
                        $("#UsersOrder").append('<div><li data-user-id=\"'+user.id+'\" class=\"chng_distr_li\">'+user.UserName+'<span class=\"remove_user chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"user chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Изменение пользователя\"><span id=\"ConfirmName\" class=\"user chnd_vk_users_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
                        $("#UsersList").append('<option data-user-id="'+user.id+'">'+user.UserName+'</option>');
                        
                    }//if
                    else if(data == "exist"){
                        ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Указанный пользователь уже существует','error');
                        $("#UsersSectionConfirm").children().last().addClass("srch_error");
                        $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
                    }//else
                    else{
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Ошибка на сервере','error');
                        $("#UsersSectionConfirm").children().last().addClass("srch_error");
                        $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
                    }//else
             });
    
}

function RemoveInfoPulseUser(user_id){
    
    $.post('ajax.php',{REMOVE_INFO_USER:'set', user_id: user_id},function(data){
        
        if(data == "ok"){
            
            $("#UsersList option[data-user-id='"+user_id+"']").remove();
            
            $(el).parent().parent().remove();
            
            if($('#UsersOrder').children().length == 0){
                $('#UsersOrder').append("<h2 id='NotFoundAnyUsers' class=\"h2\">Не найдено ни одного пользователя для поиска</h2>");
            }//if
            
        }//if
        else{
            alert(data);
        }//else
    });
    
}

function AddSocialToUser(social_id,user_id,accs_name){
    
    $.post('ajax.php',
        {
            ADD_SOCIAL_TO_USER: 'set',
            user_id: user_id,
            social_id: social_id,
            accs_name: accs_name 
            
        },
        
        function(data){
            
            if(data == 'ok'){
                ShowPersonalRoomMessage($("#AddSocialToUser"),'Социальный профиль добавлен','success');
                $("#AddSocialToUser").children().last().addClass("srch_success");
                $("#AddSocialToUser").children().last().delay(2000).fadeOut(500);
                
            }//if
            else{
                ShowPersonalRoomMessage($("#AddSocialToUser"),'Указанный профиль уже существует','error');
                $("#AddSocialToUser").children().last().addClass("srch_error");
                $("#AddSocialToUser").children().last().delay(2000).fadeOut(500);
            }
        }//success
    );
    
}

function GetDownLoadLink(left,right){
    
    $.post('ajax.php',{GET_DOWNLOAD_LINK : 'set' , left: left, right: right},function(data){
        
        window.location = "?act=download&ctrl=news&filename=" + data;
        
    });
    
}//GetDownLoadLink

function GetDownLoadLinkPDF(news_id){
    
    $.post('ajax.php',{GET_DOWNLOAD_LINK_PDF : 'set' , news_id: news_id},function(data){
        
       window.location = "?act=download&ctrl=news&filename=" + data;
        
    });
    
}//GetDownLoadLink


function GetDownLoadLinkPDFPerson(news_id){
    
    $.post('ajax.php',{GET_DOWNLOAD_LINK_PDF_PERSON : 'set' , news_id: news_id},function(data){
        
       window.location = "?act=download&ctrl=news&filename=" + data;
        
    });
    
}//GetDownLoadLink

function GetDownLoadLinkPDFbyDistricts(){
    
    districts = sessionStorage['selected_li'];
    
    districts_array = districts.split(';');
    $('#loader_dis').css('display','block');
    $('#loader_dis').append('<div>Экспортируем...</div>');
    
    $.post('ajax.php',{GET_DOWNLOAD_LINK_PDF_BY_DISTRICTS : 'set' , districts: districts_array},function(data){
           $('#loader_dis').css('display','none');
           $('#loader_dis').children().empty();
           
           window.location = "?act=download&ctrl=news&filename=" + data;
        
    });
    
}//GetDownLoadLink

function GetDownLoadLinkWordByDistricts(){
    
    $.post('ajax.php',{GET_DOWNLOAD_DISTRICTS : 'set'},function(data){

           window.location = "?act=download&ctrl=news&filename=" + data;
        
    });
    
}//GetDownLoadLinkWordByDistricts

function GetDownLoadLinkWordByStopWords(){
    
    $.post('ajax.php',{GET_DOWNLOAD_STOP_WORDS : 'set'},function(data){

           window.location = "?act=download&ctrl=news&filename=" + data;
        
    });
    
}//GetDownLoadLinkWordByDistricts
function Authorise(){
    
    if ($('#userPS').val() && $('#userLE').val()){ //if not empty
                 
                $.post("ajax.php",
                {
                    authorize: 'set',
                    userLE: $('#userLE').val(),
                    userPS: $('#userPS').val()

                },function (data){
                    
                     if(data == "yes"){//
                         $('#AuthoriseForm').submit();
                     }//if 
                     else{
                         ShowAuthorizeMessage('Неверный логин или пароль');
                         
                     }//else

                });
                
        }//end if empty
        else{
            
            ShowAuthorizeMessage('Есть пустые поля');
        }
}

function RemoveAcc(acc_id){
    
     $.post('ajax.php',{DELETE_ACC : 'set', acc_id: acc_id},function(data){

        
    });
    
}

function AddCommentToPost(post_id,comment){
    
    $.post('ajax.php',{SEND_COMMENT : 'set', post_id: post_id,comment:comment},function(data){
        
        if(data != 'fail' && data != 'to long' && data != "to short"){
            post_info = $.parseJSON(data);
            
            if($('#Comments').children().length == 0){
                
                $('#com_empty').remove();
                $('#Comments').append('<p>'+post_info.user+': '+comment+'<span class="post-date2" title="Время публикации">'+post_info.date+'</span></p>');
                
            }//if
            else{
                $('<p>'+post_info.user+': '+comment+'<span class="post-date2" title="Время публикации">'+post_info.date+'</span></p>').insertBefore($('#Comments').children().first());
            }
            
            $('#CommentText').val('');
        }//if
        else{
            alert(data);
        }
        
    });
    
}

function sendNewsToUser(user_id,news_id){
    
    $.post('ajax.php',{SEND_NEWS_TO_USER : 'set', user_id: user_id,news_id:news_id},function(data){
        
        if(data == 'ok'){
            alert('ok');
        }//if
        else{
            alert(data);
        }//else
        
    });
    
}//sendNewsToUser

function setPostTag(news_id,post_tag){
    
     $.post('ajax.php',{SET_POST_TAG : 'set', post_tag: post_tag,news_id: news_id},function(data){
        
        if(data == 'ok'){
        }//if
        else{
            alert(data);
        }//else
        
    });
    
}

$(document).ready(function(){
//    $('#new_news_count').fadeOut(0);
//    $('#new_news_count').removeClass("hidden");
    
    //клик на 1 из кружочков
    $('body').on('click','.circles div',function(){
        var class_list_this = $(this).attr('class').split(/\s+/);
        var class_list_first = $(this).parent().children().first().attr('class').split(/\s+/);
        $(this).parent().children().first().removeClass(class_list_first[0]);
        $(this).parent().children().first().addClass(class_list_this[0]);
        
        $(this).removeClass(class_list_this[0]);
        $(this).addClass(class_list_first[0]);
        
        post_id = $(this).parent().parent().data('post-id');
        if(post_id == undefined){
            post_id = $('[data-news-id]:first').data('news-id');
        }
        setPostTag(post_id,class_list_this[0]);
        
    });
    
    $('#Comment').click(function(){
        post_id = $('[data-news-id]:first').data('news-id');
        comment = new String($('#CommentText').val());
        comment = comment.trim();
        
        if(post_id != undefined && comment.length != 0){
            AddCommentToPost(post_id,comment);
        }
        else{
            post_id = $('[data-person-id]:first').data('person-id');
            if(post_id != undefined && comment.length != 0){
                AddCommentToPost(post_id,comment);
            }   
        }        
    });
    
    $('body').on('click','.show_all',function(){

        //alert($(this).parent());
        var txt_part, all_txt;
        txt_part = new String($(this).parent().children('.post-text').html());
        all_txt = new String($(this).parent().children('.last_part').html());        
        var dest;
        dest = $(this).parent().offset().top;
        
        if(txt_part.length <= 310){    
            $(this).parent().children('.post-text').html(all_txt);
            $(this).parent().children('.last_part').html(txt_part);
            
        }else{
            $(this).parent().children('.post-text').html(all_txt);
            $(this).parent().children('.last_part').html(txt_part);
            $('body,html').animate({scrollTop:dest},600);
        }
        

    });
    $('#userPS').keypress(function(e){
        if(e.which == 13){
            Authorise();
        }//if
    });
    
    $('body').on('click','#ExportToPDF_districts',GetDownLoadLinkPDFbyDistricts);
    $('#ExportToWord_dstr').click(GetDownLoadLinkWordByDistricts);
    $('#ExportToWord_sw').click(GetDownLoadLinkWordByStopWords);
    
    $('#ExportToPDF').click(function(){
        if($('div[data-news-id]').length != 0){
            
            news_id = new String($('[data-news-id]:first').data('news-id'));
            GetDownLoadLinkPDF(news_id);
            
        }//if
        else{
            
            news_id = new String($('[data-person-id]:first').data('person-id'));
            GetDownLoadLinkPDFPerson(news_id);
            
        }//else
        
        
        
    });
    
    $('#ExportToWord').click(function(){
        
        left = new String($('#leftDate').val());
        right = new String($('#rightDate').val());
        
        if(left.split('-').length == 3 && right.split('-').length == 3){
            GetDownLoadLink(left,right);
        }//if
        else{
            ShowPersonalRoomMessage($("#statistic_menu"),'Одна из дат не выбрана','error'); 
            $("#statistic_menu").children().last().addClass("srch_error");
            $("#statistic_menu").children().last().delay(2000).fadeOut(500);
        }//else
        
    });
    
    $('#min_menu_btn').click(function(){
        if($('#menu_min').hasClass('hidden')){
            $('#menu_min').removeClass('hidden');
            $('#menu_min').animate({height: "200px"}, 300);
        }else{
            $('#menu_min').animate({height: "0px"}, 300).addClass('hidden');  
            //$('#menu_min');         
        }
    });
    $('#AddSocialToUserInput').click(function(){
        
        user_id = $('#UsersList option:selected').data('user-id');
        social_id = $('#SocialList option:selected').data('social-id');
        accs_name = new String($('#AccName').val());
        accs_name = accs_name.trim();
        
        if(accs_name.length != 0){
            AddSocialToUser(social_id,user_id,accs_name);
        }//if
        else{
            ShowPersonalRoomMessage($("#AddSocialToUser"),'Название уккуанта не может быть пустым','error'); 
            $("#AddSocialToUser").children().last().addClass("srch_error");
            $("#AddSocialToUser").children().last().delay(2000).fadeOut(500);
        }//else
        
    });
    


    $('#NewSocialAction').click(function(){
        
        new_name = new String($('#NewSocial').val());
        new_name = new_name.trim();
        
        if(new_name.length != 0){
            AddSocial(new_name);
        }//if
        else{

            ShowPersonalRoomMessage($("#SocialSectionConfirm"),'Поле не может быть пустым','error');
            $("#SocialSectionConfirm").children().last().addClass("srch_error");
            $("#SocialSectionConfirm").children().last().delay(2000).fadeOut(500);
        }//else
        
    });
    
    $("#search").change(function(){
        if(!$("#search").val()){
            $('#search_list_div').css('display','none');
        }
    });
    $("#search").keyup(function(){
        if(new String($(this).val()).trim()){

            GetGlobalNewsByShortDescription($(this).val());

        }//if
        else{
            $('#search_list_div').css('display','none');
        }//else
        
    });
    $("#search").keypress(function(e) {
        if(e.which == 13 && $("#search").val() ) {
            window.location = "index?ctrl=news&act=GetNewsBySearchWord&SearchWord=" + $("#search").val();
        }
        else{
            
            
        }
    });
    
    $('#Main_users').click(function(){

        $('#newsContent').children().remove();
        GetOurUsers();
        
    });
    
    $('#Opponents').click(function(){
        
        
        $('#newsContent').children().remove();
        
        GetOpponents();
        
        
        
    });
    
    $('#SortDistrictsByTitle').click(function(){
        
        SortDistrictsBtTitle();
        
    });
    $('#SortDistrictsByDate').click(function(){
        
        SortDistrictsByDate();
        
    });
    
    $('#SortStopWordsByTitle').click(function(){
        SortStopWordsByTitle();
    });
    $('#SortStopWordsByDate').click(function(){
        SortStopWordsByDate();
    });
    
    
    $('#SearchByDate').click(function(){
        
        left = new String($('#leftDate').val());
        right = new String($('#rightDate').val());
        if(left.split('-').length == 3 && right.split('-').length == 3){
            GetNewsBetweenDates(left,right);
        }//if
        else{
            ShowPersonalRoomMessage($("#statistic_menu"),'Одна из дат не выбрана','error'); 
            $("#statistic_menu").children().last().addClass("srch_error");
            $("#statistic_menu").children().last().delay(2000).fadeOut(500);
        }//else
        
    });
    
    $('#ShowAllNews').click(function(){
        
        
        if($('#ShowAllNews').prop('checked') == true){
            ShowHiddenNews();
        }
        else{
            HideHiddenNews();
        }
        
    });
    $('.addeed_new_news').fadeOut(0);
    $('.addeed_new_news').click(function(){
       //location.reload();
       document.location.href = "http://user1187254.atservers.net/";
    });
    $('#dialog_sidebar').fadeOut(0);
    
    $('.h1').click(function(){
        $('#dialog_sidebar').fadeIn(300);
        $('#dialog_sidebar').delay(2000).fadeOut(300);
    });
    setInterval(GetAllNewsCount,5000);
    
    $("#loader_dis").fadeOut(0);
    var loc = new String($(location)[0]);
    if(loc.indexOf("act=Districts")!= -1){
        
        if(loc.indexOf("#") != -1){
            var pos = loc.search("#");
            var params = loc.substring(pos+1);
            var session_districts, sw;
            //alert(params);
            params = params.split('&');
            //alert(params[0]);
            params[0] = params[0].substring(8);//выделили все районы
            
            //вытащим стоп-слово
            params[1] = params[1].substring(12);

            if(params[1].length >2){
                sw = params[1];
            }else{
                sw = 'empty';
            }
            
            $("#loader_dis").fadeIn(300);
            $("#search-panel").fadeOut(0);
            $fl=false;
            $("#minimize").text('+');

            $("#search_news_by_stop_words").blur();
            
            session_districts = params[0].split(';');
            //if(session_districts.indexOf('empty') == -1){
                for(var i = 0;i < session_districts.length-1;i++){
                    //alert(session_districts[i]);
                    GetNewsByDistrictOrStopWord(session_districts[i],sw);
                }                
            //}
            
        }
        
    }
    
    var focus_inp=false;
    var focus_txt=false;
    
    $('h2.pof_users').click(function(){
        $("#loader_dis").fadeIn(300);
        var user = $(this).text();
//        user = 'https://twitter.com/'+user;
        
        $('#newsContent').empty();
        
        $.post("ajax.php",{GET_NEWS_BY_POI: 'SET',POI_USER: user},function(data){
            //alert(data);
                AddNewsToNewsContent(data);

            }
        );
    });
    
    
    
    
    
    
    
    
    $('#postTitle').click(function(){
        focus_txt=false;
        focus_inp=true;
    });
    
    $('#makePostArea').click(function(){
        focus_txt=true;
        focus_inp=false;
    });
    
    $('#show_add_bw').click(function(){
        
        $('#loader_dis').css('display','block');
        $('#loader_dis').append('<div>Раскрываем все записи...</div>');
        
        $.post('ajax.php',{SHOW_ALL_NEWS_FIRST: 'set'},function(data){
              window.location = "?ctrl=news&act=news";
        });
        
    });
    
    $('#minimize_add_bw').click(function(){
        $('#loader_dis').css('display','block');
        $('#loader_dis').append('<div>Скрываем все записи...</div>');
        
        $.post('ajax.php',{HIDE_ALL_NEWS_FIRST: 'set'},function(data){
            
            window.location = "?ctrl=news&act=news";
        });
        
    });
    
    //По клику на динамический список
    
    $('body').on('click','span.user_list_span',function(){
        

        user = new String($(this).text());
        user = user.trim();

        $("#SelectedDistrict").val(user);
        district_id = $(this).data('distr-id');
        $("#SelectedDistrictId").val(district_id);

        user = user.replace(/ /g,'_');
        user += ' ';
        
            
        
        if(focus_txt){
            text = new String($('#makePostArea').val());
        }else if(focus_inp){
            text = new String($('#postTitle').val());
        }                




        first = text.substr(0,FirstPosition);

        $("#first_str").empty().html(first);
        //$("#first_str_txt_area").empty().html(first);

        second = text.substr(pos,text.length);
        first += user;
        first += second;


        if(focus_txt){
            
            $('#makePostArea').val(first);
        }else if(focus_inp){
            
            $('#postTitle').val(first);
        }


        $('#district_list_div').css("display","none");
        $('#user_list_div').css("display","none");




        FirstPosition = -1;
        count_symbols = 0;
        isSharp = false;
        isDog = false;

                
    });
    
    $('#postTitle').keyup(function(e){
          
        if(event.which == 50 ){// if @

            pos = document.getElementById('postTitle').selectionStart;
            
            if((new String($('#postTitle').val())).charAt(pos-1) == '@'){//if <- 
                
                $('#district_list_div').css("display","none");
                
                isDog = true;
                isSharp = false;
                FirstPosition = pos;
                count_symbols = 0;

                $.post('ajax.php',{GET_ALL_USERS: 'set'},function(data){
                    
                    users = $.parseJSON(data);
                    $('#user_list_content').empty();
                    $.each(users,function(indx,user){

                        $('#user_list_content').append("<span class=\"user_list_span\">"+user.Login+"</span>");

                    });

                    text = new String($('#postTitle').val());

                    pos_f = text.substr(0,pos);

                    $("#first_str").empty().html(pos_f);
                    w = $("#first_str").width();

                    if (w > 640){
                        bl_w = 640 + "px";   
                    }else{
                        bl_w = w + 5 + "px";  
                    }

                    $('#user_list_div').css({"display":"block", "margin-left":bl_w, "margin-top":"0"});
                    $(".nano").nanoScroller();
                });
                
            }//if
            
        }//if
        
        else if(event.which != 50 && event.which != 51 && event.which != 38 && event.which != 37 && event.which != 39 && event.which != 40 && event.which != 16 && event.which != 18){//if ~@
            
            if(isDog){
                
                pos = document.getElementById('postTitle').selectionStart;
                start = new String($('#postTitle').val());
                 
                start = start.substr(FirstPosition,count_symbols);
                count_symbols++;

                if(start.charAt(pos-1) == '@'){
                    $('#user_list_div').css({"display":"block", "margin-left":bl_w});
                }//if

                if(isDog){

                    $.post('ajax.php',{GetUsersStartWith: 'set', start: start},function(data){

                    users = $.parseJSON(data);
                    $('#user_list_content').empty();

                    $.each(users,function(indx,user){

                        $('#user_list_content').append("<span class=\"user_list_span\">"+user.Login+"</span>");

                    });

                    text = new String($('#postTitle').val());

                    pos_f = text.substr(0,pos);

                    $("#first_str").empty().html(pos_f);
                    w = $("#first_str").width();

                    if (w > 640){
                        bl_w = 640 + "px";   
                    }else{
                        bl_w = w + 5 + "px";  
                    }

                    $('#user_list_div').css({"display":"block", "margin-left":bl_w, "margin-top":"0"});
                    $(".nano").nanoScroller();
                });

                }//if isDog
                
            }
            
            else if(isSharp){
                
                pos = document.getElementById('postTitle').selectionStart;
                
                start = new String($('#postTitle').val());
                
                count_symbols++;
                start = start.substr(FirstPosition,count_symbols);
                
                if(count_symbols > 1){
                    
                    $.post('ajax.php',{GetDistrictsStartWith: 'set', start: start},function(data){

                    districts = $.parseJSON(data);
                    $('#district_list_content').empty();

                    $.each(districts,function(indx,district){

                        $('#district_list_content').append("<span class=\"user_list_span\">"+district.Title+"</span>");

                    });

                    text = new String($('#postTitle').val());

                    pos_f = text.substr(0,pos);

                    $("#first_str").empty().html(pos_f);
                    w = $("#first_str").width();

                    if (w > 640){
                        bl_w = 640 + "px";   
                    }else{
                        bl_w = w + 5 + "px";  
                    }

                    $('#district_list_div').css({"display":"block", "margin-left":bl_w, "margin-top":"0"});
                    $(".nano").nanoScroller();
                });
                }
                
            }
            
        }//else if
        
        else if(event.which == 51){//#
            
            pos = document.getElementById('postTitle').selectionStart;
            if((new String($('#postTitle').val())).charAt(pos-1) == '#'){//if <- 
                isSharp = true;
             isDog = false;
             
             $('#user_list_div').css("display","none");
             
             FirstPosition = pos;
             count_symbols = 0;
             
             $.post('ajax.php',{GET_ALL_DISTRICTS: 'set'},function(data){
                districts = $.parseJSON(data);
                $('#district_list_content').empty();
                
                $.each(districts, function(indx,district){
                    $('#district_list_content').append("<span class=\"user_list_span\" data-distr-id=\""+district.id+"\">"+district.Title+"</span>");
                });
                
                text = new String($('#postTitle').val());
                pos_f = text.substr(0,pos);
                
                $("#first_str").empty().html(pos_f);
                w = $("#first_str").width();
                
                if (w > 640){
                    bl_w = 640 + "px";   
                }else{
                    bl_w = w + 5 + "px";  
                }
                
                $('#district_list_div').css({"display":"block", "margin-left":bl_w, "margin-top":"0"});
                //$('div.nano-pane').css({"display":"block"});
                $(".nano").nanoScroller();
            });
            }
            
        }//else if
        
        if(isDog || isSharp){
            
            pos = document.getElementById('postTitle').selectionStart;
            start = new String($('#postTitle').val());
            start = start.substr(pos,count_symbols);
            
        }
       
        
    });
    
    $('#postTitle').keydown(function(e){
        
        if( e.which == 9 || e.which == 32 || e.which == 46 || e.which == 27){
            
            isDog = false;
            isSharp = false;
            FirstPosition = -1;
            count_symbols = 0;
            $('#district_list_div').css("display","none");
            $('#user_list_div').css("display","none");
            
        }//if
        
        else if(e.which == 8){//if BACKSPACE
            
            pos = document.getElementById('postTitle').selectionStart;
            lecsem = (new String($('#postTitle').val())).charAt(pos-1);
            
            if(lecsem == '@' || lecsem == '#'){
                
                isDog = false;
                isSharp = false;
                count_symbols = 0;
                FirstPosition = -1;
                
                $('#district_list_div').css("display","none");
                $('#user_list_div').css("display","none");
                
            }
            
            if(isDog || isSharp && count_symbols > 0){
                count_symbols--;
            }//if
                
        }
    });


    //обработчики нажатия в поле "текст"
    $('#makePostArea').keyup(function(e){
          
        if(event.which == 50 ){// if @
            pos = document.getElementById('makePostArea').selectionStart;
            
            if((new String($('#makePostArea').val())).charAt(pos-1) == '@'){//if <- 
                
                $('#district_list_div').css("display","none");
                
                isDog = true;
                isSharp = false;
                FirstPosition = pos;
                count_symbols = 0;

                $.post('ajax.php',{GET_ALL_USERS: 'set'},function(data){

                    users = $.parseJSON(data);
                    $('#user_list_content').empty();
                    $.each(users,function(indx,user){

                        $('#user_list_content').append("<span class=\"user_list_span\">"+user.Login+"</span>");

                    });

                    text = new String($('#makePostArea').val());


                    pos_f = text.substr(0,pos);

                    $("#first_str_txt_area").empty().html(pos_f);
                    w = $("#first_str_txt_area").width();

//                    r = ~~(w/830);
//                    w = w - r*830 ;
//                    alert(w);
                    
                    if (w > 640){
                        bl_w = 640 + "px";   
                    }else{
                        bl_w = w + 5 + "px";  
                    }
                    
                    h = $("#first_str_txt_area").height();
                    
                    if(h>250){
                        bl_h =290 + "px";
                    }else{
                        bl_h =h + 50 + "px";
                    }
                    
                    
                    
                    $('#user_list_div').css({"display":"block", "margin-left":'10px', "margin-top":bl_h});
                    $(".nano").nanoScroller();
                });
                
            }//if
            
        }//if
        
        else if(event.which != 50 && event.which != 51 && event.which != 38 && event.which != 37 && event.which != 39 && event.which != 40 && event.which != 16 && event.which != 18){//if ~@
            
            if(isDog){
                
                pos = document.getElementById('makePostArea').selectionStart;
                start = new String($('#makePostArea').val());
                 
                start = start.substr(FirstPosition,count_symbols);
                count_symbols++;

                if(start.charAt(pos-1) == '@'){
                    $('#user_list_div').css({"display":"block", "margin-left":bl_w});
                }//if

                if(isDog){

                    $.post('ajax.php',{GetUsersStartWith: 'set', start: start},function(data){

                    users = $.parseJSON(data);
                    $('#user_list_content').empty();

                    $.each(users,function(indx,user){

                        $('#user_list_content').append("<span class=\"user_list_span\">"+user.Login+"</span>");

                    });

                    text = new String($('#makePostArea').val());

                    pos_f = text.substr(0,pos);

                    $("#first_str_txt_area").empty().html(pos_f);
                    w = $("#first_str_txt_area").width();

                    if (w > 640){
                        bl_w = 640 + "px";   
                    }else{
                        bl_w = w + 5 + "px";  
                    }

                    h = $("#first_str_txt_area").height();
                    
                    if(h>250){
                        bl_h =290 + "px";
                    }else{
                        bl_h =h + 50 + "px";
                    }
                    $('#user_list_div').css({"display":"block", "margin-left":'10px', "margin-top":bl_h});
                    $(".nano").nanoScroller();
                });

                }//if isDog
                
            }
            
            else if(isSharp){
                
                pos = document.getElementById('makePostArea').selectionStart;
                
                start = new String($('#makePostArea').val());
                
                count_symbols++;
                start = start.substr(FirstPosition,count_symbols);
                
                if(count_symbols > 1){
                    
                    $.post('ajax.php',{GetDistrictsStartWith: 'set', start: start},function(data){

                    districts = $.parseJSON(data);
                    $('#district_list_content').empty();

                    $.each(districts,function(indx,district){

                        $('#district_list_content').append("<span class=\"user_list_span\">"+district.Title+"</span>");

                    });

                    text = new String($('#makePostArea').val());

                    pos_f = text.substr(0,pos);

                    $("#first_str_txt_area").empty().html(pos_f);
                    w = $("#first_str_txt_area").width();

                    if (w > 640){
                        bl_w = 640 + "px";   
                    }else{
                        bl_w = w + 5 + "px";  
                    }

                    h = $("#first_str_txt_area").height();
                    
                    if(h>250){
                        bl_h =290 + "px";
                    }else{
                        bl_h =h + 50 + "px";
                    }
                    $('#district_list_div').css({"display":"block", "margin-left":'10px', "margin-top":bl_h});
                    $(".nano").nanoScroller();
                });
                }
                
            }
            
        }//else if
        
        else if(event.which == 51){//#
            
            pos = document.getElementById('makePostArea').selectionStart;
            if((new String($('#makePostArea').val())).charAt(pos-1) == '#'){//if <- 
                isSharp = true;
             isDog = false;
             
             $('#user_list_div').css("display","none");
             
             FirstPosition = pos;
             count_symbols = 0;
             
             $.post('ajax.php',{GET_ALL_DISTRICTS: 'set'},function(data){
                districts = $.parseJSON(data);
                $('#district_list_content').empty();
                
                $.each(districts, function(indx,district){
                    $('#district_list_content').append("<span class=\"user_list_span\" data-distr-id=\""+district.id+"\">"+district.Title+"</span>");
                });
                
                text = new String($('#makePostArea').val());
                pos_f = text.substr(0,pos);
                
                $("#first_str_txt_area").empty().html(pos_f);
                w = $("#first_str_txt_area").width();
                
                
                if (w > 640){
                    bl_w = 640 + "px";   
                }else{
                    bl_w = w + 5 + "px";  
                }
                
                h = $("#first_str_txt_area").height();
                
                if(h>250){
                    bl_h =290 + "px";
                }else{
                    bl_h =h + 50 + "px";
                }
                $('#district_list_div').css({"display":"block", "margin-left":'10px', "margin-top":bl_h});
                $(".nano").nanoScroller();
            });
            }
            
        }//else if
        
        if(isDog || isSharp){
            
            pos = document.getElementById('makePostArea').selectionStart;
            start = new String($('#makePostArea').val());
            start = start.substr(pos,count_symbols);
            
        }
       
        
    });
    
    $('#makePostArea').keydown(function(e){
        
        if( e.which == 9 || e.which == 32 || e.which == 46 || e.which == 27){
            
            isDog = false;
            isSharp = false;
            FirstPosition = -1;
            count_symbols = 0;
            $('#district_list_div').css("display","none");
            $('#user_list_div').css("display","none");
            
        }//if
        
        else if(e.which == 8){//if BACKSPACE
            
            pos = document.getElementById('makePostArea').selectionStart;
            lecsem = (new String($('#makePostArea').val())).charAt(pos-1);
            
            if(lecsem == '@' || lecsem == '#'){
                
                isDog = false;
                isSharp = false;
                count_symbols = 0;
                FirstPosition = -1;
                
                $('#district_list_div').css("display","none");
                $('#user_list_div').css("display","none");
                
            }
            
            if(isDog || isSharp && count_symbols > 0){
                count_symbols--;
            }//if
                
        }
    });    
    

    
    
    
    
    
    
    
    //получить все новости по вк  
    
    $('#get_all_vk_news').click(function(){
        $("#loader_dis").fadeIn(300);
        
        $("#search-panel").fadeOut(200);
        
        $("#minimize").text('+');
        try{
            getSpecificNews('v');
        }
        catch(ex){
            console.log(ex);
        }
        
    });//получить все новости по вк 
    
    //получить все новости по tw
    $('#get_all_tw_news').click(function(){
        $("#loader_dis").fadeIn(300);
        $("#search-panel").fadeOut(200);
        
        $("#minimize").text('+');
                   
        getSpecificNews('t');
        
    });//получить все новости по tw
    
    
    //получить все новости по google news
    $('#get_all_google_news_news').click(function(){
        $("#loader_dis").fadeIn(300);
        $("#search-panel").fadeOut(200);
        
        $("#minimize").text('+');
        
        getSpecificNews('n');
        
    });//получить все новости по google news
    
    //получить все новости по google web
    $('#get_all_google_web_news').click(function(){
        $("#loader_dis").fadeIn(300);
        $("#search-panel").fadeOut(200);
        
        $("#minimize").text('+');
        
        getSpecificNews('g');
        
    });//получить все новости по google web
    
    //получить все новости по google web
    $('#get_all_fb_news').click(function(){
        $("#loader_dis").fadeIn(300);
        $("#search-panel").fadeOut(200);
        
        $("#minimize").text('+');
        
        getSpecificNews('f');
        
    });//получить все новости по google web
    
    $('#get_all_ya_news').click(function(){
        $("#loader_dis").fadeIn(300);
        $("#search-panel").fadeOut(200);
        
        $("#minimize").text('+');
        
        getSpecificNews('lj');
        
    });//получить все новости по google web
    
    //Изменение группы
    
    $('body').on('click','div.search span.search-icon',function(){
         if($("#search").val() ) {
            window.location = "index?ctrl=news&act=GetNewsBySearchWord&SearchWord=" + $("#search").val();
        }
    });
    
    $('body').on('click','.chnd_group_ok',function(){
        
            box = $(this).parent();
            elem = $(this).parent().prev();
            
            new_group_title = new String($(this).prev().val()); 
            group_id = $(this).parent().prev().data('group-id');
            
            new_group_title = new_group_title.trim();
            
            if(new_group_title.length != 0){
                
                $.post("ajax.php",{UPDATE_VK_GROUP: 'SET', group_id: group_id, group_new_title: new_group_title},function(data){
                
                    if(data == "ok"){
                        $('[data-group-id="'+group_id+'"]').empty();
                        $('[data-group-id="'+group_id+'"]').html('<a href="http://vk.com/'+new_group_title+'" title="Ссылка на первоисточник">'+new_group_title+'</a>');
                        $('[data-group-id="'+group_id+'"]').append('<span class="chng_distr_correct correct" title="Изменить">M</span>');
                        $(elem).parent().append("<div class=\"srch_success pers-success\"><h2 class=\"h2\">Группа изменена</h2></div>");                    
                        $(elem).parent().children().last().delay(2000).fadeOut(500);
                        
                    }//if
                    else{
                        $(elem).parent().append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такая группа есть</h2></div>");                    
                        $(elem).parent().children().last().delay(2000).fadeOut(500);
                    }
               
                
                });
                
            }//if length not 0
            else{
                $(elem).parent().append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Поле не может быть пустым<h2></div>");                    
                $(elem).parent().children().last().delay(2000).fadeOut(500);
            }

        });
        
    $('body').on('click','.chnd_vk_users_ok',function(){
            if($(this).hasClass('user')){
                box = $(this).parent();
                elem = $(this).parent().prev();
                new_group_title = new String($(this).prev().val()); 
                user_id = $(this).parent().prev().data('user-id');
            
                new_user_name = new_group_title.trim();
                if(new_user_name.length != 0){

                    $.post("ajax.php",{UPDATE_INFO_USER: 'SET', newUserName: new_user_name, user_id: user_id},function(data){

                        if(data == "ok"){

                            $('li[data-user-id="'+user_id+'"]').empty();
                            $('li[data-user-id="'+user_id+'"]').html('<span>'+new_user_name+'<span>');
                            $('option[data-user-id="'+user_id+'"]').text(new_user_name);
                            $('li[data-user-id="'+user_id+'"]').append('<span class="user chng_distr_correct correct" title="Изменить">M</span>');
                            $(elem).parent().append("<div class=\"srch_success pers-success\"><h2 class=\"h2\">Пользователь изменен</h2></div>");                    
                            $(elem).parent().children().last().delay(2000).fadeOut(500);

                        }//if
                        else{
                            $(elem).parent().append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такой пользователь есть</h2></div>");                    
                            $(elem).parent().children().last().delay(2000).fadeOut(500);
                        }


                    });
                }//if length not 0
                else{
                    $(elem).parent().append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Поле не может быть пустым<h2></div>");                    
                    $(elem).parent().children().last().delay(2000).fadeOut(500);
                }
            }
            else if($(this).hasClass('social')){
                
                new_social_title = new String($(this).prev().val()); 
                social_id = $(this).parent().prev().data('social-id');
            
                new_user_name = new_social_title.trim();
                
                UpdateSocial(social_id,new_social_title);
                
            }

        });    
    //Выбор новостей из вк
    $('#GetVkPosts').click(function(){
        $.get("vk_queries.php",null,function(data_global){ 
            if(data_global == "final"){
                alert("vk search final!");
                $.post("ajax.php",{GET_VK_POST_ACTION: 'SET'},function(data){
                    $("#GetVkPosts").val("Вконтакте поиск ( " + data + " )");
                });
            }//if
            else{
                alert(data_global);
            }//else 
        });
    });
    
    $('#GetTWPosts').click(function(){
        $.get("tw_queries.php",null,function(data_global){ 
            if(data_global == "final"){
                alert("twitter search final!");
                $.post("ajax.php",{GET_TW_POST_ACTION: 'SET'},function(data){
                    $("#GetTWPosts").val("Twitter поиск ( " + data + " )");
                });
                
            }//if
            else{
                alert(data_global);
            }//else 
        });
    });
    
    $('#GetGoogleWebPosts').click(function(){
        $.get("google_queries.php",null,function(data_global){ 
            if(data_global == "final"){
                alert("google-web search final!");
//                $.post("ajax.php",{GET_VK_POST_ACTION: 'SET'},function(data){
//                    $("#GetVkPosts").val("Вконтакте поиск ( " + data + " )");
//                });
            }//if
            else{
                alert(data_global);
            }//else 
        });
    });
    
    $('#GetGoogleNewsPosts').click(function(){
        $.get("google_news_queries.php",null,function(data_global){ 
            if(data_global == "final"){
                alert("google-news search final!");
//                $.post("ajax.php",{GET_VK_POST_ACTION: 'SET'},function(data){
//                    $("#GetVkPosts").val("Вконтакте поиск ( " + data + " )");
//                });
            }//if
            else{
                alert(data_global);
            }//else 
        });
    });    
    
    //Добавить вк группу
    $("#AddNewVkGroupSettings").click(function(){
        
        new_district_title = new String($("#NewGroup").val());
        new_district_title = new_district_title.split(',');
        
        for (i=0; i<new_district_title.length; i++){

            new_district_title[i] = new_district_title[i].trim();
            
            if(new_district_title[i].length != 0){
                $.post("ajax.php",{ADD_VK_GROUP: 'SET',GROUP: new_district_title[i]},function(data){
                    if(data != "exist" && data != "not inserted"){
                        $('#NotFoundAnyGroups').remove();
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Группа успешно добавлена','success');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_success");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                        $("#GroupsOrder").append('<div><li data-group-id=\"'+data+'\" class=\"chng_distr_li\"><a href="http://vk.com/'+new_district_title+'" title="Ссылка на первоисточник">'+new_district_title+'</a><span class=\"remove_group chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование группы\"><span id=\"ConfirmName\" class=\"group chnd_group_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
                    }//if
                    else if(data == "exist"){
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Указанная группа уже существует','error');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                    }//else
                    else{
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Ошибка на сервере','error');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                    }//else
             });
            }
            else{
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Поле не может быть пустым','error');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
            }
            
                

    }
    });

    //Добавить вк юзера для поиска
    $("#AddNewVkUser").click(function(){
        
        new_user_title = new String($("#NewVkUser").val());
        new_user_title = new_user_title.split(',');
        for (i=0; i<new_user_title.length; i++){

            new_user_title[i] = new_user_title[i].trim();
            
            if(new_user_title[i].length != 0){
                AddInfoPulseUser(new_user_title[i]);
            }
            else{
                ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Поле не может быть пустым','error');
                $("#UsersSectionConfirm").children().last().addClass("srch_error");
                $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
            }
            
                

    }
    });    
    
    //Добавить новое плохое слово
    $("#AddNewWord").click(function(){
        
        new_bad_word = new String($('#NewBadWord').val());
        new_bad_word = new_bad_word.trim();
        
        if(new_bad_word.length != 0){
            $.post("ajax.php",{CKECK_WORD: 'SET',word: new_bad_word},function(data){
                
                        if(data == "ok"){

                        $.post('ajax.php',{ADD_WORD: 'set',new_word: new_bad_word},function(data){
    
                            if(data != "bad"){
                                $.post('ajax.php',{HIDE_ALL_NEWS: 'set', word: new_bad_word },function(data){});
                                ShowPersonalRoomMessage($("#BadWordSectionConfirm"),'Слово успешно добавлено','success');
                                $("#BadWordSectionConfirm").children().last().addClass("srch_error");
                                $("#BadWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                                $("#BadWordsOrder").append('<div><li data-word-id=\"'+data+'\" class=\"chng_distr_li\">'+new_bad_word+'<span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование слова\"><span class=\"word chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
                            }//if
                            else{
                                ShowPersonalRoomMessage($("#BadWordSectionConfirm"),data,'error');
                                $("#BadWordSectionConfirm").children().last().addClass("srch_error");
                                $("#BadWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                            }
                        });
                        }//if
                        else{
                            ShowPersonalRoomMessage($("#BadWordSectionConfirm"),'Такое слово уже есть','error');
                            $("#BadWordSectionConfirm").children().last().addClass("srch_error");
                            $("#BadWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                        }


                    });
                    
        }
        else{
            ShowPersonalRoomMessage($("#BadWordSectionConfirm"),'Поле не может быть пустым','error');
            $("#BadWordSectionConfirm").children().last().addClass("srch_error");
            $("#BadWordSectionConfirm").children().last().delay(2000).fadeOut(500);
        }
    });
    
    //Добавить район в "Настройках"
    $("#AddNewDistrictSettings").click(function(){
        
        new_district_title = new String($("#NewDistrict").val());
        new_district_title = new_district_title.split(',');
        for (i=0; i<new_district_title.length; i++){

            new_district_title[i] = new_district_title[i].trim();
            
            if(new_district_title[i].length != 0){
                $.post("ajax.php",{ADD_DISTRICT: 'SET',District: new_district_title[i]},function(data){
                    if(data != "exist" && data != "not inserted"){
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Район успешно добавлен','success');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_success");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                        $("#districts_order").append('<div><li data-district-id=\"'+data+'\" class=\"chng_distr_li\">'+new_district_title+'<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
                    }//if
                    else if(data == "exist"){
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Указанный район уже существует','error');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                    }//else
                    else{
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Ошибка на сервере','error');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                    }//else
             });
            }
            else{
                        ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Район не может быть пустым','error');
                        $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                        $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
            }
            
                

    }
    });
    
    //Добавить стопслово в "Настройках"
    $("#AddStopWordSettings").click(function(){
        
        new_stop_word = new String($("#NewStopWord").val());       
        new_stop_word = new_stop_word.split(',');
        
        for (i=0; i<new_stop_word.length; i++){

            new_stop_word[i] = new_stop_word[i].trim();        
            if(new_stop_word[i].length != 0){
                $.post("ajax.php",{ADD_STOP_WORD: 'SET',stop_word: new_stop_word[i]},function(data){
                
                if(data != "exist"){
                    $("#StopWordsOrder").append('<div><li data-stop-id=\"'+data+'\" class="chng_distr_li">'+new_stop_word+'<span class=\"remove_stop_word chng_distr_correct correct\" title=\"Удалить\">J</span><span class="chng_distr_correct correct" title="Изменить">M</span></li><div class="hg_null"><input type="text" class="chng_distr_inp pers-input" placeholder="Редактирование стоп слова"><span id="ConfirmName" class="chnd_distr_ok ok" title="Подтвердить изменения">N</span></div><div>');
                    ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Стоп слово добавлено','success');
                    $("#StopWordSectionConfirm").children().last().addClass("srch_success");
                    $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                    
                }//if
                else if(data == "exist"){
                    ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Такое стоп слово уже есть','error');
                    $("#StopWordSectionConfirm").children().last().addClass("srch_error");
                    $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                }//else
            });
            }
            else{
                    ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Стоп слово не может быть пустым','error');
                    $("#StopWordSectionConfirm").children().last().addClass("srch_error");
                    $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
            }

        }
    });
    
    //скрытие и открытие записей
    $('body').on('click','span.hide_post',function(){
        
        element = $(this);
        
        type_post = $(this).text();
        post_id = $(this).parent().data('post-id');
        
        if(type_post == 'O'){//Скрыть запись
            
            $.post('ajax.php',{HIDE_SPECIFIC_NEWS: 'set', post_id: post_id},function(data){
                
                   
                   
            });
            $(element).parent().children('img').addClass('hide');
            $(element).parent().children().children('#postTitle').addClass('title_hidden');
            $(element).parent().children('#postContent').addClass('hide');
            $(element).parent().children('.show_all').addClass('hide');

            $(element).text('E');
 
        }//if
        
        else{//Открыть запись
            $.post('ajax.php',{SHOW_SPECIFIC_NEWS: 'set', post_id: post_id},null);
            $(element).parent().children().children('#postTitle').removeClass('title_hidden');
            $(element).parent().children('img').removeClass('hide');
            $(element).parent().children('#postContent').removeClass('hide');
            $(element).parent().children('.show_all').removeClass('hide');
            $(element).text('O');
            
        }
        
    });
    
    //Получить новости (Главная)
    $("#more_news").click(function(){

        $.post("get_news.php",null,function(data){

            news = $.parseJSON(data);

            $.each(news, function(idx, glob_news) {
                
                    var fl=false;
                    var last_part;
                    var Tag = null;
                    hidden = glob_news.IsHide;
                    if($.cookie('show_hidden_news') == 'false' && hidden == 1){ return; }
                    d_id = glob_news.id;
                    ch_social = new String(glob_news.Source);
                    var SearchType = new String(glob_news.SearchType);
                    title =  new String(glob_news.title);
                    description = new String(glob_news.description);
                    image = glob_news.Images;
                    date_public = glob_news.Date;
                    AllCollors = glob_news.AllCollors;
                    Tag = glob_news.Tag;

                    
                    
                    distr_str = new String(glob_news.District_str);
                    sw = new String(glob_news.Stop_words);

                    if(title.length > 50){

                        title = title.substr(0,47);
                        title += "...";
                    }//if

                    if(description.length > 300){
                        
                        last_part = new String(description);
                        description = description.substr(0,297);
                        description += "...";
                        fl = true;

                    }//if
                    description = description.replace(distr_str, " <span class=\"bold\">"+distr_str+"</span>");
                    description = description.replace(sw, " <span class=\"bold\">"+sw+"</span>");
                    description = description.replace(/\\n/g, " ");
                    description = description.replace(/\\"/g, "\"");

                    if(image != null){
                        image = new String(image);

                        if(image.indexOf('http') == -1){
                            image = "files/" + image.split(',')[0];

                        }

                    }


                    if(hidden == 0){ 
                        $("#newsContent").append("<div data-post-id="+d_id+" class=\"post\">");
                            
                            //цепляем круги
                            $("[data-post-id="+d_id+"]").append('<div class=\"circles\">');
                            if(Tag != null){
                                $("[data-post-id="+d_id+"] div.circles").append("<div class=\""+Tag+"\"></div>");
                            }
                            $.each(AllCollors, function(i_colors, single_color) {
                                if(Tag != single_color){
                                    $("[data-post-id="+d_id+"] div.circles").append("<div class=\""+single_color+"\"></div>");
                                }
                            });
                            $("[data-post-id="+d_id+"]").append('</div>');
                            
                            //цепляется иконка соц сети
                            if(SearchType == 'v'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a>");
                            }
                            else if(SearchType == 't'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a>");
                            }//if facebook
                            else if(SearchType == 'f'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a>");                 
                            }
                            else if(SearchType == 'i'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a>");
                            }
                            else if(SearchType == 'y'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a>");
                            }
                            else if(SearchType == 'lj'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a>");
                            }
                            else{
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a>");                 
                            }
                            
                        //все остальное до картинки
                        $("[data-post-id="+d_id+"]").append("<span class=\"hide_post post-icon\" title=\"Скрыть запись\">O</span><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span>");
                        
                        //картинка
                        if(image != null){
                            $("[data-post-id="+d_id+"]").append("<img  class=\"post-img\" src=\""+image+"\" alt=\"\"/>");
                        }
                        
                        //остаток после картинки
                        $("[data-post-id="+d_id+"]").append("<a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"post-text\">"+description+"</p>");
                        
                        //показать все
                        if(fl){
                            $("[data-post-id="+d_id+"]").append("<div class=\"show_all\">Показать все</div><p class=\"last_part\">"+last_part+"</p>");
                        }
                        
                        //после показать все
                        $("[data-post-id="+d_id+"]").append("<p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
                    }
                    else{
                        $("#newsContent").append("<div data-post-id="+d_id+" class=\"post\">");
                            
                            //цепляем круги
                            $("[data-post-id="+d_id+"]").append('<div class=\"circles\">');
                            if(Tag != null){
                                $("[data-post-id="+d_id+"] div.circles").append("<div class=\""+Tag+"\"></div>");
                            }
                            $.each(AllCollors, function(i_colors, single_color) {
                                if(Tag != single_color){
                                    $("[data-post-id="+d_id+"] div.circles").append("<div class=\""+single_color+"\"></div>");
                                }
                            });
                            $("[data-post-id="+d_id+"]").append('</div>');
                            
                            //цепляется иконка соц сети
                            if(SearchType == 'v'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"vk post-icon\">Q</span></a>");
                            }
                            else if(SearchType == 't'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"twitter post-icon\">R</span></a>");
                            }//if facebook
                            else if(SearchType == 'f'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"facebook post-icon\">S</span></a>");                 
                            }
                            else if(SearchType == 'i'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"info post-icon\">Y</span></a>");
                            }
                            else if(SearchType == 'y'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"yandex post-icon\">Я</span></a>");
                            }
                            else if(SearchType == 'lj'){
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"lj post-icon\">M</span></a>");
                            }
                            else{
                                $("[data-post-id="+d_id+"]").append("<a href=\""+ch_social+"\" title=\"Ссылка на первоисточник\"  target=\"_blank\"><span  class=\"google post-icon\">V</span></a>");                 
                            }
                            
                        //все остальное до картинки
                        $("[data-post-id="+d_id+"]").append("<span class=\"hide_post post-icon\" title=\"Скрыть запись\">E</span><span  class=\"post-date2\" title=\"Время публикации\">"+date_public+"</span>");
                        
                        //картинка
                        if(image != null){
                            $("[data-post-id="+d_id+"]").append("<img  class=\"hide post-img\" src=\""+image+"\" alt=\"\"/>");
                        }
                        
                        //остаток после картинки
                        $("[data-post-id="+d_id+"]").append("<a href=\"?ctrl=news&act=SpecificPostHome&id="+d_id+"\"><h2 id=\"postTitle\" class=\"title_hidden post-h2 h2\">"+title+"</h2></a><p id=\"postContent\" class=\"hide post-text\">"+description+"</p>");
                        
                        //показать все
                        if(fl){
                            $("[data-post-id="+d_id+"]").append("<div class=\"hide show_all\">Показать все</div><p class=\"last_part\">"+last_part+"</p>");
                        }
                        
                        //после показать все
                        $("[data-post-id="+d_id+"]").append("<p  class=\"post_bottom\">Район: "+distr_str+", cтоп-слово: "+sw+"</p>");
              
                    }

            });

            $.post("ajax.php",{GetCountOfNews: 'set'},function(ajax_data){
                posts_count =  $("#newsContent div.post").length;

                if(posts_count < ajax_data){
                    $("#newsContent").children().last().after($("#more_news"));
                }//if
                else{
                   $("#more_news").remove();
                }//else
            });



        });
    
    });
    
    //Добавить район в "Районы"
    $("#AddDistrict").click(function(){
        
        new_district_title = new String($("#NewDistrictTitle").val());
        new_district_title = new_district_title.trim();
        
        if(new_district_title.length != 0){
            
            $.post("ajax.php",{ADD_DISTRICT: 'SET',District: new_district_title},function(data){
                if(data == "inserted"){
                    ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Район добавлен','success');
                    $("#DistrictSectionConfirm").children().last().addClass("srch_success");
                    $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);    
                    $("#districts_order").append("<div><li data-district-id=\""+data+"\" class=\"chng_distr_li\">"+new_district_title+"<span class=\"remove_district chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>");
                }//if
                else if(data == "exist"){
                    ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Такой район уже есть','error');
                    $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                    $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                }//else
                else{
                    ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Ошибка на сервере','error');
                    $("#DistrictSectionConfirm").children().last().addClass("srch_error");
                    $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
                }//else
            });
            
            
           
        }//if
        else{
            ShowPersonalRoomMessage($("#DistrictSectionConfirm"),'Поле не может быть путым','error');
             $("#DistrictSectionConfirm").children().last().addClass("srch_error");
             $("#DistrictSectionConfirm").children().last().delay(2000).fadeOut(500);
        }//else
        
    });
    
    //Добавить стопслово в "Районы"
    $("#AddStopWord").click(function(){
        
        new_stop_word = new String($("#NewStopWord").val());
        new_stop_word = new_stop_word.trim();
        
        if(new_stop_word.length != 0){
            
            $.post("ajax.php",{ADD_STOP_WORD: 'SET',stop_word: new_stop_word},function(data){
                
                if(data == "inserted"){
                    ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Стоп слово добавлено','success');
                    $("#StopWordSectionConfirm").children().last().addClass("srch_success");
                    $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                    $("#stopWords ul.district").append("<li>"+new_stop_word+"</li>");
                }//if
                else if(data == "exist" || data == "not inserted"){
                    ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Такое стоп слово уже есть','error');
                    $("#StopWordSectionConfirm").children().last().addClass("srch_error");
                    $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                }//else
                else{

                    ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Ошибка на сервере','error');
                    $("#StopWordSectionConfirm").children().last().addClass("srch_error");
                    $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
                }//else
            });
           
        }//if
        else{
             ShowPersonalRoomMessage($("#StopWordSectionConfirm"),'Стоп слово не может быть путым','error');
             $("#StopWordSectionConfirm").children().last().addClass("srch_error");
             $("#StopWordSectionConfirm").children().last().delay(2000).fadeOut(500);
        }//else
        
    });

    //поиск по стопсловам
    $("#search_news_by_stop_words").click(function(){
        
        //Взять все районы
        $('#newsContent').children().remove();
        
        sessionStorage['selected_li']='';
        var obj;
        var no_AroundSelectedBranch;
        
        if($("li[class='jqtree_common jqtree-folder jqtree-selected']").length !=0){
            
            obj = $("li[class='jqtree_common jqtree-folder jqtree-selected']");
            no_AroundSelectedBranch = false;
            
        }else if($("li[class='jqtree_common jqtree-selected']").length !=0){
            
            obj = $("li[class='jqtree_common jqtree-selected']");
            no_AroundSelectedBranch = true;
            
        }else if($("li[class='jqtree_common jqtree-folder jqtree-closed jqtree-selected']").length !=0){
            
            obj = $("li[class='jqtree_common jqtree-folder jqtree-closed jqtree-selected']");
            no_AroundSelectedBranch = false;
        }

        sessionStorage['selected_li']=$(obj).children().first().children().last().text() + ";";
        
        if(!no_AroundSelectedBranch){
            for (var i=0; i<$(obj).children().last().children().length; i++){

                if ( $($(obj).children().last().children()[i]).hasClass('jqtree-folder')== false ){

                    var parent_title = $($(obj).children().last().children()[i]).text();
                    parent_title = parent_title.replace('►','');
                    parent_title = parent_title.replace('▼','');
                    //alert(parent_title);
                    sessionStorage['selected_li']+=parent_title +';';

                }
            }
            AroundSelectedBranch($(obj).children().last());
        }    
        //alert(sessionStorage['selected_li']);        
    //
        $("#loader_dis").fadeIn(300);
        $("#search-panel").fadeOut(200);
        $fl=false;
        $("#minimize").text('+');
        
        $("#search_news_by_stop_words").blur();
        
        

        stop_word = $("#STOP_WORD").text();
        if(stop_word == 'Стоп-слова'){stop_word=''}
        $(location).attr('href', "#районы: "+sessionStorage['selected_li']+" &стоп-слова: "+stop_word);

        
        stop_word = $("#STOP_WORD").text();
        var session_districts = sessionStorage['selected_li'].split(';');


        $("#ForMsg").empty();
        
        
        
        
        
        for(var i = 0;i < session_districts.length-1;i++){
            GetNewsByDistrictOrStopWord(session_districts[i],stop_word);
        }
        
     });
        
    //Выбор района и установка его имни в блоке
    $("#districts ul.district li").click(function(){
        
            $("#districts h2.h2-distr").text($(this).text());
            $("#newsContent div.post").remove();
            $("#more_news_by_stop_words").css("display","none");
            
    });
    
    $("#stopWords ul.district li").click(function(){
        
            $("#STOP_WORD").text($(this).text());
            $("#newsContent div.post").remove();
            $("#more_news_by_stop_words").css("display","none");
            
    });
        $fl = true;
        //Сокрытие панели поиска в "райнонах"
        $("#minimize").click(function(){
            if($fl){
                $("#search-panel").fadeOut(200);
                $fl=false;
                $("#minimize").text('+');
            }
            else
            {
                $("#search-panel").fadeIn(200);
                $fl=true;
                $("#minimize").text('─');
            }
        });
        //Изменение имэйла в личном кабинете
        $("#ConfirmEmail").click(function(){
            new_mail = new String($("#NewMailInPersonal").val());
            new_mail = new_mail.trim();
            
            if(new_mail.length == 0){
                ShowPersonalRoomMessage($("#emailSection"),'Поле не может быть пустым','error');
                $("#emailSection").children().last().delay(2000).fadeOut(300);
            }//if
            else if(new_mail.indexOf('@') == -1){
                ShowPersonalRoomMessage($("#emailSection"),'E-mail должен содержать символ @','error');
                $("#emailSection").children().last().delay(2000).fadeOut(300);
            }//if
            else{
                
                $.post("ajax.php",{EmailSuccess: 'set', NewPersonalMail: new_mail , Owner:  $("#login").text()},function(data){
                    
                    if(data == "ok"){
                        ShowPersonalRoomMessage($("#emailSection"),'Изменения успешно внесены!','success');
                        $("#emailSection").children().last().delay(2000).fadeOut(300);
                    }//if
                    else{
                        ShowPersonalRoomMessage($("#emailSection"),'Ошибка на сервере!','error');
                        $("#emailSection").children().last().delay(2000).fadeOut(300);
                    }//else
                    
                });
                
            }//else
            
        });
        //Изменение пароля в личном кабинете
        $("#ConfirmPassword").click(function(){
            
            if($("#CurrentPassword").val()){
                
                $.post("ajax.php",{CheckPassword: 'set', Owner: $("#login").text(), UserPassword: $("#CurrentPassword").val()},function(data){
                    if(data == "password_correct"){//Password is correct
                       
                       if($("#FirstPassword").val() && $("#SecondPassword").val()){//Not empty first pass and second pass
                           
                           if($("#FirstPassword").val() == $("#SecondPassword").val()){//Passwords equals
                               
                               if($("#FirstPassword").val().length > 6){//Pass length > 6
                                   
                                   $.post("ajax.php",{ChangePassword: 'set',NewPassword: $("#FirstPassword").val(), Owner: $("#login").text() },function(data){
                                       
                                       if(data == "ok"){
                                           ShowPersonalRoomMessage($("#PasswordSection"),'Пароль успешно изменен','success');
                                           $("#PasswordSection").children().last().delay(2000).fadeOut(300);
                                       }//if
                                       else{
                                           ShowPersonalRoomMessage($("#PasswordSection"),'Ошибка сервера','error');
                                           $("#PasswordSection").children().last().delay(2000).fadeOut(300);
                                       }//else
                                       
                                   });
                                   
                               }//if
                               else{
                                   ShowPersonalRoomMessage($("#PasswordSection"),'Длина нового пароля должна быть больше 6-ти символов','error');
                                    $("#PasswordSection").children().last().delay(2000).fadeOut(300);
                                }//else
                           }//if
                           else{
                               ShowPersonalRoomMessage($("#PasswordSection"),'Пароли не совпадают','error');
                               $("#PasswordSection").children().last().delay(2000).fadeOut(300);
                           }//else
                       }//if
                       else{
                           ShowPersonalRoomMessage($("#PasswordSection"),'Заполните поля с новым паролем','error');
                           $("#PasswordSection").children().last().delay(2000).fadeOut(300);
                       }//else
                       
                    }//if
                    else{
                        ShowPersonalRoomMessage($("#PasswordSection"),'Текущий пароль указан не верно','error');
                        $("#PasswordSection").children().last().delay(2000).fadeOut(300);
                    }//else
                });//post
                
            }//if
            else{
                ShowPersonalRoomMessage($("#PasswordSection"),'Укажите текущий пароль пользователя','error');
                $("#PasswordSection").children().last().delay(2000).fadeOut(300);
            }//else
        });
        //Изменение имени в личном кабинете
        $("#ConfirmName").click(function(){
            
            new_name = new String($("#NewFirstName").val());
            new_name = new_name.trim();
            
            if(new_name.length != 0){
                
               $.post("ajax.php",{ChangeFirstName: 'set', Owner: $("#login").text(), NewFirstName: $("#NewFirstName").val()}, function(data){
                   
                   if(data == "ok"){
                       ShowPersonalRoomMessage($("#FirstNameSection"),'Имя успешно изменено','success');
                       $("#FirstNameSection").children().last().delay(2000).fadeOut(300);
                   }//if
                   else{
                       ShowPersonalRoomMessage($("#FirstNameSection"),'Ошибка сервера','error');
                       $("#FirstNameSection").children().last().delay(2000).fadeOut(300);
                   }
               });
            }//if
            else{
                ShowPersonalRoomMessage($("#FirstNameSection"),'Поле не может быть пустым','error');
                $("#FirstNameSection").children().last().delay(2000).fadeOut(300);
                
               // $("#FirstNameSection").children().last().empty();

            }//else
            
        });
        //Изменение Фамилии в личном кабинете
        $("#ConfirmLastName").click(function(){
            new_last_name = new String($("#NewLastName").val());
            new_last_name = new_last_name.trim();
            
            if(new_last_name.length != 0){
                
               $.post("ajax.php",{ChangeLastName: 'set', Owner: $("#login").text(), NewLastName: $("#NewLastName").val()}, function(data){
                   
                   if(data == "ok"){
                       ShowPersonalRoomMessage($("#LastNameSection"),'Фамилия успешно изменена','success');
                       $("#LastNameSection").children().last().delay(2000).fadeOut(300);
                   }//if
                   else{
                       ShowPersonalRoomMessage($("#LastNameSection"),'Ошибка сервера','error');
                       $("#LastNameSection").children().last().delay(2000).fadeOut(300);
                   }
               });
            }//if
            else{
                ShowPersonalRoomMessage($("#LastNameSection"),'Поле не может быть пустым','error');
                $("#LastNameSection").children().last().delay(2000).fadeOut(300);
            }//else
            
        });
        
        //Анимация блока с редактированием стопслов и районов
        $("span.correct_js").click(function(){
            if($(this).parent().next().css('height')=='0px'){
                
                if($(this).parent().next().hasClass("password-chng")){
                    $(this).parent().next().css({display: "block"}).animate({height: "144px"},300);
                }
                else{
                    $(this).parent().next().css({display: "block"}).animate({height: "48px"},200);
                }

            }
            else
            {
                
                if($(this).parent().next().hasClass("password-chng")){
                    $(this).parent().next().animate({height: "0px"},300);
                }
                else{
                    $(this).parent().next().animate({height: "0px"},200);
                }
               
            }
        });
        
        var b = true; 
        //Редактирование СтопСлов и Плохих слов
        $('body').on('click','span.chnd_distr_ok',function(){
            
             if($(this).hasClass("add_bw_ok")){
                
                word = new String($(this).prev().val());
                word = word.trim();
                
                if(word.length != 0){
                    $.post('ajax.php',{HIDE_ALL_NEWS: 'set', word: word},function(data){
                        if(data == "ok"){
                            window.location = "?ctrl=news&act=news";
                        }
                        else{
                            ShowPersonalRoomMessage($("#add_bw"),data,'error');
                        }
                        
                    });
                }//if
                else{
                    ShowPersonalRoomMessage($("#add_bw"),'Заполните поле','error');
                }
                
            }//if
            else if($(this).hasClass("word")){
                
                elem = $(this).parent().parent();
                elem_to_change = $(this).parent().parent();

                word_to_update = $(this).parent().prev().data("word-id");
                new_stop_word = new String($(this).prev().val());
                new_stop_word = new_stop_word.trim();
                
                if(new_stop_word.length != 0){
                    $.post("ajax.php",{CKECK_WORD: 'SET',word: new_stop_word},function(data){
                        if(data == "ok"){

                        $.post('ajax.php',{UPDATE_WORD: 'set',word_id: word_to_update, new_word: new_stop_word},function(data){
    
                            if(data == "ok"){

                                 $(elem).first().html('<div><li data-stop-id=\"'+word_to_update+'\" class="chng_distr_li">'+new_stop_word+'<span class="chng_distr_correct correct" title="Изменить">M</span></li><div class="hg_null"><input type="text" class="chng_distr_inp pers-input" placeholder="Редактирование стоп слова"><span id="ConfirmName" class="chnd_distr_ok ok" title="Подтвердить изменения">N</span></div><div>');
                                 $(elem).append("<div class=\"srch_success pers-success\"><h2 class=\"h2\">Изменения успешно внесены</h2></div>");
                                 $(elem).children().last().delay(2000).fadeOut(500);
                            }//if
                            else{
                                $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такое стоп-слово есть</h2></div>");                              
                                $(elem).children().last().delay(2000).fadeOut(500);
                            }
                        });
                        }//if
                        else{
                            $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такое стоп-слово есть</h2></div>");                    
                            $(elem).children().last().delay(2000).fadeOut(500);
                        }


                    });
                }//if length not 0
                else{
                    $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Новое слово не может быть пустым<h2></div>");                    
                    $(elem).children().last().delay(2000).fadeOut(500);
                }
                
            }
            else if(!$(this).hasClass("dis")){
            elem = $(this).parent().parent();
            elem_to_change = $(this).parent().parent();
            
            word_to_update = $(this).parent().prev().data("stop-id");
            new_stop_word = new String($(this).prev().val());
            new_stop_word = new_stop_word.trim();
            if(new_stop_word.length != 0){
                $.post("ajax.php",{CHECK_STOP_WORD: 'SET',stop_word: new_stop_word},function(data){
                
                    if(data == "ok"){
                    $.post('ajax.php',{UPDATE_STOP_WORD: 'set',stop_id: word_to_update, new_word: new_stop_word},function(data){
                        if(data == "ok"){
                            
                             $(elem).first().html('<div><li data-stop-id=\"'+word_to_update+'\" class="chng_distr_li">'+new_stop_word+'<span class="chng_distr_correct correct" title="Изменить">M</span></li><div class="hg_null"><input type="text" class="chng_distr_inp pers-input" placeholder="Редактирование стоп слова"><span id="ConfirmName" class="chnd_distr_ok ok" title="Подтвердить изменения">N</span></div><div>');
                             $(elem).append("<div class=\"srch_success pers-success\"><h2 class=\"h2\">Изменения успешно внесены</h2></div>");
                             $(elem).children().last().delay(2000).fadeOut(500);
                        }//if
                        else{
                            $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такое стоп-слово есть или пустое поле</h2></div>");                              
                            $(elem).children().last().delay(2000).fadeOut(500);
                        }
                    });
                    }//if
                    else{
                        $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такое стоп-слово есть</h2></div>");                    
                        $(elem).children().last().delay(2000).fadeOut(500);
                    }
               
                
                });
            }//if length not 0
            else{
                $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Поле не может быть пустым<h2></div>");                    
                $(elem).children().last().delay(2000).fadeOut(500);
            }
            
             }//if has not class dis
        });
        //Редактирование районов
        $('body').on('click','span.dis',function(){
             
            if($(this).hasClass("dis")){
                
                elem = $(this).parent().parent();
                dist_id = $(this).parent().prev().data("district-id");
                new_district_title = $(this).prev().val();
                new_district_title = new  String (new_district_title);
                new_district_title = new_district_title.trim();
                
                if( new_district_title.length != 0){
                     $.post("ajax.php",{CHECK_DISTRICT: 'SET',district: new_district_title},function(data){

                        if(data == "ok"){
                        $.post('ajax.php',{UPDATE_DISTRICT: 'set',new_district_title: new_district_title, district_id: dist_id},function(data){
                            if(data == "ok" && new_district_title != ''){
                                 $(elem).first().html('<div><li data-district-id=\"'+dist_id+'\" class=\"chng_distr_li\">'+new_district_title+'<span class=\"chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Редактирование района\"><span id=\"ConfirmName\" class=\"dis chnd_distr_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
                                 $(elem).append("<div class=\"srch_success pers-success\"><h2 class=\"h2\">Изменения успешно внесены</h2></div>");
                                 $(elem).children().last().delay(2000).fadeOut(500);
                            }//if
                            else{
                                $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такое район есть или пустое поле</h2></div>");                              
                                $(elem).children().last().delay(2000).fadeOut(500);
                            }
                        });
                        }//if
                        else{
                            $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Такое район уже есть</h2></div>");                    
                            $(elem).children().last().delay(2000).fadeOut(500);
                        }


                    });
                }
                else{
                     $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Поле не может быть пустым</h2></div>");                    
                     $(elem).children().last().delay(2000).fadeOut(500);
                }
             }//if has not class dis
        });
        
        //Удаление значки
        $('body').on('click','span.chng_distr_correct',function(){
            
            if($(this).hasClass('remove_group')){
                
                el = $(this);
                
                group_id = $(this).parent().data('group-id');
                
                $.post('ajax.php',{REMOVE_GROUP:'set',group_id: group_id  },function(data){
                    if(data == "ok"){
                        $(el).parent().parent().remove();
                        
                        if($('#GroupsOrder').children().length == 0){
                            $('#GroupsOrder').append("<h2 id='NotFoundAnyGroup' class=\"h2\">Не найдено ни одной группы для поиска</h2>");
                        }
                    }
                    else{
                        alert(data);
                    }
                });
                
            }//if
            else if($(this).hasClass('remove_user')){
                
                el = $(this);
                
                user_id = $(this).parent().data('user-id');

                RemoveInfoPulseUser(user_id);
            }
            else if($(this).hasClass('remove_word')){
                word_id = $(this).parent().data('word-id');
               
                el = $(this);
                $.post('ajax.php',{DELETE_BAD_WORD: 'set', word_id: word_id},function(data){
                    if(data == 'ok'){
                        $(el).parent().parent().remove();
                    }//if
                    else{
                        alert(data);
                    }
                });
            }//if
            else if($(this).hasClass('remove_social')){
                el = $(this);
                
                social_id = $(this).parent().data('social-id');

                RemoveSocial(social_id,el);
                
            }//remove social
            else if($(this).hasClass('remove_user_hub')){
                
                acc_id = $(this).data('acc-id');
                RemoveAcc(acc_id);
                
                $(this).parent().remove();   
                
            }//remove social
            else if($(this).parent().next().css('height') == "0px"){
                $(this).parent().next().css({display: "block"}).animate({height: "44px"},200);
            }//if
            else
            {
                $(this).parent().next().animate({height: "0px"},200);                
                
            }//else            
        });
        //Удаление поста
        window.onbeforeunload = function() {
            
            $("div.delete-post:contains(\"N\")").each(function(i,e){
               
               var post_id = $(this).data('post-id');
               
                $.post("ajax.php",{DeleteMyNews: 'set',post_id: post_id},function(data){
                
                  if(data == '1'){
                     
                    var el_count = $("div.delete-post").length;
                     
                     if(el_count == 0){
                         
                        $("#newsContent").append("<h2 class=\"post-h2 h2\" style=\"margin: 15px 0px\">У вас пока нет записей!</h2>");
                        
                     }//if
                     
                  }//if deleted succesful
                  
                  else {alert("Ошибка сервера! Не возможно удалить запись!data = " + data);}
                  
                });
                
            });
            
        };
        //Анимация удаления
        $("div.delete-post").click(function(){
            
            if($(this).text() == "J"){
                
                $(this).text("N");
                $(this).attr('title',"Восстановить");
                $(this).parent().animate({opacity: 0.5},200);
                
            }
            else{
                $(this).text("J");
                $(this).attr('title',"Удалить");
                $(this).parent().animate({opacity: 1},200);
                
            }
        });
        //добавление записи
        $("#addPost").click(function(){
            
            if($("#postTitle").val() && $("#makePostArea").val()){
                $("#NewPostForm").submit();
            }//if not empty post field
            else if($("#postTitle").val().length > 97){
                ShowPostMessage("Длина заголовка слишком велика!");
            }//else if
            else{
                ShowPostMessage("Поля не должны быть пустыми!");
            }
        });
        //Авторизация
        $('#Authorise').click(function(){
          
        if ($('#userPS').val() && $('#userLE').val()){ //if not empty
                 
                $.post("ajax.php",
                {
                    authorize: 'set',
                    userLE: $('#userLE').val(),
                    userPS: $('#userPS').val()

                },function (data){
                    
                     if(data == "yes"){//
                         $('#AuthoriseForm').submit();
                     }//if 
                     else{
                         ShowAuthorizeMessage('Неверный логин или пароль');
                         
                     }//else

                });
                
        }//end if empty
        else{
            
            ShowAuthorizeMessage('Есть пустые поля');
        }      
            
            
        });
        //регистрация на welcome.php
        $('#register').click(function(){
            
           if($('#RLogin').val() && $('#RMail').val() && $('#RPass').val()){
               
               user_login = new String($('#RLogin').val());
               user_login = user_login.trim();
               if( user_login.match(/[а-яА-Я !#@\'\\\/\"$?&^*(){}\[\]<>`.,:;]/g)){
                   ShowRegisterMessage('Логин должен содержать только латинские символы');
               }//if user login contains space
               else{
                   mail = new String($('#RMail').val());
                   mail = mail.trim();
                   
                   pass = new String($('#RPass').val());
                   pass = pass.trim();

                   $.post("ajax.php",{

                        fastregister: 'set',
                        userLogin: user_login,
                        userEmail: mail

                    },function(data){

                   if(data == "used_login"){

                       ShowRegisterMessage('Такой логин уже есть');

                   }//if
                   else if(data == "used_email"){

                       ShowRegisterMessage('Такой email уже используется!');

                   }//else if
                   else if(mail.indexOf('@') == -1){

                       ShowRegisterMessage('Email должен содержать @');

                   }//else if
                   else if(pass.length < 7){

                       ShowRegisterMessage('Длина пароля слишком мала');

                   }//else if
                   else if(pass.length > 50){
                       ShowRegisterMessage('Длина пароля слишком велика');
                   }//else if
                   else if(data == "acc_free"){

                       $("#registerForm").submit();

                   }//else if
                   else{
                      ShowRegisterMessage('Server error');
                   }//else

                }); 
                
               }//else
               
            
           }//if not empty
           else{
               
               ShowRegisterMessage('Есть пустые поля!');
               
           }//else
            
        });
        
        //Полная регистрация 
        $("#registerNewUser").click(function(){
            
            if($("#newUserLogin").val() && $("#newUserLogin").val().length < 50){
                
                
                $.post("ajax.php",{newUserLogin: $("#newUserLogin").val(), mainregister: 'set' },function(data){
                    
                    
                    if(data == "used_login"){
                        
                        ShowRegisterMessage('Указанный логин уже используется');
                        
                    }//if
                    else{
                            
                        if($("#newMail").val()){
                            
                            var mail = new String($("#newMail").val());

                            if(mail.indexOf('@') == -1){

                                ShowRegisterMessage('E-mail должен содержать @');

                            }//if
                            else{
                                
                                $.post("ajax.php",{
                                newUserLogin: $("#newUserLogin").val(),
                                newMail: $("#newMail").val(),
                                mainregister: 'set'
                            },function(data){
                                
                                if(data == "used_email"){

                                    ShowRegisterMessage('Указанный e-mail уже используется');

                                }//if
                                else if(!$("#userPS").val() || $("#userPS").val().length < 7 || $("#userPS").val() != $("#userPS2").val() ){
                                            ShowRegisterMessage('Пароли не совпадают или слишком малы');
                                }//else pass
                                else if(!$("#NewFirstName").val()){
                                    ShowRegisterMessage('Поле имя должно быть заполненно');
                                }//else

                                else if(!$("#NewLastName").val()){
                                    ShowRegisterMessage('Поле фамилия должно быть заполненно');
                                }//else
                                else{
                                    $("#registerForm").submit();
                                }//else ok
                            
                        });//post email
                                
                            }//else
                   
                 
            }//if email
            else{
                ShowRegisterMessage('Поле e-mail должно быть заполненно');
            }//else empty email 
                        
            }//else 
                           
                
            });//post.login
                
        }//if login
        else{
                
                ShowRegisterMessage('Поле логин должно быть заполненно');
                
        }//else empty login
            
            
        });//register click

});
