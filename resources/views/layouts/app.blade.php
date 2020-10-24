<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/toastr.min.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <style>
        a.btn{
            color:#fff!important;
        }

        table tbody tr.green,.green{
            background: #28a745!important;
        }

        table tbody tr.orange,.orange{
            background: #ffc107!important;
        }

        table tbody tr.red,.red{
            background: #b91d19!important;
            color:#ffffff!important;
        }

    </style>
</head>
<body>

<script>
    window.addEventListener('DOMContentLoaded',function(){
        $('#dataTable').dataTable();

        $(".delete").on("submit", function(){
            return confirm("Kayıt silinsin mi?");
        });

        $('.select2').select2();

        let recipient = '';
        let invoice_no = ';'
        let amount = 0;
        let paid = 0;
        let left = 0;
        let path = '';
        let tr=null;

        $('#invoiceModal').on('show.bs.modal', function (event) {

            let button = $(event.relatedTarget) ;// Button that triggered the modal
             recipient = button.data('client_name'); // Extract info from data-* attributes
             invoice_no = button.data('invoice_no'); // Extract info from data-* attributes
             amount = button.data('amount'); // Extract info from data-* attributes
             paid = button.data('paid'); // Extract info from data-* attributes
             left = button.data('left'); // Extract info from data-* attributes
             path = button.data('path'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            let modal = $(this);
            modal.find('.modal-title').text('Invoice Operations - ' + invoice_no);
            modal.find('.modal-body input#recipient-name').val(recipient);
            modal.find('.modal-body form.delete').attr('action',path);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            tr = button.parents('tr');

            $(".pay_all",modal).off('click').click(function(e){

                let t = $(this);
                e.preventDefault();

                $.ajax({
                    type:'PATCH',
                    url:path,
                    data:{process:'pay_all'},
                    success:function(data){

                        if(data.success == true){

                            toastr.success(data.message);
                            tr.removeClass('red');
                            tr.removeClass('orange');
                            tr.addClass('green');
                            $('.status_text',tr).html('Paid');
                            $('#invoiceModal').modal('hide');

                        }else{
                            toastr.error(data.message);
                        }

                    }
                });
            });

            $(".delayed",modal).off('click').click(function(e){

                let t = $(this);
                e.preventDefault();

                $.ajax({
                    type:'PATCH',
                    url:path,
                    data:{process:'delay'},
                    success:function(data){
                        if(data.success==true){
                            toastr.success(data.message);
                            tr.addClass('red');
                            $('.status_text',tr).html('Delayed');
                            $('#invoiceModal').modal('hide');
                        }else{
                            toastr.error(data.message);
                        }

                    }
                });
            });

            $(".send_mail",modal).off('click').click(function(e){

                let t = $(this);
                e.preventDefault();

                $.ajax({
                    type:'PATCH',
                    url:path,
                    data:{process:'send_mail'},
                    success:function(data){
                        if(data.success==true){
                            toastr.success(data.message);
                            $('#invoiceModal').modal('hide');
                        }else{
                            toastr.error(data.message);
                        }

                    }
                });
            });

        });

        $('#modal_pay_partial').on('show.bs.modal', function (event) {

            $('#invoiceModal').modal('hide');

            let modal = $(this);

            $('#to_be_paid',modal).val(left);

            $(".pay",modal).off('click').click(function(e){

                let t = $(this);
                e.preventDefault();

                $.ajax({
                    type:'PATCH',
                    url:path,
                    data:{process:'pay_partial',paid:$('#to_be_paid').val()},
                    success:function(data){
                        if(data.success==true){
                            toastr.success(data.message+'(Please refresh page to see changes.)');
                            tr.addClass('orange');
                            $('.status_text',tr).html('Partially Paid');
                            $('#modal_pay_partial').modal('hide');
                        }else{
                            toastr.error(data.message);
                        }

                    }
                });
            });

        });

    });

</script>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/invoices">
                                        Invoices
                                    </a>
                                    <a class="dropdown-item" href="/clients">
                                        Clients
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="p-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
