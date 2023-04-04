<?php
    namespace app;

    /* Classe Conection conexão com banco de dados
    ------------------------------------*/
    class connection{

        //Metodo GET 
        public static function getDb(){
            try{
                $oConn = new \PDO(
                     "mysql:host=sql300.epizy.com;dbname=epiz_31283322_poker; charset=utf8",
                    "epiz_31283322",
                    "gxH3TUhTl0"
                );
                return $oConn;

            }catch(\PDOException $e){

            }
        }
    }



?>