<?php

$position = get_field('position');
$contact    = get_field('contact');

get_header();

?>

<div id="primary" class="content-area" >
    <main id="main" class="site-main">
        <div class="container">
            <div class="card rounded-5 h-100">
                <div class="row">
                    <div class="col-lg-4">
                        <?php the_post_thumbnail('small', ['class' => ' img-fluid rounded-start-5']); ?>
                    </div>
                    <div class="col-lg-8">
                    <div class="card-body">

                        <?php

                        the_title('<h2 class="display-3 card-title fw-bold text-acai">','</h2>');

                        echo $position ? '<h3 class="text-muted">' . $position .'</h3>': '';
                        ?>

                        <p class="card-text"><?php the_content(); ?></p>
                        <?php echo $contact ? 'You can get in touch with Stephen <a href="mailto:'. $contact .'">here</a>': ''; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php

get_footer();

