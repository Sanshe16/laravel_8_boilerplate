api['updateProducts'] = ajax_path + '/admin/products/:id';
api['getShippingType'] = ajax_path + '/admin/shipping/types/:id';

var update_product_submit_btn = $('#update_product_btn');

// CREATE & EDIT PRODUCT TEXTAREA EDITOR
tinymce.init({
    selector: '#description',
    plugins: 'code table lists',
    toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
    setup: function (editor) {
        editor.on('Change', function (e) {
            tinyMCE.triggerSave();
        });
    }
});

$(".remove-img").on("click", function () {
    $(this).closest("tr").remove();
});
let remove_id = $('.update_products').data('id');
let url = api["updateProducts"];
url = url.replace(":id", remove_id);
// Update products
Dropzone.autoDiscover = false;
$(".dropzone").sortable({
    items: '.dz-preview',
    cursor: 'grab',
    opacity: 0.5,
    containment: '.dropzone',
    distance: 20,
    tolerance: 'pointer',
    stop: function () {
        var queue = myDropzone.getAcceptedFiles();
        newQueue = [];
        $('#imageUpload .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {
            var name = el.innerHTML;
            queue.forEach(function (file) {
                if (file.name === name) {
                    newQueue.push(file);
                }
            });
        });
        myDropzone.files = newQueue;
    }
});

myDropzone = new Dropzone('div#imageUpload', {
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 100,
    maxFilesize: 12,
    paramName: 'image',
    clickable: true,
    method: 'POST',
    url: url,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    renameFile: function (file) {
        var dt = new Date();
        var time = dt.getTime();
        return time + file.name;
    },
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    init: function () {
        var myDropzone = this;
        $('.update-button').on("click", function (e) {
            e.preventDefault();

            button_status(update_product_submit_btn, true, 'Updating');

            if ($('#categories-add-div').find('.selected_category_tag').length < 1) {
                toastr.error('Please select a category for your product');
                button_status(update_product_submit_btn, false);
                return;
            }

            //VALIDATE IMAGE (Upload at least 1 image) 

            tinyMCE.triggerSave();
            if (myDropzone.getAcceptedFiles().length) {
                myDropzone.uploadFiles(myDropzone.getAcceptedFiles());
            }
            else {
                $('.promotion_validate_message').remove();
                var _this = $(this);
                var data = getFormData();

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    url: url,
                    data: data,
                    success: function (response) {
                        if (response.responseCode == 200) {
                            toastr.success(response.message);
                            setTimeout(function () {
                                button_status(update_product_submit_btn, false);
                                window.location.reload();
                            }, 1500);
                        }
                        else {
                            toastr.error('There are something went wrong');
                            button_status(update_product_submit_btn, false);
                        }
                    },
                    error: function (errors) {
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
                            button_status(update_product_submit_btn, false);
                        }, 1000);
                    }
                });
            }
        });

        this.on('sending', function (file, xhr, formData) {
            // Append all form inputs to the formData Dropzone will POST
            var data = getFormData();
            for (const pair of data.entries()) {
                formData.append(pair[0], pair[1]);
            }
        });
    },
    error: function (file, response) {
        // Replace the below with error msgs for the data
        if (response.responseCode == 422) {
            $('.create_product_error').remove();
            // you can loop through the errors object and show it to the user
            // display errors on each form field
            $.each(response.payload.errors, function (input_name, error) {

                var element = $(document).find('[name="' + input_name + '"]');
                element.after($('<div class="create_product_error" ><span style="color: red;">' + error[0] + '</span></div>'));
            });

            setTimeout(function () {      // button reset
                button_status(update_product_submit_btn, false);
            }, 1000);
        }
        else if (response.errors.code) {
            $("#code-error").text(response.errors.code);

        }
        // Replace the above
        else {
            try {
                var res = JSON.parse(response);
                if (typeof res.message !== 'undefined' && !$modal.hasClass('in')) {
                    $("#success-icon").attr("class", "fas fa-thumbs-down");
                    $("#success-text").html(res.message);
                    $modal.modal("show");
                } else {
                    if ($.type(response) === "string")
                        var message = response; //dropzone sends it's own error messages in string
                    else
                        var message = response.message;
                    file.previewElement.classList.add("dz-error");
                    _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                    _results = [];
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i];
                        _results.push(node.textContent = message);
                    }
                    return _results;
                }
            } catch (error) {
                console.log(error);
            }
            setTimeout(function () {      // button reset
                button_status(update_product_submit_btn, false);
            }, 1000);
        }
    },
    successmultiple: function (file, response) {
        if (response.responseCode == 200) {
            toastr.success(response.message);
            setTimeout(function () {
                button_status(update_product_submit_btn, false);
                window.location.reload();
            }, 1500);
        }
    },
    completemultiple: function (file, response) {
        console.log(file, response, "completemultiple");
    },
    reset: function () {
        console.log("resetFiles");
        // this.removeAllFiles(true);
        $('#imageUpload').find('.dz-default.dz-message').show();
    }
});

