<?php
/**
 * Rafflesia - RafflesiaException.php
 * Date: 9/23/2016
 */

declare(strict_types = 1);

namespace Rafflesia;

/**
 * Class RafflesiaException
 *
 * @package Rafflesia
 */
class RafflesiaException extends \Exception {

    const Unknown_Error       = 0;
    const Socket_Create       = 1;
    const Socket_Bind         = 2;
    const Socket_Set_Block    = 3;
    const Socket_Set_Nonblock = 4;
    const Socket_Listen       = 5;
    const Socket_Accept       = 6;
    const Socket_Connect      = 7;
    const Socket_SendTo      = 8;
    const Socket_RecvFrom    = 9;

    const Sockets_Extension = 101;

    protected static $constants;


    /**
     * RafflesiaException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, int $code = 0, \Throwable $previous = null) {
        parent::__construct(static::getName($code).": ".$message, $code, $previous);
    }


    /**
     * @param int $value
     * @return string
     * @throws RafflesiaException
     */
    public static function getName(int $value): string {
        if (!is_array(static::$constants))
            static::$constants = (new \ReflectionClass(__CLASS__))->getConstants();

        if (($key = array_search($value, static::$constants)) === false)
            throw new RafflesiaException("Invalid RafflesiaException constant value '$value'.");

        return str_replace("_", " ", $key);
    }

}