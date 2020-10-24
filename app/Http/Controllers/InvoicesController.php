<?php

namespace App\Http\Controllers;

use App\Client;
use App\Log;
use App\Mail\PaymentDelayed;
use App\Invoice;
use function Couchbase\passthruDecoder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;

class InvoicesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $invoices = Invoice::with('client')->get();

        return view('invoices.index', compact('invoices'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::all();
        return view('invoices.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (request()->has('excel_file')) {

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            $spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);

            $worksheet = $spreadsheet->getActiveSheet();

            $rows = [];

            foreach ($worksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(TRUE); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }


            for ($i = 1; $i < count($rows); $i++) {

                $row = $rows[$i];

                $client = Client::where('code', $row[0])->first();

                if (!$client) {

                    $client = Client::create(
                        ['code' => $row[0],
                            'name' => $row[1],
                            'email' => $row[2],
                            'tel' => $row[3],
                        ]);
                }

                $order_no = $row[4];

                $amount = $row[5];
                $paid = $row[6];
                $left = $row[7];

                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row[8]);

                $date = date('Y-m-d H:i:s', $date);

                $invoice = Invoice::create([
                    'client_id' => $client->id,
                    'payment_status' => $left == 0 ? 2 : ($paid > 0 ? 3 : 1),
                    'order_no' => $order_no,
                    'due_date' => $date,
                    'amount' => $amount,
                    'paid' => $paid,
                    'left' => $left
                ]);

                Log::create(
                    [
                        'user_id' => Auth()->id(),
                        'invoice_id' => $invoice->id,
                        'operation' => 'Created'
                    ]
                );

            }

            return redirect('/invoices');
        }

        //validate

        $attributes = request()->validate([
            'client_id' => 'required',
            'order_no' => 'required',
            'due_date' => 'required',
            'amount' => 'required'
        ]);

        $attributes['paid'] = 0;
        $attributes['left'] = $attributes['amount'];
        //persist

        $invoice = Invoice::create($attributes);

        Log::create(
            [
                'user_id' => Auth()->id(),
                'invoice_id' => $invoice->id,
                'operation' => 'Created'
            ]
        );
        //redirect

        return redirect('/invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::find($id);

        //Mail::to(Auth()->user())->send(new PaymentDelayed());

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::find($id);
        $clients = Client::all();

        return view('invoices.edit')->with('invoice', $invoice)->with('clients', $clients);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        $ajax = false;
        $mail_sent = false;

        $input = $request->all();

        $ret = array(
            'success' => false,
            'message' => 'Process failed. Please try again.'
        );

        if (array_key_exists('process', $input)) {

            $ajax = true;
            switch ($input['process']) {

                case 'pay_all':
                    $invoice->paid = $invoice->amount;
                    $invoice->left = 0;
                    $invoice->payment_status = 2;
                    $invoice->save();
                    $ret = array(
                        'success' => true,
                        'message' => 'Updated',
                        'last_status' => $invoice->status
                    );

                    break;
                case 'pay_partial':

                    $paid = $input['paid'];

                    if ($invoice->left < $paid) {
                        $ret = array(
                            'success' => false,
                            'message' => 'Can not make payment bigger than left amount.'
                        );
                        break;
                    }

                    $invoice->paid = $invoice->paid + $paid;
                    $invoice->left = $invoice->left - $paid;
                    $invoice->payment_status = 3;
                    $invoice->save();
                    $ret = array(
                        'success' => true,
                        'message' => 'Updated',
                        'last_status' => $invoice->payment_status

                    );

                    break;

                case 'delay':

                    if ($invoice->left == 0) {
                        $ret = array(
                            'success' => true,
                            'message' => 'Paid invoice can not be marked as delayed',
                            'last_status' => $invoice->payment_status
                        );
                        break;
                    }

                    $invoice->payment_status = 4;
                    $invoice->save();
                    $ret = array(
                        'success' => true,
                        'message' => 'Updated',
                        'last_status' => $invoice->payment_status

                    );

                    break;

                case 'send_mail':

                    Mail::to($invoice->client)->send(new PaymentDelayed($invoice));

                    $ret = array(
                        'success' => true,
                        'message' => 'Mail sent.',
                        'last_status' => $invoice->payment_status

                    );
                    $mail_sent = true;

                    break;

                default:
                    break;
            }

        } else {

            $attributes = request()->validate([
                'client_id' => 'required',
                'order_no' => 'required',
                'due_date' => 'required',
                'amount' => 'required',
                'paid' => 'required',
                'left' => 'required'
            ]);

            $invoice->fill($attributes);
            $invoice->save();
        }

        $changes = $invoice->getChanges();
        unset($changes['updated_at']);

        Log::create(
            [
                'user_id' => Auth()->id(),
                'invoice_id' => $invoice->id,
                'operation' => $mail_sent ? 'Mail sent' : json_encode($changes)
            ]
        );

        if ($ajax) {
            return response()->json($ret);
        } else {
            return redirect($invoice->path());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();

        return redirect('/invoices');
    }
}
