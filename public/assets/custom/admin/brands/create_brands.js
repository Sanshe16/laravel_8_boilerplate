

api['storeBrands'] = ajax_path + '/admin/brands';
api['updateBrands'] = ajax_path + '/admin/brands/:id';
api['deleteBrands'] = ajax_path + '/admin/brands/:id';


// ADD brands
$('body').on('submit', '.add_brands', function(e){
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('title', $('#title').val());
    var isActive = 0;
    if ($('#is_active').is(":checked"))
    {
        isActive = 1;
    }
    data.append('is_active', isActive);

    var submit_btn = _this.find('.add-button');
    button_status(submit_btn, "loading");

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["storeBrands"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,

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

// UPDATE BRANDS
$('body').on('submit', '.update_brands', function(e){
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    data.append('_method', 'put');
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('title', $('#title').val());
    var isActive = 0;
    if ($('#is_active').is(":checked"))
    {
        isActive = 1;
    }
    data.append('is_active', isActive);
    
    var submit_btn = _this.find('.add-button');
    button_status(submit_btn, "loading");
    let remove_id = _this.data('id');
    let url = api["updateBrands"];
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



var loadFile= function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imagePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
