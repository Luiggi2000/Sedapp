<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrdenCorte
 *
 * @property $id
 * @property $zona_id
 * @property $user_id
 * @property $fecha
 * @property $estado
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @property Zona $zona
 * @property Evidencia[] $evidencias
 * @property Historial[] $historials
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class OrdenCorte extends Model
{
    protected $perPage = 20;

    protected $fillable = ['zona_id', 'user_id', 'fecha', 'estado', 'direccion'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function zona()
    {
        return $this->belongsTo(\App\Models\Zona::class, 'zona_id', 'id');
    }

    public function evidencias()
    {
        return $this->hasMany(\App\Models\Evidencia::class, 'orden_corte_id', 'id');
    }

    public function historials()
    {
        return $this->hasMany(\App\Models\Historial::class, 'orden_corte_id', 'id');
    }
}

