
$(document).ready(function(){
    $(".thumbnail img").click(function(){
        var plaatje = $(this).attr("src");
        $("#product_view_img").attr("src",plaatje);
    })
})