<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $fillable = ['currency_code', 'exchange_rate', 'date'];
protected $table = 'exchange_rates';


}
