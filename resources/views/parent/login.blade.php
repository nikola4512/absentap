<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    <title>{{ $config['title'] }}</title>
    <base href="{{ url('') }}" />
    <meta name="title" content="{{ $config['title'] }}" />
    <meta name="description" content="{{ $config['description'] }}" />
    <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#3a6073">
    <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#3a6073">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="styles/vendor.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/toastr.min.css">
    <script src="scripts/modernizr.js"></script>
</head>

<body>
    <div class="preloader-wrapper">
        <div class="preloader">
            <img src="images/logomsonly.png" alt="Logo">
        </div>
    </div>
    <main>
        <section class="login">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div class="logo">
                            <div class="d-flex">
                                <img src="{{ $config['first_logo'] }}" alt="Logo One">
                                @if (!empty($config['second_logo']) && $config['second_logo'] != 'images/logomsonly.png')
                                    <img src="{{ $config['second_logo'] }}" class="ms-1" alt="Logo One">
                                @endif
                            </div>
                            <h1>{{ $config['first_title'] }}</h1>
                            <h1>{{ $config['second_title'] }}</h1>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div class="form">
                            <div class="head">
                                <h1>Parent Login</h1>
                                <h2>Masuk ke akun anda</h2>
                            </div>
                            <div class="body"> 
                                <form action="parent/sign-in" id="form-login" autocomplete="off" method="POST">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control form-control-sm" name="username" required="required" minlength="5" placeholder="Username" loginRegex>
                                    </div>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                        <input type="password" class="form-control form-control-sm" name="password" required="required" minlength="5" placeholder="Password" />
                                    </div>
                                    <div class="cage-btn">
                                        <button type="submit" class="btn btn-sm btn-submit"><i class="fas fa-sign-in-alt"></i> Masuk</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="scripts/vendor.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/jquery.validate.min.js"></script>
        <script src="scripts/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#form-login").validate({
                    highlight: function(element) {
                        $(element).closest('.form-group').find('.invalid').removeClass('d-none').addClass(
                            'd-block');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').find('.invalid').removeClass('d-block').addClass(
                            'd-none');
                    },
                    errorElement: 'label',
                    errorClass: 'd-block invalid',
                    errorPlacement: function(error, element) {
                        error.appendTo(element.closest('.form-group'));
                    },
                    submitHandler: function(form, eve) {
                        eve.preventDefault();
                        var myform = $(form);
                        var btnSubmit = myform.find("[type='submit']");
                        var btnSubmitHtml = btnSubmit.html();
                        var url = myform.attr("action");
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
                            type: "POST",
                            url: url,
                            data: data,
                            dataType: 'JSON',
                            success: function(response) {
                                if (response.status == 'success') {
                                    btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                                    toastr.success(response.message, 'Success !', {
                                        closeButton: true,
                                        progressBar: true,
                                        timeOut: 1000
                                    });
                                    setTimeout(function() {
                                        if (response.redirect == "" || response
                                            .redirect == "reload") {
                                            location.reload();
                                        } else {
                                            location.href = response.redirect;
                                        }
                                    }, 1000);
                                } else {
                                    btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                                    toastr.error(response.message, 'Failed !', {
                                        closeButton: true,
                                        progressBar: true,
                                        timeOut: 3000
                                    });
                                }
                            },
                            error: function(response) {
                                btnSubmit.removeClass("disabled").html(btnSubmitHtml);
                                $.each(response.error, function(i, field) {
                                    toastr.error(i + ' ' + field, 'Failed !', {
                                        closeButton: true,
                                        progressBar: true,
                                        timeOut: 3000
                                    });
                                });
                            }
                        });
                    }
                });
            });
        </script>
    </main>
</body>

</html>
