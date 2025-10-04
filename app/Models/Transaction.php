<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'chart_of_account_id', 'description', 'debit', 'credit'];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
}

