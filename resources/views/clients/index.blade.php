@extends('layouts.app')

@section('content')

    <h1>
        Client List
        <a class="btn btn-primary" style="float: right;" href="/clients/create">New Client</a>
    </h1>
    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>Client Code</th>
            <th>Client Name</th>
            <th>Email</th>
            <th>Tel</th>
            <th>Amount</th>
            <th>Paid</th>
            <th>Left</th>
            <th>Delayed</th>
            <th>Awaiting</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>

        @foreach($clients as $client)

            <tr>
                <td>{{$client->code}}</td>
                <td>{{$client->name}}</td>
                <td>{{$client->email}}</td>
                <td>{{$client->tel}}</td>
                <td>{{$client->totals('amount')}}</td>
                <td>{{$client->totals('paid')}}</td>
                <td>{{$client->totals('left')}}</td>
                <td>{{$client->totals('delayed')}}</td>
                <td>{{$client->totals('awaiting')}}</td>
                <td class="d-flex">
                    <a href="{{$client->path()}}" class="btn btn-info btn-sm text-white mr-1">Invoices</a>

                    <form class="delete" method="post" action="{{$client->path()}}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>

                    <a href="{{$client->path()}}/edit" class="btn btn-primary btn-sm text-white ml-1">Edit</a>

                </td>
            </tr>

        @endforeach

        </tbody>

    </table>
@endsection
