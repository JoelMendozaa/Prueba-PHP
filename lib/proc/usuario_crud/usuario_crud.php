<?php


    class UsuarioCRUD extends ProgramaBase
    {
        const LIMITE_SCROLL = 5;

        function __construct()
        {

            $this->usuario = new Usuario();


            parent::__construct($this->usuario);

        }

        function inicializar()
        {
            $this->form->accion('/'. $this->seccion .'/');

            $paso        = new Hidden('paso'); 
            $paso->value = 1;

            $oper        = new Hidden('oper'); 
            $id          = new Hidden('id');        

            $nombre      = new Input   ('nombre'       ,['placeholder' => 'Nombre del usuario...'   , 'validar' => True, 'ereg' => EREG_TEXTO_100_OBLIGATORIO  ]);
            $email       = new Textarea('email'        ,['placeholder' => 'Edad del usuario...'     , 'validar' => True ]);
            $edad        = new Input   ('edad'         ,['placeholder' => 'Edad del usuario...'     , 'validar' => True, 'ereg' => EREG_TEXTO_150_OBLIGATORIO  ]);

            $this->form->cargar($paso);
            $this->form->cargar($oper);
            $this->form->cargar($id);
            $this->form->cargar($nombre);
            $this->form->cargar($email);
            $this->form->cargar($edad);
        }


        function existe($id='')
        {

            $cantidad = 0;
            if (   !empty($this->form->val['nombre']) 
                && !empty($this->form->val['email'])
                && !empty($this->form->val['edad'])
            )
            {   

                $cantidad = $this->usuario->existeUsuario(
                     $this->form->val['nombre']
                    ,$this->form->val['email']
                    ,$this->form->val['edad']
                    ,$this->form->val['id']
                );
            }

            return $cantidad;
        }


        function recuperar()
        {

            $this->usuario->recuperar($this->form->val['id']);



            $this->form->elementos['nombre']->value     = $this->usuario->nombre;
            $this->form->elementos['email']->value      = $this->usuario->email;
            $this->form->elementos['edad']->value       = $this->usuario->edad;
        }


        function resultados_busqueda()
        {
            $listado_usuarios = '
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Edad</th>
                    </tr>
                </thead>
                <tbody>
            
            ';

            $limite = UsuarioCrud::LIMITE_SCROLL;

            $pagina = $this->form->val['pagina'];

            $offset = $pagina * $limite;



            $opt = [];
    
            $opt['orderby']['fecha_ult_mod'] = 'DESC';   
            $opt['offset'] = $offset;
            $opt['limit']  = $limite;
        
        
            $resultado = $this->usuario->seleccionar($opt);

            if ($resultado->num_rows > 0) 
            {
                while ($fila = $resultado->fetch_assoc()) 
                {
                    $listado_usuarios .= "
                        <tr>
                            <th scope=\"row\">
                                ". enlace("/{$this->seccion}/actualizar/{$fila['id']}",'Actualizar',['class' => 'btn btn-primary']) ."
                                ". enlace("#",'Eliminar',['class' => 'btn btn-danger','onclick' => "if(confirm('Cuidado, estás tratando de eliminar el usuario: {$fila['nombre']}')) location.href = '/usuario/eliminar/{$fila['id']}';"]) ."
                            </th>
                            <td>{$fila['nombre']}</td>
                            <td>{$fila['email']}</td>
                            <td>{$fila['edad']}</td>
                        </tr>
                    ";
                }

                if ($resultado->num_rows == UsuarioCrud::LIMITE_SCROLL)
                    $siguiente = '<li class="page-item">'. enlace("/{$this->seccion}/pag/". ($pagina + 1), 'Siguiente',['class' => 'page-link']) .'</li>';
            } 
            else 
            {
                $listado_usuarios = '<tr><td colspan="5">No hay resultados</td></tr>';
            }

            if($pagina)
                $pagina_anterior = '<li class="page-item">'. enlace("/{$this->seccion}/pag/". ($pagina - 1), 'Anterior',['class' => 'page-link']) .'</li>';
            

            $listado_usuarios .= '
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        '. $pagina_anterior .'
                        '. $siguiente .'
                    </ul>
                </nav>


                <div class="alta">'. enlace("/{$this->seccion}/alta/", 'Alta de usuario',['class' => 'btn btn-success']) .'</div>
            ';
            

            return $listado_usuarios;


        }
    }





