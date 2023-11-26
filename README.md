# checklinks
PHP Wrapper Script for [linkchecker](https://linkchecker.github.io/linkchecker/index.html) to check multiple URLs and send reports via mail if errors or warnings are found.
Access to the `proc_open` PHP function is required.
It uses [PHPMailer](https://github.com/PHPMailer/PHPMailer) for email sending and [Process](https://github.com/symfony/process) for executing command line programs.

## Requirements
* PHP > 7.2.5
* enabled proc_open function
* linkchecker executable

## Installation
### From Git
* Clone this repo `git clone https://github.com/e-dschungel/checklinks`
* Install dependencies using composer `composer install --no-dev`
* Rename `config/config.dist.php` to `config/config.conf.php` and edit it according to your needs
* Rename `config/linkchecker.dist.conf` to `config/linkchecker.conf` and edit it according to your needs
* Create url specific configuration (named like url, dots replaced with underscores)

### From ZIP file
* Download `checklinks.zip` (NOT `Source Code (zip)` or `Source Code (tar.gz)`)  from https://github.com/e-dschungel/checklinks/releases/latest
* Extract and upload it to your webserver
* Rename `config/config.dist.php` to `config/config.conf.php` and edit it according to your needs
* Rename `config/linkchecker.dist.conf` to `config/linkchecker.conf` and edit it according to your needs
* Create url specific configuration (named like url, dots replaced with underscores)

## Configuration
The configuration can be loaded from two files: the general file `linkchecker.conf` and a specific file for a given URL (dots replaced with underscores).
The URL specific file will be loaded after `linkchecker.conf` and will overwrite settings given there.

|variable|description|
|---|---|
|$cl_config['linkcheckerPath']|path to linkchecker executable|
|$cl_config['URLs']|Array of URLs to check|
|$cl_config['subject']|Subject of the email|
|$cl_config['body']|Text of the email body|
|$cl_config['emailTo']| email adress of the recipient|
|$cl_config['emailFrom']| email adress shown as sender of the dump|
|$cl_config['emailBackend']| email backend,can be smtp oder sendmail|
|$cl_config['SMTPHost']| SMTP hostname|
|$cl_config['SMTPAuth']| use SMTP authentication? true or false|
|$cl_config['SMTPUsername']| SMTP username|
|$cl_config['SMTPPassword']| SMTP password|
|$cl_config['SMTPSecurity']| type of SMTP security setting, can be "starttls" or "smtps"|
|$cl_config['SMTPPort']| SMTP port|
## Changelog
### Version 0.1
* first public release

* ### Version 0.2
* update PHPMailer to 6.9.1
* update dev dependencies
