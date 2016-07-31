<?php


App::uses( 'Component','Controller' );

/**
 * @author PhamLuc
 * @access Public
 * @package Controller/Componet
 * @author Ngo Anh Tuan <tuanngo.technical@gmail.com>
 *
 */
class MessengerApiComponent extends Component {


    var $controller;
    var $split = "---";
    var $Common = null;
    var $db = null;
    var $sleep = false;

    /**
     * Start up
     * @param string $controller
     * @access Public
     * @author Ngo Anh Tuan <tuanngo.technical@gmail.com>
     * @return void
     */
    function startup(&$controller) {
        $this->controller = $controller;
    }

    /**
     * Gui hinh anh
     */
    public function sendImageMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'image',
                'payload' => [
                    'url' => $option['image'],
                ]
            ]
        ];
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    /**
     * Gui hinh anh
     */
    public function sendTypingOn($option)
    {
        $message = [
            'recipient' => ['id' => $option['sender']],
            "sender_action" => "typing_on"
        ];
        $this->sendMessage($message);
    }

    /**
     * Gui hinh anh
     */
    public function sendGifMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'image',
                'payload' => [
                    'url' => $option['image'],
                ]
            ]
        ];
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendAudioMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'audio',
                'payload' => [
                    'url' => $option['audio'],
                ]
            ]
        ];
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendVideoMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'video',
                'payload' => [
                    'url' => $option['video'],
                ]
            ]
        ];
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendFileMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'file',
                'payload' => [
                    'url' => $option['file'],
                ]
            ]
        ];
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendButtonMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'template',
                'payload' => $option['payload']
            ]
        ];

        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendGenericMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'template',
                'payload' => $option['payload']
            ]
        ];

        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendReceiptMessage($option)
    {
        $params = [
            "attachment" => [
                'type' => 'template',
                'payload' => $option['payload']
            ]
        ];

        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $params
        ];
        $this->sendMessage($message);
    }

    public function sendQuickReply($option)
    {
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => $option['quick_replies']
        ];
        $this->sendMessage($message);
    }

    public function sendTextMessage($option){
        $message = [
            'recipient' => ['id' => $option['sender']],
            'message' => ['text' => $option['message']]
        ];
        $this->sendMessage($message);
    }

    /**
     * Gui message
     */
    public function sendMessage(&$data)
    {
        $token = 'EAANwDoZAjs9wBAAiyhF32QgDEkqqA9bHo0tQj7mcTgB5bQjPSt5leKzvBNghDNq5MBmjUh6NRJPoRDLylupYiOZBUMUpro6HKYhaqR7MIhUZAeqGWiPfc0G4ujyC6XIuBqC4UcGZARctog100NL8JXhe1b2rC6ltZBRCcZCP8MwwZDZD';

        $options = [
                 'http' => [
                        'method' => 'POST',
                        'content' => json_encode($data),
                        'header' => 'Content-Type: application/json'
                ]

        ];

        $context = stream_context_create($options);

        file_get_contents('https://graph.facebook.com/v2.6/me/messages?access_token='. $token, false, $context);

        // exit;
    }
}