<?php
/**
 * functions.php
 *
 * @package WordPress
 * @subpackage reform2
 * @since 1.0.0
 *
 */

if ( ! function_exists( 'enlarge_reform_setup' ) ) :

function enlarge_reform_setup() {

	add_theme_support( 'automatic-feed-links' );
	// アイキャッチ画像を有効にする。
	add_theme_support('post-thumbnails');

	add_editor_style( array( 'css/editor.css', get_stylesheet_directory_uri() ) );

	// コンテンツ最大幅
	if ( ! isset( $content_width ) ) {
	    $content_width = 710;
		}

}
endif;
add_action( 'after_setup_theme', 'enlarge_reform_setup' );


// スクリプト
function enlarge_enqueue() {
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri(). '/js/jquery.min.js', '', '2.2.5', true );
	wp_enqueue_script('slick', get_template_directory_uri(). '/js/lib/slick.js', array('jquery'), '4.2.6', true );
	wp_enqueue_script('wideslider', get_template_directory_uri(). '/js/lib/wideslider.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('magnific-popup', get_template_directory_uri(). '/js/lib/magnific-popup.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('offject', get_template_directory_uri(). '/js/lib/ofi.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('main', get_template_directory_uri(). '/js/main.js', array('jquery'), '1.1.0', true );
	wp_enqueue_script( 'yubinbango', 'https://yubinbango.github.io/yubinbango/yubinbango.js', array(), null, true );
	wp_enqueue_style('reset', get_template_directory_uri(). '/css/reset.css', array(), null, 'all' );
	wp_enqueue_style('fonts', get_template_directory_uri(). '/css/fonts.css', array(), null, 'all' );
	wp_enqueue_style('slick', get_template_directory_uri(). '/css/lib/slick.css', array(), null, 'all' );
	wp_enqueue_style('slick-theme', get_template_directory_uri(). '/css/lib/slick-theme.css', array(), null, 'all' );
	wp_enqueue_style('magnific-popup', get_template_directory_uri(). '/css/lib/magnific-popup.css', array(), null, 'all' );
	wp_enqueue_style('style', get_template_directory_uri(). '/css/style.css', array(), null, 'all' );
	wp_enqueue_style('wideslider', get_template_directory_uri(). '/css/lib/wideslider.css', array(), null, 'all' );
	wp_enqueue_style('enlarge-editor', get_template_directory_uri(). '/css/editor.css', array(), null, 'all' );
	wp_enqueue_script('lightbox', get_template_directory_uri(). '/js/lightbox.js', array('jquery'), '2.10.0', true );

}
add_action( 'wp_enqueue_scripts', 'enlarge_enqueue' );


//ページネーション(アーカイブページ)
function pagination($pages = '', $range = 2)
{
     $showitems = ($range * 2)+1;//表示するページ数（５ページを表示）

     global $paged;//現在のページ値
     if(empty($paged)) $paged = 1;//デフォルトのページ

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;//全ページ数を取得
         if(!$pages)//全ページ数が空の場合は、１とする
         {
             $pages = 1;
         }
     }

     if(1 != $pages)//全ページが１でない場合はページネーションを表示する
     {
		 echo "<div class=\"pagenation\">\n";
		 echo "<ul>\n";
		 //Prev：現在のページ値が１より大きい場合は表示
         if($paged > 1) echo "<li class=\"prev\"><a href='".get_pagenum_link($paged - 1)."'>前のページを見る</a></li>\n";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                //三項演算子での条件分岐
                echo ($paged == $i)? "<li class=\"active\">".$i."</li>\n":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>\n";
             }
         }
		//Next：総ページ数より現在のページ値が小さい場合は表示
		if ($paged < $pages) echo "<li class=\"next\"><a href=\"".get_pagenum_link($paged + 1)."\">次のページを見る</a></li>\n";
		echo "</ul>\n";
		echo "</div>\n";
     }
}
//ポストリンク(PREV)にクラスを追加
function enlarge_default_add_prev_post_link_class($output) {
  return str_replace('<a href=', '<a class="btn-prev" href=', $output);
}
add_filter( 'previous_post_link', 'enlarge_default_add_prev_post_link_class' );

//ポストリンク(NEXT)にクラスを追加
function enlarge_default_add_next_post_link_class($output) {
  return str_replace('<a href=', '<a class="btn-next" href=', $output);
}
add_filter( 'next_post_link', 'enlarge_default_add_next_post_link_class' );


