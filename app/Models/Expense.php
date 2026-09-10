<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['user_id', 'description', 'amount', 'expense_date'];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
