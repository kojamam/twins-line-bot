<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder;
use Twins;

class ChatBotController extends Controller
{
    public function webhook(Request $request)
    {
        $text = '';
        if(strpos($request->input("events.0.message.text"),'掲示') !== false){
            Twins::auth(env('TWINS_ID'), env('TWINS_PASSWORD'));
            $notices = Twins::getNotices();
            foreach ($notices as $notice) {
                $text .= $notice['title']."\n\n";
            }
            $text .= 'https://twins.tsukuba.ac.jp/campusweb/';
        }else{
            $text = 'Twinsにそんな機能無いよ';
        }

        $bot = $this->__connectLine();
        $textMessageBuilder = new MessageBuilder\TextMessageBuilder($text);
        $response = $bot->pushMessage($request->input("events.0.source.userId"), $textMessageBuilder);

        return ;
    }

    public function showNotices()
    {
        Twins::auth(env('TWINS_ID'), env('TWINS_PASSWORD'));
        $notices = Twins::getNotices();
        return response()->json($notices);
    }

    private function __connectLine()
    {
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient(env('LINE_ACCESS_TOKEN'));
        return new LINEBot($httpClient, ['channelSecret' => env('LINE_SECRET')]);
    }
}
