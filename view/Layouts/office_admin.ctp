<!DOCTYPE html>
<html lang="vi">
    <head>
        <?php echo $this->element('admin/page-head'); ?>
    </head>

    <body data-theme="<?= Configure::read('THEME'); ?>" data-run-mode="<?= SERVER_RUN_MODE; ?>" data-cache-version="<?= $cache_version ?>" data-lang="<?= Configure::read('Config.language'); ?>">
        <section id="container">
            <?php echo $this->element('admin/office/top-head'); ?>
            <?php echo $this->element('admin/office/left-menu'); ?>
            <section id="main-content">
                <section class="wrapper site-min-height">
                    <div class="row">
                        <section class="panel" id="main-panel">
                        <?php
                            if(empty($main_page_hide_breadcrumb)){
                                echo $this->element('admin/breadcrumb');
                            }
                        ?>
                        <?php echo $this->fetch('content'); ?>
                        </section>
                        </div>
                    </div>
                </section>
            </section>
            <?php echo $this->element('admin/footer'); ?>

        </section>

        <?php echo $this->element('admin/footer-js'); ?>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
