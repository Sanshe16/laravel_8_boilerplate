api['createPrivacy'] = ajax_path + '/admin/privacy';
api['updatePrivacy'] = ajax_path + '/admin/privacy/:id';

var add_privacy_btn = $('#add_privacy_button');
var update_privacy_btn = $('#update_privacy_button');

// ADD  BANNERS
$('body').on('submit', '#add_privacy_form', function(e){
    e.preventDefault();
    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('_method', 'POST');
    button_status(add_privacy_btn, true, 'Creating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createPrivacy"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_privacy_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_privacy_btn, false);
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
                button_status(add_privacy_btn, false);
            }, 1000);
        }
    });
});

// UPDATE Privacy
$('body').on('submit', '#privacy_update_form', function(e){
    e.preventDefault();
    var _this = $(this);
    button_status(update_privacy_btn, true, 'Updating');

    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('_method', 'PUT');

    let remove_id = _this.data('id');
    let url = api["updatePrivacy"];
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
                    button_status(update_privacy_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(update_privacy_btn, false);
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
                button_status(update_privacy_btn, false);
            }, 1000);
        }
    });
});

var loadFile= function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
