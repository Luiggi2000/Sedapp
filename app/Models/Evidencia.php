<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Evidencia
 *
 * @property $id
 * @property $orden_corte_id
 * @property $imagen
 * @property $created_at
 * @property $updated_at
 *
 * @property OrdenCorte $ordenCorte
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Evidencia extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['orden_corte_id', 'imagen', 'observaciones', 'tipo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordenCorte()
    {
        return $this->belongsTo(OrdenCorte::class, 'orden_corte_id', 'id');
    }

}
