<?php

get_header();

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="container">
            <div class="row align-items-center mb-4 rounded-start-pill p-3 bg-gray-300">
                <div class="col-2 p-0">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/hero.png' ; ?>" class="img-fluid rounded-circle"  loading="lazy" style="border: 1px solid #ffe3ee;" >
                </div>
                <div class="col-10">
                    <?php

                    the_archive_title('<h2 class="display-3 card-title fw-bold text-acai">','</h2>');
                    the_archive_description();
                    ?>
                </div>
            </div>

            <?php if (have_posts() ): ?>
                <div class="row col-10 g-0 offset-lg-2">
                <?php while (have_posts()): the_post();

                    $position = get_field('position');
                    $contact    = get_field('contact');

                ?>
                    <div class="col-6 col-lg-4 mb-5">
                        <div class="card border-0 rounded-5">
                        <?php the_post_thumbnail('team-image', ['class'=> 'card-img-top rounded-top-5 img-fluid']); ?>
                            <div class="card-body bg-acai">
                                <?php
                                    the_title('<h3 class="text-white card-title display-5 fw-bold mb-0">','</h3>');

                                echo $position ? '<h4 class="text-white mb-0 fw-bold ">' . $position .'</h4>': '';
                                ?>
                            </div>

                            <div class="card-body p-0">
                                <p class="card-text"><?php the_content(); ?></p>
                            </div>
                            <div class="list-group list-group-flush">
                                <?php echo '<a  class="list-group-item list-group-item-action btn btn-primary btn-acai" href="mailto:'. $contact .'">' .$contact. '</a>'; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile; // end while ?>

                </div>
            <?php endif; ?>

            </div>
<?php


get_footer();