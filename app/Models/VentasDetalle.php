<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasDetalle extends Model
{
    use HasFactory;
    protected $table = 'pedido';

    protected $fillable = [
        'cliente_id',
        'fecha',
        'estado',
    ];

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con el modelo PedidoProductos
    public function productos()
    {
        return $this->hasMany(PedidoProductos::class, 'pedido_id');
    }

    // Relación con el modelo CambioEstado
    public function cambiosEstado()
    {
        return $this->hasMany(CambioEstado::class, 'pedido_id');
    }

    // Relación con el modelo Evidencia
    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'pedido_id');
    }

    // Método para obtener el total del pedido
    public function getTotal()
    {
        $total = 0;

        foreach ($this->productos as $producto) {
            $total += $producto->cantidad * $producto->producto->precio;
        }

        return $total;
    }

    // Método para obtener el importe pagado
    public function getImportePagado()
    {
        // Lógica para calcular el importe pagado según tu estructura de base de datos
    }

    // Método para obtener el importe debido
    public function getImporteDebido()
    {
        // Lógica para calcular el importe debido según tu estructura de base de datos
    }
}

