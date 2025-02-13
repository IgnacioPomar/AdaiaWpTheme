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
        		<a href="<?=home_url ();?>"><span class="adaiaLogo"></span></a>
        	</div>
            <nav>
			<?php
			$mnuOpcs = getMnuAnchored ();

			$arrays = array_chunk ($mnuOpcs, 4);

			$ulClass = '';
			foreach ($arrays as $opcs)
			{
				$ulClass = ('' == $ulClass) ? 'leftMnu' : 'rightMnu';

				echo '<ul class="' . $ulClass . '">';

				foreach ($opcs as $opc)
				{
					echo "<li>$opc</li>";
				}
				echo '</ul>';
			}

			?>
            </nav>
           
        </div></div>
    </header>
    <main>
