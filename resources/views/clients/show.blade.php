@extends('layouts.app')

@section('content')

    <h1>
        Client Details ({{$client->name}})
        <a class="btn btn-primary" style="float: right" href="{{$client->path()}}/edit">Edit Client</a>
    </h1>

    <div>
        <ul>
            <li>Name: {{$client->name}}</li>
            <li>Code: {{$client->code}}</li>
            <li>Email: {{$client->email}}</li>
            <li>Tel: {{$client->tel}}</li>
            <li>Paid Total: {{$client->totals('paid')}}</li>
            <li>Left Total: {{$client->totals('left')}}</li>
            <li>Delayed Total: {{$client->totals('delayed')}}</li>
        </ul>
    </div>

    <h3>Invoices:</h3>
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Left</th>
            <th>Date</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>

        @foreach($client->invoices as $invoice)

            <tr class="{{$invoice->statusColor()}}">
                <td>{{$invoice->order_no}}</td>
                <td>{{number_format($invoice->amount,2)}}</td>
                <td>{{number_format($invoice->paid,2)}}</td>
                <td>{{number_format($invoice->left,2)}}</td>
                <td>{{date('d.m.Y',strtotime($invoice->due_date))}}</td>
                <td class="status_text">{{$invoice->statusText()}}</td>
                <td class="text-right d-flex">
                    <a href="{{$invoice->path()}}" class="btn btn-info btn-sm fg-white mr-1">Details</a>
                    <button class="btn btn-primary btn-sm ml-1 openModal"
                            data-toggle="modal" data-target="#invoiceModal"
                            data-client_name="{{$invoice->client->name}}"
                            data-invoice_no="{{$invoice->order_no}}"
                            data-amount="{{$invoice->amount}}"
                            data-paid="{{$invoice->paid}}"
                            data-left="{{$invoice->left}}"
                            data-path="{{$invoice->path()}}"
                    >Actions
                    </button>
                </td>
            </tr>

        @endforeach

        </tbody>

        <tfoot>
            <tr>
                <th>Toplamlar:</th>
                <th>{{$client->totals('amount')}}</th>
                <th>{{$client->totals('paid')}}</th>
                <th>{{$client->totals('left')}}</th>
                <th colspan="3"></th>
            </tr>
        </tfoot>

    </table>

    <a class="btn btn-info" href="/clients">Return To List</a>

    @include('layouts.modal')

@endsection
