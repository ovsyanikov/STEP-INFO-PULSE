

$(document).ready(function(){
   
   $('#ChangeNewPassword').click(function(){

       at = new String( $('#AccessToken').val() );
       firstPass = new String( $('#NewPassword').val());
       newPass = new String( $('#ConfirmNewPassword').val() );

       if($('#NewPassword').val() == $('#ConfirmNewPassword').val()){
           
            if(firstPass.length >= 8){
                
                if(! firstPass.match(/[а-яА-Я !#@\'\\\/\"$?&^*(){}\[\]<>`.,:;]/g)){
                   ChangePassword(at,newPass);
                }//if user login contains space
                else{
                    ShowPersonalRoomMessage($('#recoveryDIV'),'Пароль должен содержать только латинские буквы и цифры','error');
                }
                
            }//if
            else{
                ShowPersonalRoomMessage($('#recoveryDIV'),'Длина нового пароля должна быть более 8-ми символов','error');
            }//else
           
            
       }//if
       else{
           ShowPersonalRoomMessage($('#recoveryDIV'),'Введенные пароли не совпадают','error');
       }
      
       
   });
   
   $('#ResetPassword').click(function(){
       userEmail = new String($('#userEmailLogin').val());
       userEmail = userEmail.trim();
       
       if(userEmail.length != 0){
           GeneratePassword(userEmail);
       }
       else{
            ShowChangeMessage($('#recoveryDIV'),'Поле Email - пустое','error');
       }
       
   });
    
    
});
//SELECT NOW( ) + INTERVAL 1 DAY
function ShowPersonalRoomMessage(controll,message,type){
   
    if(type == "success"){
        
        $("#" + $(controll).attr('id')).children(".pers-error").remove();
        
        if($("#" + $(controll).attr('id')).children().last().attr("class") == "pers-success"){
            $("#" + $(controll).attr('id')).children().last().html("<h2 class=\"h2\">"+message+"</h2>");;
        }
        
        else{
            $("#" + $(controll).attr('id')).append("<div class=\"pers-success\"><h2 class=\"h2\">"+message+"</h2></div>");
        }//else
        $(controll).children().last().fadeOut(0).fadeIn(300).delay(2000).fadeOut(500); 
    }//if
    else{
        
        $("#" + $(controll).attr('id')).children(".pers-success").remove();
        
        if($("#" + $(controll).attr('id')).children().last().attr("class") == "pers-error"){
            $("#" + $(controll).attr('id')).children().last().html("<h2 class=\"h2\">"+message+"</h2>");
        }//if
        else {
            $("#" + $(controll).attr('id')).append("<div class=\"pers-error\" style='margin: 0 auto; width: 92%;'><h2 class=\"h2\">"+message+"</h2></div>");
        }//else
        $(controll).children().last().fadeOut(0).fadeIn(300).delay(2000).fadeOut(500);
       
    }//if
}

function ChangePassword(accessToken,newPassword){
    
    $.post('ajax.php',{CHANGE_PASSWORD: 'set',access_token: accessToken, user_password: newPassword},function(data){

        if(data == "ok"){
            ShowPersonalRoomMessage($('#recoveryDIV'),'Пароль успешно изменен','error');
             window.location = "?ctrl=start&act=welcome";
        }
        else{
            ShowPersonalRoomMessage($('#recoveryDIV'),'Не верно указан полученный код',error);
        }
        
    });
    
}



function GeneratePassword(mail){
    
    $.post('ajax.php',{GENERATE_PASSWORD: 'set',user_mail: mail},function(data){

        if(data == "ok"){
            
            window.location = "?ctrl=user&act=ChangePassword";
            
        }
        else if(data == 'already'){
            window.location = "?ctrl=user&act=ChangePassword";
        }
        else{
            ShowPersonalRoomMessage($('#recoveryDIV'),'Неверно указан Email','error');

        }
    });
    
}