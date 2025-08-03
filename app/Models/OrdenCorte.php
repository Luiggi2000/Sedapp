<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrdenCorte
 *
 * @property $id
 * @property $zona_id
 * @property $tecnico_id
 * @property $afectado_id
 * @property $fecha
 * @property $estado
 * @property $direccion
 * @property $observaciones
 * @property $created_at
 * @property $updated_at
 *
 * @property User $tecnico
 * @property User $afectado
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
        'direccion',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date'
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
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'orden_corte_id');
    }

    public function historials()
    {
        return $this->hasMany(Historial::class, 'orden_corte_id');
    }

    public function getEstadoColorClass(): string
    {
        return match($this->estado) {
            'pendiente' => 'bg-yellow-100 text-yellow-800',
            'en_proceso' => 'bg-blue-100 text-blue-800',
            'completada' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
