<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Usuarios
            <small>Panel de control</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fa fa-dashboard"></i>Inicio</a></li>
            <li class="active">Tablero</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
            <button class="btn btn-primary" data-toggle="modal" data-target="#ModalIngresarUsuario">
                Agregar Usuario
            </button>
        </div>
        <div class="box-body">
            <table class="table table-bordered dt-responsive table-striped table-hover dtable" width="100%">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Foto</th>
                        <th>Perfil</th>
                        <th>Estado</th>
                        <th>Ultimo login</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $item = null;
                    $value = null;
                    $respuesta = new UsersController();
                    $users = $respuesta->ctrViewsUsers($item, $value);
                ?>
                <?php foreach ($users as $key => $value) { ?>
                    <tr>
                        <td><?= $value['id'] ?></td>
                        <td><?= $value['name'] ?></td>
                        <td><?= $value['user'] ?></td>
                        <?php 
                            if ($value['avatar'] != '') {
                                echo '<td><img src="'.$value['avatar'].'" class="img-thumbnail" width="40px" alt=""></td>';        
                            } else {
                                echo '<td><img src="views/img/users/default/anonymous.png" class="img-thumbnail" width="40px" alt=""></td>';
                            }
                        ?>
                        <?php 
                            switch ($value['profile']) {
                                case 1:
                                    echo '<td>Administrador</td>';
                                    break;
                                case 2:
                                    echo '<td>Especial</td>';
                                    break;
                                case 3:
                                    echo '<td>Vendedor</td>';
                                    break;
                            }

                            if ($value['status'] != 0) {
                                echo '<td><button class="btn btn-success btn-xs btnActivar" idUsuario="'.$value['id'].'" status="0">Activado</button></td>';
                            } else {
                                echo '<td><button class="btn btn-danger btn-xs btnActivar" idUsuario="'.$value['id'].'" status="1">Desactivado</button></td>';
                            }
                        ?>
                        
                        <td><?= $value['last_login'] ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning btnEditarUsuario" idUsuario="<?= $value['id'] ?>" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btnDeleteUser" idUsuario="<?= $value['id'] ?>" usuario="<?= $value['name'] ?>" fotoUsuario="<?= $value['avatar'] ?>"><i class="fa fa-times"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
</div>

<!-- Modal Crear Usuario -->
<div id="ModalIngresarUsuario" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST" enctype="multipart/form-data">
                <!-- Modal header -->
                <div class="modal-header" style="background-color: #f39c12 ">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Usuario</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Ingresar Nombre -->
                        <div class="form-group">
                            <div class="input-group">
    	                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            	<input type="text" class="form-control" placeholder="Ingresar nombre" name="nuevoNombre">
                            </div>
                        </div>                    
                        <!-- ingresar usuario -->
                        <div class="form-group">
                            <div class="input-group">
    	                        <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                            	<input type="text" class="form-control" placeholder="Ingresar usuario" name="nuevoUsuario" id="nuevoUsuario">
                            </div>
                        </div>                    
                        <!-- ingresar contraseña -->
                        <div class="form-group">
                            <div class="input-group">
    	                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            	<input type="text" class="form-control" placeholder="Ingresar password" name="nuevoPassword">
                            </div>
                        </div>
                        <!-- ingresar tipo de perfil -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list-ul"></i></span>
                            	<select class="form-control" name="nuevoPerfil">
                                    <option>Seleccionar perfil</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Especial</option>
                                    <option value="3">Vendedor</option>
                                </select>
                            </div>
                        </div>
                        <!-- subir imagen de perfil -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="exampleInputFile">Subir imagen</label>
                                <input type="file" class="nuevaFoto" id="exampleInputFile" name="nuevaFoto">
                                <p class="help-block">Peso maximo de la imagen 5MB</p>
                                <img src="views/img/users/default/anonymous.png" class="img-thumbnail preview" width="115px" alt="Foto">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                    $createUser = new UsersController();
                    $createUser->ctrCreateUser();
                ?>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div id="modalEditarUsuario" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="POST" enctype="multipart/form-data">
                <!-- Modal header -->
                <div class="modal-header" style="background-color: #f39c12 ">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Usuario</h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="box-body">
                        <!-- Ingresar Nombre -->
                        <div class="form-group">
                            <div class="input-group">
    	                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            	<input type="text" class="form-control" value="" id="editarNombre" name="editarNombre">
                            </div>
                        </div>                    
                        <!-- ingresar usuario -->
                        <div class="form-group">
                            <div class="input-group">
    	                        <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                            	<input type="text" class="form-control" value="" id="editarUsuario" name="editarUsuario" readonly>
                            </div>
                        </div>                    
                        <!-- ingresar contraseña -->
                        <div class="form-group">
                            <div class="input-group">
    	                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            	<input type="text" class="form-control" placeholder="Escriba la nueva contraseña" name="editarPassword">
                                <input type="hidden" id="passwordActual" name="passwordActual" val="">
                            </div>
                        </div>
                        <!-- ingresar tipo de perfil -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list-ul"></i></span>
                            	<select class="form-control" name="editarPerfil">
                                    <option value="" id="editarPerfil"></option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Especial</option>
                                    <option value="3">Vendedor</option>
                                </select>
                            </div>
                        </div>
                        <!-- subir imagen de perfil -->
                        <div class="form-group">
                            <div class="input-group">
                                <label for="exampleInputFile">Subir imagen</label>
                                <input type="file" class="nuevaFoto" id="exampleInputFile" name="editarFoto">
                                <p class="help-block">Peso maximo de la imagen 5MB</p>
                                <img src="views/img/users/default/anonymous.png" class="img-thumbnail preview" width="115px" alt="Foto">
                                <input type="hidden" name="fotoActual" id="fotoActual">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php
                    $editUser = new UsersController();
                    $editUser->ctrEditUser();
                ?>
            </form>
        </div>
    </div>
</div>

<?php
    $deleteUser = new UsersController();
    $deleteUser->ctrDeleteUser();
?>