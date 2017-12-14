# CryptoPaste

CryptoPaste is a pastebin clone that encrypts the stored data in a way that the server cannot read it. Only those you give the decryption key to will be able to read the information.


With custom expiration options such as:

    - Custom expiration time limit
	- Expiration from file creation
	- Expiration from file last access


## Install

Currently it is very easy to install and update this application.


### Dependencies

    git
    php
    mariadb (or any MySQL database)

### Installation

Please ensure that PHP has been setup, and a MySQL database with a user created.

#### Downloading the application

To download the application and keep it updated, please clone this repository
     git clone https://github.com/faximilie/cryptopaste

After the repository has been cloned from github, it is ready to run the install script.

#### Installation Script

To configure the application correctly, please use the installation script. It is very easy to configure through the command line, please follow these steps


     php install.php --db:user=<database username> --db:pass=<database password> --db:host=<database address> --db:port=<database port> --www:host=<hostname of site>


Once this command has been run, a config file should be generated in inc/config.php


