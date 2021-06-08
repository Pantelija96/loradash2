@extends('layouts.loginlayout')


@section('title') Lora - Login
@endsection


@section('content')
<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
<div class="col-lg-6">
    <div class="p-5">
        <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Dobrodošli!</h1>
        </div>
        <form class="user" method="POST" action="{{ route('loginroute') }}">
          {{ csrf_field() }}
            <div class="form-group">
                <input type="email" class="form-control form-control-user" id="email" name="email" aria-describedby="emailHelp" placeholder="Unesite Email...">
            </div>
            <div class="form-group">
                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Lozinka">
            </div>

            <input type="submit" class="btn btn-danger btn-user btn-block" value="Uđi" />
        </form>
        <hr>
        <!--<div class="text-center">
  <a class="small" href="zaboravljena-lozinka.php">Zaboravili ste lozinku?</a>
  </div>
  <div class="text-center">
  <a class="small" href="registracija.php">Registruj se</a>
  </div> -->
    </div>
</div>
@endsection
