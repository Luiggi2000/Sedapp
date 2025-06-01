<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Zona
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @property Corte[] $cortes
 * @property OrdenCorte[] $ordenCortes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Zona extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cortes()
    {
        return $this->hasMany(\App\Models\OrdenCorte::class, 'id', 'zona_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ordenCortes()
    {
        return $this->hasMany(\App\Models\OrdenCorte::class, 'id', 'zona_id');
    }

}
