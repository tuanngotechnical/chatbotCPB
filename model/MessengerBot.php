<?php
App::uses( 'AppModel', 'Model' );
/**
 * Avatar for system
 *
 * @param type  meta
 * @return type  meta
 * @access public
 */
class MessengerBot extends AppModel {
    var $name = 'MessengerBot';
    var $shardKeys = array('_id');

    var $mongoSchema = array(
            'sender_id' => array('type' => 'string'), // mã người nhận
            'message' => ['type' => 'string'], // thông điệp ghi log
            'create_info' => array(
                    'create_user' => array('type' => 'string'), // mã người tạo
                    'modify_user' => array('type' => 'string'), // mã người chỉnh sửa
                    'status' => array('type' => 'int'), // trạng thái của danh mục: 1) Đăng tạm, 3) Từ chối; 4) Xác nhận; 5) Khóa; 9) Xóa
                    'created' => array('type' => 'datetime'), // ngày tạo danh mục
                    'modified' => array('type' => 'datetime'), // ngày chỉnh sửa sau cùng
            ),
    );

    public function findBySender($value='')
    {
    	return $this->find('first', ['conditions' => ['sender_id' => $value], 'order' => ['_id' => 'desc']]);
    }
}

?>