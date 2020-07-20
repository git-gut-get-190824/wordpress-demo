<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head();?>
</head>

<header class="site-header">
    <div class="container">
        
        <div class="site-header-inner d-flex justify-content-between align-items-center">
            <div class="site-title">
                <a href="<?php echo esc_url( home_url('/') ); ?>"><img class="img-fluid" src="<?php echo get_template_directory_uri();?>/images/common/logo.png" alt="<?php bloginfo('title'); ?>"></a>
            </div>
            
            <nav class="global-menu" aria-label="global-menu">
                <?php wp_nav_menu( array(
                    'theme_location' => 'global_menu',
                    'container' => false,
                    'container_class' => '',
                    'menu_class' => 'd-flex', //ulクラス
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                )); ?>
            </nav>
        </div>
        
    </div>
</header>

<?php breadcrumb(); ?>

<header class="page-header offset-y-m">
    <div class="container">
        <h1 class="page-title h3"><?php the_title(); ?></h1>
    </div>
</header>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
