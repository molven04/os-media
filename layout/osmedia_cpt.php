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

<div id="main-content" class="main-content" style="margin:auto; width:80%; padding:10px;">

    <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header"></header><!-- .entry-header -->

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">

                <?php  echo OSmedia_video() ?>

                <div class="entry-content" style="margin-top:20px">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <?php the_content(); ?>
                </div><!-- .entry-content -->

            </div>
        </div>

    </article><!-- #post-## -->

    <?php endwhile ?>

</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();