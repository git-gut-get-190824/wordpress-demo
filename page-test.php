<?php get_header(); ?>

<main class="offset-y-m">
    <div class="container">
        <h2 class="heading-h2"><i class="uil uil-search"></i>企業情報検索</h2>

    <?php
    // 検索対象にするフィールドのスラッグ
    $target_fields = ['location', 'business_type', 'working_conditions'];

    // フィールドグループを取得
    $fields = acf_get_fields('group_5f12317c2e986'); // *フックで戻り値の中身をデフォルトとは違う構成に書き換えています
    //var_dump($fields);
    ?>



    <!-- 検索フォーム -->
    <form action="">
        <?php foreach( $fields as $field ): ?>
            <?php if( in_array( $field['name'], $target_fields )  ): // 検索対象のフィールドなら処理 ?>

            <h3 class="font-weight-bold h5"><?php echo $field['label']; ?></h3>
        
                <?php foreach( $field['choices'] as $choice ): ?>
                <label class="checkbox"><input type="checkbox" name="<?php echo $field['name'];?>[]" value="<?php echo $choice; ?>">
                    <span><?php echo $choice; ?></span>
                </label>
                <?php endforeach; ?>
        
            <?php endif; ?>
        <?php endforeach; ?>

        <p class="text-center">
            <input class="btn btn-lg btn-primary" type="submit" value="検索">
        </p>
    </form>



    <!-- 検索結果 -->
    <?php
    if( !empty($_GET) ):

        // サブクエリに投げる条件のベース
        $args = array(
                //'numberposts' => -1,
                'post_type' => 'company',
                'meta_query' => array(
                    'relation' => 'AND',
                ),
        );

        // サブクエリ条件ベースに追加
        // foreach( $target_fields as $target_field_slug ){
        //     $value = $_GET[$target_field_slug];
        //     if( !empty($value) ){
                array_push( $args['meta_query'],
                    array(
                        'key' => $target_field_slug, //カスタムフィールドのキー
                        'value' => $_GET['location'],   // 検索値の配列
                        'type' => 'CHAR',   // 型
                        'compare' => (count($value)==1)? 'LIKE' : 'REGEXP',   // 論理間関係
                    )
                );
        //     }
        // }

        // pr($args); debug

        // サブクエリ発行
        $the_query = new WP_Query($args);
        echo $the_query->request;
        
    ?>

    <hr class="my-5">

    <div class="balloon-gray">
        <h2 class="h5 font-weight-bold">検索条件</h2>
        <dl class="clearfix mb-0">
            <?php foreach( $target_fields as $target_field_slug ): ?>
            <?php $value = $_GET[$target_field_slug]; ?>
                <?php if( !empty($value) ):?>
                <dt class="float-left mr-2"><?php echo $fields[$target_field_slug]['label']; ?>:</dt>
                <dd><?php echo esc_html(implode('、', $value)); ?></dd>
                <?php endif; ?>
            <?php endforeach; ?>
        </dl>
    </div>

    <?php if( $the_query->have_posts() ):?>
    <p><?php echo $the_query->found_posts; ?>件見つかりました。</p>
    <dl class="serach-results">
        <?php while($the_query->have_posts()): ?>
            <?php $the_query->the_post(); ?>
            <div class="search-results-item">
                <dt><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dt>
                <?php foreach( $target_fields as $target_field_slug ): ?>
                <dd><?php the_field($target_field_slug); ?></dd>
                <?php endforeach; ?>
            </div>
        <?php endwhile; ?>
    </dl>

    <?php else: ?>
    <p>見つかりませんでした。</p>
    <?php endif; ?>
    <?php wp_reset_postdata();?>


    <?php endif; ?>
    
    </div>
</main>


<?php get_footer(); ?>