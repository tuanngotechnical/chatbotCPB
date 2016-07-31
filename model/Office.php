<?php
App::uses( 'AppModel', 'Model' );

class Office extends AppModel {
    var $shardKeys = array('_id');
    var $name = 'Office';

    var $mongoSchema = array(
            'name' => ['type' => 'string'], //sdt người mua hàng
            'website' => ['type' => 'string'], // log
            'fanpage' => ['type' => 'string'], // log
            'address' => ['type' => 'string'], // log
            'phone' => ['type' => 'string'], // log
            'email' => ['type' => 'string'], // log
            'api' => [],
            'create_info' => array(
                'create_user' => array('type' => 'string'),
                'modify_user' => array('type' => 'string'),
                'status' => array('type' => 'int'),
                'created' => array('type' => 'datetime'),
                'modified' => array('type' => 'datetime')
            ),
    );

    var $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => array( 'notBlank' ),
                'message' => "Tên không được rỗng.",
            ),
            'maxLength' => array(
                'rule' => array( 'maxLength', 255 ),
                'message' => "Tên không được quá 255 ký tự."
            )
        ),
        'website' => array(
            'notBlank' => array(
                'rule' => array( 'notBlank' ),
                'message' => "Website không được rỗng.",
            ),
            'maxLength' => array(
                'rule' => array( 'maxLength', 255 ),
                'message' => "Website không được quá 255 ký tự."
            )
        ),
        'fanpage' => array(
            'notBlank' => array(
                'rule' => array( 'notBlank' ),
                'message' => "Fanpage không được rỗng.",
            ),
            'maxLength' => array(
                'rule' => array( 'maxLength', 255 ),
                'message' => "Fanpage không được quá 255 ký tự."
            )
        ),
        'email' => array(
            'notBlank' => array(
                'rule' => array( 'notBlank' ),
                'message' => "Email không được rỗng.",
            ),
            'wrong' => array(
                'rule' => array( 'Email' ),
                'message' => "Email không đúng định dạng.",
            ),
            'maxLength' => array(
                'rule' => array( 'maxLength', 255 ),
                'message' => "Email không được quá 255 ký tự."
            )
        ),
        'phone' => array(
            'notBlank' => array(
                'rule' => array( 'notBlank' ),
                'message' => "Số điện thoại không được rỗng.",
            ),

            'maxLength' => array(
                'rule' => array( 'maxLength', 255 ),
                'message' => "Số điện thoại không được quá 255 ký tự."
            )
        ),
    );

    public function init(&$data){

        $init = [
            'name' => null, // mã đơn hàng
            'website' => '',
            'fanpage' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'create_info' => array(
                'create_user' => null,
                'modify_user' => null,
                'status' => 4,
                'created' => null,
                'modified' => null
            ),
        ];
        $data = array_replace_recursive($init, $data);
    }

    var $elasticSearch = [
        'type' => 'offices',
        'fields' => [],
        'body' => []
    ];
}