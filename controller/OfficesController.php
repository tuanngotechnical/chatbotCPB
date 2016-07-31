<?php
App::uses( 'AppController', 'Controller');

class OfficesController extends AppController {

    var $helpers = array('Html', 'Form');
    var $name = 'Offices';
    var $uses = ['Office', 'Product', 'Bill'];
    var $components = array('Common', "Session", 'MessengerApi', 'ExtProduct', 'ExtBill', 'ExtOffice');
    var $allowedActions = ['index', 'register', 'state', 'api', 'viewp', 'test', 'find', 'callback'];
    var $layout = 'office';

    // call log
    public function index(){
        $this->set('showFooter', false);
    }

    public function test()
    {
        $ret = $this->_getRequest('toi muon dat hang');
        $this->_dosolve($ret);
        pr($ret);
        exit;
    }

    public function _noAnswer($options, $msg)
    {
        $options['message'] = $msg;
        $this->MessengerApi->sendTextMessage($options);
    }

    public function find($options = [])
    {
        $this->MessengerApi->sendTypingOn($options);

        $MessengerBot = MyApp::uses('MessengerBot', 'Model');
        if(isset($options['view_more'])){
            $_opt = $MessengerBot->findBySender($options['sender']);
            if(!empty($_opt)){
                $msg = json_decode($_opt['MessengerBot']['message'], true);

                $options['offset'] = $msg['offset'] + $msg['limit'];
                $options['price'] = $msg['price'];
            }
        }

        if(empty($options['offset'])){
            $options['offset'] = 0;
        }

        if(empty($options['limit'])){
            $options['limit'] = 9;
        }

        $offset = $options['offset'];
        $limit = $options['limit'];

        // // lưu điều kiện xuống db sử dụng cho load more

        $msg = [
            'sender_id' => $options['sender'],
            'message' => json_encode($options)
        ];

        $this->Common->get_common_info($msg);
        $MessengerBot->save($msg);

        list($products, $total) = $this->ExtOffice->searchProduct($options, $offset, $limit);

        if(empty($products)){
            $this->_noAnswer($options, __('Thật xin lỗi! Tôi không tìm thấy thông tin bạn yêu cầu. Bạn có thể thử lại với một yêu cầu khác?'));
        }

        $elements = [];

        foreach ($products as $key => $p) {
            $elements[] = [
                'title' => $p['name'] . ' - ' .  numberFormat($p['prices']['regular'], 'đ'),
                'image_url' => $p['image'],
                'subtitle' => empty($p['desc']) ? __('(chưa có thông tin mô tả)') : substr($p['desc'], 0, 100),
                'buttons' => [
                    [
                        'type' => 'postback',
                        'title' => __('Xem chi tiết'),
                        'payload' => "pid_" . $p['code']
                    ],
                    [
                        'type' => 'web_url',
                        'url' => $p['link_detail'],
                        'title' => __('Đặt mua')
                    ]
                ]
            ];
        }

        $params = [
            'payload' => [
                'template_type' => 'generic',
                'elements' => $elements
            ],
            'sender' => $options['sender']
        ];

        $this->MessengerApi->sendGenericMessage($params);

        // gửi để xem thêm
        $options['payload'] = [
            'template_type' => 'button',
            'text' => __("Nếu bạn chưa chọn được mẫu ưng ý, tôi có thể hiển thị thêm những mẫu khác để bạn xem nhé!"),
            'buttons' => [
                [
                    'type' => 'postback',
                    'payload' => 'VIEW_MORE_PRODUCT',
                    'title' => __('Xem thêm')
                ],
                [
                    'type' => 'web_url',
                    'url' =>  URL_BASE,
                    'title' => __('Xem tại website')
                ],
            ]
        ];

        $this->MessengerApi->sendButtonMessage($options);
    }

