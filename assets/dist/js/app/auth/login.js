$(document).ready(function(){
    $('form#login input').on('change', function(){
        $(this).parent().removeClass('has-error');  
        $(this).next().next().text('');
    });
    $('#admin').click(function () {
        $("input[name=identity]").val('admin@mail.com');
        $("input[name=password]").val('Password@123');
                                  });
    $('#teacher').click(function () {
        $("input[name=identity]").val('andrew@mail.com');
        $("input[name=password]").val('76512309');
                                  });
    $('#student').click(function () {
        $("input[name=identity]").val('atef@mail.com');
        $("input[name=password]").val('989348912');
                                  });
    
    $('form#login').on('submit', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var infobox = $('#infoMessage');
        infobox.addClass('callout callout-info').text('Checking...');

        var btnsubmit = $('#submit');
        btnsubmit.attr('disabled', 'disabled').val('Wait...');

        $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(data){
            infobox.removeAttr('class').text('');
            btnsubmit.removeAttr('disabled').val('Login');
            if(data.status){
                infobox.addClass('callout callout-success text-center').text('Login Success');
                var go = base_url + data.url;
                window.location.href = go;
            }else{
                if(data.invalid){
                    $.each(data.invalid, function(key, val){
                    $('[name="'+key+'"').parent().addClass('has-error');
                    $('[name="'+key+'"').next().next().text(val);
                    if(val == ''){
                        $('[name="'+key+'"').parent().removeClass('has-error');  
                        $('[name="'+key+'"').next().next().text('');
                    }
                    });
                }
                    if(data.failed){
                        infobox.addClass('callout callout-danger text-center').text(data.failed);
                    }
                }
            }
        });
    });
});