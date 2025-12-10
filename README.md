# About the Project
Launched in 2025, this project delivers a security-focused application framework that forms a robust foundation for modern web applications.

- Built around security by design
- Framework that enforces secure coding practices
- Developers are guided to write secure, robust code
- Provides a flexible base for various web applications
- Fully developed in PHP

# What the Project Provides
A lightweight yet powerful PHP framework with built-in security features for building secure and scalable web applications.

- Core framework for structured, maintainable web applications
- Controller-based routing with regular expression support for clean, RESTful URLs without GET parameters
- Enforced nonces and CSRF tokens
- Built-in user login and registration
- Built-in usage of HTTP security headers
- Native support for Content Security Policy (CSP) headers
- MDN Documentation on CSP
- Two-Factor Authentication (2FA) with TOTP
- Modular architecture for easy extension via modules
- Small footprint; optional dependencies only when needed (e.g., ORM, PDF library)
- ORM powered by Illuminate/Database (as known from the Laravel framework)
- 100% PHP with modern PHP 8
- Strict typing for improved type safety

# Get it up and running
## Requirements 
- LAMP stack *1
- PHP >= 8.4
- MySQL/MariaDB
- MySQLi extension (https://www.php.net/manual/en/book.mysqli.php) 
- GD extension (https://www.php.net/manual/en/book.image.php)

## Web-server
*1 I do prefer using NGINX with PHP-FPM instead of Apache. But Apache will also work just fine. 

### Web-server configuration
Make sure to meet the following requirements:
- [ ] Deny access to hidden files (filename prefixed by a dot)
- [ ] Deny execution of any code within /public folder 
- [ ] Enable routing by passing url-arguments to index.php
- [ ] (optional) Define custom 504 error page 

### NGINX example config for development environment

```
server {
	listen 80 default_server;
	listen [::]:80 default_server;

	root /mnt/app-framework/src;

	index index.php index.html;

	server_name dev.local;

	error_page 504 /views/504.html;

	location ~ /\. {
		deny all;
	}

	location /public {
		location ~ \.php$ { return 403; }
	}

	location / {
		try_files $uri $uri/ /index.php?$args;
	}

	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
		fastcgi_buffers 16 32k;
		fastcgi_buffer_size 64k;
		fastcgi_busy_buffers_size 64k;
	}

}
```
