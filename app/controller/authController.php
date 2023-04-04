<?php

namespace app\controller;

use app\connection;
use mf\controller\controller;
use mf\model\modelConnect;

/*------------------------------------------- 
| Extensão da classe controller             |
----------------------------------------------*/
class authController extends controller{

    /* Função Autenticar Usuario
    ---------------------------------*/
    public function autenticar(){
        
  // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');

         //Verificar se é apenas um espectador
         if ($_POST['participante'] == 'espectador'){  
            session_start();    
            
            $_SESSION['participante'] = 'espectador' ;  
            $_SESSION['cNome']  = $_POST['nome'] ;
            $_SESSION['cSquad'] = trim(strtoupper($_POST['squad'])) ;
               
            return  header('Location:  /Poker/home');
        }

        // Atribui os valores ao objeto Usuario
        $oUsr->__set('cNome'             , $_POST['nome']);
        $oUsr->__set('cSquad'            , strtoupper(trim($_POST['squad'])));

        //Chamar o Metodo Autenticar
        $oUsr = $oUsr->dbAutentic();

        if($oUsr->__get('nId') != null && $oUsr->__get('cNome') != null && $oUsr->__get('cSquad') != ''){
            // Iniciar Sessão 
            session_start();
            
            //Atribui Valores a Sessão
            $_SESSION['nId']            = $oUsr->__get('nId');
            $_SESSION['cNome']          = $oUsr->__get('cNome');
            $_SESSION['cSquad']         = $oUsr->__get('cSquad');    
            $_SESSION['participante']   = 'participante';
            
            // Realiza o POST no caminho HOME
            header('Location:  /Poker/home');

        }else{
   
            header('Location: /Poker/?login=erro');
        }     
        
    }

    //Função Sair
    public function sair(){
        session_start();
        $oUsr = modelConnect::getModel('dbUsuario');
         
        if($_REQUEST['nId'] == ""){
            $oUsr->__set('nId'   , $_SESSION['nId']);
            session_destroy();
            header('Location: /Poker/');
        }else{
            $oUsr->__set('nId'   , $_REQUEST['nId']);
        }

        $$lOK= $oUsr->dbDelet();

        
        

    }

}

?>
