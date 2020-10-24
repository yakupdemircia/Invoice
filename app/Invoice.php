<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{

    protected $guarded = [];
    use SoftDeletes;

    public function client()
    {
        return $this->belongsTo('App\Client','client_id','id');
    }

    public function logs(){

        return $this->hasMany('App\Log','invoice_id','id');
    }

    public function path()
    {
        return "/invoices/{$this->id}";
    }


    public function statusText(){

        $status = '';

        switch ($this->payment_status){
            case 1:
                $status = 'Awaiting';
                break;
            case 2:
                $status = 'Paid';
                break;
            case 3:
                $status = 'Partially Paid';
                break;
            case 4:
                $status = 'Delayed';
                break;
            default:
                $status = 'Awaiting';
        }
        return $status;
    }

    public function statusColor(){

        $color = '';

        switch ($this->payment_status){
            case 1:
                $color = 'gray';
                break;
            case 2:
                $color = 'green';
                break;
            case 3:
                $color = 'orange';
                break;
            case 4:
                $color = 'red';
                break;
            default:
                $color = 'gray';
        }

        return $color;

    }
}
