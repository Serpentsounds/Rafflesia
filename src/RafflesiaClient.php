<?php
/**
 * Rafflesia - RafflesiaClient.php
 * Date: 9/23/2016
 */

namespace Rafflesia;

/**
 * Class RafflesiaClient
 *
 * @package Rafflesia
 */
class RafflesiaClient {

    const Default_Server = "127.0.0.1";
    const Default_Port = 44045;

    const Default_Timeout = 1000;
    const Default_Interval = 5;

    protected $socket;
    protected $clientSocketName;
    protected $server;
    protected $port;


    /**
     * RafflesiaClient constructor.
     *
     * @param string $server
     * @param int $port
     * @throws RafflesiaException
     */
    public function __construct(string $server = self::Default_Server, int $port = self::Default_Port) {

        try {
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($this->socket === false)
                throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Create);

            if (!socket_set_nonblock($this->socket))
                throw new RafflesiaException(socket_strerror(socket_last_error()), RafflesiaException::Socket_Set_Nonblock);

            $this->server = $server;
            $this->port = $port;
        }

        catch (RafflesiaException $e) {
            echo $e->getMessage();
            throw $e;
        }

    }


    /**
     * @param string $data
     * @param int $timeout Time to wait for server before giving up. Raise above default if server is remote.
     * @param int $interval Time between polls. Raise above default if server is remote.
     * @return RafflesiaMessage
     * @throws RafflesiaException
     */
    public function send(string $data, int $timeout = self::Default_Timeout, int $interval = self::Default_Interval): RafflesiaMessage {
        if (!($timeout > 0))
            throw new RafflesiaException("Timeout value of $timeout is not greater than 0.");
        if (!($interval > 0))
            throw new RafflesiaException("Interval value of $interval is not greater than 0.");

        $elapsed = 0;
        $connected = $sent = $received = false;
        while ($elapsed < $timeout) {

            //  Not yet connected
            if (!$connected) {
                //  Connection successful
                if (@socket_connect($this->socket, $this->server, $this->port))
                    $connected = true;

                //  Windows socket error code for socket already connected
               elseif (socket_last_error() == 10056)
                    $connected = true;
            }

            //  Connected, attempt to send query
            if ($connected && !$sent &&
               ($bytesSent = @socket_sendto($this->socket, $data, strlen($data), 0, $this->server, $this->port)) !== false)
                    $sent = true;

            //  Connected and query sent, wait for response
            if ($connected && $sent &&
               ($bytesReceived = @socket_recvfrom($this->socket, $response, 65535, 0, $from, $this->port)) !== false) {
                $received = true;
                break;
            }

            $elapsed += $interval;
            usleep($interval * 1000);
        }

        if (!$connected)
            throw new RafflesiaException("Unable to connect to the Rafflesia server.", RafflesiaException::Socket_Connect);
        if (!$sent)
            throw new RafflesiaException("Unable to send query to the Rafflesia server.", RafflesiaException::Socket_SendTo);
        if (!$received)
            throw new RafflesiaException("Rafflesia server did not respond in the allotted time.", RafflesiaException::Socket_RecvFrom);

        /** @var $bytesSent
         *  @var $response
         *  @var $bytesReceived */
        return new RafflesiaMessage($data, $bytesSent, $response, $bytesReceived, $this->server, $this->port);
    }

}