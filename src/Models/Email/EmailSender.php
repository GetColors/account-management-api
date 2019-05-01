<?php

namespace Atenas\Models\Email;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class EmailSender
{
    public function sendEmail(string $addressee,string  $subject,string  $message):void
    {
        $data = json_encode([
            "addressee" => $addressee,
            "subject" => $subject,
            "message" => $message
        ]);

        $url = parse_url(RABBITMQ_CONFIG['url']);

        $vhost = substr($url['path'], 1);

        if($url['scheme'] === "amqps") {
            $ssl_opts = array(
                'capath' => '/etc/ssl/certs'
            );
            $conn = new AMQPSSLConnection($url['host'], 5671, $url['user'], $url['pass'], $vhost, $ssl_opts);
        } else {
            $conn = new AMQPStreamConnection($url['host'], 5672, $url['user'], $url['pass'], $vhost);
        }

        $ch = $conn->channel();
        $exchange = 'amq.direct';

        $queue = 'send_email';

        $ch->queue_declare($queue, false, true, false, false);
        $ch->exchange_declare($exchange, 'direct', true, true, false);
        $ch->queue_bind($queue, $exchange);

        $msg = new AMQPMessage(json_encode($data), array('content_type' => 'application/json', 'delivery_mode' => 2));
        $ch->basic_publish($msg, $exchange);
    }
}