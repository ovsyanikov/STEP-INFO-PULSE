function GetNewsById(id){
    
    var arrayJS = {'method': 'GetNewsById', 'params':[id]};
    
    $.ajax({
        
            type: 'POST',
            url: 'vingardiumAJAX.php',
            dataType: 'json',
            data: arrayJS,
            success: function(data){
                 $('#NewsTitle').text(data.title);
                 $('#NewsDescription').text(data.description);
                 $('#SpecialNews').children('#RemoveNews').remove();
            },
            error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.responseText);
                alert(thrownError); 
            }
            
        });
    
    
}

$(document).ready(function(){
    
    $('#FindGlobalNews').click(function(){
        news_id = $('#NewsId').val();
        GetNewsById(news_id);
        
    });
    
});