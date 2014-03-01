## Openshift Laravel Quickstart
This is an Openshift Quickstart for Laravel 4.
### Cartridge Requirement
You need to install the PHP5.4 and MySQL5.5 cartridge before installing this quickstart.

####NOTE
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
