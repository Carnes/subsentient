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


### Using gentoo's rc
file: /etc/init.d/subsentient
```bash
#!/sbin/openrc-run

depend() {
        need net
}

start() {
        if [ -e /var/run/subsentient.pid ]; then
                rm -rf /var/run/subsentient.pid || return 1
        fi
        
        cd /var/www/localhost/htdocs/subsentient/server
        PID=`php -q server.php > /dev/null 2>&1 & echo $!`
        echo $PID > /var/run/subsentient.pid
}

stop() {        
        if [ -f /var/run/subsentient.pid ]; then
                PID=`cat /var/run/subsentient.pid`
                kill -HUP $PID
                rm -f /var/run/subsentient.pid
        fi
}
```
