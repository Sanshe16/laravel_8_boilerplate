api['createBanner'] = ajax_path + '/admin/banner';
api['updateBanner'] = ajax_path + '/admin/banner/:id';

var add_banner_btn = $('.add_banner_button');
var update_banner_btn = $('.update_banner_button');

// ADD  BANNERS
$('body').on('submit', '#add_banner_form', function(e){
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('order', $('#order').val());
    data.append('_method', 'POST');
    button_status(add_banner_btn, true, 'Creating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createBanner"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_banner_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_banner_btn, false);
                toastr.error('There are something went wrong');
            }
        },
        error: function (errors)
        {
            $('.error_message').remove();
            if (errors.status == 422)
            {
                $.each(errors.responseJSON.errors, function (input_name, error) {
                    var element = $(document).find('[name="' + input_name + '"]');
                    element.after($('<div class="error_message"><span style="color: red;">' + error[0] + '</span></div>'));
                });
            }
            setTimeout(function () {      // button reset
                button_status(add_banner_btn, false);
            }, 1000);
        }
    });
});

// UPDATE BANNERS
$('body').on('submit', '#banner_update_form', function(e){
    e.preventDefault();
    var _this = $(this);
    button_status(update_banner_btn, true, 'Updating');

    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('order', $('#order').val());
    data.append('_method', 'PUT');

    let remove_id = _this.data('id');
    let url = api["updateBanner"];
    url = url.replace(":id", remove_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: url,
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(update_banner_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(update_banner_btn, false);
                toastr.error('There are something went wrong');
            }
        },
        error: function (errors)
        {
            $('.error_message').remove();
            if (errors.status == 422) {

                $.each(errors.responseJSON.errors, function (input_name, error) {
                    var element = $(document).find('[name="' + input_name + '"]');
                    element.after($('<div class="error_message" ><span style="color: red;">' + error[0] + '</span></div>'));
                });
            }
            setTimeout(function () {      // button reset
                button_status(update_banner_btn, false);
            }, 1000);
        }
    });
});

// ORDER INPUT FIELDS ACCEPT JUST POSITIVE VALUE
$('body').on('keydown change', '#order', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 69) {
        e.preventDefault();
        return false;
    }
})

var loadFile= function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
