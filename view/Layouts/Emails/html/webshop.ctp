<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<title><?= __("%s's Email", BASE_NAME); ?></title>
<?php if(SERVER_RUN_MODE != "REAL"): ?>
    <link rel="stylesheet" type="text/css" href="<?= URL_BASE; ?>/theme/V05/css/email.css" />
<?php else: ?>
    <link rel="stylesheet" type="text/css" href="<?= APP; ?>/webroot/theme/V05/css/email.css" />
<?php endif; ?>
<link rel="stylesheet" type="text/css" href="<?= URL_BASE; ?>/theme/V05/css/lato-font.css" />
</head>
<body class="text-center">
    <div class="cover">
        <table class="table">
            <tr>
                <td class="a">
                    <img style="display:inline" src="<?= URL_BASE; ?>/theme/V05/img/email/f-logo.png" />
                </td>
            </tr>
            <tr>
                <td class="e">
                    <div class="m">
                            <?php echo $this->fetch('content'); ?>
                            <div class="s">
                                <hr class="h" />
                                <p class="g"><?= __('Thân ái!'); ?> <br />
                                 <?= __('Nhóm phát triển %s', BASE_NAME); ?></p>
                            </div>
                     </div>
                </td>
            </tr>
            <tr>
                <td  class="f">
                    <div class="k">
                    <img style="float:lelt;" src="<?= URL_BASE; ?>/theme/V05/img/email/phone.png">
                    <span><?= __('Hotline: 1900 6454'); ?></span>
                    <br />
                    <a href="https://www.facebook.com/thietkehoavietnam"><img src="<?= URL_BASE; ?>/theme/V05/img/email/fb.jpg" style="margin:10px;"></a>
                    <br />
                    <p class="k"><?= __('Copyright © %s %s, Inc.', date('Y'), BASE_NAME); ?></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>