    /**
     * Xem chi tiết sản phẩm
     */
    public function viewp($option = [])
    {
        $p = $this->ExtProduct->getDetailByCode($option['code'] );

        if(empty($p)){
            $this->_noAnswer($option, __('Thật xin lỗi! Tôi không tìm thấy thông tin bạn yêu cầu. Bạn có thể thử lại với một yêu cầu khác?'));
            return;
        }

        // gửi 1 tin hình ảnh và 1 tin nội dung
        $option['image'] = $p['Product']['image'];
        $this->MessengerApi->sendImageMessage($option);

        // gui thong tin
        $text = $p['Product']['code'] . "\n\n" .  substr($p['Product']['desc'], 0, 200) . "\n\n Giá: " . numberFormat($p['Product']['prices']['regular_show'], 'đ');

        //
        $option['message'] = $text;
        $this->MessengerApi->sendTextMessage($option);

        $p['Product']['link_detail'] =  URL_BASE . '/products/detail/' . _SEO($p['Product']['name'] . SPLIT_URL . (string)$p['Product']['_id']);

        // hiển thị button đặt mua
        $option['payload'] = [
            'template_type' => 'button',
            'text' => __("Bạn muốn?\n\n"),
            'buttons' => [
                [
                    'type' => 'web_url',
                    'url' => $p['Product']['link_detail'],
                    'title' => __('Đặt hàng ngay')
                ],
                [
                    'type' => 'web_url',
                    'url' => $p['Product']['link_detail'],
                    'title' => __('Xem tại website')
                ],
            ]
        ];

        $this->MessengerApi->sendButtonMessage($option);
    }

    /**
     * Lấy tình trạng đơn hàng
     */
    public function state($option = [])
    {
        // $option['code'] = 'HDF00137';
        $this->MessengerApi->sendTypingOn($options);
        $p = $this->ExtOffice->getBillstate($option['code'] );

        if(empty($p)){
            $this->_noAnswer($option, __('Thật xin lỗi! Tôi không tìm thấy đơn hàng của bạn? Xin bạn vui lòng kiểm tra lại mã đơn hàng nhé! (ví dụ: tình trạng đơn hàng FHD0001)'));
            return;
        }

        $option['message'] = __('Đơn hàng của bạn đang ở tình trạng: => %s', $p);

        $this->MessengerApi->sendTextMessage($option);
    }

    /**
     * Hỏi người dùng
     */
    public function narrowdown($options)
    {
        // hiển thị button đặt mua
        $options['quick_replies'] = [
            'text' => __('Bạn muốn mua sản phẩm giá khoản bao nhiêu?'),
            'metadata' => 'HOI_GIA',
            'quick_replies' => [
                [
                    'content_type' => 'text',
                    'title' => __('Dưới 500K'),
                    'payload' => __('PRICE_LESS_THAN_500K')
                ],
                [
                    'content_type' => 'text',
                    'title' => __('500K-1 triệu'),
                    'payload' => __('PRICE_FROM_500K_TO_1M')
                ],
                [
                    'content_type' => 'text',
                    'title' => __('1-2 triệu'),
                    'payload' => __('PRICE_FROM_1M_TO_2M')
                ],
                [
                    'content_type' => 'text',
                    'title' => __('> 2 triệu'),
                    'payload' => __('PRICE_FROM_2M')
                ]
            ]
        ];

        $this->MessengerApi->sendQuickReply($options);
    }

    public function _dosolve($options)
    {
        switch ($options['method']) {
            case 'find':
                $this->find($options);
                break;

            case 'viewp':
                $this->viewp($options);
                break;

            case 'buy':
                $this->buy($options);
                break;

            case 'state':
                $this->state($options);
                break;

            case 'narrowdown':
                $this->narrowdown($options);
                break;

            case 'hi':
                $options['message'] = __('Xin chào bạn, tôi là AutoBot của Thiết Kế Hoa, hân hạnh được hỗ trợ bạn. Tôi có thể giúp gì cho bạn? (sản phẩm, đơn hàng, thông tin liên hệ, đặt hàng...)');
                $this->MessengerApi->sendTextMessage($options);
                break;

            case 'test':
                $_SESSION['count'] = @(int)$_SESSION['count'] + 1;
                $options['message'] = $_SESSION['count'];
                $this->MessengerApi->sendTextMessage($options);

                break;
            default:

                $options['message'] = __('Rất xin lỗi, tôi không tìm được nội dung bạn yêu cầu. Xin cho tôi một vài thông tin khác để được hỗ trợ bạn tốt hơn?');
                $this->MessengerApi->sendTextMessage($options);
                break;
        }
    }

