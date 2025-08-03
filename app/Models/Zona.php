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
    public function ordenCortes()
    {
        return $this->hasMany(OrdenCorte::class, 'zona_id');
    }
}