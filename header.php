<!DOCTYPE html>
<html <?php
language_attributes ();
?>>
<head>
    <meta charset="<?=bloginfo ('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=wp_title ('|', true, 'right');?></title>
    <link rel="stylesheet" href="<?=get_stylesheet_uri ();?>">
    <?=wp_head ();?>
</head>
<body <?=body_class ();?>>
    <header>
        <div class="container">
        <!--
            <h1><a href="<?=home_url ();?>"><?=bloginfo ('name');?></a></h1>
            <nav>
                <?=wp_nav_menu (array ('theme_location' => 'primary', 'menu_class' => 'nav-menu'));?>
            </nav>
            -->
        </div>
    </header>
    <main>
