<?php
    require_once '../controllers/UsersController.php';
    require_once '../models/Users.php';
    class AjaxUsers
    {
        /** 
         * EDITAR USUARIO
        */
        public $idUsuario;

        public function ajaxEditUsers()
        {
            $item = 'id';
            $value = $this->idUsuario;
            $respuesta = UsersController::ctrViewsUsers($item, $value);

            echo json_encode($respuesta);
        }

        /** 
         * ACTIVAR USUARIO
        */
        public $activarUsuario;
        public $activarId;

        public function ajaxActUser()
        {
            $item1 = 'status';
            $value1 = $this->activarUsuario;

            $item2 = 'id';
            $value2 = $this->activarId;
            
            $respuesta = Users::ActUser($item1, $value1, $item2, $value2);
        }

        /** 
         * REVISAR SI EL USUARIO YA ESTA REGISTRADO
        */
        public $valUser;

        public function ajaxValUser()
        {
            $item = 'user';
            $value = $this->valUser;

            $respuesta = UsersController::ctrViewsUsers($item, $value);

            echo json_encode($respuesta);
        }
    }

    /** 
     * EDITAR USUARIO
    */
    if (isset($_POST['idUsuario'])) {
        $editar = new AjaxUsers();
        $editar->idUsuario = $_POST['idUsuario'];
        $editar->ajaxEditUsers();
    }

    /** 
     * ACTIVAR USUARIO
    */

    if (isset($_POST['activarUsuario'])) {
        $activarUsuario = new AjaxUsers();
        $activarUsuario->activarId = $_POST['activarId'];
        $activarUsuario->activarUsuario = $_POST['activarUsuario'];

        $activarUsuario->ajaxActUser();
    }

    /** 
     * REVISAR SI EL USUARIO YA ESTA REGISTRADO
    */
    if (isset($_POST['validarUsuario'])) {
        $valUser = new AjaxUsers();
        $valUser->valUser = $_POST['validarUsuario'];
        $valUser->ajaxValUser();
    }