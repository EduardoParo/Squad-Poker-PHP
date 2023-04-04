<?php
    //Definir nome do arquivo para instaciar a classe index/public
    namespace app;

    //Utilizar a classe Route
    use mf\route\route;
 
 /*--------------------------------------
* 2° Classe Lista das Rotas
* @author Eduardo Paro de Simoni
---------------------------------------*/
    class Routes extends route {
        
        //Função para definir o array de rotas
        protected function iniRoutes(){

            // Rota Index
            $aRoutes['index'] = array(
                'route' => '/Poker/',
                'controller' => 'indexController',
                'action'    =>  'index'
            );

            // Rota Autenticar
            $aRoutes['autenticar'] = array(
                'route' => '/Poker/autenticar',
                'controller' => 'authController',
                'action'    =>  'autenticar'
            );
            // Rota Home
            $aRoutes['home'] = array(
                'route' => '/Poker/home',
                'controller' => 'appController',
                'action'    =>  'home'
            );
            // Rota Sair
               $aRoutes['sair'] = array(
                'route' => '/Poker/sair',
                'controller' => 'authController',
                'action'    =>  'sair'
            );

            // Incluir Voto
            $aRoutes['insertVoto'] = array(
                'route' => '/Poker/insertVoto',
                'controller' => 'appController',
                'action'    =>  'insertVoto'
            );

             // Mostrar Votos
             $aRoutes['getAllUser'] = array(
                'route' => '/Poker/getAllUser',
                'controller' => 'appController',
                'action'    =>  'getAllUser'
            );

            // Mostrar Votos
            $aRoutes['showVotos'] = array(
               'route' => '/Poker/showVotos',
               'controller' => 'appController',
               'action'    =>  'showVotos'
            );

               // Mostrar Votos
               $aRoutes['resetVotos'] = array(
                'route' => '/Poker/resetVotos',
                'controller' => 'appController',
                'action'    =>  'resetVotos'
             );

            // Mostrar Votos
            $aRoutes['getUsrVencedor'] = array(
               'route' => '/Poker/getUsrVencedor',
               'controller' => 'appController',
               'action'    =>  'getUsrVencedor'
            );
                
			
            $this->setRoutes($aRoutes);
    

        }
    }

?>