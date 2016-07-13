<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#UsersIrapag').val($prev);$('#UsersIndexForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#UsersIrapag').val($next);$('#UsersIndexForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#UsersIrapag').val(1);$('#UsersIndexForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#UsersIndexForm").submit()');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#UsersIrapag').val($ultima);$('#UsersIndexForm').submit()");
$funcresetear = "$('#UsersIrapag').val(1);";
$funcresetear .= "$('#UsersRegPag').val(25);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#UsersIndexForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$nusers = count($users);
?>
<h1><?php echo __('Usuarios de la aplicación de Coberturas de Centros TDT');?></h1>
<?php
echo $this->Form->create('Users',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
echo $this->Form->hidden('tampag', array('value' => $this->Paginator->counter('{:current}')));
echo $this->Form->hidden('irapag', array('value' => '0'));
?>
<h4>
    <?php 
    echo __('Criterios de Búsqueda');
    echo ' &mdash; ';
    echo $this->Html->Link(
            '<i class = "icon-refresh"></i>', '#', array('id' => 'resetear', 'title' => __('Borrar Criterios'), 'escape' => false)
    );
    ?>
</h4>
<fieldset class="column-group gutters">
    <div class="control-group large-50">
        <div class="column-group gutters">
            <?php
            if (!empty($usersel)){
                echo $this->Form->label('User.usuario', __('Usuario'), array('class' => 'content-right large-20'));
                echo $this->Form->input('usuario', array('options' => $usersel, 'empty' => __('Seleccionar Usuario'), 'div' => 'control large-80'));
            ?>
            <?php
            }
            else{
            ?>
                <p class="ink-alert basic warning">No se han encontrado Usuarios</p>
            <?php
            }
            ?> 
        </div>
    </div>
    <div class="control-group large-50">
        <div class="column-group gutters">
            <?php
            $opciones = array('admin' => __('Administrador'), 'colab' => __('Colaborador'), 'consum' => 'Consumidor');
            echo $this->Form->label('User.role', __('Tipo de Usuario'), array('class' => 'content-right large-40'));
            echo $this->Form->input('role', array('options' => $opciones, 'empty' => __('Seleccionar Tipo'), 'div' => 'control large-60'));
            ?> 
        </div>
    </div>
</fieldset>
<h4>
    <?php 
        echo __('Resultados de la Búsqueda');
        if ($nusers > 0){
            echo ' &mdash; '.$this->Paginator->counter("Usuarios <b>{:start}</b> a <b>{:end}</b> de <b>{:count}</b>");
        }
    ?>
</h4>
<?php
if ($nusers > 0){
?>
    <div class="column-group gutters">
        <div class="large-50 control-group column-group gutters">
            <?php
            $opciones = array(20 => 20, 30 => 30, 50 => 50, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('User.regPag', __('Usuarios por página'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
        <div class="large-50 content-center">
            <nav class="ink-navigation">
                <ul class="pagination blue shadowed rounded">
                <?php
                    echo '<li';
                    if ($this->Paginator->counter('{:page}') == 1) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-fast-backward"></i>', '#', array('id' => 'primera', 'title' => __('Primera Página'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li';
                    if (!$this->Paginator->hasPrev()) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-backward"></i>', '#', array('id' => 'anterior', 'title' => __('Anterior'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li>';
                    echo $this->Html->Link(
                            __('Página') . ' ' . $this->Paginator->counter('{:page}') . ' de ' . $this->Paginator->counter('{:pages}'), '#', array('title' => __('Página') . ' ' . $this->Paginator->counter('{:page}'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li';
                    if (!$this->Paginator->hasNext()) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-forward"></i>', '#', array('id' => 'siguiente', 'title' => __('Siguiente'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li';
                    if ($this->Paginator->counter('{:page}') == $this->Paginator->counter('{:pages}')) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-fast-forward"></i>', '#', array('id' => 'ultima', 'title' => __('Última Página'), 'escape' => false)
                    );
                    echo '</li>';
                ?>
                    
                </ul>
            </nav>
        </div>
    </div>
<?php
    echo $this->Form->end();
?>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th>
                <?php echo __('Acciones');?> &mdash;
                <?php
                echo $this->Html->Link(
                        '<i class = "icon-plus"></i>',
                        array('controller' => 'users', 'action' => 'agregar'),
                        array('title' => __('Nuevo Usuario'), 'alt' => __('Nuevo Usuario'), 'escape' => false)
                );
                ?>
            </th>
            <th><?php echo __('Nombre');?></th>
            <th><?php echo __('Usuario');?></th>
            <th><?php echo __('Tipo de Usuario');?></th>
        </tr>
<?php
        foreach ($users as $usuario) {
?>
            <tr>
                <td>
                    <?php 
                    echo $this->Html->Link(
                            '<i class = "icon-edit"></i>',
                            array('controller' => 'users', 'action' => 'editar', $usuario['User']['id']),
                            array('title' => __('Modificar Usuario'), 'alt' => __('Modificar Usuario'), 'escape' => false)
                        );
                    ?> &mdash;
                    <?php 
                    echo $this->Html->Link(
                            '<i class = "icon-key"></i>',
                            array('controller' => 'users', 'action' => 'acceso', $usuario['User']['id']),
                            array('title' => __('Cambiar Contraseña'), 'alt' => __('Cambiar Contraseña'), 'escape' => false)
                        );
                    ?> &mdash;
                    <?php 
                    echo $this->Form->postLink(
                            '<i class = "icon-undo"></i>',
                            array('controller' => 'users', 'action' => 'resetear', $usuario['User']['id']), 
                            array('title' => __('Resetear Contraseña'), 'alt' => __('Resetear Contraseña'), 'escape' => false), 
                            __('¿Seguro que desea resetear la contraseña del Usuario')." '". $usuario['User']['username'] . "'?"
                            );
                    ?> &mdash;
                    <?php 
                    echo $this->Form->postLink(
                            '<i class = "icon-trash"></i>',
                            array('controller' => 'users', 'action' => 'borrar', $usuario['User']['id']), 
                            array('title' => __('Borrar Usuario'), 'escape' => false), 
                            __('¿Seguro que desea eliminar el Usuario')." '". $usuario['User']['username'] . "'?"
                            );
                    ?>
                </td>
                <td><?php echo $usuario['User']['nomComp'];?></td>
                <td><?php echo $usuario['User']['username'];?></td>                
                <td><?php echo $usuario['User']['role'];?></td>
            </tr>
<?php

        }
?>

    </table>
<?php
}
else{
?>
    <div class="ink-alert block error">
        <h4>Error en la búsqueda</h4>
        <p><?php echo __('No se han encontrado Usuarios con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
    echo $this->Form->end();
}
?>

