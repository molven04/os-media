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

        <div id="primary" class="content-area">
            <div id="content" class="site-content" style="margin-right: 0;" role="main">

                <?php  echo OSmedia_video(); ?>

                <div class="entry-content">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <?php the_content(); ?>
                    <?php edit_post_link( __( 'Edit', 'twentyfourteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
                </div><!-- .entry-content -->

            </div>
        </div>

    <?php endwhile ?>

</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();