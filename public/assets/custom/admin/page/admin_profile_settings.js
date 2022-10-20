

api['getStates'] = ajax_path + '/getStates';
api["update/general/setting"] = ajax_path + '/admin/general/settings';
api["update/business/setting"] = ajax_path + '/admin/business/setting';


$(document).ready(function()
{
    $("#country").change(function()
    {
        var selectedCountry = $("#country option:selected").val();
        var token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: api['getStates'],
            data: { country_id : selectedCountry, _token : token },
            success:function(response){
                if(response.responseCode == 200) 
                {
                    var data_value = response.payload.data.states;
                    if(data_value.length != 0)
                    {
                        $("#states").empty();
                        $("#states").append('<option>Select</option>');
                        $.each(response.payload.data.states, function (key, value) {
                            $("#states").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    } 
                    else
                    {
                        $("#states").empty();
                        toastr.error('This Country has no States');
                    }  
                }
                else
                {
                    $("#states").empty();
                    toastr.error('There are something went wrong');
                }
            }
        });
    });
});


$(document).ready(function () {
    $('.sumoSelect_search').SumoSelect({ triggerChangeCombined: true, search: true, selectAll: false, placeholder: 'Nothing selected' });
});

var sumo_selected_values = [];
var sumo_selected_values_difference = null;
$('body').on('change','.sumoSelect_search', function()
{
    var selectValues = $(this).val();
    jQuery.grep(selectValues, function(el) {
        if (jQuery.inArray(el, sumo_selected_values) == -1)
        {
            sumo_selected_values_difference = el;
        }
    });
});


// ADMIN GENERAL SETTING UPDATE 
$('body').on('submit', '.general_setting', function(e){
    e.preventDefault();
    var _this = $(this);
    var parent = _this.closest('.admin_settings_tabs');
    var submit_btn = parent.find('.update-button');
    button_status(submit_btn, true, 'Updating');
    var country = $('#country').val();
    var states = $('#states').val();

    if (country != "" && (states == '' || $.trim(states) == 'Select')) {
        $('#states').after("<span  class='state_validate_message' style='color:red'>The state field is required</span>");
        button_status(submit_btn, false);
        return;
    }


    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["update/general/setting"],
        type: "POST",
        data: _this.serialize(),

        success:function(response)
        {
            if(response.responseCode == 200) 
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(submit_btn, false);
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
                    element.after($('<div class="error_message"><span style="color: red;">' + error[0] + '</span></div>'));     
                });
            }
            setTimeout(function () {      // button reset 
                button_status(submit_btn, false);
            }, 1000); 
        }
    });
});

// ADMIN BUSINESS SETTING UPDATE
$('body').on('submit', '.business_setting', function(e){
    e.preventDefault();
    var _this = $(this);
    var parent = _this.closest('.admin_settings_tabs');
    var submit_btn = parent.find('.update-button_bussines');
    button_status(submit_btn, true, 'Updating');

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["update/business/setting"],
        type: "POST",
        data: _this.serialize(),

        success:function(response){
            if(response.responseCode == 200) 
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(submit_btn, false);
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
                button_status(submit_btn, false);
            }, 1000); 
        }
    });
});
