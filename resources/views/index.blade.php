<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.1.96/css/materialdesignicons.min.css" rel="stylesheet" crossorigin="anonymous">

        <style>
            .form-switch-lg{
                min-height: 2.375em;
                width: 60px;
                margin: 0 auto;
            }
            .form-switch-lg .form-check-input{
                width: 3.82em; /*height x 2*/
                height: 1.91em;
            }

            .form-switch-lg .form-check-label{
                height: 2.375em;
                line-height: 2.375em;
                padding-left: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Tareas</h1>
            <hr>
            <button id="btn-agregar" class="btn btn-primary mb-4"><i class="mdi mdi-plus"></i> Agregar</button>

            <table id="table-registros" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">Tarea</th>
                        <th class="text-center" scope="col">Tiempo Estimado</th>
                        <th class="text-center" scope="col">Creación</th>
                        <th class="text-center" scope="col">Inicio</th>
                        <th class="text-center" scope="col">Fin</th>
                        <th class="text-center" scope="col">Terminado</th>
                        <th class="text-center" scope="col">Completar</th>
                        <th class="text-center" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($tareas as $key => $tarea) {
                        $creacion = strtotime($tarea->created_at);
                        $inicio = strtotime($tarea->fecha_inicio);
                        $fin = strtotime($tarea->fecha_fin);
                        $completado = strtotime($tarea->fecha_completo);
                        ?>
                        <tr id="tarea-<?= $tarea->idtarea ?>">
                            <th scope="row"><?= $tarea->idtarea ?></th>
                            <td scope="col"><?= $tarea->tarea ?></td>
                            <td scope="col" class="text-center"><?= $tarea->tiempo_estimado ?> Horas</td>
                            <td scope="col" class="text-center"><?= $creacion ? date('d/m/Y', $creacion) : '' ?></td>
                            <td scope="col" class="text-center"><?= $inicio ? date('d/m/Y H:i', $inicio) : '' ?></td>
                            <td scope="col" class="text-center"><?= $fin ? date('d/m/Y H:i', $fin) : '' ?></td>
                            <td scope="col" class="text-center"><?= $completado ? date('d/m/Y H:i', $completado) : '' ?></td>
                            <td scope="col">
                                <?php
                                if ($inicio) {
                                    ?>
                                    <div class="form-check form-switch form-switch-lg">
                                        <input class="form-check-input check-completar" type="checkbox" role="switch" value="<?= $tarea->idtarea ?>" <?= intval($tarea->completado) === 1 ? 'checked disabled' : '' ?>>
                                    </div>
                                    <?php
                                }
                                ?>
                            </td>
                            <td scope="col" class="text-center">
                                <?php
                                if (intval($tarea->completado) === 0 && !$inicio) {
                                    ?>
                                    <button class="btn btn-success me-2 btn-iniciar" data-tarea="<?= $tarea->idtarea ?>" data-bs-toggle="tooltip" data-bs-title="Iniciar"><i class="mdi mdi-play"></i></button> | 
                                    <button class="btn btn-warning ms-2 me-2 btn-editar" data-tarea="<?= $tarea->idtarea ?>" data-bs-toggle="tooltip" data-bs-title="Editar"><i class="mdi mdi-pencil"></i></button> | 
                                    <button class="btn btn-danger ms-2 btn-eliminar" data-tarea="<?= $tarea->idtarea ?>" data-bs-toggle="tooltip" data-bs-title="Eliminar"><i class="mdi mdi-trash-can"></i></button>
                                    <?php
                                }
                                ?>


                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <form id="modal-crear-editar" method="POST" action="#" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
            @csrf
        </form>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <script>
            $(function () {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

                const $table = $('#table-registros');
                const $formModal = $('#modal-crear-editar');
                let formTmp = [
                    '<div class="mb-3">',
                    '    <label for="exampleFormControlInput1" class="form-label">Nombre tarea</label>',
                    '    <input type="text" class="form-control" name="tarea" required>',
                    '</div>',
                    '<div class="mb-3">',
                    '    <label for="exampleFormControlInput1" class="form-label">Tiempo estimado</label>',
                    '    <input type="number" class="form-control" name="tiempo_estimado" step="0.5" min="0.5" required>',
                    '</div>',
                    ''
                ];
                let uri = "";
                let method = "POST";

                /*
                 * ACCION DE INICIAR TAREA
                 */
                $table.on('click', '.btn-iniciar', function (ev) {
                    ev.preventDefault();

                    uri = "http://localhost/prueba/public/tareas/" + this.dataset.tarea;
                    method = "POST";

                    formTmp.pop();
                    formTmp.push('');

                    $formModal.find('.modal-body').html([
                        '<input type="hidden" name="iniciar_tarea" value="' + this.dataset.tarea + '">',
                        '<input type="hidden" name="_method" value="PUT">'
                    ].join(''));

                    $formModal.trigger('submit');
                });

                /*
                 * ACCION DE CREAR TAREA
                 */
                $('#btn-agregar').on('click', function (ev) {
                    ev.preventDefault();

                    uri = "<?= route('tareas.store') ?>";
                    method = "POST";

                    formTmp.pop();
                    formTmp.push('');

                    $formModal.find('.modal-title').text('Crear tarea');
                    $formModal.find('.modal-body').html(formTmp.join(''));
                    $formModal.modal('toggle');
                });

                /*
                 * ACCION DE EDITAR TAREA
                 */
                $table.on('click', '.btn-editar', function (ev) {
                    ev.preventDefault();

                    uri = "http://localhost/prueba/public/tareas/" + this.dataset.tarea;
                    method = "POST";

                    formTmp.pop();
                    formTmp.push('<input type="hidden" name="_method" value="PUT">');

                    $formModal.find('.modal-title').text('Editar tarea');
                    $formModal.find('.modal-body').html(formTmp.join(''));

                    $.ajax({
                        method: 'GET',
                        url: uri,
                        data: {}
                    }).done(function (response) {
                        let $inputs = $formModal.find('.form-control');

                        $inputs.each(function (index, elem) {
                            var name = elem.name;

                            elem.value = response[name];
                        });

                        $formModal.modal('toggle');
                    });
                });

                /*
                 * ACCION DE ELIMINAR TAREA
                 */
                $table.on('click', '.btn-eliminar', function (ev) {
                    ev.preventDefault();

                    uri = "http://localhost/prueba/public/tareas/" + this.dataset.tarea;
                    method = "POST";

                    formTmp.pop();
                    formTmp.push('');

                    let confirmar = confirm('Seguro que desea eliminar la tarea #' + this.dataset.tarea + '?');

                    if (confirmar) {
                        $formModal.find('.modal-body').html([
                            '<input type="hidden" name="_method" value="DELETE">'
                        ].join(''));

                        $formModal.trigger('submit');
                    }
                });

                /*
                 * ACCION DE DAR POR COMPLETADO UNA TAREA
                 */
                $table.on('change', '.check-completar', function (ev) {
                    ev.preventDefault();

                    uri = "http://localhost/prueba/public/tareas/" + this.value;
                    method = "POST";

                    formTmp.pop();
                    formTmp.push('');

                    $formModal.find('.modal-body').html([
                        '<input type="hidden" name="completado" value="' + this.checked + '">',
                        '<input type="hidden" name="_method" value="PUT">'
                    ].join(''));

                    $formModal.trigger('submit');
                });

                /*
                 * ENVIO DEL FORMULARIO
                 */
                $formModal.on('submit', function (ev) {
                    ev.preventDefault();

                    $.ajax({
                        method: method,
                        url: uri,
                        data: $(this).serialize()
                    }).done(function (msg) {
                        location.reload();
                    });

                    return false;
                });

                $formModal.on('hidden.bs.modal', function (ev) {
                    ev.preventDefault();

                    $formModal.find('.modal-body').empty();
                });
            });
        </script>
    </body>
</html>