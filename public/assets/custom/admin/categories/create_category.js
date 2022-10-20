

api['createCategory'] = ajax_path + '/admin/categories';
api['updateCategory'] = ajax_path + '/admin/categories/:id';
api['getSubCategories'] = ajax_path + '/admin/categories/:id/sub-categories';

$(document).ready(function () {
    $('.sumoSelect_search').SumoSelect({ triggerChangeCombined: true, search: true, selectAll: false, placeholder: 'Nothing selected' });
});

var sumo_selected_values = [];
var sumo_selected_values_difference = null;
$('body').on('change', '.sumoSelect_search', function () {
    var selectValues = $(this).val();
    jQuery.grep(selectValues, function (el) {
        if (jQuery.inArray(el, sumo_selected_values) == -1) {
            sumo_selected_values_difference = el;
        }
    });
});


// ADD Category
$('body').on('submit', '.add_category', function (e) {
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('name', $('#name').val());
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    // from each category dropdown, add its level and selected id to selectedCategories array
    // only if the level is defined and id is greater than 0 because 0 is for placeholder text
    var selectedCategories = [];
    $('.category').each(function (index) {
        if ($(this).data('level') != null && $(this).val() > 0)
            selectedCategories.push({ 'level': $(this).data('level'), 'id': $(this).val() });
    });
    // parent_id 0 means that it has no parent.
    var parent_id = 0;
    if (selectedCategories.length) {
        // get object which has highest level,
        // category with this id will be the direct parent of the new category
        var maxObj = selectedCategories.reduce((max, obj) => (max.level > obj.level) ? max : obj);
        parent_id = maxObj.id;
    }
    data.append('parent_id', parent_id);
    var submit_btn = _this.find('.add-button');
    button_status(submit_btn, "loading");

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: api["createCategory"],
        type: "POST",
        contentType: false,
        processData: false,
        data: data,

        success: function (response) {
            if (response.responseCode == 200) {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(submit_btn, "reset");
                    window.location.reload();
                }, 1500);
            }
            else {
                button_status(submit_btn, "reset");
                toastr.error('There are something went wrong');
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
                button_status(submit_btn, "reset");
            }, 1000);
        }
    });
});

// UPDATE Category
$('body').on('submit', '.update_category', function (e) {
    e.preventDefault();
    var _this = $(this);
    var data = new FormData();
    data.append('_method', 'put');
    var files = $('#upload-photo')[0].files;
    if (files.length > 0) {
        data.append('image', files[0]);
    }
    data.append('name', $('#name').val());
    var isActive = 0;
    if ($('#is_active').is(":checked")) {
        isActive = 1;
    }
    data.append('is_active', isActive);
    // from each category dropdown, add its level and selected id to selectedCategories array
    // only if the level is defined and id is greater than 0 because 0 is for placeholder text
    var selectedCategories = [];
    $('.category').each(function (index) {
        if ($(this).data('level') != null && $(this).val() > 0)
            selectedCategories.push({ 'level': $(this).data('level'), 'id': $(this).val() });
    });
    // parent_id 0 means that it has no parent.
    var parent_id = 0;
    if (selectedCategories.length) {
        // get object which has highest level,
        // category with this id will be the direct parent of the new category
        var maxObj = selectedCategories.reduce((max, obj) => (max.level > obj.level) ? max : obj);
        parent_id = maxObj.id;
    }
    data.append('parent_id', parent_id);
    var submit_btn = _this.find('.add-button');
    button_status(submit_btn, "loading");
    let remove_id = _this.data('id');
    let url = api["updateCategory"];
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

        success: function (response) {
            if (response.responseCode == 200) {
                toastr.success(response.message);
                setTimeout(function () {
                    button_status(submit_btn, "reset");
                    window.location.reload();
                }, 1500);
            }
            else {
                button_status(submit_btn, "reset");
                toastr.error('There are something went wrong');
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
                button_status(submit_btn, "reset");
            }, 1000);
        }
    });
});

