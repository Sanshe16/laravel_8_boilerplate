api['createFaq'] = ajax_path + '/admin/faq';
api['updateFaq'] = ajax_path + '/admin/faq/:id';

var add_faq_btn = $('#add_faq_button');
var update_faq_btn = $('#update_faq_button');

// ADD FAQs
$('body').on('submit', '#add_faq_form', function(e){
    e.preventDefault();
    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    data.append('question', $('#question').val());
    data.append('answer', $('#answer').val());
    data.append('order', $('#order').val());
    data.append('_method', 'POST');
    button_status(add_faq_btn, true, 'Creating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createFaq"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_faq_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_faq_btn, false);
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
                button_status(add_faq_btn, false);
            }, 1000);
        }
    });
});

// UPDATE faq
$('body').on('submit', '#faq_update_form', function(e){
    e.preventDefault();
    var _this = $(this);
    button_status(update_faq_btn, true, 'Updating');

    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    data.append('question', $('#question').val());
    data.append('answer', $('#answer').val());
    data.append('order', $('#order').val());
    data.append('_method', 'PUT');

    let remove_id = _this.data('id');
    let url = api["updateFaq"];
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
                    button_status(update_faq_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(update_faq_btn, false);
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
                button_status(update_faq_btn, false);
            }, 1000);
        }
    });
});

// INTEGER INPUT FIELDS ACCEPT JUST POSITIVE VALUE
$('body').on('keydown change', '.faqs_order', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 69) {
        e.preventDefault();
        return false;
    }
})

