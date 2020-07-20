<?php get_header(); ?>

<main>
    <section>
        <div class="container">
            <?php if(have_posts()): ?>
                <?php while(have_posts()): ?>
                    <?php the_post(); ?>

                    <?php var_dump(get_the_taxonomies()); ?>
                    <?php var_dump(get_post_taxonomies()); ?>

                    <?php 
                    var_dump(get_the_terms($post->ID, 'event-category'));
                    ?>
            
                    <?php
                    $tax_name = 'event-category';
                    $terms = get_the_terms($post->ID, $tax_name);
                    var_dump( $terms );
            
                    $terms = get_the_terms($post_id, $tax_name);
                    foreach( $terms as $term_obj ){
                        if( $term_obj->name == '閉館日' ){
                            echo '閉館日だよ!';
                        }
                    }
                    ?>
            
                <?php endwhile;?>
            <?php endif; ?>
        </div>

    </section>
</main>





<?php get_footer(); ?>