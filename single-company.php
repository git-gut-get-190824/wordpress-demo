<?php get_header(); ?>

<main class="offset-y-m">
    <section>
        <div class="container">
            <?php if(have_posts()): ?>
                <?php while(have_posts()): ?>
                    <?php the_post(); ?>

                    <?php $field_group = acf_get_fields('group_5f12317c2e986'); ?>
                    <?php //var_dump($field_group); ?>

                    <?php $fields = get_fields(); ?>
                    <?php //var_dump($fields); ?>
                    
                    <?php if( $fields ): ?>
                        <dl>
                        <?php foreach($fields as $field_name => $field_values): ?>
                            <dt><?php echo $field_group[$field_name]['label']; ?></dt>    
                            <dd><?php echo implode('、', $field_values); ?></dd>
                        <?php endforeach; ?>
                        </dl>
                    <?php endif; ?>

                <?php endwhile;?>
            <?php endif; ?>
        </div>

    </section>
</main>





<?php get_footer(); ?>