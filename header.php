<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="<?php echo get_bloginfo( 'template_directory' );?>/style.css" rel="stylesheet">

    <title><?php the_title(); ?> · <?php bloginfo("name"); ?></title>

    <?php wp_head(); ?>
</head>

<body>

    <header>
        <nav class="blog-nav">
            <a class="blog-nav-item active" href="<?php echo get_bloginfo( 'wpurl' );?>">Blog</a>
            <a class="blog-nav-item" href="sobre-mi">Sobre mi</a>
            <a class="nav-title" href="<?php echo get_bloginfo( 'wpurl' );?>">
                <h1>
                    <span class="title"><?php bloginfo("name"); ?></span>
                    <span class="subtitle">
                        <span class="subtitle-content"><?php bloginfo("description"); ?></span>
                    </span>
                </h1>
            </a>
            <a class="blog-nav-item" href="el-cajon">El cajón</a>
            <a class="blog-nav-item" href="contacto">Contacto</a>
        </nav>
    </header>

    <div id="content-wrapper">