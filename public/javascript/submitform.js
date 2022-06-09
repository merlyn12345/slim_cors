$(document).ready(function(){
    $('#kategorie option').bind('click', function(){
        $.get('/secure/selectdata/'+$(this).val(), function (response){
            if(response){
               if($('#item').length > 0){
                    $('#item').remove();
               }
               $(response).insertAfter('#kategorie');
            }
        });
    });
});