    public function _getPostback($text)
    {
        $item = [];

        switch ($text) {
            case 'PRICE_LESS_THAN_500K':
                $item['method'] = 'find';
                $item['price'] = ['from' => 0,'to' => 500000];
                break;
            case 'PRICE_FROM_500K_TO_1M':
                $item['method'] = 'find';
                $item['price'] = ['from' => 500000,'to' => 1000000];
                break;
            case 'PRICE_FROM_1M_TO_2M':
                $item['method'] = 'find';
                $item['price'] = ['from' => 1000000,'to' => 2000000];
                break;
            case 'PRICE_FROM_2M':
                $item['method'] = 'find';
                $item['price'] = ['from' => 2000000,'to' => 200000000];
                break;
            case 'VIEW_MORE_PRODUCT':
                $item['method'] = 'find';
                $item['view_more'] = true;

                break;
            default:
                $arr = explode("_", $text);
                switch ($arr[0]) {
                    case 'pid':
                        $item = [
                            'method' => 'viewp',
                            'code' => $arr[1],
                        ];
                        break;

                    default:
                        # code...
                        break;
                }
                break;
        }

        return $item;
    }

    public function _getRequest($text)
    {
        $items = [
            'find' => [
                    'words' => ['gia', 'tu', 'dat', 'hang', 'den', 'toi', 'xem', 'mau', 'san', 'pham', 'danh', 'sach'],
                    'score' => 0,
                    'price' => [
                        'from' => -1,
                        'to' => -1
                    ]
            ],
            'state' => [
                'words' => ['don', 'hang', 'ma', 'tinh', 'trang', 'xem'],
                'score' => 0,
                'attr' => [
                    'code' => null
                ]
            ],
            'narrowdown' => [
                'words' => ['dat', 'hang', 'toi', 'muon', 'pham', 'tim', 'mua', 'hoa'],
                'score' => 0,
                'attr' => [
                    'code' => null
                ]
            ],
            'viewp' => [
                'words' => ['xem', 'chi', 'tiet', 'san', 'pham'],
                'score' => 0,
                'attr' => [
                    'code' => null
                ]
            ],
            'hi' => [
                'words' => ['hi', 'giup', 'chao', 'xin', 'ban', 'oi', 'ad'],
                'score' => 0,
            ],
            'test' => [
                'words' => ['test'],
                'score' => 0,
            ]
        ];

        $text = $this->Common->_SEOTV(strtolower($text));
        $words = explode(" ", $text);

        foreach ($items as $key => $value) {
            foreach ($value['words'] as $_k => $_v) {
                foreach ($words as $k => $v) {
                    if($_v == $v){
                        $items[$key]['score'] ++;
                    }
                }
            }
        }

        // tìm giá trị điểm cao nhất
        $item = ['score' => 0];

        foreach ($items as $key => $value) {
            if($value['score'] > $item['score']){
                $item = $value;
                $item['method'] = $key;
            }
        }

        if(empty($item['method'])){
            $item['method'] = 'test';
        }

        switch ($item['method']) {
            case 'find':
                $numbers = [];
                foreach ($words as $w) {
                    if(is_numeric($w) && $w > 1000){
                        $numbers[] = $w;
                    }
                }

                if(!empty($numbers)){
                    sort($numbers);
                    $item['price']['from'] = array_shift($numbers);
                    if(count($numbers) > 0){
                        $item['price']['to'] = array_pop($numbers);
                    }
                } else {
                    $item['price']['from']  = 0;
                    $item['price']['to'] = 90000000;
                }
                $item['offset'] = 0;
                $item['limit'] = 9;



                break;

            case 'viewp':

                foreach ($words as $w) {
                    if(strlen($w) == 6 && substr($w, 0, 1) == 'p' && is_numeric(substr($w, 1))){
                        $item['code'] = strtoupper($w);
                        break;
                    }
                }

                break;

            case 'narrowdown':

                break;

            case 'state':
                foreach ($words as $w) {
                    if(strlen($w) == 8 && is_numeric(substr($w, 3))){
                        $item['code'] = strtoupper($w);
                        break;
                    }
                }

                break;
            case 'hi':
                break;

            default:
                $item = [
                    'method' => 'wrong'
                ];
                break;
        }
        // $ret = [
        //     'method' => 'find',
        //     'price' => ['from' => '500000', 'to' => '1500000'],
        // ];

        return $item;

    }

