jQuery(document).ready(function($){
    $('.signature').on('input', function(){
        var text = $(this).val();
        var imagePath = $('.signed-efc img').attr('data-src');
        imagePath += "?name=" + text;
        $('.signed-efc img').attr({'src':imagePath});
        $('.download-link').attr({'href':imagePath});
        if(text.length > 0){
            $('.download-link').removeAttr('disabled');
        }else{
            $('.download-link').attr({'disabled':true});
        }
    })
})