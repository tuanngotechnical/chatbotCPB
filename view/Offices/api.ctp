<style type="text/css">
	#b_register .input{
		margin-bottom: 10px;
	}
	#b_register input{
		padding:12px 20px;
		font-size: 17px;
	}

	#api_desc .key{
		color:blue;
	}

	#api_desc p{
		margin-bottom: 0;
	}
	#api_desc .val{
		color:red;
	}

	.desc{
		margin-bottom: 25px;
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
		<h2><?= __('KHAI BÁO CÁC API SỬ DỤNG'); ?></h2>
		<hr class="linecolor" />

	</div>
	<div class="row" id="api_desc">
		<div class="col-md-2">
		</div>
		<div class="col-md-8">
			<h3><?= __('Thông tin công ty'); ?></h3>
			<table class="table table-bordered">
			<tbody>
				<tr>
					<td>Tên công ty</td>
					<td><?= $office['name']; ?></td>
				</tr>
				<tr>
					<td>Website</td>
					<td><?= $office['website']; ?></td>
				</tr>
				<tr>
					<td>Điện thoại</td>
					<td><?= $office['phone']; ?></td>
				</tr>
			</tbody>
			</table>
		<h3><?= __('APIs'); ?></h3>
		<?php echo $this->Form->create('Office'); ?>
		<?php echo $this->Form->hidden('Office._id', ['value' => @$office['_id']]); ?>
		<?php echo $this->Form->input('Office.api.welcome', ['label' => __('Lời chào khi có người dùng bắt đầu chat.'), 'class' => 'form-control', 'placeholder' => __('Xin chào bạn, Tôi là ChatBot của ABC, hân hạnh được phục vụ bạn.'), 'type' => 'textarea', 'required' => true, 'value' => @$office['api']['welcome']]); ?>

		<?php echo $this->Form->input('Office.api.search', ['label' => __('Đường link đến API tìm kiếm'), 'class' => 'form-control', 'placeholder' => __('https://..../api/search'), 'type' => 'url', 'required' => true, 'value' => @$office['api']['search']]); ?>
		<div class="desc">
			<a href="#" class="v-more"><?= __('Xem định nghĩa API này'); ?></a>
			<div class="mt10 alert" style="display:none">
			<p><?= __('Các tham số được truyền bằng phương thức GET. ví dụ: GET[price] = ["from" => 0, "to" => 9000000].'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">method</span>
							<br />
							<p><?= __('Loại phương trức tìm kiếm'); ?></p>
						</td>
						<td><span class="val">find</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">price</span>
							<br />
							<p><?= __('Tìm theo giá cả'); ?></p>
						</td>
						<td><span class="val">['from' => 0, 'to' => 9000000]</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">category_id</span>
							<br />
							<p><?= __('Tìm theo danh mục sản phẩm'); ?></p>
						</td>
						<td><span class="val">18231</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">province_id</span>
							<br />
							<p><?= __('Tìm theo tỉnh thành'); ?></p>
						</td>
						<td><span class="val">hcm</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">offset</span>
							<br />
							<p><?= __('Lấy từ vị trí'); ?></p>
						</td>
						<td><span class="val">0</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">limit</span>
							<br />
							<p><?= __('Số lượng cần trả về'); ?></p>
						</td>
						<td><span class="val">10</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">access_token</span>
							<br />
							<p><?= __('Tìm theo giá cả'); ?></p>
						</td>
						<td><span class="val">kfdlk**(^@$%$@LJSDLKJFDL</span></td>
					</tr>
				</tbody>
			</table>

			<p><?= __('Kết quả trả về kiểu JSON.'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">products</span>
							<br />
							<p><?= __('Trả về danh sách sản phẩm'); ?></p>
						</td>
						<td>
							<span class="val">array</span>
							<table class="table table-bordered mt5">
								<thead>
									<th><?= __('Tham số'); ?></th>
									<th><?= __('Giá trị'); ?></th>
								</thead>
								<tbody>
									<tr>
										<td><span class="key">code</span></td>
										<td><span class="val">P098934</span></td>
									</tr>
									<tr>
										<td><span class="key">name</span></td>
										<td><span class="val">Hoa chúc mừng tuyệt đẹp</span></td>
									</tr>
									<tr>
										<td><span class="key">price</span></td>
										<td><span class="val">Giá bán sản phẩm</span></td>
									</tr>
									<tr>
										<td><span class="key">image</span></td>
										<td><span class="val">https://www.thietkehoa.com/photos/view/photos/larges/57811a8b7f8b9af601ca486d.jpg</span></td>
									</tr>
									<tr>
										<td><span class="key">description</span></td>
										<td><span class="val">Nội dung mô tả thông tin sản phẩm</span></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<span class="key">code</span>
						</td>
						<td><span class="val">ok</span></td>
					</tr>
				</tbody>
			</table>

			</div>
		</div>

		<!-- xem chi tiết -->
		<?php echo $this->Form->input('Office.api.product', ['label' => __('Đường link đến API chi tiết sản phẩm'), 'class' => 'form-control', 'placeholder' => __('https://..../api/product'), 'type' => 'url', 'required' => true, 'value' => @$office['api']['product']]); ?>
		<div class="desc">
			<a href="#" class="v-more"><?= __('Xem định nghĩa API này'); ?></a>
			<div class="mt10 alert" style="display:none">
			<p><?= __('Các tham số được truyền bằng phương thức GET. ví dụ: GET[code] = "P00120".'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">method</span>
							<br />
							<p><?= __('Loại phương trức tìm kiếm'); ?></p>
						</td>
						<td><span class="val">product</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">code</span>
							<br />
							<p><?= __('Mã sản phẩm'); ?></p>
						</td>
						<td><span class="val">P00124</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">access_token</span>
							<br />
							<p><?= __('Tìm theo giá cả'); ?></p>
						</td>
						<td><span class="val">kfdlk**(^@$%$@LJSDLKJFDL</span></td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>

		<!-- xem chi đơn hàng -->
		<?php echo $this->Form->input('Office.api.order', ['label' => __('Đường link đến API chi tiết đơn hàng'), 'class' => 'form-control', 'placeholder' => __('https://..../api/order'), 'type' => 'url', 'required' => true, 'value' => @$office['api']['order']]); ?>
		<div class="desc">
			<a href="#" class="v-more"><?= __('Xem định nghĩa API này'); ?></a>
			<div class="mt10 alert" style="display:none">
			<p><?= __('Các tham số được truyền bằng phương thức GET. ví dụ: GET[code] = "P00120".'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">method</span>
							<br />
							<p><?= __('Loại phương trức tìm kiếm'); ?></p>
						</td>
						<td><span class="val">order</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">code</span>
							<br />
							<p><?= __('Mã đơn hàng'); ?></p>
						</td>
						<td><span class="val">FDH00124</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">access_token</span>
							<br />
							<p><?= __('Tìm theo giá cả'); ?></p>
						</td>
						<td><span class="val">kfdlk**(^@$%$@LJSDLKJFDL</span></td>
					</tr>
				</tbody>
			</table>

			<p><?= __('Kết quả trả về kiểu JSON.'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">order_id</span>
						</td>
						<td><span class="val">FDH12321</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">summary</span>
						</td>
						<td><span class="val">$20.00</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">customer</span>
						</td>
						<td><span class="val">Ngô Anh Tuấn</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">code</span>
						</td>
						<td><span class="val">ok</span></td>
					</tr>
				</tbody>
			</table>

			</div>
		</div>

		<!-- xem chi đơn hàng -->
		<?php echo $this->Form->input('Office.api.orderstate', ['label' => __('Đường link đến API chi tiết đơn hàng'), 'class' => 'form-control', 'placeholder' => __('https://..../api/order'), 'type' => 'url', 'required' => true, 'value' => @$office['api']['orderstate']]); ?>
		<div class="desc">
			<a href="#" class="v-more"><?= __('Xem định nghĩa API này'); ?></a>
			<div class="mt10 alert" style="display:none">
			<p><?= __('Các tham số được truyền bằng phương thức GET. ví dụ: GET[code] = "P00120".'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">method</span>
							<br />
							<p><?= __('Loại phương trức tìm kiếm'); ?></p>
						</td>
						<td><span class="val">orderstate</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">code</span>
							<br />
							<p><?= __('Mã đơn hàng'); ?></p>
						</td>
						<td><span class="val">FDH00124</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">access_token</span>
							<br />
							<p><?= __('Tìm theo giá cả'); ?></p>
						</td>
						<td><span class="val">kfdlk**(^@$%$@LJSDLKJFDL</span></td>
					</tr>
				</tbody>
			</table>

			<p><?= __('Kết quả trả về kiểu JSON.'); ?></p>
			<table class="table table-bordered mt5">
				<thead>
					<th><?= __('Tham số'); ?></th>
					<th><?= __('Giá trị'); ?></th>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="key">text</span>
						</td>
						<td><span class="val">Tình trạng đơn hàng: Đã được giao xong, thay đổi lúc 09:00 20/7/2016</span></td>
					</tr>
					<tr>
						<td>
							<span class="key">code</span>
						</td>
						<td><span class="val">ok</span></td>
					</tr>
				</tbody>
			</table>

			</div>
		</div>

		<button class="btn btn-primary"><?= __('Lưu thông tin API'); ?></button>
		<a href="/offices/index" class="btn btn-secondary"><?= __('Hủy bỏ'); ?></a>
		<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<div style="background:#eee; margin-top:50px;" class="text-center pd20">
	<strong>@2016. Made with love by CPB.</strong>
</div>

<script type="text/javascript">
	$(function(){
		$('.v-more').click(function(e){
			e.preventDefault();
			var $c = $(this).next();
			if($c.is(":visible")){
				$c.slideUp();
			} else {
				$c.slideDown();
			}
		});
	});
</script>