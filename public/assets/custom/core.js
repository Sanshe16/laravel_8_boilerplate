/**
 * core js of Gifter app
 *
 */

var api = [];
var ajax_path = $('meta[name="server-path"]').attr('content');
var id = $('meta[name="auth-id"]').attr('content');

// globle button status function 
function button_status(element, handle = true, text = 'Loading',) {
    if (handle) {
        /* loading */
        element.data('text', element.html());
        element.prop('disabled', true);
        element.html('<span class="spinner-grow spinner-grow-sm mr-2"></span>' + [text]);
    } else {
        /* reset */
        element.prop('disabled', false);
        element.html(element.data('text'));
    }
}

// TOOTIP FOR ALL WEBSITE 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});

// TOOTIP FOR ALL DataTable 
$('table').on('draw.dt', function() {
    $('[data-toggle="tooltip"]').tooltip();
})

/*   Dropzone JS on remove */
$('document').on('click', '.dz-remove', function () {
    let _this = $(this);
    let parent = _this.closest('.dropzone');

    setTimeout(() => {
        if (parent.find('.dz-preview.dz-image-preview').length) {
            parent.find('.dz-default.dz-message').show();
        }
        else {
            parent.find('.dz-default.dz-message').hide();
        }
    }, 600);

});

/*  END Dropzone JS on remove */


