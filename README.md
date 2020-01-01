# REAM

Real Estate Asset Manager 🧾

[![Repo Size](https://img.shields.io/github/repo-size/Clem9963/REAM)](https://github.com/Clem9963/REAM/pulse)
[![Last Commit](https://img.shields.io/github/last-commit/Clem9963/REAM)](https://github.com/Clem9963/REAM/commits/master)
[![Issues](https://img.shields.io/github/issues/Clem9963/REAM)](https://github.com/Clem9963/REAM/issues)
[![Issues](https://img.shields.io/github/issues-closed/Clem9963/REAM)](https://github.com/Clem9963/REAM/issues)
[![Pull Requests](https://img.shields.io/github/issues-pr/Clem9963/REAM)](https://github.com/Clem9963/REAM/pulls)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for testing purposes.
Currently, this software was designed to work on Linux and no portage on Windows nor MacOS has been set up yet.
See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to set up the software and how to install them.
Down below is written the list of commands to type in a Debian or Debian-based system:

```
apt install apache2 php libapache2-mod-php mysql-server php-mysql
apt install php-curl php-gd php-intl php-json php-mbstring php-xml php-zip
apt install adminer composer
```

### Installing

Here is a step by step series of indications that tell you how to get a development environment running.

First, you will need to create a symbolic link that points towards the index of the project.

```
ln -s /PARENT_PATH_OF_THE_PROJECT/REAM/symfony/public /var/www/REAM
```

This link is used in order to avoid working in the /var/www/ folder. Nevertheless, you will have to tell it to the Apache server. To do that, you can edit the config file which is usually located there : /etc/apache2/sites-available/000-default.conf

Then, you will need to install all of the PHP dependencies of the project using the composer packet manager.
Run the following command in the symfony folder:

```
composer install
```

After that, the SQL database should be initialized, please open mysql using the following command:

```
mysql -u root
```

In MySQL enter the following:

```
USE mysql;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
FLUSH PRIVILEGES;
exit;
```

> **Disclaimer** :  The username and password just above can be guessed extremely easily! They are used here for testing purposes and should not be used in deployment environments.

The database of the project must be also created, enter the following command in the symfony folder:

```
php bin/console doctrine:database:create
```

Finally, you will have to set up correctly adminer:

```
mkdir /usr/share/adminer
wget "http://www.adminer.org/latest.php" -O /usr/share/adminer/latest.php
ln -s /usr/share/adminer/latest.php /usr/share/adminer/adminer.php
echo "Alias /adminer.php /usr/share/adminer/adminer.php" | tee /etc/apache2/conf-available/adminer.conf
a2enconf adminer.conf
```

### Updating locally the database

When changes are made to the structure of the REAM database, these changes can be taken into account and applied.
The following command, executed in the symfony folder, shows all the pending operations:

```
php bin/console doctrine:schema:update --dump-sql
```

Here is what you can type to update the database's schema:

```
php bin/console doctrine:schema:update --force
```

The command just above must be executed when the project is first installed.

### Lauching the Web server and the MySQL service

The REAM software requires the following services to be launched in order to work correctly.
To do that, you can enter the following commands:

```
service apache2 start
service mysql start
```

## Deployment

The project has not been released yet. Deployment indications are coming soon...

## Built With

- [Symfony](https://symfony.com/) - PHP web application framework.
- [Twig](https://twig.symfony.com/) - Template engine for PHP.
- [Material Design for Bootstrap](https://mdbootstrap.com/) - UI KIT.
- [jQuery](https://jquery.com/) - JavaScript library : DOM Traversal and Manipulation, Event Handling, and Ajax.

## Contributing

A charter for submitting pull requests to us has not been determined yet.

## Authors

- **Sylvain Bessonneau** - _Backend Developper_ - [sbessonneau](https://github.com/sbessonneau)
- **Clément Dif** - _Frontend Developper_ - [Clem9963](https://github.com/Clem9963)

See also the list of [contributors](https://github.com/Clem9963/REAM/contributors) who participated in this project.

## License

This project is licensed under the GNU General Public License v3 (GPL-3) - see the [LICENSE](LICENSE) file for details.
