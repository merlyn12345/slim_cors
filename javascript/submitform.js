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
    $("#submitbutton").bind("click", function (event) {

        event.preventDefault();

        const form = $('#submitform')[0];

        // Create an FormData object
        const data = new FormData(form);

        // disabled the submit button
        $("#submitbutton").prop("disabled", true);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/secure/submit",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {

                $("#output").text(data);
                console.log("SUCCESS : ", data);
                $("#submitbutton").prop("disabled", false);

            },
            error: function (e) {

                $("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#submitbutton").prop("disabled", false);

            }
        });

    });
});