<div class="column-group">
    <div class="large-40 push-center">
        <?php
        echo $this->Form->create('Users',array(
            'inputDefaults' => array(
                'label' => false,
                'div' => false),
            'class' => 'ink-form'
        ));
        ?>
        <fieldset>
            <legend><?php echo __('Iniciar Sesión');?></legend>
            <div class="control-group column-group gutters">
                <?php
                echo $this->Form->label('User.username', __('Usuario'), array('class' => 'content-right large-20'));
                echo $this->Form->input('User.username', array('div' => 'control large-80'));
                ?>
                <?php
                echo $this->Form->label('User.password', __('Contraseña'), array('class' => 'content-right large-20'));
                echo $this->Form->input('User.password', array('div' => 'control large-80'));
                ?>
            </div>
            <div class="content-center">
                <?php
                echo $this->Form->button(
                        '<i class = "icon-signin"></i> ' . __('Iniciar Sesión'),
                        array('class' => 'ink-button blue', 'title' => __('Iniciar Sesión'), 'alt' => __('Iniciar Sesión'), 'escape' => false)
                );
                echo $this->Form->button(
                        '<i class = "icon-remove"></i> '.__('Cancelar Cambios'),
                        array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar Cambios'), 'alt' => __('Cancelar Cambios'), 'escape' => false)
                );
                ?>
            </div>
        </fieldset>
        <?php
        echo $this->Form->end();
        ?>
    </div>
</div>