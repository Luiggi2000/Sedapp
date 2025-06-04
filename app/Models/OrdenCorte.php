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

protected $fillable = [
    'zona_id',
    'tecnico_id',
    'afectado_id',
    'fecha',
    'estado',
    'direccion'
];

  public function tecnico()
{
    return $this->belongsTo(User::class, 'tecnico_id');
}

public function afectado()
{
    return $this->belongsTo(User::class, 'afectado_id');
}

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id', 'id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'orden_corte_id', 'id');
    }

    public function historials()
    {
        return $this->hasMany(Historial::class, 'orden_corte_id', 'id');
    }
    
}

