<?php
class WebSocket
{
    public static function handShake($receved_header, $client_conn)
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $receved_header);
        foreach($lines as $line)
        {
            $line = chop($line);
            if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
            {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "WebSocket-Origin: http://".Config::$host."\r\n" .
            "WebSocket-Location: ws://".Config::$host.":".Config::$port."/subsentient\r\n".
            "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        socket_write($client_conn,$upgrade,strlen($upgrade));
    }

    public static function sendDataToAll($data)
    {
        $jsonData = self::mask(json_encode($data));
        $jsonDataLen = strlen($jsonData);
        $socketManager = SocketManager::getInstance();
        foreach($socketManager->clientConnections as $clientConnection)
            socket_write($clientConnection,$jsonData,$jsonDataLen);
    }

    public static function sendDataToAllButOne($data, $connection)
    {
        $jsonData = self::mask(json_encode($data));
        $jsonDataLen = strlen($jsonData);
        $socketManager = SocketManager::getInstance();
        foreach($socketManager->clientConnections as $clientConnection)
            if($clientConnection != $connection)
                socket_write($clientConnection,$jsonData,$jsonDataLen);
    }

    public static function sendDataToOne($data, $connection)
    {
        if($connection == null)
            return;
        $jsonData = self::mask(json_encode($data));
        $dataLen = strlen($jsonData);
        socket_write($connection,$jsonData,$dataLen);
    }


    public static function decodeMessage($text) {
        $length = ord($text[1]) & 127;
        if($length == 126) {
            $masks = substr($text, 4, 4);
            $data = substr($text, 8);
        }
        elseif($length == 127) {
            $masks = substr($text, 10, 4);
            $data = substr($text, 14);
        }
        else {
            $masks = substr($text, 2, 4);
            $data = substr($text, 6);
        }
        $text = "";
        for ($i = 0; $i < strlen($data); ++$i) {
            $text .= $data[$i] ^ $masks[$i%4];
        }
        return $text;
    }


    private static function mask($text)
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($text);
        if($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        elseif($length >= 65536)
            $header = pack('CCNN', $b1, 127, $length);
        return $header.$text;
    }
}