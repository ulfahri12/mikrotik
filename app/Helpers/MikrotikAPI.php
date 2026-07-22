<?php

namespace App\Helpers;

class MikrotikAPI
{
    private mixed $socket;
    private mixed $host;
    private int $port;

    public function __construct(mixed $host, $port = 8728)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function connect(mixed $user, mixed $pass)
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, 30);
        if (!$this->socket) throw new \Exception("Cannot connect: $errstr");
        $this->login($user, $pass);
        return $this;
    }

    private function login(mixed $user, mixed $pass)
    {
        $this->write(['/login', '=name=' . $user, '=password=' . $pass]);
        $this->read();
    }

    public function write(mixed $words)
    {
        foreach ($words as $word) {
            $len = strlen($word);
            if ($len < 0x80) fwrite($this->socket, chr($len));
            fwrite($this->socket, $word);
        }
        fwrite($this->socket, chr(0));
    }

    public function read()
    {
        $response = [];
        while (true) {
            $len = ord(fread($this->socket, 1));
            if ($len === 0) break;
            $response[] = fread($this->socket, $len);
        }
        return $response;
    }

    public function query(mixed $words)
    {
        $this->write($words);
        return $this->read();
    }

    public function disconnect()
    {
        fclose($this->socket);
    }
}