@extends('layouts.layout')


@section('title') Lora - Password Change
@endsection

@section('css')

@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Promena lozinke</h1>
</div>
<!-- Content Row Table -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <div class="card shadow mb-12">

            <div class="card-body">

                <form method="POST" action="{{ route('passwordchange') }}">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="newPassword" class="col-sm-2 col-form-label">Nova lozinka</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Nova lozinka">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="newPasswordAgain" class="col-sm-2 col-form-label">Nova lozinka ponovo</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="newPasswordAgain" name="newPasswordAgain" placeholder="Nova lozinka ponovo" onkeyup="checkPasswords()">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" id="submitNewPassword" disabled name="submit" value="true" class="btn btn-danger">Promeni lozinku</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
