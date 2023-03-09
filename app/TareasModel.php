<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareasModel extends Model {

    protected $table = 'tareas';
    protected $primaryKey = 'idtarea';
    public $incrementing = TRUE;
    public $timestamps = TRUE;
    protected $fillable = ['idtarea', 'tarea', 'tiempo_estimado', 'completado'];

}