function getFormData() {
    var data = new FormData();
    data.append('_method', 'put');
    data.append('product_name', $('#product_name').val());
    data.append('purchase_price', $('#purchase_price').val());
    data.append('product_price', $('#product_price').val());
    data.append('shipping_type_id', $('#shipping_type').val());
    data.append('shipping_cost', $('#shipping_cost').val());

    data.append('product_box', $('#product_box').val());
    data.append('sku', $('#sku').val());
    data.append('quantity', $('#quantity').val());
    data.append('details', $('#description').val());
    data.append('stock_limit', $('#stock_limit').val());
    var prevImgs = $("input[name='prev_img[]']")
        .map(function () { return $(this).val(); }).get();
    data.append('prev_img_ids', prevImgs);
    // from each category dropdown, add its level and selected id to selectedCategories array
    // only if the level is defined and id is greater than 0 because 0 is for placeholder text
    // var selectedCategories = [];
    // $('.category').each(function (index) {
    //     if ($(this).data('level') != null && $(this).val() > 0)
    //         selectedCategories.push({ 'level': $(this).data('level'), 'id': $(this).val() });
    // });
    // parent_id 0 means that it has no parent.
    var parent_id = 0;
    // if (selectedCategories.length) {
    //     // get object which has highest level,
    //     // category with this id will be the direct parent of the new category
    //     var maxObj = selectedCategories.reduce((max, obj) => (max.level > obj.level) ? max : obj);
    //     parent_id = maxObj.id;
    // }
    data.append('category_id', parent_id);

    // add all the selected categories to form data
    $.each($('#categories-add-div').find('.selected_category_tag'), function (i, tag) {
        data.append('selected_categories[' + i + ']', $(tag).data('id'));
    });

    var isActive = 0;
    var isPromotion = 0;
    var run_continue = 0;
    var end_date = $('#end_date').val();
    var start_date = $('#start_date').val();
    var promotion_price = $('#promotion_price').val();
    var stock_vendor_name = $('#stock_vendor_name').val();
    var product_stock_owner = '';
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }

    if ($('#is_promotion').is(":checked")) {
        isPromotion = 1;
    }
    if (isPromotion == 1 && promotion_price == '') {
        $('#promotion_price').after("<span  class='promotion_validate_message' style='color:red'>The promotion price field is required</span>");
        return;
    }

    if ($('#vendor_stock_owner').is(":checked")) {   // for check product stock owner. if vendor the get name. 
        product_stock_owner = $('#vendor_stock_owner').val();
    }
    else {
        product_stock_owner = $('#product_inWarehouse').val();
    }

    if (product_stock_owner == 'Vendor' && stock_vendor_name == '') {
        $('#stock_vendor_name').after("<span  class='promotion_validate_message' style='color:red'>The stock owner name field is required</span>");
        return;
    }

    if ($('#run_continue').is(":checked")) {
        run_continue = 1;
    }
    if (run_continue == 1) {
        end_date = $('#end_date').val('');
    }
    if (isPromotion == 1 && start_date == '') {
        $('#start_date').after("<span  class='promotion_validate_message' style='color:red'>The start date field is required</span>");
        return;
    }

    data.append('start_date', $('#start_date').val());
    data.append('end_date', end_date);
    data.append('product_stock_owner', product_stock_owner);
    data.append('stock_vendor_id', stock_vendor_name);
    data.append('promotion_price', $('#promotion_price').val());
    data.append('is_active', isActive);
    data.append('is_promotion', isPromotion);
    data.append('run_continue', run_continue);
    return data;
}

