<?php
/**
 * Template Name: Featured video single
 *
 * @package WordPress
 * @subpackage OSmedia-theme
 * 
 */

get_header(); 

?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

        <article style="padding-top:0" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header"></header><!-- .entry-header -->

            <?php  echo OSmedia_video() ?>

            <div class="entry-content" style="margin-top:20px">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                <?php the_content(); ?>
                <?php
                    wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ) );
                ?>
            </div><!-- .entry-content -->

            <?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

        </article><!-- #post-## -->

        <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>

       <?php endwhile ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php
get_footer();