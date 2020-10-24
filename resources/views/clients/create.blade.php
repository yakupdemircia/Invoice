@extends('layouts.app')

@section('content')

        <h1>Client Oluştur</h1>

    <form method="POST" action="/clients">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Client Name">
        </div>

        <div class="form-group">
            <label for="tel">Phone</label>
            <input type="text" class="form-control" name="tel" id="tel" placeholder="Phone">
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Client Email">
        </div>

        <div class="form-group">
            <label for="code">Kod</label>
            <input type="text" class="form-control" name="code" id="code" placeholder="Client Code">
        </div>

        <a href="/clients" class="btn btn-success">Cancel</a>

        <button type="submit" class="btn btn-primary" style="float: right">Save</button>
    </form>

@endsection