//CHECKBOX CLICK PRICE INPUT SHOW OR HIDE
$('body').on('click', '#is_promotion', function (e) {
    if ($('#is_promotion').is(":checked")) {
        $(".promotion_price_inputbox").slideDown("fast");
    }
    else {
        $(".promotion_price_inputbox").slideUp("fast");
    }
});

//CHECKBOX IS_CONTINUE PROMOTION INPUT SHOW OR HIDE
$('body').on('click', '#run_continue', function (e) {
    if ($('#run_continue').is(":checked")) {
        $(".promotion_end_date").slideUp("fast");
    }
    else {
        $(".promotion_end_date").slideDown("fast");
    }
});

//RadioButton PROMOTION INPUT SHOW OR HIDE
$('body').on('click', '#vendor_stock_owner', function (e) {
    if ($('#vendor_stock_owner').is(":checked")) {
        $(".select_vendor_name").slideDown("fast");
    }
});
$('body').on('click', '#product_inWarehouse', function (e) {
    if ($('#product_inWarehouse').is(":checked")) {
        $(".select_vendor_name").slideUp("fast");
    }
});


//   CHARACTOR COUNT AND VALIDATE
$('body').on('keyup paste change click', '.text_typed_value_count', function (e) {
    $(this).attr('maxlength', 255);
    var _this = $(this);

    var parent = _this.closest('.form-group');
    var typed_text_count = parent.find('.text_typed_value_count').val().length;
    parent.find('.text_count').text(typed_text_count);
});

//  GET SPECIPIC SHIPPING TYPE PRICE
$("#shipping_type").change(function () {
    var _this = $(this);
    var parent = _this.closest('.gifter_shipping_type');
    var shipping_type_id = $("#shipping_type option:selected").val();
    let url = api['getShippingType'];
    url = url.replace(':id', shipping_type_id);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        url: url,
        data: { shipping_type_id: shipping_type_id },
        success: function (response) {
            if (response) {
                parent.find("#shipping_cost").val(response.shipping_cost);
            }
        },
        error: function (error) {
            toastr.error('There are something went wrong');
        }
    });
});

// INTEGER INPUT FIELDS ACCEPT JUST POSITIVE VALUE 
$('body').on('keydown change', '#purchase_price', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 189) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#product_price', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 109) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#quantity', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 109) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#stock_limit', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 109) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#promotion_price', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 109) {
        e.preventDefault();
        return false;
    }
})
$('body').on('keydown change', '#shipping_cost', function (e) {
    if (e.which === 13 || e.which === 189 || e.which === 109) {
        e.preventDefault();
        return false;
    }
})

// PRICES LIMITATION 
$('body').on('blur', '.gifter_js_validation', function (e) {
    var stock_min_limit = parseInt($('#stock_limit').val());
    var quantity = parseInt($('#quantity').val());
    var selling_price = parseInt($('#product_price').val());
    var promotion_price = parseInt($('#promotion_price').val());

    $('.limitation_validate_message').remove();

    if(stock_min_limit > quantity){
        $('#stock_limit').after("<span class='limitation_validate_message' style='color:red'>Low stock limit should be less than the quantity</span>");
        return false;
    }
    if(promotion_price > selling_price){
        $('#promotion_price').after("<span class='limitation_validate_message' style='color:red'>Promotion price should be less than selling price</span>");
        return false;
    }
});
 
// PROMOTION START & END DATE 
$(document).ready(function () {
    $(".__promotion_start_date").datepicker({
        minDate: new Date(),
        altFormat: "dd/mm/yy",
        dateFormat: "dd/mm/yy",
        onSelect: function(dateText) {
            $('#end_date').val('');
            $('#end_date').datepicker('option', 'minDate', dateText);
        }
    });

    $(".__promotion_end_date").datepicker({
        minDate: new Date(),
        altFormat: "dd/mm/yy",
        dateFormat: "dd/mm/yy",
        onSelect: function(dateText) {
            
        }
    });
});
