<?php
/**
 * Template Name: Featured video list
 *
 * @package WordPress
 * @subpackage OSmedia-theme
 * @since Twenty Thirteen 1.0
 */
?>

<?php get_header(); ?>

<div id="main-content" class="main-content">

  <div id="primary" class="content-area">

    <?php
    $mypost = array( 'post_type' => 'osmedia_cpt', 'showposts' => 15, 'paged' => $paged /*, 'category_name' => Featured */);
    $loop = new WP_Query( $mypost );
    ?>

    <div id="featured-content" class="featured-content">
      <div class="featured-content-inner">
          <?php /* The loop */ ?>
          <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
          
            <?php // get_template_part( 'content', get_post_format() ); ?>      
            
            <?php // get_template_part( 'content', 'featured-post' ); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
              <a class="post-thumbnail" href="<?php the_permalink(); ?>">
              <?php
                if (has_post_thumbnail( $post->ID )) {
                  $array_img_cover_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
                  $img_cover_url = $array_img_cover_url[0];
                // }elseif (file_exists($img_cover_url)){
                  // $img_cover_url = get_post_meta($post->ID, 'OSvid_img', true);
                }else{
                  $img_cover_url = get_post_meta($post->ID, 'OSvid_img', true);
                }
                // if (file_exists($img_cover_url)) $img_cover_url=$img_cover_url;  else $img_cover_url=$ftrd_image[0];
                  echo '<img src='.$img_cover_url.' />'; //the_post_thumbnail( 'twentyfourteen-full-width' ); 
              ?>
              <img src="<?php echo get_stylesheet_directory_uri() ?>/images/OS-frame-play.png" />
              </a>

              <header class="entry-header">
                <?php //if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && twentyfourteen_categorized_blog() ) : ?>
                <div class="entry-meta">
                  <span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'OStheme' ) ); ?></span>
                </div><!-- .entry-meta -->
                <?php //endif; ?>

                <?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h1>' ); ?>
                <?php // the_excerpt( '<p class="entry_exc">','</p>' ); ?>
              </header><!-- .entry-header -->
            </article><!-- #post-## -->
                  
          <?php endwhile ?>
          
      </div><!-- .featured-content-inner -->
    </div><!-- #featured-content .featured-content -->

  </div>  
</div>
<?php wp_reset_query(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>