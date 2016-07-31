<?php
    $alias = 'Office';
    $icons = [
        'desc' => 'icon-arrow-down',
        'asc' => 'icon-arrow-up'
    ];
    $fields = [
        '1' => ['dir' => 'asc', 'text' => 'Tên doanh nghiệp'],
        '2' => ['dir' => 'asc', 'text' => 'Website'],
        '3' => ['dir' => 'asc', 'text' => 'Fanpage'],
        '4' => ['dir' => 'asc', 'text' => 'Email'],
        '5' => ['dir' => 'asc', 'text' => 'Phone'],
        '6' => ['dir' => 'asc', 'text' => 'Trạng thái'],
        '7' => ['dir' => 'asc', 'text' => 'Ngày tạo'],
    ];

    $fields[$sort]['cur'] = $dir;

    if($dir == 'asc'){
        $dir = 'desc';
    } else {
        $dir = 'asc';
    }

    $fields[$sort]['dir'] = $dir;
?>

 <div class="col-md-12">
    <section class="panel">
        <div class="col-xs-12 panel-heading">
            <i class="icon-list"></i> <?= __('Danh sách công ty (%s)', $total); ?>
        </div>
        <div id="table-list">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>

                        <?php foreach ($fields as $key => $value): ?>
                            <th>
                                <a href="?sort=<?= $key; ?>&dir=<?= $value['dir']; ?>"><?= __($value['text']); ?>
                                    <?php if(isset($value['cur'])): ?>
                                        <i class="<?= $icons[$value['cur']]; ?>"></i>
                                    <?php else: ?>
                                        <i class="<?= $icons[$value['dir']]; ?>"></i>
                                    <?php endif; ?>
                                </a>
                            </th>
                        <?php endforeach; ?>
                        <th class="actions"><?= __('Actions');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $v): ?>
                    <tr>

                        <td><?= $v['name']; ?></td>
                        <td><?= $v['website'] ?></td>
                        <td><?= $v['fanpage'] ?></td>
                        <td><?= $v['email'] ?></td>
                        <td><?= $v['phone'] ?></td>
                        <td><?= $status[$v['create_info']['status']]; ?></td>
                        <td><?= $v['create_info']['created']; ?></td>
                        <td class="actions">
                            <?php if($v['create_info']['status'] !== 5): ?>
                                <a class="btn btn-warning btn-xs" href="#" data-update="1" data-status="5" data-model="<?= $alias; ?>" data-id="<?= $v['_id']; ?>" title="<?= __('Khóa nội dung này'); ?>"><i class="icon-lock"></i></a>
                            <?php else: ?>
                                <a class="btn btn-warning btn-xs" href="#" data-update="1" data-status="4" data-model="<?= $alias; ?>" data-id="<?= $v['_id']; ?>" title="<?= __('Mở khóa nội dung này'); ?>"><i class="icon-unlock"></i></a>
                            <?php endif; ?>
                            <a class="btn btn-danger btn-xs" href="#" data-update="1" data-status="9" data-model="<?= $alias; ?>" data-id="<?= $v['_id']; ?>" title="<?= __('Xóa nội dung này'); ?>"><i class="icon-remove-sign"></i></a>
                            <a class="btn btn-info btn-xs" href="/offices/api/<?= $v['_id']; ?>" title="<?= __('Xem API'); ?>"><i class="icon-code"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->element('admin/form-delete', ['alias' => $alias]); ?>