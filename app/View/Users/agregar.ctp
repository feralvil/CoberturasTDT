<h1><?php echo __('Agregar Nuevo Usuario');?></h1>
<?php
echo $this->Form->create('Users',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h4><?php echo __('Datos del Usuario');?></h4>
<fieldset class="column-group gutters">
    <div class="control-group large-50 required validation error">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('User.username', __('Usuario'), array('class' => 'content-right large-20'));
            echo $this->Form->input('User.username', array('div' => 'control large-80'));
            ?> 
        </div>
    </div>
    <div class="control-group large-50 required validation error">
        <div class="column-group gutters">
            <?php
            $opciones = array('admin' => __('Administrador'), 'colab' => __('Colaborador'), 'consum' => 'Consumidor');
            echo $this->Form->label('User.role', __('Tipo de Usuario'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.role', array('options' => $opciones, 'empty' => __('Seleccionar Tipo'), 'div' => 'control large-60'));
            ?> 
        </div>
    </div>    
    <div class="control-group large-33 required validation error">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('User.nombre', __('Nombre'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.nombre', array('div' => 'control large-60'));
            ?>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('User.apellido1', __('1er Apellido'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.apellido1', array('div' => 'control large-60'));
            ?>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('User.apellido2', __('2o Apellido'), array('class' => 'content-right large-40'));
            echo $this->Form->input('User.apellido2', array('div' => 'control large-60'));
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