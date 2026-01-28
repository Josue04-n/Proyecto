# 📊 Arquitectura de Base de Datos - Proyecto Ternos

## 🎯 Reglas Arquitectónicas Aplicadas

### **REGLA 1: Catálogos / Entidades**
Las entidades maestras que representan recursos del negocio llevan control de activación:
```php
$table->boolean('is_active')->default(true)->comment('Control de activación');
```

### **REGLA 2: Procesos / Flujos**
Las transacciones que siguen un flujo de estado llevan enumeraciones:
```php
$table->enum('estado', ['pendiente', 'procesando', 'completado', 'anulado'])->default('pendiente');
```

---

## 📋 Clasificación de Tablas

### ✅ CATÁLOGOS/ENTIDADES (REGLA 1) - Control `is_active` o `estado` booleano

| Tabla | Control de Estado | Tipo | Comentario |
|-------|------------------|------|-----------|
| `users` | `is_active` (boolean) | Catálogo | Usuarios del sistema |
| `operarios` | `estado` (boolean) | Catálogo | Trabajadores |
| `clientes` | `activo` (boolean) | Catálogo | Clientes del negocio |
| `proveedores` | `is_active` (boolean) | Catálogo | Proveedores ✅ *Agregado* |
| `tipos_prenda` | `estado` (boolean) | Catálogo | Tipos de prendas |
| `insumos` | `estado` (boolean) | Catálogo | Materiales/insumos |
| `locales` | - | Catálogo | Sucursales |
| `tarifas` | - | Catálogo | Precios |
| `contratos` | - | Catálogo | Acuerdos comerciales |

---

### 🔄 PROCESOS/FLUJOS (REGLA 2) - Control `enum estado`

| Tabla | Estados | Tipo | Flujo de Negocio |
|-------|---------|------|-----------------|
| `ordenes_produccion` | `pendiente`, `en_proceso`, `parcial`, `finalizada`, `cerrada`, `entregada` | Flujo | Ciclo de fabricación |
| `compras` | `pendiente`, `recibida`, `anulada` | Flujo | Adquisición de insumos |
| `ventas` | `estado_pago`: `pagado`, `pendiente`, `anulado` | Flujo | Transacciones comerciales |
| `asignaciones_trabajo` | - | Flujo | Distribución de tareas |
| `entregas_produccion` | - | Flujo | Entrega de productos |
| `movimientos_caja` | - | Flujo | Transacciones monetarias |
| `pagos_operarios` | - | Flujo | Compensación laboral |

---

### 📦 DETALLES/ÍTEMS (Tablas Dependientes)

| Tabla | Relacionada | Propósito |
|-------|-------------|----------|
| `detalle_compras` | `compras` | Líneas de compra |
| `detalle_ventas` | `ventas` | Líneas de venta |
| `consumos_orden` | `ordenes_produccion` | Insumos consumidos |
| `asignaciones_trabajo` | `ordenes_produccion` | Tareas asignadas |

---

### ⚙️ AUXILIARES/CONFIGURACIÓN

| Tabla | Propósito |
|-------|----------|
| `periodos_contables` | Períodos fiscales |
| `prendas_tienda` | Inventario de prendas |
| `inventario_sucursales` | Stock por ubicación |
| `transferencias_inventario` | Movimientos internos |
| `cuentas` | Catálogo contable |

---

## 🔐 Auditoría en Todas las Tablas

Todas las tablas transaccionales incluyen:
```php
$table->unsignedBigInteger('created_by')->nullable();  // Usuario que creó
$table->unsignedBigInteger('updated_by')->nullable(); // Usuario que actualizó
$table->timestamps();  // created_at, updated_at
```

Modelo implementado en `App\Models\Cliente`:
```php
protected static function boot() {
    static::creating(fn($model) => $model->created_by = Auth::id());
    static::updating(fn($model) => $model->updated_by = Auth::id());
}
```

---

## 🗑️ Soft Deletes

Tablas con eliminación lógica (softDeletes):
- `clientes`
- `operarios`
- Otras entidades críticas

---

## 📝 Cambios Realizados

✅ **Usuarios**: Agregado `is_active` como catálogo del sistema  
✅ **Proveedores**: Agregado `is_active` para consistencia  
✅ **Órdenes**: Ya tenía enum de estado detallado  
✅ **Compras**: Ya tenía enum de estado  
✅ **Ventas**: Ya tenía `estado_pago`  

---

## 🎓 Notas de Diseño

1. **Consistencia**: Todas las entidades maestras tienen control de estado
2. **Trazabilidad**: Auditoría completa en todas las tablas transaccionales
3. **Flexibilidad**: Enums permiten agregar estados sin alterar la estructura
4. **Integridad**: Foreign keys mantienen relaciones consistentes
5. **Seguridad**: Soft deletes preservan datos históricos

