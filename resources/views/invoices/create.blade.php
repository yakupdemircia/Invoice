@extends('layouts.app')

@section('content')

        <h1>Create Invoice</h1>

    <form method="POST" action="/invoices">
        @csrf
        <div class="form-group">
            <label for="client_id">Client</label>
            <select class="form-control select2" name="client_id" id="client_id" required>
                <option value="">Select Client</option>
                @foreach($clients as $client)
                    <option value="{{$client->id}}">{{$client->code}} - {{$client->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="order_no">Invoice No</label>
            <input type="text" class="form-control" name="order_no" id="order_no" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" name="amount" id="amount" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="due_date" id="date" required>
        </div>

        <a href="/invoices" class="btn btn-success">Cancel</a>

        <button type="submit" class="btn btn-primary" style="float: right">Save</button>
    </form>

@endsection
