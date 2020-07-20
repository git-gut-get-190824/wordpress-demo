<?php get_header(); ?>

<!-- イベントカレンダー -->
<section class="event-calendar offset-y-m">
    
    <div class="container">

        <div class="section-header">
            <h2 class="section-title">イベント情報</h2>
        </div>

        <div class="section-body">
            <?php echo do_shortcode( "[eo_fullcalendar
	        				defaultView='month'
	        				headerLeft='title'
	        				headerCenter=''
	        				headerRight='category prev,next,today'
	        				timeformat='G:i'
	        				titleformatmonth='Y年F'
	        				columnformatmonth='D'
	        			]" );
	        			/* パラメータの説明
	        			columnformatmont マンスビューでのカラム(グレーのth)のフォーマット
	        			*/
            ?>
        </div>

    </div> <!-- end of .container -->

</section>



<?php get_footer(); ?>