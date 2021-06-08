<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lora Iot Billing Dashboard">
    <meta name="author" content="TERI ENG">

    <title>@yield('title')</title>


    <link href="{{ asset('/') }}vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <link href="{{ asset('/') }}css/lora.css" rel="stylesheet">

    <script src="{{ asset('/') }}vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('/') }}vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="{{ asset('/') }}js/script.js"></script>
</head>


<body class="bg-gradient-danger">

    <div class="container">

        <!--  Okvir -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Card Body -->
                        <div class="row">
                            @yield('content')
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</body>

</html>
