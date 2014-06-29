<?php
abstract class CmdHandler {
    abstract function handle($request);
    abstract function proc($request);
}