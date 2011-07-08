<div id="head">
    <div id="heading">
        <?php echo image_tag("logo.png", array("id"=>"logo")) ?>
        <?php include_partial("global/socialMediaLinks") ?>
    </div>
    <div id="navbar">

    </div>
    <div id="controlbar">
        <?php if ($sf_user->isAuthenticated()): ?>
            <?php echo $sf_user->getGuardUser()->getEmailAddress() ?>
             |
            <?php echo link_to("Log out","sfGuardAuth/signout") ?>
        <?php endif ?>
    </div>
</div>