api['addVendor'] = ajax_path + '/admin/vendor';
api['updateVendor'] = ajax_path + '/admin/vendor/:id';
api['getStates'] = ajax_path + '/getStates';

var add_vendor_btn = $('#add_vendor_button');
var update_vendor = $('#update_vendor');


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

// SUMOSELECT SEARCH FOR COUNTRY
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

// ADD VENDORS
$('body').on('submit', '#add_vendors', function(e){
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('first_name', $('#first_name').val());
    data.append('last_name', $('#last_name').val());
    data.append('email', $('#email').val());
    data.append('country', $('#country').val());
    data.append('states', $('#states').val());
    data.append('city', $('#city').val());
    data.append('phone_number', $('#phone_number').val());
    data.append('fax', $('#fax').val());
    data.append('zip_code', $('#zip_code').val());
    data.append('address', $('#address').val());
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('company_name', $('#company_name').val());
    data.append('company_url', $('#company_url').val());
    data.append('business_type', $('#business_type').val());

    button_status(add_vendor_btn, true, 'Creating');
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["addVendor"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,
        success:function(response){
            if(response.responseCode == 200)
            {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(add_vendor_btn, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(add_vendor_btn, false);
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
                button_status(add_vendor_btn, false);
            }, 1000); 
        }
    });
});


// UPDATE VENDOR
$('body').on('submit', '.update_vender_profile', function(e){
    e.preventDefault();
    var _this = $(this);
    button_status(update_vendor, true, 'Updating');
    let remove_id = _this.data('id');
    let url = api["updateVendor"];
    url = url.replace(":id", remove_id);
    $('.state_validate_message').remove();

    var data = new FormData(); 
    data.append('id', remove_id); 
    data.append('first_name', $('#first_name').val());
    data.append('last_name', $('#last_name').val());
    data.append('email', $('#email').val());
    data.append('phone_number', $('#phone_number').val());
    data.append('fax', $('#fax').val());
    data.append('zip_code', $('#zip_code').val());
    data.append('address', $('#address').val());
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    var country = $('#country').val();
    var states = $('#states').val();

    if (country != "" && (states == '' || $.trim(states) == 'Select')) {
        $('#states').after("<span  class='state_validate_message' style='color:red'>The state field is required</span>");
        button_status(update_vendor, false);
        return;
    }
    data.append('country', $('#country').val());
    data.append('states', $('#states').val());
    data.append('city', $('#city').val());
    data.append('company_name', $('#company_name').val());
    data.append('company_url', $('#company_url').val());
    data.append('business_type', $('#business_type').val());
    data.append('_method', 'PUT');
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
                    button_status(update_vendor, false);
                    window.location.reload();
                }, 1500);
            }
            else
            {
                button_status(update_vendor, false);
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
                button_status(update_vendor, false);
            }, 1000); 
        }
    });
});

// UPLOAD VENDOR PROFILE PIC 
var loadFile= function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
