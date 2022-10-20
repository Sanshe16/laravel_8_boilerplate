api['deleteEvent'] = ajax_path + '/admin/events/:id';

$(function(){
    $('body').on('click', '.delete-events', function() {
        deleteEvent($(this));
    });
    function deleteEvent(data) {
        if ($(data).attr("data-id")) {
            let remove_id = data.data("id");
            let url = api["deleteEvent"];
            url = url.replace(":id", remove_id);
            let active = "Delete";

            Swal.fire({
                title: 'Are you sure you want to ' + active + ' this?',
                text: "You can't undo this action.",
                type: active === 'Active' ? 'success' : 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, ' + active + ' it!',
                confirmButtonColor: "#4B49AC",
                confirmButtonClass: "btn-danger",
                cancelButtonText: "Cancel",
                allowOutsideClick: false,
                onClose: () => {
                    removeBodyPadding();
                },
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data:{'_method':'DELETE'},
                        url: url,
                        type: 'Post',
                        success: function (data) {
                            if (data.responseCode === 200) {
                                toastr.success('Event deleted successfully');
                                setTimeout(function () {// wait for 2 secs(2)
                                    location.reload(); // then reload the page.(3)
                                }, 1000);

                            } else if (data.responseCode === 401) {
                                toastr.error(data.response);
                            }
                            return data;
                        },
                        error: function () {
                            toastr.error('Record Not Found!', 'Some Thing Went Wrong', 'error');
                        }
                    });
                }
            });
        }
    }
});
