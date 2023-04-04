<?php
    namespace app;

    /* Classe Conection conexão com banco de dados
    ------------------------------------*/
    class connection{

        //Metodo GET 
        public static function getDb(){
            try{
                $oConn = new \PDO(
                     "mysql:host=sql107.epizy.com; dbname=epiz_31289306_poker; charset=utf8",
                    "epiz_31289306",
                    "HbpowLyQUxf7"
                );
                return $oConn;

            }catch(\PDOException $e){

            }
        }
    }



?>