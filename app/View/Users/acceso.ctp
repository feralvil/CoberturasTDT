<h1><?php echo __('Modificar Contraseña del Usuario');?></h1>
<?php
echo $this->Form->create('Users',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h4><?php echo __('Datos del Usuario');?></h4>
<table class="ink-table bordered alternating hover">
    <tr>
        <th><?php echo __('Nombre'); ?></th>
        <th><?php echo __('Usuario'); ?></th>
    </tr>
    <tr>
        <td><?php echo $usuario['User']['nomComp']; ?></td>
        <td><?php echo $usuario['User']['username']; ?></td>
    </tr>
</table>
<h4><?php echo __('Cambiar Contraseña');?></h4>
<fieldset class="column-group gutters">
    <div class="control-group large-50 required validation error">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('User.password', __('Nueva Contraseña'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.password', array('div' => 'control large-60'));
            ?> 
        </div>
    </div>
    <div class="control-group large-50 required validation error">
        <div class="column-group gutters">
            <?php
            $opciones = array('admin' => __('Administrador'), 'colab' => __('Colaborador'), 'consum' => 'Consumidor');
            echo $this->Form->label('User.passconf', __('Confirmar Contraseña'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.passconf', array('type' => 'password', 'div' => 'control large-60'));
            ?> 
        </div>
    </div>
</fieldset>
<div class="content-center">
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar Usuario'),
            array('class' => 'ink-button blue', 'title' => __('Guardar Usuario'), 'alt' => __('Guardar Usuario'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-remove"></i> '.__('Cancelar Cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar Cambios'), 'alt' => __('Cancelar Cambios'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '. __('Volver'),
            array('controller' => 'users', 'action' => 'index'),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>