// // GET subCategories
$('body').on('change', '.category', function (e) {
    var _this = $(this);

    /* get categories */
    var selected_categories = [];

    $.each($('#categories-add-div').find('.selected_category_tag'), function (i, tag) {
        selected_categories[i] = $(tag).data('id');
    });

    if (_this.val() > 0) {
        var url = api['getSubCategories'].replace(':id', _this.val());
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: url,
            type: "GET",
            success: function (response) {
                if (response.responseCode == 200) {
                    let subCategories = response.payload.data.subCategories;
                    _this.closest('.category-div').nextAll().remove();
                    if (subCategories.length > 0) {
                        let html = `<div class="col col-6 category-div">
                        <div class="form-group">
                            <label for="parent_category">${_this.children("option:selected").text()}'s sub-categories</label>
                            <select class="select form-control input-field sumoSelect_search category" data-level="${_this.data('level') + 1}"  name="parent_category">
                                <option value="0">Select a category if it's child</option>`;
                        subCategories.forEach(element => {
                            let is_disabled = false;
                            let $index = selected_categories.indexOf(element['id'])
                            if ($index >= 0) {
                                is_disabled = true;
                            }
                            html += `<option value="${element['id']}" ${is_disabled ? 'disabled' : ''}>${element['name']}</option>`;
                        });
                        html += `</select>
                            </div>
                        </div>`;
                        $('#categories-parent-div').append(html);

                        $('.sumoSelect_search').SumoSelect({ triggerChangeCombined: true, search: true, selectAll: false, placeholder: 'Nothing selected' });
                    }
                    else {
                        if (!$('#parent_category').hasClass('ingoreMultipleSelections')) {
                            createSelectedCategoryTag(_this.val(), _this.find("option:selected").text(), _this.prop('selectedIndex'));

                            let parent_category_first = $('.category-div:first');
                            parent_category_first.nextAll().remove();
                            // if (_this.data('level') == 0) {
                            _this[0].sumo.disableItem(_this.prop('selectedIndex'));
                            // }

                            $('#parent_category')[0].sumo.selectItem(0);
                            $('#parent_category')[0].sumo.reload();

                            $('#categories-parent-div').hide();
                            $('#selected-categories-text-div').hide();
                            $('#add_another_category_btn').show();
                        }
                    }
                    // setTimeout(function () {
                    //     button_status(submit_btn, "reset");
                    //     window.location.reload();
                    // }, 1500);
                }
                else {
                    // button_status(submit_btn, "reset");
                    toastr.error('There are something went wrong');
                }
            }
        });
    }
    else if (_this.val() == 0) {
        _this.closest('.category-div').nextAll().remove();
        return;
    }

});

var loadFile = function (event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('imagePreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};


$('body').on('click', '#add_another_category_btn', function (e) {
    $('#categories-parent-div').show();
    $('#add_another_category_btn').hide();
});

$('body').on('click', '.remove_selected_category', function (e) {
    let _this = $(this);
    let parent = _this.closest('.selected_category_tag');
    let id = parent.attr('data-id');

    parent.remove();

    if ($('#categories-add-div').children().length) {
        $('#selected-categories-text-div').hide();
    }
    else {
        $('#selected-categories-text-div').show();
    }

    var index = $('#parent_category option[value='+ id +']').index();
    if(index != -1)
    {
        $('#parent_category')[0].sumo.enableItem(`${index}`);
    }
   
});

function createSelectedCategoryTag(value, text, index) {
    let div = '<div class="mx-1 selected_category_tag d-flex justify-content-between" data-id="' + value + '"  data-index="' + index + '" data-text="' + text + '"  style="max-width: 150px; height: 33px; background-color: #4B49AC; border-radius: 10px; color: white; padding: 5px 5px 5px 5px; "><p class="text-center text-truncate" style="max-width: 120px;" data-toggle="tooltip" title="' + text + '">' + text + '</p><i class="ml-1 ti-close remove_selected_category" style="color: white; cursor: pointer; font-size: 10px; "></i></div>';
    $('#categories-add-div').append(div);
}
