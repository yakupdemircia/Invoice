@extends('layouts.app')

@section('content')

    <div class="pl-5" style="display:flex;align-items: center;">
        <h1>Edit Invoice</h1>
    </div>

    <form class="p-5" method="POST" action="{{$invoice->path()}}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="client_id">Client</label>
            <select class="form-control select2" name="client_id" id="client_id" required>
                <option value="">Select Client</option>
                @foreach($clients as $client)
                    <option value="{{$client->id}}" {{$invoice->client->id == $client->id ? 'selected="selected"':''}}>{{$client->code}} - {{$client->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="order_no">Invoice No</label>
            <input type="text" class="form-control" name="order_no" id="order_no" value="{{$invoice->order_no}}" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" name="amount" id="amount" value="{{$invoice->amount}}" required>
        </div>

        <div class="form-group">
            <label for="paid">Paid</label>
            <input type="number" class="form-control" name="paid" id="paid" value="{{$invoice->paid}}" required>
        </div>

        <div class="form-group">
            <label for="left">Left</label>
            <input type="number" class="form-control" name="left" id="left" value="{{$invoice->left}}" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" name="due_date" id="date" value="{{date('Y-m-d',strtotime($invoice->due_date))}}" required>
        </div>

        <button type="submit" class="btn btn-primary" style="float: right;">Save</button>

        <a href="{{$invoice->path()}}" class="btn btn-success">Cancel</a>
    </form>

@endsection
