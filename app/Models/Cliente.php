<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cliente extends Model
{
    protected $fillable = [
        'tipo_cliente',
        'identificacion',
        'razon_social',
        'primer_nombre',
        'segundo_nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'telefono',
        'direccion',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Boot del modelo - Auditoría automática
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * SCOPE: Solo clientes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * SCOPE: Solo clientes inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * SCOPE: Incluir eliminados (para auditoría)
     */
    public function scopeConEliminados($query)
    {
        return $query->withTrashed();
    }

    /**
     * Relaciones
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}