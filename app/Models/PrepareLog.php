<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrepareLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'shift',
        'item_id',
        'stok_awal',
        'stok_prepare',
        'stok_terpakai',
        'stok_sisa',
        'user_id'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->stok_sisa = $model->stok_awal + $model->stok_prepare - $model->stok_terpakai;
        });
    }
}
