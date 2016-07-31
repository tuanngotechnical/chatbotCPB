<?php

/**
 * Initialize , <strong>Render</strong> Captcha
 * @package Controller/Component
 * @author Ngo Anh Tuan <tuanngo.technical@gmail.com>
 * @access public
 */
class ExtOfficeComponent extends Component {

    var $controller;

    /**
     * Initialize
     * @param string $controller
     * @access Public
     * @author Ngo Anh Tuan <tuanngo.technical@gmail.com>
     * @return void
     */
    function initialize(&$controller) {

        $this->controller = $controller;
    }

    /**
     * Lấy tình trạng đơn hàng
     */
    public function getBillState($value='')
    {
        //data from elastic
        $elastic['index'] = ELASTIC_DATABASE;
        $elastic['type'] = 'bills';
        $elastic['body'] = [
            'query' => [
                'filtered' => [
                    'filter' => ['match' => ['code' => $value]]]
                ],
            '_source' => ['state', 'create_info.modified']
        ];

        $restuls = $this->controller->Elasticsearch->search($elastic);

        if(empty($restuls['hits']['hits'])){
            return null;
        }

        $bill = &$restuls['hits']['hits']['0']['_source'];

        $Codetbl = MyApp::uses('Codetbl', 'Model');
        $status = $Codetbl->getList('BIL', 'STA');

        return __('%s, cập nhật lúc %s.', $status[$bill['state']], date('H:i d/m/Y', $bill['create_info']['modified']) );

    }

    /**
     * Tim kiem san pham
     */
    public function searchProduct(&$data, $offset, $limit = 9, $order = null)
    {
        $total = 0;

        //data from elastic
        $elastic['index'] = ELASTIC_DATABASE;
        $elastic['type'] = 'products';
        $elastic['body'] = [
            'query' => [
                'filtered' => [
                    'filter' => ['bool' => ['must_not' => ['term' => ['create_info.status' => 9]]]]
                ]
            ],
        ];

        $order = ['random' => ["order" => 'asc']];


        $elastic['body']['sort'] = $order;


        if(!empty($data)){
            if(!empty($data['code'])){  //mã sản phẩm
                $elastic['body']['query']['filtered']['query']['bool']['should'][] = ['match' => ['code' => $data['code']]];
                $elastic['body']['query']['filtered']['query']['bool']['should'][] = ['match' => ['name' => $data['code']]];
                $this->controller->set('code', $data['code']);
            }

            if(!empty($data['price'])){
                if(!empty($data['price']['from'])){
                    $elastic['body']['query']['filtered']['filter']['bool']['must'][] = ['range' => ['prices.regular' => ['gte' => $data['price']['from']]]];
                }

                if(!empty($data['price']['to'])){
                    $elastic['body']['query']['filtered']['filter']['bool']['must'][] = ['range' => ['prices.regular' => ['lte' => $data['price']['to']]]];
                }
            }
        }



        //limit
        $elastic['from'] = $offset;
        $elastic['size'] = $limit;

        $elastic['body']['_source'] = ['_id', 'favorite', 'cate_ids', 'style_id', 'name', 'desc', 'code', 'images', 'checked', 'create_info', 'prices', 'shop_id', 'attributes', 'design', 'at_home'];


        $results = $this->controller->Elasticsearch->search($elastic);

        $items = [];
        if(!empty($results['hits']['hits'])){
            // nếu đang xem tất cả cửa hàng
            if(empty($shop_id)){
                $Shop = MyApp::uses("Shop", 'Model');
                $shops = $Shop->getList();
            } else {
                $shops[(string)$shop_id] = $this->controller->Auth->user('shop.name');
            }

            $total = $results['hits']['total'];
            foreach ($results['hits']['hits'] as $k => $val) {
                $val['_source']['_id'] = $val['_id'];
                $val = &$val['_source'];

                $val['creator'] = $this->controller->User->getSimpleFields($val['create_info']['create_user']);

                if(empty($val['name'])){
                    $val['name'] = $val['code'];
                } else {
                    $val['name'] .= ' - ' . $val['code'];
                }
                //images
                if(!empty($val['images'])){
                    $val['image'] = array_shift($val['images']);
                    $val['image'] = $this->controller->Common->setImage($val['image'], Photo);
                }

                $val['link_detail'] = URL_BASE . '/products/detail/' . _SEO($val['name'] . SPLIT_URL . (string)$val['_id']);

                //lấy thông tin vật tư
                if(!empty($val['attributes'])){
                    foreach ($val['attributes'] as $_k => $_val) {
                        $val['attributes'][$_k]['attr_name'] = $this->controller->ExtMaterial->getDetailById($_val['attr_id'], ['name']);
                    }

                }

                $val['shop_name'] = @$shops[$val['shop_id']];
                $items[$k] = $val;
            }
        }

        $this->controller->set('total', $total);

        return [$items, $total];
    }

    /**
     * Save Product
     */
    public function save(&$office){
        $controller = &$this->controller;
        $als = 'Office';

        if(empty($office['_id'])){
            $controller->Common->get_common_info($office);
            $controller->Office->init($office);

            // lưu cms
            $data = ['Office' => $office];
            if($controller->Office->save($data)){
                return ['ok' => true, 'id' => $controller->Office->id, 'msg' => __('Lưu thông tin thành công!')];
            } else {
                return ['msg' => __('Lưu thông tin thất bại. Xin kiểm tra thông tin đã nhập!')];
            }
        } else{
            return $this->update($office);
        }
    }

    public function getDetail($id='')
    {
        $elastic['index'] = ELASTIC_DATABASE;
        $elastic['type'] = 'offices';
        unset($elastic['body']);
        $elastic['id'] = (string)$id;

        $info = $this->controller->Elasticsearch->get($elastic);
        if($info){
            $info['_source']['_id'] = $info['_id'];
            $info = &$info['_source'];
        }

        return $info;
    }

    /**
     * update state of bill
     */
    public function update(&$update){
        $controller = &$this->controller;

        $update['_id'] = _getID($update['_id']);
        $update = $controller->_convertDataForUpdate($update);
        $update['create_info.modified'] = new MongoDate();

        if($controller->Office->saveWithKeys($update)){

            return ['ok' => true, 'msg' => __('Cập nhật nội dung thành công!')];
        } else{
            return ['msg' => __('Cập nhật thất bại. Xin thử lại sau!')];
        }
    }

    /**
     * detail by id
     */
    public function getDetailCms($id){
        $data = $this->controller->Cms->find('first', [
            'conditions' => ['_id' => _getID($id), 'create_info.status' => ['$ne' => 9]]
            ]);

        if(!empty($data)){
            $data['Cms']['creator'] = $this->controller->User->getSimpleFields($data['Cms']['create_info']['create_user'], ['_id', 'username', 'info.full_name']);

            return $data['Cms'];
        }
        return false;
    }

    // lay danh sach cms
    public function getOffices(){
        $elastic['index'] = ELASTIC_DATABASE;
        $elastic['type'] = 'offices';
        $elastic['body'] = [
            'sort' => ['create_info.created' => ['order' => 'desc']]
        ];

        $elastic['body']['query']['filtered']['query']['bool']['must'][] = ['term' => ['create_info.status' => 4]];

        // $elastic['body']['_source'] = ['title', 'text'];

        $results = $this->controller->Elasticsearch->search($elastic);

        $items = [];
        if(!empty($results['hits']['hits'])){
            foreach ($results['hits']['hits'] as $k => $v) {
                $v['_source']['_id'] = $v['_id'];

                $items[] = $v['_source'];
            }

        }

        return [$items, $results['hits']['total']];
    }
}
