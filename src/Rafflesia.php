<?php
/**
 * Rafflesia - Rafflesia.php
 * Date: 23/09/2016
 */

declare(strict_types = 1);

namespace Rafflesia;


/**
 * Class RafflesiaException
 *
 * @package Rafflesia
 */
class RafflesiaException extends \Exception {

    const Socket_Create    = 0;
    const Socket_Bind      = 1;
    const Socket_Set_Block = 2;
    const Socket_Listen    = 3;
    const Socket_Accept    = 4;

    protected static $constants;

    public static function getName(int $value) {
        if (!is_array(static::$constants))
            static::$constants = (new \ReflectionClass(__CLASS__))->getConstants();
    }

}


/**
 * Class Rafflesia
 *
 * @package Rafflesia
 */
class Rafflesia {

    private $socket;


    /**
     * Rafflesia constructor.
     *
     * @param string $bindSocket
     * @throws RafflesiaException
     */
    public function __construct(string $bindSocket = "../server.sock") {

        $this->socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if ($this->socket === false)
            throw new RafflesiaException(socket_last_error(), RafflesiaException::Socket_Create);

        if (!socket_bind($this->socket, $bindSocket))
            throw new RafflesiaException(socket_last_error(), RafflesiaException::Socket_Bind);

        if (!socket_set_block($this->socket))
            throw new RafflesiaException(socket_last_error(), RafflesiaException::Socket_Set_Block);

    }

    public function listen() {
        if (!socket_listen($this->socket))
            throw new RafflesiaException(socket_last_error(), RafflesiaException::Socket_Listen);

        while (true) {
            try {
                $connection = socket_accept($this->socket);
                if (!$connection)
                    throw new RafflesiaException(socket_last_error(), RafflesiaException::Socket_Accept);

            }

            catch (RafflesiaException $e) {
                echo $e->getMessage();
            }
        }
    }
}