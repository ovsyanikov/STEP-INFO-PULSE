$(document).ready(function(){
//Добавить вк юзера для поиска
    $("#AddNewFbUser").click(function(){
        
        new_user_title = new String($("#NewFbUser").val());
        new_user_title = new_user_title.split(',');
        for (i=0; i<new_user_title.length; i++){

            new_user_title[i] = new_user_title[i].trim();
            
            if(new_user_title[i].length != 0){
 
                $.post("ajax.php",{ADD_FB_USER: 'SET',USER: new_user_title[i]},function(data){
                    if(data != "exist" && data != "not inserted"){
                        $('#NotFoundAnyUsers').remove();
                        ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Пользователь добавлен','success');
                        $("#UsersSectionConfirm").children().last().addClass("srch_success");
                        $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
                        $("#UsersOrder").append('<div><li data-user-id=\"'+data+'\" class=\"chng_distr_li\">'+new_user_title+'<span class=\"remove_user_facebook chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"user chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Изменение пользователя\"><span id=\"ConfirmName\" class=\"user chnd_vk_users_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
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
            else{
                ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Поле не может быть пустым','error');
                $("#UsersSectionConfirm").children().last().addClass("srch_error");
                $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
            }
            
                

    }
    }); 
    
    $('body').on('click','.chnd_fb_users_ok',function(){
        
            box = $(this).parent();
            elem = $(this).parent().prev();
            new_group_title = new String($(this).prev().val()); 
            group_id = $(this).parent().prev().data('user-id');
            
            new_group_title = new_group_title.trim();
             
            if(new_group_title.length != 0){
                
                $.post("ajax.php",{UPDATE_FB_USER: 'SET', group_id: group_id, group_new_title: new_group_title},function(data){
                
                    if(data == "ok"){

                        $('[data-user-id="'+group_id+'"]').text(new_group_title);
                        $('[data-user-id="'+group_id+'"]').append('<span class="user chng_distr_correct correct" title="Изменить">M</span>');
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
                $(elem).append("<div class=\"srch_error pers-error\"><h2 class=\"h2\">Поле не может быть пустым<h2></div>");                    
                $(elem).children().last().delay(2000).fadeOut(500);
            }

        });
        
        
    $("#AddNewTwitterUser").click(function(){
        
        new_user_title = new String($("#NewTwitterUser").val());
        new_user_title = new_user_title.split(',');
        for (i=0; i<new_user_title.length; i++){

            new_user_title[i] = new_user_title[i].trim();
            
            if(new_user_title[i].length != 0){
 
                $.post("ajax.php",{ADD_TWITTER_USER: 'SET',USER: new_user_title[i]},function(data){
                    if(data != "exist" && data != "not inserted"){
                        $('#NotFoundAnyUsers').remove();
                        ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Пользователь добавлен','success');
                        $("#UsersSectionConfirm").children().last().addClass("srch_success");
                        $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
                        $("#UsersOrder").append('<div><li data-user-id=\"'+data+'\" class=\"chng_distr_li\"><a href="https://twitter.com/'+new_user_title+'" title="Ссылка на первоисточник">'+new_user_title+'</a><span class=\"remove_user_twitter chng_distr_correct correct\" title=\"Удалить\">J</span><span class=\"user chng_distr_correct correct\" title=\"Изменить\">M</span></li><div class=\"hg_null\"><input id=\"\" type=\"text\" class=\"chng_distr_inp pers-input\" placeholder=\"Изменение пользователя\"><span id=\"ConfirmName\" class=\"user chnd_vk_users_ok ok\" title=\"Подтвердить изменения\">N</span></div></div>');
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
            else{
                ShowPersonalRoomMessage($("#UsersSectionConfirm"),'Поле не может быть пустым','error');
                $("#UsersSectionConfirm").children().last().addClass("srch_error");
                $("#UsersSectionConfirm").children().last().delay(2000).fadeOut(500);
            }
            
                

    }
    }); 
    
    $('body').on('click','.chnd_twitter_users_ok',function(){
        
            box = $(this).parent();
            elem = $(this).parent().prev();
            new_group_title = new String($(this).prev().val()); 
            group_id = $(this).parent().prev().data('user-id');
            
            new_group_title = new_group_title.trim();
             
            if(new_group_title.length != 0){
                
                $.post("ajax.php",{UPDATE_TWITTER_USER: 'SET', group_id: group_id, group_new_title: new_group_title},function(data){
                
                    if(data == "ok"){

                        $('[data-user-id="'+group_id+'"]').empty();
                        $('[data-user-id="'+group_id+'"]').html('<a href="https://twitter.com/'+new_group_title+'"  title="Ссылка на первоисточник">'+new_group_title+'</a>');
                        $('[data-user-id="'+group_id+'"]').append('<span class="user chng_distr_correct correct" title="Изменить">M</span>');
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

        });            
        
    $('body').on('click','span.chng_distr_correct',function(){
        
        if($(this).hasClass('remove_user_twitter')){
                
                el = $(this);
                
                user_id = $(this).parent().data('user-id');
                $.post('ajax.php',{REMOVE_TWITTER_USER:'set', user_id: user_id},function(data){
                    if(data == "ok"){
                        $(el).parent().parent().remove();
                        if($('#UsersOrder').children().length == 0){
                            $('#UsersOrder').append("<h2 id='NotFoundAnyUsers' class=\"h2\">Не найдено ни одного пользователя для поиска</h2>");
                        }
                    }
                    else{
                        alert(data);
                    }
                });
            }
            
        else if($(this).hasClass('remove_user_facebook')){
                
                el = $(this);
                
                user_id = $(this).parent().data('user-id');
                $.post('ajax.php',{REMOVE_FACEBOOK_USER:'set', user_id: user_id},function(data){
                    if(data == "ok"){
                        $(el).parent().parent().remove();
                        if($('#UsersOrder').children().length == 0){
                            $('#UsersOrder').append("<h2 id='NotFoundAnyUsers' class=\"h2\">Не найдено ни одного пользователя для поиска</h2>");
                        }
                    }
                    else{
                        alert(data);
                    }
                });
            }
    });
});
