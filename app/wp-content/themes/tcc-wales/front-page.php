<?php

/*
 * Template name: Home Page
 */

get_header();

?>
<div id="primary" class="content-area" style="background: url('<?php echo get_stylesheet_directory_uri() . '/assets/img/hero-bg.svg'; ?>'), linear-gradient(45deg, #78ffeb 0%, #FFF 70% ); background-blend-mode: screen; background-repeat: no-repeat;background-size: cover;background-attachment: fixed">
    <main id="main" class="site-main">

        <div class="bg-container" style="background: url('<?php echo get_stylesheet_directory_uri() . '/assets/img/hero-bg.svg'; ?>'), linear-gradient(45deg, #78ffeb 0%, #FFF 70% ); background-blend-mode: screen; background-repeat: no-repeat;background-size: cover;background-attachment: fixed">
        <?php

        get_template_part('template-parts/hero');

        ?>
        </div>
        <?php

        get_template_part('template-parts/features');



        get_template_part('template-parts/story');

        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<style>
    .home-page div#breadcrumbs {
        display: none;
    }
</style>

<?php
get_footer();