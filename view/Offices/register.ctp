<style type="text/css">
	#b_register .input{
		margin-bottom: 25px;
	}
	#b_register input{
		padding:12px 20px;
		font-size: 17px;
	}
</style>
<div style="background:#0084FF; margin-bottom:50px;">
	<div class="pd20 container">
		<img src="<?= ShowStaticContent('/img/logo_1.png'); ?>">
	</div>
</div>

<div class="text-center">
<?php echo $this->Session->flash(); ?>
</div>

<div class="container" id="b_register">

	<div class="text-center">
		<h2><?= __('NHẬP THÔNG TIN DOANH NGHIỆP BẠN'); ?></h2>
		<hr class="linecolor" />

	</div>
	<div class="row">
		<div class="col-md-3">
		</div>
		<div class="col-md-6">
		<?php echo $this->Form->create('Office'); ?>
		<?php echo $this->Form->input('name', ['label' => __('Tên công ty'), 'class' => 'form-control', 'placeholder' => __('Nhập vào tên công ty của bạn'), 'type' => 'text', 'required' => true]); ?>
		<?php echo $this->Form->input('website', ['label' => __('Website'), 'class' => 'form-control', 'placeholder' => __('https://'), 'type' => 'url', 'required' => true]); ?>
		<?php echo $this->Form->input('fanpage', ['label' => __('Facebook Fanpage'), 'class' => 'form-control', 'placeholder' => __('https://www.facebook.com/...'), 'type' => 'url', 'required' => true]); ?>
		<?php echo $this->Form->input('email', ['label' => __('Email'), 'class' => 'form-control', 'placeholder' => __('abc@...'), 'type' => 'email', 'required' => true]); ?>
		<?php echo $this->Form->input('phone', ['label' => __('Số điện thoại'), 'class' => 'form-control', 'placeholder' => __('0989xxxxx'), 'type' => 'text', 'required' => true]); ?>

		<button class="btn btn-primary"><?= __('Lưu & chuyển đến nhập thông tin API'); ?></button>
		<a href="/offices/index" class="btn btn-secondary"><?= __('Hủy bỏ'); ?></a>
		<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<div style="background:#eee; margin-top:50px;" class="text-center pd20">
	<strong>@2016. Made with love by CPB.</strong>
</div>