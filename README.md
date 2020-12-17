# CiFireCMS - Gratis Rasa Premium
CiFireCMS is a open source PHP web framework, using the CodeIgniter3 framework with an interesting concept and easy to use by anyone.


## Minimum System Requirements
```
+--------------+----------------+
|  System      |  Version       |
+--------------+----------------+
|  Web server  |  Apache 2.4.x  |
|  PHP         |  7.3.x, 5.6.x  |
|  MySQL       |  5.7.x         |
|  MariaDB     |  10.3.x        |
+--------------+----------------+
```


## PHP Extension
```
+--------------+----------+
|  Extension   |  Config  |
+--------------+----------+
|  pdo_mysql   |  ON      |
|  pdo_sqlite  |  ON      |
|  pdo_sqlite  |  ON      |
|  json        |  ON      |
|  fileinfo    |  ON      |
|  intl        |  ON      |
+--------------+----------+
```


## Installation
- Download the CiFireCMS source code from github or from the official website.
- Extract the cifirecms.zip file in your web directory. Make sure the .htaccess file is copied correctly.
- Create a new database for installation.
- Launch your browser and enter the url of your website.
- Follow the installation steps.
- After completing the installation process, please delete other files from root directory, except index.php and .htaccess files.
- CiFireCMS is ready to use.


## Permission
Change permission for folder below to ``775``.
```
cifirecms
├── l-content
│   ├── temp    --> 775
│   ├── thumbs  --> 775
└── └── uploads --> 775
```

## .htaccess
Standard **.htaccess** configuration.
```
RewriteEngine On
RewriteCond $1 !^(index\.php|resources|robots\.txt)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L,QSA]
```

To set your website accessible by **http** or **https** please change the configuration file **.Htaccess** add the following code below the ``RewriteEngine On`` code line.


#### Redirect HTTP to HTTPS

```
# non-www to www.
RewriteCond %{HTTPS} off [OR]
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ https://www.%1%{REQUEST_URI} [L,NE,R=301]

# www to non-www.
RewriteCond %{HTTPS} off [OR]
RewriteCond %{HTTP_HOST} ^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ https://%1%{REQUEST_URI} [L,NE,R=301]
```

#### Redirect HTTPS to HTTP
```
# non-www to www.
RewriteCond %{HTTPS} on [OR]
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ http://www.%1%{REQUEST_URI} [L,NE,R=301]

# www to non-www.
RewriteCond %{HTTPS} on [OR]
RewriteCond %{HTTP_HOST} ^www\. [NC]
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.+)$ [NC]
RewriteRule ^ http://%1%{REQUEST_URI} [L,NE,R=301]
```

### Environment
If the web is ready online, please change the code in ``index.php`` Search for the following line of code:
```
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
```
Change to :
```
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
```


## Backend

* Backend url ``http://your-web-domain/l-admin``
* For first login, please enter the login data same as the installation process.


## Official Links
* GitHub         : https://github.com/CiFireCMS
* Facebook       : https://web.facebook.com/cifirecms
* Facebook group : https://web.facebook.com/groups/cifirecms/


## License
CiFireCMS is licensed under the MIT License.