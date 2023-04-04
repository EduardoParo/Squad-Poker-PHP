<?php
    // Definir namespace
    namespace app\controller;

    // Executar a classe  do arquivo
    use mf\controller\controller;
    use mf\model\modelConnect;

     /*-------------------------------------------
    * 4° Classe IndexController
    *@author Eduardo Paro de Simoni
    -----------------------------------------*/
    class indexController extends controller {

        // Metodo POST Index
        public function index(){

            //Executar a View Index
            $this->reqLayout('index');
        }

    }

    



?>