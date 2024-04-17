<?php

$lead = get_field('hero_lead');

?>
<div class="hero-bg py-lg-5" >
    <div class="container rounded-start-5">
        <div class="row flex-row-reverse">
            <div class="col-8 mx-auto mx-lg-0 col-md-6 mx-lg-auto col-xl-6">
<!--                <img src="--><?php //echo get_stylesheet_directory_uri() . '/assets/img/group.png' ; ?><!--" class="img-fluid" alt="Bootstrap Themes" loading="lazy" style="border: 1px solid #ffe3ee; box-shadow: 0px 3px 35px hsl(331.77deg 71% 33% / 10%);" >-->
                <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/group.png' ; ?>" class="img-fluid" alt="Bootstrap Themes" loading="lazy" >
            </div>
            <div class="col-xl-5 col-xl-6 p-5 d-flex flex-column justify-between align-center p-xxl-5 text-center text-xl-start">
                <div class="top-wrap mt-lg-5">
                    <h1 class="display-2 fw-bold lh-1">Together Creating Communities</h1>
                    <?php the_title('<h1 class=" fw-bold lh-1">','</h1>'); ?>
                    <p class="lead text-berry fw-bold"><?php echo $lead; ?> We empower diverse communities to enact change by tackling social injustices.</p>
                </div>
                <div class="bottom-wrap">
                    <div class="d-grid gap-2 d-md-flex justify-center justify-content-xl-start mt-4">
                        <button type="button" class="btn btn-primary btn-acai">About TCC</button>
                        <button type="button" class="btn btn-outline-secondary btn-outline-acai bg-white">Join Us</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

