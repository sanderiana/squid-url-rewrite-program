## Squid Redirector

https://wiki.squid-cache.org/Features/Redirectors  
This script is for `url_rewrite_program` directive.   
Requirements: `squid-3.4+` `php-7+`


### Installation:

- copy php files to some folder.
- add execution permission to `redirector.php`.
- edit `url_rewrite_program` directive. `squid.conf`
```
url_rewrite_program /path/to/redirector.php
```
- reload squid config

```
squid -k reconfigure
```

### Configuration:
`config.php`  
```
# timeout period on a script
'time_out' => 86400,

'redirect' => [
  # domain : from url
  # port : port
  # redirect : to url  
   
  # any port 
  ['domain' => '127.0.1.1', 'redirect' => '127.0.0.1'],

  # some port
  ['domain' => '127.0.0.1', 'port' => '80', 'redirect' => '10.0.0.1'],
  ['domain' => '127.0.0.1', 'port' => '81', 'redirect' => '10.0.0.2'],
  ['domain' => '127.0.0.1', 'redirect' => '10.0.0.3'],
]

# see Test section
```

### Test:
```
$ ./redirector.php
127.0.1.1                       # input
OK status=302 url="127.0.0.1"   # output
127.0.0.1:80                    
OK status=302 url="10.0.0.1"        
127.0.0.1:81                    
OK status=302 url="10.0.0.2"        
127.0.0.1                       
OK status=302 url="10.0.0.3"    
127.0.0.2                              
127.0.0.2                       
```


### License:
<div><a href="http://opensource.org/licenses/mit-license.php">MIT</a></div>
My small candy project.