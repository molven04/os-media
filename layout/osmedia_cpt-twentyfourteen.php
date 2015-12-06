<?php
/**
 * Template Name: Featured video single
 *
 * @package WordPress
 * @subpackage OSmedia-theme
 * @since Twenty Thirteen 1.0
 */

get_header(); 

?>

<div id="main-content" class="main-content">

    <?php while ( have_posts() ) : the_post(); ?>

        <div id="primary" class="content-area" style="padding-top:0; margin-top:0">
            <div id="content" class="site-content" style="margin-right: 0;" role="main">

                <?php  echo OSmedia_video(); // OSmedia videoplayer ?>

                <div class="entry-content" style="margin: 20px 30px; max-width:1024px">
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

                    <?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
                </div><!-- .entry-content -->

            </div>
        </div>

    <?php endwhile ?>

</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();