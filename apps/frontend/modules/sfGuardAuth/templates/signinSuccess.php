<?php use_helper('I18N') ?>

<!--h1><?php echo __('Signin', null, 'sf_guard') ?></h1-->

<div class="login-form">
    <div class="top">
        <h1>Visualise the Australian Facebook Landscape</h1>
        <ul>
            <li class="industry">Compare by Industry</li>
            <li class="australia">See the top 10 brands Australia wide</li>
            <li class="updated">Updated daily</li>
            <li class="print">Print, export and share</li>
        </ul>
        <div class="links">
            <a href="<?php echo url_for("@sf_guard_register") ?>" class="signup">Sign Up Now</a> <a href="<?php echo url_for("@sf_guard_register") ?>" class="clickhere">Click Here</a>
        </div>
        <div class="image"><?php echo image_tag("landing-page-graph.png", array()) ?></div>
    </div>
    <div class="bottom">
        <h2 class="insights-heading">Insights Login</h2>
        <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>
    </div>
</div>