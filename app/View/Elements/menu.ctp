<?php
/**
 *
 * Menú de Navegación
 *
 */
$controlador = $this->request->controller;
$rol = AuthComponent::user('role');
$nomUser = AuthComponent::user('nombre');
if ($rol != 'admin'){
    $nomUser = substr($nomUser, 0, 1);
    $nomUser .= '. '.AuthComponent::user('apellido1');
}

?>
<div class="large-80">
    <nav class='ink-navigation'>
        <ul class='menu horizontal rounded blue'>
            <?php
            echo '<li';
            if ($controlador == "centros"){
                echo ' class="active"';
            }
            echo '>';
            echo $this->Html->Link(__('Centros'),array('controller' => 'centros', 'action' => 'index'), array('title' => __('Centros')));
            echo '</li>';
            echo '<li';
            if ($controlador == "municipios"){
                echo ' class="active"';
            }
            echo '>';
            echo $this->Html->Link(__('Municipios'),array('controller' => 'municipios', 'action' => 'index'), array('title' => __('Municipios')));
            echo '</li>';
            echo '<li';
            if ($controlador == "coberturas"){
                echo ' class="active"';
            }
            echo '>';
            echo $this->Html->Link(__('Coberturas'),array('controller' => 'coberturas', 'action' => 'index'), array('title' => __('Coberturas')));
            echo '</li>';
            if ((AuthComponent::user('role') == 'admin')|| (AuthComponent::user('role') == 'colab')){
                echo '<li';
                if ($controlador == "multiples"){
                    echo ' class="active"';
                }
                echo '>';
                echo $this->Html->Link(__('Múltiples'),array('controller' => 'multiples', 'action' => 'index'), array('title' => __('Múltiples')));
                echo '</li>';
                echo '<li';
                if ($controlador == "programas"){
                    echo ' class="active"';
                }
                echo '>';
                echo $this->Html->Link(__('Programas'),array('controller' => 'programas', 'action' => 'index'), array('title' => __('Programas')));
                echo '</li>';
            }
            if ((AuthComponent::user('role') == 'admin')|| (AuthComponent::user('role') == 'colab')){
                echo '<li';
                if ($controlador == "sims"){
                    echo ' class="active"';
                }
                echo '>';
                echo $this->Html->Link(__('Supervisión'),array('controller' => 'sims', 'action' => 'index'), array('title' => __('Supervisión')));
                echo '</li>';
            }
            if ((AuthComponent::user('role') == 'admin')|| (AuthComponent::user('role') == 'colab')){
                echo '<li';
                if ($controlador == "eventos"){
                    echo ' class="active"';
                }
                echo '>';
                echo $this->Html->Link(__('Eventos'),array('controller' => 'eventos', 'action' => 'index'), array('title' => __('Eventos')));
                echo '</li>';
            }
            if (AuthComponent::user('role') == 'admin'){
                echo '<li';
                if ($controlador == "users"){
                    echo ' class="active"';
                }
                echo '>';
                echo $this->Html->Link(__('Usuarios'),array('controller' => 'users', 'action' => 'index'), array('title' => __('Usuarios')));
                echo '</li>';
            }
            ?>
        </ul>
    </nav>
</div>
<div class="large-20 content-right">
    <?php
    echo $this->Html->Link(
            '<i class = "icon-signout"></i> ' .$nomUser,
            array('controller' => 'users', 'action' => 'logout'),
            array('class' => 'ink-button blue', 'title' => __('Cerrar Sesión'), 'alt' => __('Cerrar Sesión'), 'escape' => false)
    );
    ?>
</div>
