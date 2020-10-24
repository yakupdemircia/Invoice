@extends('layouts.app')

@section('content')

    <div class="pl-5" style="display:flex;align-items: center;">
        <h1>Edit Client</h1>
    </div>

    <form class="p-5" method="POST" action="{{$client->path()}}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Client Name" value="{{$client->name}}">
        </div>

        <div class="form-group">
            <label for="tel">Phone</label>
            <input type="text" class="form-control" name="tel" id="tel" placeholder="Phone" value="{{$client->tel}}">
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Client Email" value="{{$client->email}}">
        </div>

        <div class="form-group">
            <label for="code">Kod</label>
            <input type="text" class="form-control" name="code" id="code" placeholder="Client Code" value="{{$client->code}}">
        </div>

        <button type="submit" class="btn btn-primary" style="float: right;">Save</button>
        <a href="{{$client->path()}}" class="btn btn-success">Cancel</a>
    </form>

@endsection
