<?php
// デバッグ用
function pr($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

// クラシックエディタ: TinyMCE
add_filter( 'use_block_editor_for_post', '__return_false' ); 





/**************************************************
 * タイトルタグ
 *************************************************/
add_theme_support('title-tag');

// セパレータ変更
function wp_document_title_separator( $separator ) {
  $separator = '|';
  return $separator;
}
add_filter( 'document_title_separator', 'wp_document_title_separator' );

// 出力テキスト変更
function wp_document_title_parts( $title ) {
  if ( is_home() || is_front_page() ) {
    unset( $title['tagline'] ); // キャッチフレーズを出力しない
  } else if ( is_category() ) {
    $title['title'] .= '一覧';
    } else if ( is_tag() ) {
    $title['title'] = '「' . $title['title'] . '」タグの記事一覧';
    } else if ( is_archive() ) {
    if( (is_post_type_archive()) ){
        $title['title'] = $title['title'];
    } else {
        $title['title'] .= 'の記事一覧';
    }
  }
  return $title;
}
add_filter( 'document_title_parts', 'wp_document_title_parts', 10, 1 );





/**************************************************
 * CSS/JSファイルの読み込み
 *************************************************/
// フロントエンド
function my_styles_and_scripts() {
    $uri = get_template_directory_uri();
    
    //css
    wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
    wp_enqueue_style( 'unicons', 'https://unicons.iconscout.com/release/v2.1.9/css/unicons.css' );
    wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap' );
    //wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
    wp_enqueue_style( 'common-font', $uri . '/css/fonts.css' );
    wp_enqueue_style( 'common-style', $uri . '/css/style.css' );
    //wp_enqueue_style( 'editor-style', $uri . '/css/editor-style.css' );
    
    //js
    //WordPress 本体の jQuery を登録解除
    //wp_deregister_script( 'jquery' ); 

    // </body>の直前で読み込む
    //wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.4.1.min.js', array(), '3.3.1', true );
    //wp_enqueue_script( 'bootstrap-popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array('jquery'), '1.14.3', true );
    //wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', array('jquery', 'bootstrap-popper'), '4.1.3', true );
    //wp_enqueue_script( 'slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array( 'jquery' ), '1.8.1', true );
    wp_enqueue_script( 'script', $uri . '/js/script.js', array( 'eo_front' ), '', true ); 
}
add_action( 'wp_enqueue_scripts', 'my_styles_and_scripts' );





/**************************************************
 *  メニュー
 *************************************************/
 // メニュー
 function my_menu_setup(){
    register_nav_menus( array(
        'global_menu' => 'グローバルメニュー',
        // 'footer_menu' => 'フッターメニュー',
      )
    );
}
add_action( 'after_setup_theme', 'my_menu_setup' );





/**************************************************
 * パンくずリスト
 *************************************************/
function breadcrumb($divOption = array("id" => "breadcrumb", "class" => "breadcrumb-list")){
  global $post;
  $str ='';
  if(!is_home()&&!is_admin()){
      $tagAttribute = '';
      foreach($divOption as $attrName => $attrValue){
          $tagAttribute .= sprintf(' %s="%s"', $attrName, $attrValue);
      }
      $str.= '<nav'. $tagAttribute .'>';
      $str.= '<div class="container"><ul>';
      $str.= '<li><a href="'. home_url() .'/">トップ</a></li>';
      if(is_category()) {
          $cat = get_queried_object();
          if($cat -> parent != 0){
              $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
              foreach($ancestors as $ancestor){
                  $str.='<li><a href="'. get_category_link($ancestor) .'">'. get_cat_name($ancestor) .'</a></li>';
              }
          }
          $str.='<li>'. $cat -> name . '</li>';
      } elseif(is_single()){
          //投稿タイプを取得: 通常の投稿(post) or カスタム投稿
          $postType = get_post_type();
          
          if( $postType == 'post'){ //通常の投稿
            
            $categories = get_the_category($post->ID);
            $cat = $categories[0];
            if($cat -> parent != 0){
                $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
                foreach($ancestors as $ancestor){
                    $str.='<li><a href="'. get_category_link($ancestor).'">'. get_cat_name($ancestor). '</a></li>';
                }
            }
            $str.='<li><a href="'. get_category_link($cat -> term_id). '">'. $cat-> cat_name . '</a></li>';
            $str.= '<li>'. $post -> post_title .'</li>';
          } else {  //カスタム投稿
            
            $obj = get_post_type_object( $postType );
            $str.='<li><a href="'. get_post_type_archive_link( $obj->name ). '">'. $obj->labels->name . '</a></li>';

            $taxonomies = get_the_taxonomies();
            if ( !empty($taxonomies) ){ // タクソノミーが設定されているとき
                // 最初のタクソノミーだけ取り出す
                foreach($taxonomies as $tax_slug => $term_link){
                    $terms_arr = get_the_terms( $post->ID, $tax_slug );
                    break;
                }
                $term = $terms_arr[0];
                if($term -> parent != 0){
                    $ancestors = array_reverse(get_ancestors( $term -> term_id, 'genre' ));
                    foreach($ancestors as $ancestor){
                        $str.='<li><a href="'. get_term_link($ancestor).'">'. get_term($ancestor) -> name . '</a></li>';
                    }
                }
                $str.='<li><a href="'. get_term_link($term -> term_id). '">'. $term-> name . '</a></li>';
            }
            $str.= '<li>'. $post -> post_title .'</li>';
        
            
            
          }
          
      } elseif(is_page()){
          if($post -> post_parent != 0 ){
              $ancestors = array_reverse(get_post_ancestors( $post->ID ));
              foreach($ancestors as $ancestor){
                  $str.='<li><a href="'. get_permalink($ancestor).'">'. get_the_title($ancestor) .'</a></li>';
              }
          }
          $str.= '<li>'. $post -> post_title .'</li>';
      } elseif(is_date()){
          if(get_query_var('day') != 0){
              $str.='<li><a href="'. get_year_link(get_query_var('year')). '">' . get_query_var('year'). '年</a></li>';
              $str.='<li><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. get_query_var('monthnum') .'月</a></li>';
              $str.='<li>'. get_query_var('day'). '日</li>';
          } elseif(get_query_var('monthnum') != 0){
              $str.='<li><a href="'. get_year_link(get_query_var('year')) .'">'. get_query_var('year') .'年</a></li>';
              $str.='<li>'. get_query_var('monthnum'). '月</li>';
          } else {
              $str.='<li>'. get_query_var('year') .'年</li>';
          }
      } elseif(is_search()) {
          $str.='<li>「'. get_search_query() .'」で検索した結果</li>';
      } elseif(is_author()){
          $str .='<li>投稿者 : '. get_the_author_meta('display_name', get_query_var('author')).'</li>';
      } elseif(is_tag()){
          $str.='<li>タグ : '. single_tag_title( '' , false ). '</li>';
      } 


      // タクソノミーアーカイブ
      elseif(is_tax()){
          
          // タームオブジェクト（現在のページのオブジェクト）を取得
          $my_term = get_queried_object(); 

          // タームが属するタクソノミーを取得
          $my_taxonomy = $my_term->taxonomy;

          // タクソノミーが属する投稿タイプを取得
          $post_types = get_taxonomy( $my_taxonomy )->object_type; //戻り値は配列

          // 投稿タイプオブジェクトを取得
          $obj = get_post_type_object( $post_types[0] );

          // 投稿タイプ > ターム名の順に追加
          $str .= '<li><a href="'. get_post_type_archive_link( $obj->name ). '">'. $obj->labels->name . '</a></li>';
          $str .= '<li>' . get_the_archive_title() . '</li>';

      } 
      

      elseif(is_attachment()){
          $str.= '<li>'. $post -> post_title .'</li>';
      } elseif(is_404()){
          $str.='<li>ページが見つかりません</li>';
      } elseif(is_post_type_archive()){
          $str.= '<li>'. post_type_archive_title('', false) .'</li>';
      } else{
          $str.='<li>'. get_the_title() .'</li>';
      }
      $str.='</ul>';
      $str.='</div>';
      $str.='</nav>';
  }
  echo $str;
}










/**************************************************
 * プラグイン > Event Organiser
 *************************************************/
add_filter('eventorganiser_calendar_event_link','myprefix_maybe_no_calendar_link',10,3);
function myprefix_maybe_no_calendar_link( $link, $event_id, $occurrence_id ){
    
        // リンクしないにチェックが有れば
        if( get_field('no_link') ){
            return false; // リンクなし
        }

        // タクソノミーが指定タームなら
        $tax_name = 'event-category';
        $terms = get_the_terms($post_id, $tax_name);
        foreach( $terms as $term_obj ){
            if( $term_obj->name == '閉館日' ){
                return false; // リンクなし
            }
        }

        return $link;
}





/**************************************************
 * プラグイン > ACF
 *************************************************/
add_filter('acf/load_fields', 'neko_acf_fields_custom');
function neko_acf_fields_custom($fields){
    
    if( is_page(38) // 企業情報検索ページ
    || is_page(53)  // テストページ
    ||  is_singular( 'company' ) ){  // 投稿タイプが企業情報
        $new_fields = [];
    
        foreach($fields as $field){
            $new_fields[$field['name']] = array(
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'choices' => $field['choices']
            );
        }
        return $new_fields;
    }

    return $fields;

}





/**************************************************
 * カスタム投稿タイプ > company
 *************************************************/
$my_post_type = 'company';

// 投稿一覧にカラムを追加 *ここではカラム追加のみで値表示は別フック
function neko_add_columns_to_company_postlist($columns) {
    $new_columns = array(
        'location' => '所在地',
        'business_type' => '業種',
        'working_conditions' => '働きやすさ'
    );
  
    // デフォルトのラベルも変更
    $columns['date'] = '投稿日';
  
    // カラムの位置を調整: 追加カラムをdateの前に
    $pos = array_search('date', array_keys($columns));
    $columns = array_merge( array_slice($columns, 0, $pos), $new_columns, array_slice($columns, $pos) );
  
    return $columns;
}
add_filter("manage_{$my_post_type}_posts_columns" , 'neko_add_columns_to_company_postlist');
  

// 追加したカラムに値を表示する
function neko_show_values_in_added_columns($column_name, $post_id) {

    // カスタムフィールドの値を取得
    $values = get_post_meta($post_id, $column_name, true);
    if( $values ){
        $stitle = implode('、', $values); 
    }
    // if( $column_name == 'location' ) {
    //     $stitle = get_post_meta($post_id, 'location', true);
    // }
    // elseif( $column_name == 'business_type' ) {
    //     $stitle = get_post_meta($post_id, 'business_type', true);
    // }
    // elseif( $column_name == 'working_conditions' ){ // 値は配列
    //     $values = get_post_meta($post_id, 'working_conditions', true);
    //     pr($values);
        
    //     if( $values ){
    //         $stitle = implode('、', $values);
    //     }
    // }
  
    // 取得値のフロントへの出力
    if ( isset($stitle) && $stitle ) {
        echo esc_attr($stitle);
    } else {
        echo '-'; //値がないときの表示
    }

}
add_action( "manage_{$my_post_type}_posts_custom_column", 'neko_show_values_in_added_columns', 10, 2 );
  

// 追加したカラムをソート可能にする;これだけではソートは機能しない
function neko_add_sorting_to_columns( $columns ) {
    $columns['location'] = 'location';
    $columns['business_type'] = 'business_type';
    return $columns;
}
add_filter( "manage_edit-{$my_post_type}_sortable_columns", "neko_add_sorting_to_columns" );


//ソートを機能させる
function neko_make_added_columns_sortable ($query) {
    global $typenow, $pagenow;
  
    if ( $typenow == 'company'  // $my_post_typeだと何故か動作しない..
        && is_admin()
        && $pagenow == 'edit.php') {
  
      if( isset($_GET['orderby']) && $_GET['orderby'] === 'location' ) {
        $query->query_vars['meta_key'] = 'location';
        $query->query_vars['orderby'] = 'meta_value';
      }

      if( isset($_GET['orderby']) && $_GET['orderby'] === 'business_type' ) {
        $query->query_vars['meta_key'] = 'business_type';
        $query->query_vars['orderby'] = 'meta_value';
      }
  
    }
}
add_action( 'parse_query', 'neko_make_added_columns_sortable');


unset($my_post_type);


