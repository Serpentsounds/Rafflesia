<?php
/**
 * Rafflesia - RafflesiaMessage.php
 * Date: 9/23/2016
 */

namespace Rafflesia;

/**
 * Class RafflesiaMessage
 *
 * @package Rafflesia
 */
class RafflesiaMessage {

    protected $send;
    protected $recv;

    protected $bytesSent;
    protected $bytesReceived;

    protected $server;
    protected $port;


    /**
     * RafflesiaMessage constructor.
     *
     * @param string $send
     * @param int    $bytesSent
     * @param string $recv
     * @param int    $bytesReceived
     * @param string $server
     * @param int    $port
     */
    public function __construct(string $send, int $bytesSent, string $recv, int $bytesReceived, string $server, int $port) {
        $this->send = $send;
        $this->recv = $recv;

        $this->bytesSent     = $bytesSent;
        $this->bytesReceived = $bytesReceived;

        $this->server = $server;
        $this->port   = $port;
    }


    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->send;
    }


    /**
     * @return string
     */
    public function getResponse(): string {
        return $this->recv;
    }


    /**
     * @return int
     */
    public function getBytesSent(): int {
        return $this->bytesSent;
    }


    /**
     * @return int
     */
    public function getBytesReceived(): int {
        return $this->bytesReceived;
    }


    /**
     * @return string
     */
    public function getServer(): string {
        return $this->server;
    }


    /**
     * @return int
     */
    public function getPort(): int {
        return $this->port;
    }
}