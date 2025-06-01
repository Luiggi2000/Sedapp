<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Historial
 *
 * @property $id
 * @property $orden_corte_id
 * @property $user_id
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property OrdenCorte $ordenCorte
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Historial extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['orden_corte_id', 'user_id', 'observaciones'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordenCorte()
    {
        return $this->belongsTo(\App\Models\OrdenCorte::class, 'orden_corte_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    
}
