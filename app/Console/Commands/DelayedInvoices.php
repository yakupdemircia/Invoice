<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DelayedInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DelayedInvoices:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geciken faturaları bulur ve mail atar';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $delayed_invoices = \DB::table('invoices')
            ->whereDate('due_date', '<=', date('Y-m-d'))->where('left','>',0)->get();

        dd($delayed_invoices);

    }
}
