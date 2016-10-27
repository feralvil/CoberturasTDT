<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('ol');
		echo $this->Html->css('ink');

		echo $this->Html->script('ol'); // Incluimos ol.js
		echo $this->Html->script('jquery'); // Incluimos la bilbioteca JQuery
		echo $this->Html->script('ink-all'); // Incluimos el JS de Ink
		echo $this->Html->script('autoload'); // Incluimos Autoload.js

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<style>
		#map {
			height: 600px;
	  	}
 	</style>
</head>
<body>
	<div id="container" class="ink-grid">
		<div id="header">
                    <?php echo $this->element('cabecera'); ?>
		</div>
                <?php
                if ($this->Session->check('Auth.User')){
                ?>
                    <div id="menu" class="column-group"><?php echo $this->element('menu'); ?></div>
                <?php
                }
                ?>
		<div id="content">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->Session->flash('auth'); ?>
                    <?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer"  class="content-center">
                    <p>DGTIC &mdash; Servici de Telecomunicacions i Societat Digital</p>
		</div>
	</div>
    	<?php
            echo $this->Js->writeBuffer();
        ?>
</body>
</html>
