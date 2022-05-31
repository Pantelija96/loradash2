<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iot Dashboard</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/core.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/components.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/')}}css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/') }}js/pace.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/blockui.min.js"></script>

    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/') }}js/app.js"></script>
    <!-- /theme JS files -->
</head>

<body class="bg-telekom">

<!-- Page container -->
<div class="page-container login-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">


                <!-- Simple login form -->
                <form action="{{ route('login') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="panel panel-body login-form">
                        <div class="text-center">
                            <div class="icon-object bg-telekom-slova"><i class="icon-reading"></i></div>
                            <h5 class="content-group">Ulogujte se na vas nalog <small class="display-block">Unesite svoje kredencijale</small></h5>
                        </div>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="text" id="email" name="email" class="form-control" placeholder="Email" value="pantelija@gmail.com">
                            <div class="form-control-feedback">
                                <i class="icon-envelope text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="password" name="lozinka" id="lozinka" class="form-control" placeholder="Lozinka">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn bg-telekom-slova btn-block">Nastavi <i class="icon-circle-right2 position-right"></i></button>
                        </div>

                    </div>
                </form>
                <!-- /simple login form -->


                <!-- Footer -->
                <div class="footer text-muted">
                    &copy; 2021. <a href="{{ url('/logout') }}">IoT Dashboard App</a> by <a href="#" target="">TERI Engineering</a>
                </div>
                <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            $( document ).ready(function() {
                new PNotify({
                    title: 'Greška!',
                    text: '{{ $error }}',
                    addclass: 'bg-danger',
                    hide: false,
                    buttons: {
                        sticker: false
                    }
                });
            });
        </script>
    @endforeach
@endif
</body>
</html>
