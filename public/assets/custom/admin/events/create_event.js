api['createEvent'] = ajax_path + '/admin/events';
api['updateEvent'] = ajax_path + '/admin/events/:id';

var add_event_btn = $('#add_event_button');
var update_event_btn = $('#update_event_button');

// ADD  EVENTS
$('body').on('submit', '#add_event_form', function(e){
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
    data.append('start_date', $('#start_date').val());
    data.append('end_date', $('#end_date').val());
    data.append('_method', 'POST');
    button_status(add_event_btn, true, 'Creating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createEvent"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_event_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_event_btn, false);
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
                button_status(add_event_btn, false);
            }, 1000);
        }
    });
});


// UPDATE EVENTS
$('body').on('submit', '#event_update_form', function(e){
    e.preventDefault();
    var _this = $(this);
    button_status(update_event_btn, true, 'Updating');

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
    data.append('start_date', $('#start_date').val());
    data.append('end_date', $('#end_date').val());
    data.append('_method', 'PUT');

    let remove_id = _this.data('id');
    let url = api["updateEvent"];
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
                    button_status(update_event_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(update_event_btn, false);
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
                    element.after($('<div class="error_message" ><span style="color: red;">' + error[0] + '</span></div>'));
                });
            }
            setTimeout(function () {      // button reset
                button_status(update_event_btn, false);
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

// EVENTS START & END DATE
$(document).ready(function () {
    $(".__event_start_date").datepicker({
        minDate: new Date(),
        altFormat: "yy/mm/dd",
        dateFormat: "yy/mm/dd",
        onSelect: function(dateText) {
            $('#end_date').val('');
            $('#end_date').datepicker('option', 'minDate', dateText);
        }
    });

    $(".__event_end_date").datepicker({
        minDate: new Date(),
        altFormat: "yy/mm/dd",
        dateFormat: "yy/mm/dd",
        onSelect: function(dateText) {

        }
    });
});

// IMAGE PREVIEW EVENT
var loadFile= function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
