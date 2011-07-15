<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <?php include_partial("global/heading") ?>
    <?php if ($sf_user->hasPermission("manage-users")): ?>
        <?php echo link_to("Facebook Pages","facebookpage/index"); ?> |
        <?php echo link_to("Industries","industry/index"); ?> | 
        <?php echo link_to("Users","guard/users"); ?> |
        <?php echo link_to("Permissions","guard/permissions"); ?> |
        <?php echo link_to("Renew Token","oauth/index"); ?> |
        <?php  echo link_to("Force update","populate/index"); ?>
    <?php endif; ?>
    <div id="content">
        <?php echo $sf_content ?>
    </div>
    <?php include_partial("global/footer") ?>
  </body>
</html>
