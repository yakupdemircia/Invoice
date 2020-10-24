@extends('layouts.app')

@section('content')

        <h1>
            Invoice Details ({{$invoice->order_no}})
            <a class="btn btn-primary" style="float: right" href="{{$invoice->path()}}/edit">Edit Invoice</a>
        </h1>

    <!-- @todo Totals to be calculated -->
    <div>
        <ul>
            <li>Client Code: <a href="{{$invoice->client->path()}}">{{$invoice->client->code}}</a></li>
            <li>Client: <a href="{{$invoice->client->path()}}">{{$invoice->client->name}}</a></li>
            <li>Date: {{date('d.m.Y',strtotime($invoice->due_date))}}</li>
            <li>Status: <span class="{{$invoice->statusColor()}}">{{$invoice->statusText()}}</span></li>
            <li>Amount: {{number_format($invoice->amount,2)}}</li>
            <li>Paid: {{number_format($invoice->paid,2)}}</li>
            <li>Left: {{number_format($invoice->left,2)}}</li>
        </ul>
    </div>

    <h3>Logs:</h3>
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>User</th>
            <th>Action</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>

        @foreach($invoice->logs as $log)

            <tr>
                <td>{{$log->user->name}}</td>
                <td>{{$log->operation}}</td>
                <td>{{$log->created_at}}</td>
            </tr>


         @endforeach
        </tbody>

    </table>

    <a class="btn btn-info" href="/invoices">Return To List</a>

@endsection
