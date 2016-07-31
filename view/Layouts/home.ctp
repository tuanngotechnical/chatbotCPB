<!DOCTYPE html>
<html lang="vi">
    <head itemscope itemtype="http://schema.org/WebSite">
        <?php echo $this->element('front/page-head'); ?>
    </head>
    <body id="not-login" data-user="<?= @$global_user['_id']; ?>"  data-theme="<?= Configure::read('THEME'); ?>" data-run-mode="<?= SERVER_RUN_MODE; ?>" data-cache-version="<?= $cache_version ?>" data-lang="<?= Configure::read('Config.language'); ?>">

        <?php echo $this->element('front/top-menu'); ?>
        <?php echo $this->fetch('content'); ?>
        <?php echo $this->element('front/footer'); ?>
        <?php echo $this->element('front/footer-js'); ?>

    </body>
</html>