<?php

namespace App\Http\Controllers;

use App\TareasModel;
use Illuminate\Http\Request;

class TareasController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tareas = TareasModel::orderBy('idtarea', 'ASC')->get();

        return view('index', ['tareas' => $tareas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->validate([
            'tarea' => 'required',
            'tiempo_estimado' => 'required'
        ]);

        TareasModel::create($data);

        return response()->json(['success' => 'true']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TareasModel  $tareasModel
     * @return \Illuminate\Http\Response
     */
    public function show(TareasModel $tareasModel, $idtarea) {
        $recurso = $tareasModel->findOrFail($idtarea);
        
        return response()->json($recurso);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TareasModel  $tareasModel
     * @return \Illuminate\Http\Response
     */
    public function edit(TareasModel $tareasModel) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TareasModel  $tareasModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TareasModel $tareasModel, $idtarea) {
        $iniciar = $request->input('iniciar_tarea');
        $completado = $request->input('completado');

        $recurso = $tareasModel->findOrFail($idtarea);

        if (!isset($completado) && !isset($iniciar)) {
            $data = $request->validate([
                'tarea' => 'required',
                'tiempo_estimado' => 'required'
            ]);
        } elseif (isset($iniciar) && !empty($iniciar)) {
            $ini = date('Y-m-d H:i:s');

            $data = [
                'fecha_inicio' => $ini,
                'fecha_fin' => date('Y-m-d H:i:s', strtotime('+' . ($recurso->tiempo_estimado * 60) . ' minutes', strtotime($ini)))
            ];
        } else {
            $data = [
                'completado' => $completado === 'true' ? 1 : 0,
                'fecha_completo' => date('Y-m-d H:i:s')
            ];
        }

        $tareasModel->where('idtarea', $idtarea)->update($data);

        return response()->json(['success' => TRUE]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TareasModel  $tareasModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(TareasModel $tareasModel, $idtarea) {
        $tareasModel->where('idtarea', $idtarea)->delete();

        return response()->json(['success' => TRUE]);
    }

}
