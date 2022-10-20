api['createHelpCenter'] = ajax_path + '/admin/helpcenter';
api['updateHelpCenter'] = ajax_path + '/admin/helpcenter/:id';

var add_helpCenter_btn = $('#add_helpCenter_button');
var update_helpCenter_btn = $('#update_helpCenter_button');

// ADD helpCenter
$('body').on('submit', '#add_helpCenter_form', function(e){
    e.preventDefault();
    var _this = $(this)
    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }

    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('description', $('#description').val());
    data.append('queryIcon', $('#queryIcon').val());
    data.append('order', $('#order').val());
    data.append('_method', 'POST');
    button_status(add_helpCenter_btn , true, 'Creating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createHelpCenter"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_helpCenter_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_helpCenter_btn, false);
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
                button_status(add_helpCenter_btn, false);
            }, 1000);
        }
    });
});

// UPDATE helpCenter
$('body').on('submit', '#helpCenter_update_form', function(e){
    e.preventDefault();
    var _this = $(this);
    button_status(update_helpCenter_btn, true, 'Updating');

    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }

    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('description', $('#description').val());
    data.append('queryIcon', $('#queryIcon').val());
    data.append('order', $('#order').val());
    data.append('_method', 'PUT');

    let remove_id = _this.data('id');
    let url = api["updateHelpCenter"];
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
                    button_status(update_helpCenter_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(update_helpCenter_btn, false);
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
                button_status(update_helpCenter_btn, false);
            }, 1000);
        }
    });
});

// INTEGER INPUT FIELDS ACCEPT JUST POSITIVE VALUE
$('body').on('keydown change', '.helpCenter_order', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 69) {
        e.preventDefault();
        return false;
    }
})

$('.select2').select2();

