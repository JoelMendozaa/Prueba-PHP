<?php



    class Usuario extends Tabla_Usuario
    {
        const TABLA = 'usuarios';


        function __construct()
        {
            parent::__construct(self::TABLA);

        }



        function existeUsuario($nombre,$edad,$email,$id='')
        {
            $opt = [];
            
            $opt['select']['nombre']     = '';
            $opt['where']['nombre']      = $nombre;
            $opt['where']['edad']        = $edad;
            $opt['where']['email']       = $email;

            if(!empty($id))
                $opt['notwhere']['id'] = $id;
      
        
        
            $resultado = $this->seleccionar($opt);

            return $resultado->num_rows;
            
        }
    }