    public function callback()
    {
        $this->layout = 'ajax';

        //echo $_GET['hub_challenge']; exit;
        Configure::write('debug', 0);
        file_put_contents(APP . "tmp/fb.txt", file_get_contents('php://input'));
        // file_put_contents(APP . "tmp/fb.txt", 'ngo anh tuan');
        $str = file_get_contents( APP . "tmp/fb.txt");

        // ghi log
        $log = APP . "tmp/fb_log.txt";
        $f = fopen($log, 'a');
        fwrite($f, "\n=====================\n");
        fwrite($f, $str);
        fclose($f);

        // xu li
        $fb = json_decode($str);

        $type = '';
        if(property_exists($fb->entry[0]->messaging[0], 'message')){
            if(property_exists($fb->entry[0]->messaging[0]->message, 'quick_reply')){
                $text = $fb->entry[0]->messaging[0]->message->quick_reply->payload;
                $_opt = $this->_getPostback($text);
            } else {
                $text = $fb->entry[0]->messaging[0]->message->text;
                $_opt = $this->_getRequest($text);
            }
        } else if(property_exists($fb->entry[0]->messaging[0], 'postback')){
            $text = $fb->entry[0]->messaging[0]->postback->payload;
            $_opt = $this->_getPostback($text);
        }

        $sender = $fb->entry[0]->messaging[0]->sender->id;

        $_opt['sender'] = $sender;
        $this->_dosolve($_opt);
        exit;
    }

    /**
     * Đăng ký doanh nghiệp
     */
    public function register($id = null){
        if(!empty($this->request->data)){
            $ret = $this->ExtOffice->save($this->request->data['Office']);

            if(isset($ret['ok'])){
                $this->Session->setFlash($ret['msg'], 'messages/message_success');
                $this->redirect('/offices/api/' . $ret['id']);
            } else {
                $this->Session->setFlash($ret['msg'], 'messages/message_error');
            }
        } else {
            if(!empty($id)){

            }
        }
    }

    /**
     * Đăng ký doanh nghiệp
     */
    public function api($id = null){
        if(!empty($this->request->data)){

            $this->Common->get_common_info($this->request->data['Office']);

            $ret = $this->ExtOffice->save($this->request->data['Office']);

            if(isset($ret['ok'])){
                $this->Session->setFlash($ret['msg'], 'messages/message_success');
            } else {
                $this->Session->setFlash($ret['msg'], 'messages/message_error');
            }

            $office = $this->ExtOffice->getDetail($id);

            $office = am($office, $this->request->data['Office']);
            $this->set('office', $office);
        } else {
            if(!empty($id)){
                $office = $this->ExtOffice->getDetail($id);
                $this->set('office', $office);
            }
        }
    }


    public function manager_index($status = 4)
    {
        $fields = array(
            '1' => 'name',
            '2' => 'website',
            '3' => 'fanpage',
            '4' => 'address',
            '5' => 'email',
            '6' => 'phone',
            '7' => 'create_info.status',
            '8' => 'create_info.create_user',
            '9' => 'create_info.created',
        );

        $dataForm = [];
        $items = [];

        //submit form search
        if(!empty($this->request->data)){
            $dataForm = &$this->request->data['ShortUrl'];
        }

        $sort = 6;
        $dir = 'desc';

        if(!empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }

        if(!empty($_GET['dir'])){
            $dir = $_GET['dir'];
        }

        $order = [$fields[$sort] => ["order" => $dir]];
        $limit = 30;
        $page = isset($_GET['page']) ? (int)$_GET['page']: 1;

        list($items, $total) = $this->ExtOffice->getOffices($dataForm, $limit * ($page - 1), $limit, $order);


        $this->set('status', $this->_getCodeList('list', 'COM', 'STS'));
        $this->set('items', $items);
        $this->set('sort', $sort);
        $this->set('dir', $dir);
        $this->set('total', $total);

        $this->set('title_for_layout', __('Quản lý doanh nghiệp'));
        $this->set('main_page_title', __('Quản lý doanh nghiệp'));
        $this->set('main_page_description', __('Trang này quản lý tất cả doanh nghiệp đã đăng ký'));
        $this->_addCrumbs(array('text' => __('Quản lý doanh nghiệp')));

        $this->layout = 'office_admin';
    }
}

