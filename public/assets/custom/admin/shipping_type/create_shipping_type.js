api['shippingType'] = ajax_path + '/admin/shippingtype';
api['updateShippingType'] = ajax_path + '/admin/shippingtype/:id';

var add_shipping_submit_btn = $('#add-shipping_type_button');

// ADD Shipping Type
$('body').on('submit', '#add_shipping_type', function(e){
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('shipping_cost', $('#shipping_cost').val());
    data.append('min_shipping_days', $('#min_shipping_days').val());
    data.append('max_shipping_days', $('#max_shipping_days').val());

    button_status(add_shipping_submit_btn, true, 'Creating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["shippingType"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,

        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_shipping_submit_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_shipping_submit_btn, false);
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
                    element.after($('<div class="error_message"><span style="color: red;">' + error[0] + '</span></div>'));
                });
            }
            setTimeout(function () {      // button reset
                button_status(add_shipping_submit_btn, false);
            }, 1000);
        }
    });
});

// UPDATE SHIPPING TYPES
$('body').on('submit', '#shipping_types', function(e){
    e.preventDefault();
    var _this = $(this);
    var update_shipping_type = $('#update_shipping_type');
    button_status(update_shipping_type, true, 'Updating');

    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    data.append('name', $('#name').val());
    data.append('shipping_cost', $('#shipping_cost').val());
    data.append('min_shipping_days', $('#min_shipping_days').val());
    data.append('max_shipping_days', $('#max_shipping_days').val());
    data.append('_method', 'PUT');

    let remove_id = _this.data('id');
    let url = api["updateShippingType"];
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
                    button_status(update_shipping_type, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(submit_btn, false);
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
                button_status(update_shipping_type, false);
            }, 1000);
        }
    });
});

// INTEGER INPUT FIELDS ACCEPT JUST POSITIVE VALUE
$('body').on('keydown change', '#shipping_cost', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 69) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#min_shipping_days', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 69) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#max_shipping_days', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 69) {
        e.preventDefault();
        return false;
    }
})
