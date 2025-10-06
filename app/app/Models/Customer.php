<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    // Hanya kolom yang ada di migration
    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    // Contoh relasi, sesuaikan jika tidak ada table 'transactions'
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