// パンくずリスト
function breadcrumb(){
  global $post;
  $str = '';
  $pNum = 2;
  $str.= '<div class="breadcrumb">';
  $str.= '<ul class="breadcrumb-list">';
  $str.= '<li ><a href="'.home_url('/').'" class="home"><span class="icon-home"></span></a></li>';

  /* 通常の投稿ページ  */
  if(is_singular('post')){
    $categories = get_the_category($post->ID);
    $cat = $categories[0];

    if($cat->parent != 0){
      $ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
      foreach($ancestors as $ancestor){
        $str.= '<li><a href="'. get_category_link($ancestor).'"><span>'.get_cat_name($ancestor).'</span></a></li>';
      }
    }
    $str.= '<li><a href="'. get_category_link($cat-> term_id). '"><span>'.$cat->cat_name.'</span></a></li>';
    $str.= '<li><span>'.$post->post_title.'</span></li>';
  }

  /* カスタムポスト */
  elseif(is_single() && !is_singular('post')){
    $cp_name = get_post_type_object(get_post_type())->label;
    $cp_url = home_url('/').get_post_type_object(get_post_type())->name;

    $str.= '<li><a href="'.$cp_url.'"><span>'.$cp_name.'</span></a></li>';
    $str.= '<li><span>'.$post->post_title.'</span></li>';
  }

  /* 固定ページ */
  elseif(is_page()){
    $pNum = 2;
    if($post->post_parent != 0 ){
      $ancestors = array_reverse(get_post_ancestors($post->ID));
      foreach($ancestors as $ancestor){
        $str.= '<li><a href="'. get_permalink($ancestor).'"><span>'.get_the_title($ancestor).'</span></a></li>';
      }
    }
    $str.= '<li><span>'. $post->post_title.'</span></li>';
  }

  /* カテゴリページ */
	elseif(is_category()) {
    $cat = get_queried_object();
    $pNum = 2;
    if($cat->parent != 0){
      $ancestors = array_reverse(get_ancestors($cat->cat_ID, 'category'));
      foreach($ancestors as $ancestor){
        $str.= '<li><a href="'. get_category_link($ancestor) .'"><span>'.get_cat_name($ancestor).'</span></a></li>';
      }
    }
    $str.= '<li><span>'.$cat->name.'</span></li>';
  }

  /* タグページ */
  elseif(is_tag()){
    $str.= '<li><span>'. single_tag_title('', false). '</span></li>';
  }

	/* タームページ */
	elseif(is_tax('seko_cat')) {
		$cat = get_queried_object();
		$str.= '<li><a href="'.home_url('/').'seko/"><span>施工事例</span></a></li>';
    $str.= '<li><span>'.$cat->name.'</span></li>';
	}

  /* 時系列アーカイブページ */
  elseif(is_date()){
    if(get_query_var('day') != 0){
      $str.= '<li><a href="'. get_year_link(get_query_var('year')).'"><span>'.get_query_var('year').'年</span></a></li>';
      $str.= '<li><a  href="'.get_month_link(get_query_var('year'), get_query_var('monthnum')).'"><span>'.get_query_var('monthnum').'月</span></a></li>';
      $str.= '<li><span>'.get_query_var('day'). '</span>日</li>';
    } elseif(get_query_var('monthnum') != 0){
      $str.= '<li><a href="'. get_year_link(get_query_var('year')).'"><span>'.get_query_var('year').'年</span></a></li>';
      $str.= '<li><span>'.get_query_var('monthnum'). '</span>月</li>';
    } else {
      $str.= '<li><span>'.get_query_var('year').'年</span></li>';
    }
  }

  /* 投稿者ページ */
  elseif(is_author()){
    $str.= '<li><span>投稿者 : '.get_the_author_meta('display_name', get_query_var('author')).'</span></li>';
  }

  /* 添付ファイルページ */
  elseif(is_attachment()){
    $pNum = 2;
    if($post -> post_parent != 0 ){
      $str.= '<li><a href="'.get_permalink($post-> post_parent).'"><span>'.get_the_title($post->post_parent).'</span></a></li>';
    }
    $str.= '<li><span>'.$post->post_title.'</span></li>';
  }

  /* 検索結果ページ */
  elseif(is_search()){
    $str.= '<li><span>「'.get_search_query().'」で検索した結果</span></li>';
  }

  /* 404 Not Found ページ */
  elseif(is_404()){
    $str.= '<li><span>お探しの記事は見つかりませんでした。</span></li>';
  }

  /* その他のページ */
  else{
    $str.= '<li><span>'.wp_title('', false).'</span></li>';
  }
  $str.= '</ul>';
  $str.= '</div>';

  echo $str;
}

//カスタム投稿 the_archive_title 不要文字を削除
add_filter( 'get_the_archive_title', function ($title) {
    if ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        }
    return $title;
});

//管理画面エディタ「見出し１」を削除する
function custom_editor_settenlarges( $initArray ){
$initArray['block_formats'] = "段落=p; 見出し2=h2; 見出し3=h3; 見出し4=h4;";
return $initArray;
}
add_filter( 'tiny_mce_before_init', 'custom_editor_settenlarges' );

// エディタ2段目に追加
add_filter( 'mce_buttons_2', 'add_mce_buttons_2' );
function add_mce_buttons_2( $buttons ) {
  $add_buttons_2 = array(
    'backcolor'
  );
  return array_merge( $buttons, $add_buttons_2 );
}

function normalizeTime($date_str) {
    // 年月日の各パーツを分割する
    preg_match( "/([0-9]*)年([0-9]*)月([0-9]*)日/", $date_str, $data );
    if ( Count( $data ) != 4 ) {
        return $str;
    }

    // 先頭0埋めでYYYYMMDD形式の日付文字列に変換する
    $outStr = sprintf( "%04.4d/%02.2d/%02.2d", $data[1], $data[2], $data[3] );

    return strtotime($outStr);
}

//関数
//記事本文の最初の画像を取得
function post_content_first_image() {
global $post, $posts;
$first_img = '';
ob_start();
ob_end_clean();
$output = preg_match_all('/<img.+src=[\'"](.+?jpeg|.+?bmp|.+?jpg|.+?png)[\'"].*>/i', $post->post_content, $matches);
$first_img = $matches [1] [0];
return $first_img;
}

function my_js_function() {
echo <<< EOM
<script>

$(function() {
	$('.slider1').slick({
		settings: {
			adaptiveHeight:true,
			slidesToShow: 12,
			slidesToScroll: 1
			}
	});
});

</script>
EOM;
}
add_action( 'wp_footer', 'my_js_function',100 );


function change_posts_per_page($query) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( $query->is_archive('album') ) {
        $query->set( 'posts_per_page', '30' );
    }
}
add_action( 'pre_get_posts', 'change_posts_per_page' );

?>
