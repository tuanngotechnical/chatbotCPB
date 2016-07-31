<!DOCTYPE html>
<html lang="vi">
    <head itemscope itemtype="http://schema.org/WebSite">
        <?php echo $this->element('front/office/page-head'); ?>
    </head>
    <body id="not-login" data-user="<?= @$global_user['_id']; ?>"  data-theme="<?= Configure::read('THEME'); ?>" data-run-mode="<?= SERVER_RUN_MODE; ?>" data-cache-version="<?= $cache_version ?>" data-lang="<?= Configure::read('Config.language'); ?>">

        <?php echo $this->fetch('content'); ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <?php if(!empty($showFooter)): ?>
        <?php echo $this->element('front/office/footer'); ?>
        <?php echo $this->element('front/footer-js'); ?>
    	<?php endif; ?>

    </body>
</html>