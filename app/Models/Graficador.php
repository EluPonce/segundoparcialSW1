<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graficador extends Model
{
    // Esto es necesario si deseas utilizar la asignación masiva
    protected $fillable = ['Titulo', 'Descripcion', 'FechaCreacion', 'HoraCreacion','Estado','Contenido']; // Reemplaza estos nombres con los campos reales de tu tabla

    // Si la tabla no sigue la convención de pluralización o es diferente, define el nombre de la tabla
    protected $table = 'graficador'; // Si tu tabla no es 'graficadores', asegúrate de colocar el nombre correcto aquí

    protected $primaryKey = 'IdGraficador'; 
    // Si tienes alguna columna de tipo fecha que se maneje automáticamente, puedes agregarla aquí
    // protected $dates = ['created_at', 'updated_at']; // Esto es solo si usas fechas

}
