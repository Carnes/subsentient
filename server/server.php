<?php
function __autoload($class_name) {
    set_include_path("includes");
    include $class_name . '.php';
};

$socketManager = SocketManager::getInstance();
$requestHandler = RequestHandler::getInstance();

declare(ticks=1);
function closeAndExit()
{
    Logger::Log("Server Shutdown");
    SocketManager::getInstance()->close();
    exit(0);
}
pcntl_signal(SIGHUP, "closeAndExit");
pcntl_signal(SIGINT, "closeAndExit");
pcntl_signal(SIGTERM, "closeAndExit");

Logger::Log("Server Started");
while (true) {
    usleep(Config::MicroSecondsPerTick);
    $requestHandler->tick();
    $requests = $socketManager->getClientRequests();
    foreach($requests as $request)
        $requestHandler->dispatchRequest($request);
}