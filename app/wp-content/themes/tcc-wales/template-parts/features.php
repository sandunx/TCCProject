
<?php
global $wp_query;
if( have_rows('features')): ?>

<div class="feature-bg">
    <div class="container py-5" id="featured-3">
        <div class="row">
        <?php

        while( have_rows('features') ): the_row();

        $heading = get_sub_field('feature_heading');
        $content = get_sub_field('feature_content');
        $i = get_row_index();
        $colors = [
            'acai',
            'grape',
            'berry',
        ];
        $color = $colors[$i-1];
        $offset = 3 % $i ? 'offset-lg-2' : '';

        ?>
            <div class="feature col-lg-10 mb-5 <?php echo $offset; ?>">
                <div class="card rounded-5 h-100 d-flex flex-column h-100 justify-content-between shadow ">
                <div class="row">
                    <div class="col-lg-4">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/School.jpeg' ;?>" alt="" class="img-fluid rounded-start-3">
                    </div>
                        <div class="col-lg-8">
                            <div class="card-body my-3">
                                <h3 class="display-3 fw-bold card-title text-<?php echo $color ?>"><?php echo $heading; ?></h3>
                                <p class="card-text text-acai-dark"><?php echo $content; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<?php endif; ?>