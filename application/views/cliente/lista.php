<?php
/**
 * Created by PhpStorm.
 * User: Juan Carlos
 * Date: 16/02/2018
 * Time: 09:33 AM
 */
?>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th class="text-center">Cedula</th>
        <th class="text-center">Nombre Cliente</th>
        <th class="text-center">Telefono</th>
        <th class="text-center">Correo</th>
        <!-- <th class="text-center">Fecha Nac.</th>-->
        <th class="text-center">Trabajo</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($clientes as $row) {
        ?>
        <tr>
            <td><?= $row->ci_nit ?></td>
            <td><?= ucwords(strtolower($row->nombre_cliente)) ?></td>
            <td><?= $row->telefono ?></td>
            <td><?= $row->correo ?></td>
            <!-- <td><?= date('d/m/Y', strtotime($row->fecha_nacimiento)) ?></td>-->
            <td><?= $row->trabajo ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
