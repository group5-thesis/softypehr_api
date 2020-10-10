<?php
namespace App\Http\Middleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class LogAfterRequest {
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }
    public function terminate($request, $response)
    {
        $method = $request->method();
        $url = $request->fullUrl();
        $ip = $request->ip();
        $payload = json_encode($request->all());
        $logObject  = [
            "method"=>$method,
            "url"=>$url,
            "ip"=>$ip,
            "payload"=>$payload
        ];

        $tz = new \DateTimeZone(config('app.timezone'));
        $dtObj = Carbon::now($tz);
        $name ='request.'.$dtObj->format('Y-m-d') . '.log';
        $path  = storage_path('logs/' . $name);
        $logger = new Logger('log');
        $logger->pushHandler(new StreamHandler($path, Logger::DEBUG));
        $logger->info(stripslashes(json_encode($logObject,JSON_UNESCAPED_SLASHES)));

    }
}