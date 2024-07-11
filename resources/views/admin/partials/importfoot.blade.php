<script src="scripts/jquery.js"></script>
<script src="scripts/bootstrap.bundle.js"></script>
<script src="scripts/main.js"></script>
<script src="scripts/jquery.validate.min.js"></script>
<script src="scripts/toastr.min.js"></script>
<script>
    var submitted = false;
    $(document).ready(function() {
        $(".ajax").validate({
            highlight: function(element) {
                $(element).closest('.form-group').find('.invalid').removeClass('d-none').addClass(
                    'd-block');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').find('.invalid').removeClass('d-block').addClass(
                    'd-none');
            },
            errorElement: 'small',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                error.appendTo(element.closest('.form-group'));
            },
            submitHandler: function(form, eve) {
                eve.preventDefault();
                var myform = $(form);
                var btnSubmit = myform.find("[type='submit']");
                var btnSubmitHtml = btnSubmit.html();
                var url = myform.attr("action");
                var method = myform.attr("method");
                var data = new FormData(form);
                $.ajax({
                    beforeSend: function() {
                        btnSubmit.addClass("disabled").html(
                            "<i class='fa fa-spinner fa-pulse fa-fw'></i> Loading ... "
                        );
                    },
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: method,
                    url: url,
                    data: data,
                    dataType: 'JSON',
                    success: function(response) {
                        if (!response.error) {
                            submitted = true;
                            btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                            toastr.success(response.message, 'Success !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                            setTimeout(function() {
                                if (response.redirect == "" || response
                                    .redirect == "reload") {
                                    location.reload();
                                } else {
                                    location.href = response.redirect;
                                }
                            }, 1500);
                        } else {
                            btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                            $.each(response.error, function(i, field) {
                                toastr.error(field, 'Failed !', {
                                    closeButton: true,
                                    progressBar: true,
                                    timeOut: 1500
                                });
                            });
                        }
                    },
                    error: function(response) {
                        btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                        $.each(response.error, function(i, field) {
                            toastr.error(i + ' ' + field, 'Failed !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                        });
                    }
                });
            }
        });
        // $.ajax({
        //     type: 'GET',
        //     url: 'admin/pengaduan/check',
        //     success: function(response) {
        //         if (response > 0) {
        //             $('#notif').addClass('text-bg-danger').removeClass('text-bg-success').html(response);
        //         } else {
        //             $('#notif').removeClass('text-bg-danger').addClass('text-bg-success').html(response);
        //         }
        //     }
        // });
        // setInterval(() => {
        //     $.ajax({
        //         type: 'GET',
        //         url: 'admin/pengaduan/check',
        //         success: function(response) {
        //             if (response > 0) {
        //                 $('#notif').addClass('text-bg-danger').removeClass('text-bg-success').html(response);
        //             } else {
        //                 $('#notif').removeClass('text-bg-danger').addClass('text-bg-success').html(response);
        //             }
        //         }
        //     });
        // }, 5000);
    });
</script>
@yield('importfootAppend')
