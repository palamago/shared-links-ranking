Shared Links Ranking
====================

See a Top 10 shared news/posts based on RSS feed from your favorites news portals/blogs.

[Live example instance](http://news-visualization.rhcloud.com/) with Argentina's main online news portals.

Requirements
------------
PHP
MySQL
Curl
Apache (for production)

Installation
------------
* Clone repo
 git clone git@github.com:palamago/shared-links-ranking.git

* Create an empty MySQL database

* Create config file
Copy .env.php.sample to .env.php and complete the database info.

```php
<?php
return array(
		'debug'      => true,
		'db_host'      => 'localhost',
		'db_database'  => '',
		'db_username'  => '',
		'db_password'  => '',
		'enc_key'		=> '',
		'url'		=> 'http://localhost:8000'
	);
?>
```

In cloned folder:

* Run migrate & example data
```shell
php artisan migrate --seed
```

* Run process to collect information from social networks
```shell
php artisan data-checker
```

* Run artisan server for local dev environment
```shell
php artisan serve
```

* Go to http://localhost:8000 , you can see top 10 shared NYT news for Latest and Sports RSS's

* For admin page, go to http://localhost:8000/admin with admin/admin credentials (you must change it).

...

TO-DO