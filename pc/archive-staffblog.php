<?php
/**
 * Template Name:スタッフブログ一覧ページ
 *
 * @package Wordpress
 * @subpackage reform2
 * @since 1.0.0
 */
get_header(); ?>


 <div id="top" class="container">
   <?php get_sidebar(); ?>
   <main class="main" role="main">
     <?php breadcrumb(); ?>
     <div class="page-title-area">
       <h2 class="page-title">スタッフブログ</h2>
       <p class="page-subtitle">Staff Blog</p>
     </div>
     <ul class="staff-menu-list">
       <li><a href="<?php echo home_url(); ?>/staffblog_cat/hachioji">八王子店</a></li>
       <li><a href="<?php echo home_url(); ?>/staffblog_cat/kitano">北野店</a></li>
       <li><a href="<?php echo home_url(); ?>/staffblog_cat/hino">日野店</a></li>
       <li><a href="<?php echo home_url(); ?>/staffblog_cat/cafe">Caféエンラージ</a></li>
     </ul>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
    <div class="blog-post">
      <h3 class="heading1"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
      <p class="right"><?php the_time('Y年n月j日(D)'); ?></p>
      <div class="contet">
        <?php the_content(); ?>
      </div>
      <p class="right mt20">カテゴリー：<?php
      $terms = get_the_terms( $post->ID, 'staffblog_cat' );
      if ( $terms && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
          printf(
            '<a href="%1$s">%2$s</a> ',
            get_term_link( $term, 'staffblog_cat' ),
            $term->name
          );
        }
      }
      ?> | <?php if( function_exists('zilla_likes') ) zilla_likes(); ?>
      <div class="general-button iine-btn">
        <div class="button-content">
          <span class="icon-font">good</span>
          <span class="button-text">よかった<div class="iine-cnt"></div></span>
        </div>
      </div>
    </p>
    </div>
<?php endwhile; ?>
<?php endif; ?>
    <?php
      //Pagenation
      if (function_exists("pagination")) {
      pagination($additional_loop->max_num_pages);
      }
    ?>
 	</main>
 </div>
<?php get_footer(); ?>
