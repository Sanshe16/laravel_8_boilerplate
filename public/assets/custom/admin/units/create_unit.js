api['createUnit'] = ajax_path + '/admin/units';
api['updateUnit'] = ajax_path + '/admin/units/:id';

$(document).ready(function () {
    $('.sumoSelect_search').SumoSelect({ triggerChangeCombined: true, search: true, selectAll: false, placeholder: 'Nothing selected' });
});

// ADD Unit
$('body').on('submit', '.add_unit', function(e){
    e.preventDefault();
    var _this = $(this);
    var isActive = 0;
    if ($('#is_active').is(":checked"))
    {
        isActive = 1;
    }
    var submit_btn = _this.find('.add-button');
    button_status(submit_btn, "loading");

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createUnit"],
        type: "POST",
        data: {
            name: $('#name').val(),
            code: $('#code').val(),
            base_unit: $('#base_unit').val(),
            operator: $('#operator').val(),
            operation_value: $('#operation_value').val(),
            is_active: isActive
        },

        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(submit_btn, "reset");
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(submit_btn, "reset");
                toastr.error('There are something went wrong');
            }
        },
        error: function (errors)
        {
            $('.error_message').remove();
            if (errors.status == 422) {
                $('#success_message').fadeIn().html(errors.responseJSON.message);
                // you can loop through the errors object and show it to the user
                // display errors on each form field
                $.each(errors.responseJSON.errors, function (input_name, error) {

                    var element = $(document).find('[name="' + input_name + '"]');
                    element.after($('<div class="error_message" ><span style="color: red;">' + error[0] + '</span></div>'));
                });
            }
            setTimeout(function () {      // button reset
                button_status(submit_btn, "reset");
            }, 1000);
        }
    });
});

// UPDATE Unit
$('body').on('submit', '.update_unit', function(e){
    e.preventDefault();
    var _this = $(this);
    var isActive = 0;
    if ($('#is_active').is(":checked"))
    {
        isActive = 1;
    }
    var submit_btn = _this.find('.add-button');
    button_status(submit_btn, "loading");
    let remove_id = _this.data('id');
    let url = api["updateUnit"];
    url = url.replace(":id", remove_id);

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: url,
        type: "POST",
        data: {
            '_method': 'put',
            'name': $('#name').val(),
            'code': $('#code').val(),
            'base_unit': $('#base_unit').val(),
            'operator': $('#operator').val(),
            'operation_value': $('#operation_value').val(),
            'is_active': isActive
        },

        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(submit_btn, "reset");
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(submit_btn, "reset");
                toastr.error('There are something went wrong');
            }
        },
        error: function (errors)
        {
            matched = $(".content-id *");
                for (var i = 0; i < matched.length; i++) {
                    $('#emptyData').remove();
                }
            if (errors.status == 422) {
                $('#success_message').fadeIn().html(errors.responseJSON.message);
                // you can loop through the errors object and show it to the user
                // display errors on each form field
                $.each(errors.responseJSON.errors, function (input_name, error) {

                    var element = $(document).find('[name="' + input_name + '"]');
                    element.after($('<div class="content-id"  id="emptyData" ><span style="color: red;">' + error[0] + '</span></div>'));
                });
            }
            setTimeout(function () {      // button reset
                button_status(submit_btn, "reset");
            }, 1000);
        }
    });
});
