# php-websocketd
Turn any program that uses STDIN/STDOUT into a WebSocket server and display in the browser.

Server based on [workerman](https://github.com/walkor/Workerman).

# Live demo
[Live demo](http://120.27.122.71:7779/)

# Start and stop
**start**  
Run with command ```php start.php start -d```   
![star](https://github.com/walkor/php-websocketd/blob/master/Applications/php-websocketd/Web/imgs/start.png?raw=true)

Visit ```http://ip:7779``` in your browser.

**stop**  
php start.php stop

# Change command
Open ```Applications/php-websocketd/start.php ``` and change CMD constant (For example```tail -f /var/log/nginx/www.access.log```).
![change command](https://github.com/walkor/php-websocketd/blob/master/Applications/php-websocketd/Web/imgs/cmd.png?raw=true)

# Screenshot
**htop**
![htop](https://github.com/walkor/php-websocketd/blob/master/Applications/php-websocketd/Web/imgs/htop.jpg?raw=true)

**tail nginx access.log**
![tail](https://github.com/walkor/php-websocketd/blob/master/Applications/php-websocketd/Web/imgs/tail.png?raw=true)

# Related links
[https://github.com/joewalnes/websocketd](https://github.com/joewalnes/websocketd)    
[https://github.com/chjj/term.js](https://github.com/chjj/term.js)    
[https://github.com/walkor/Workerman](https://github.com/walkor/Workerman)    

