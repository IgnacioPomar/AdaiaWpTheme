<!DOCTYPE html>
<html <?=language_attributes ();?>>
<head>
    <meta charset="<?=bloginfo ('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=wp_head ();?>
</head>
<body <?=body_class ();?>>
	<?=wp_body_open ();?>
    <header>
        <div id="mnuBg"><div id="mnuContainer">
        	<label for="menu-toggle" class="mnuLbl">☰</label>
            <input type="checkbox" id="menu-toggle" class="menu-toggle" />
        	<div id="brand">
        		<a href="<?=home_url ();?>" aria-label="<?=esc_attr (get_bloginfo ('name'));?>"><span class="adaiaLogo" aria-hidden="true"></span></a>
        	</div>
            <nav>
			<?php
			$mnuOpcs = getMnuAnchored ();
			// $numOpcs = count ($mnuOpcs);
			// $chunkSize = intdiv ($numOpcs, 2) + ($numOpcs % 2); // Mitad +1 si es impar
			$chunkSize = 4;
			$arrays = array_chunk ($mnuOpcs, $chunkSize);

			$ulClass = '';
			foreach ($arrays as $opcs)
			{
				$ulClass = ('' == $ulClass) ? 'leftMnu' : 'rightMnu';

				echo '<ul class="' . $ulClass . '">';

				foreach ($opcs as $opc)
				{
					echo '<li><a href="' . $opc [0] . '" onclick="document.getElementById(\'menu-toggle\').checked = false; return true;">' . $opc [1] . '</a></li>';
				}
				echo '</ul>';
			}

			?>
            </nav>
           
        </div></div>
    </header>
    <main>
