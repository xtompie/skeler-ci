<?php

class Discord
{
    public static function msg($webhook, $msg)
    {
        file_get_contents(
            $webhook,
            false, 
            stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json; charset=utf-8',
                    'content' => json_encode([
                        'content' => $msg,
                    ]),
                ],
            ])
        );
    }    
}
