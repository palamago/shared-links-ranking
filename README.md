## Openshift Laravel Quickstart
This is an Openshift Quickstart for Laravel 4.

### Installation - Using PHP and MySQL Cartridges
You will need to install the PHP5.4 and MySQL5.5 cartridges before installing this quickstart for Laravel.

After installing PHP and MySQL cartridges, add the quickstart github repository and pull afterwhich you can push to your Openshift repository

```shell
git remote add upstream -m master git@github.com:muffycompo/openshift-laravel4-quickstart-app.git
git pull -s recursive -X theirs upstream master
```
#### NOTE: See [After Openshift application creation](https://github.com/muffycompo/openshift-laravel4-quickstart-app#after-openshift-application-creation)

### Installation - Using Openshift Origin Instant App
This Quickstart is also configured to utilize your Openshift Origin installation. To provide Laravel 4 as an instant app for Openshift Origin, you will need to modify `/etc/openshift/quickstarts.json` and add the following to the end of the file

```json
{
	"quickstart": {
		"id": "10",
		"name": "Laravel 4",
		"website": "http://www.laravel.com",
		"initial_git_url": "git://github.com/muffycompo/openshift-laravel4-quickstart-app.git",
		"cartridges": ["php-5.4", "mysql-5.5"],
		"summary": "Laravel is a PHP web application framework with expressive, elegant syntax.",
		"tags": ["php", "instant_app", "framework", "mysql"],
		"admin_tags": []
	}
}
```

### After Openshift application creation
You should remove the comment # in `.openshift/actions_hooks/build` by changing
```shell
#( unset GIT_DIR ; cd $OPENSHIFT_REPO_DIR ; php $OPENSHIFT_DATA_DIR/composer.phar -q --no-ansi install )
```
to become
```shell
( unset GIT_DIR ; cd $OPENSHIFT_REPO_DIR ; php $OPENSHIFT_DATA_DIR/composer.phar -q --no-ansi install )
```
Now using git
```shell
git add .
git commit -a -m 'Install Laravel 4 Composer Dependencies'
git push
```
### Post Installation
After the installation of composer dependencies, Laravel 4 is ready to go. We have setup your database credentials `app/config/database.php` to use Openshift environment variables (OPENSHIFT*).