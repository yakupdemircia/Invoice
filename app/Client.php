<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    protected $guarded = [];
    use SoftDeletes;

    public function path()
    {
        return "/clients/{$this->id}";
    }

    public function invoices(){

        return $this->hasMany(Invoice::class,'client_id','id');

    }

    public function totals($key){

        switch ($key){

            case 'paid':
                return number_format(Invoice::where('client_id',$this->id)->sum('paid'),2);
                break;

            case 'amount':
                return number_format(Invoice::where('client_id',$this->id)->sum('amount'),2);
                break;

            case 'left':
                return number_format(Invoice::where('client_id',$this->id)->sum('left'),2);
                break;

            case 'delayed':

                return number_format(Invoice::where(['client_id'=>$this->id,'payment_status'=>4])->sum('left'),2);

                break;

            case 'awaiting':
                $t = Invoice::where(['client_id'=>$this->id,'payment_status'=>1])->sum('left');
                $t += Invoice::where(['client_id'=>$this->id,'payment_status'=>3])->sum('left');

                return number_format($t,2);

                break;

            default:
                break;
        }


    }

}
