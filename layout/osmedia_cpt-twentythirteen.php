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

<div id="main-content" class="main-content">

    <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header"></header><!-- .entry-header -->

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">

                <?php  echo OSmedia_video(); // OSmedia videoplayer ?>

                <div class="entry-content">
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

                    <?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
                </div><!-- .entry-content -->

            </div>
        </div>

    </article><!-- #post-## -->

    <?php endwhile ?>

</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();