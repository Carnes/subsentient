subsentient
===========

A javascript development game.
Mostly me exploring the canvas element, websockets, and server-driven business logic.  Example should be running here: http://tageverything.org/subsentient/


Requirements:
PHP CLI, webserver (of any kind) that can run PHP
Additionally, PHP CLI must be compiled with the pcntl flag.

How to Install:
Copy files into web accessable directory.
OPTIONAL: Move server directory outside of web accessable directory.

How to configure:
There are two configs, one for client and the other for server.  Be sure to edit both the base Config.php and server Config.php

How to manually run server:

1. cd into server directory. Ex: cd server
2. run server in headerless php cli.  Ex: php -q server.php

To use the server as a service an example init.d script is included in server directory.


