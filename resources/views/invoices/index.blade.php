@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <h1>
                Invoice List
            </h1>
        </div>
        <div class="col-md-6">
            <form method="post" action="/invoices" enctype="multipart/form-data">
                @csrf
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <a class="btn btn-primary" style="float: right;" href="/invoices/create">New Invoice</a>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="excel_file" name="excel_file" required>
                                <label class="custom-file-label" for="excel_file">Import From Excel</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-warning">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>Client Code</th>
            <th>Client Name</th>
            <th>Invoice No</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Left</th>
            <th>Date</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>

        @foreach($invoices as $invoice)

            <tr class="{{$invoice->statusColor()}}">
                <td><a href="{{$invoice->client->path()}}">{{$invoice->client->code}}</a></td>
                <td><a href="{{$invoice->client->path()}}">{{$invoice->client->name}}</a></td>
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
            <th colspan="3">Totals:</th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="3"></th>
        </tr>
        </tfoot>

    </table>

    @include('layouts.modal')

@endsection
