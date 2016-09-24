<?php
/**
 * Rafflesia - RafflesiaServer.php
 * Date: 23/09/2016
 */

declare(strict_types = 1);

namespace Rafflesia;

/**
 * Class Rafflesia
 *
 * @package Rafflesia
 */
class RafflesiaServer {

    private $socket;
    private $port;

    /**
     * @param string $message
     */
    protected static function console(string $message) {
        echo "$message\n";
    }

    /**
     * Rafflesia constructor.
     *
     * @param string $bindAddress
     * @param int $port
     * @throws RafflesiaException
     */
    public function __construct(string $bindAddress = "0.0.0.0", int $port = 44045) {

        try {
            if (!extension_loaded("sockets"))
                throw new RafflesiaException("Sockets extension is not loaded.", RafflesiaException::Sockets_Extension);

            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($this->socket === false)
                throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Create);

            $this->port = $port;
            if (!socket_bind($this->socket, $bindAddress, $this->port))
                throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Bind);

            if (!socket_set_block($this->socket))
                throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Set_Block);
        }

        catch (RafflesiaException $e) {
            static::console($e->getMessage());
            throw $e;
        }

    }


    /**
     * @throws RafflesiaException
     */
    public function listen() {
        if (!socket_listen($this->socket))
            throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Listen);

        while (true) {
            try {
                $connection = socket_accept($this->socket);
                if (!$connection)
                    throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Accept);

                $bytes = socket_recvfrom($connection, $query, 65535, 0, $from, $this->port);
                static::console("$bytes bytes received from $from: $query");

                socket_getsockname($connection, $address, $port);
                $toSend = "ur a zombie";
                socket_sendto($connection, $toSend, strlen($toSend), 0, $address, $port);

            }

            catch (RafflesiaException $e) {
                echo $e->getMessage(). "\n";
            }
        }
    }
}