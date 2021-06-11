@extends('layouts.layout')


@section('title') Lora - Users (KAMS)
@endsection

@section('css')

@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dodavanje key account manager-a (KAM-ova)</h1>
</div>

<!-- Content Row Table -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <!-- Dodavanje novih KAM-ova !-->
        <div class="card shadow mb-12">
            <div class="card-body">

                <form method="POST" action="{{ route('addUser') }}">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="ime" class="col-sm-2 col-form-label">Ime</label>
                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="ime" name="ime" placeholder="Ime korisnika">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="prezime" class="col-sm-2 col-form-label">Prezime</label>
                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="prezime" name="prezime" placeholder="Prezime korisnika">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="input" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Lozinka</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Lozinka" value="SifrA123">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" value="submit" class="btn btn-danger">Dodaj korisnika</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
