<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property $id
 * @property $name
 * @property $apellido
 * @property $telefono
 * @property $rol_id
 * @property $email
 * @property $email_verified_at
 * @property $password
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 *
 * @property Role $role
 * @property OrdenCorte[] $ordenCortes
 * @property Historial[] $historials
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Authenticatable
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'apellido', 'telefono', 'password', 'rol_id', 'email'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ordenCortesTecnico()
    {
        return $this->hasMany(OrdenCorte::class, 'tecnico_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ordenCortesAfectado()
    {
        return $this->hasMany(OrdenCorte::class, 'afectado_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historials()
    {
        return $this->hasMany(Historial::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRolNombre(): string
    {
        return optional($this->role)->name ?? 'Sin Rol';
    }

    public function getNombreCompleto(): string
    {
        return $this->name . ' ' . $this->apellido;
    }
    public function ordenCortes()
{
    return $this->hasMany(\App\Models\OrdenCorte::class, 'tecnico_id');
}

}
