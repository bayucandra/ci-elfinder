#CodeIgniter elFinder with AWS S3 Connector

##Prequesites

* CI V3
* Make sure CodeIgniter config $config['composer_autoload'] = TRUE;


##Installation steps
1. In directory `/application` do:
    * `composer require Studio-42/elFinder`,
    * `composer require barryvdh/elfinder-flysystem-driver`
    * `composer require league/flysystem-aws-s3-v3`
1. If prompted: **"No composer.json in current directory..."** pres **"n"**
1. In root, write following code in **.htaccess** :
    ```RewriteEngine On
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule ^(.*)$ index.php/$1 [L]
    ```

##CI Controller setup
1. Don't forget to include:
    ```
    use Aws\S3\S3Client;
    use League\Flysystem\AwsS3v3\AwsS3Adapter;
    use League\Flysystem\Filesystem;
    ```
    
1. Create controller at **/application/controllers/elfinder_lib.php**, follow function inside this repo.
    * `manager()` is just the "bridge" of "connector" and "view" ( frontend ) stuff
    * `connector()` is the main connector which will connect elFinder (frontend) with the S3 driver, you can connect this from another frontend part which has another elFinder frontend.
1. Important notes about `connector()`
    * The only thing you need to put for AWS config is at variable `$aws_config`
    * Inside `preCheck()` method, you can adjust max files upload individually and max volume of whole S3 path to have.
    
    
##CI View setup/demo
Notes: Just demo, if you only need controller for another frontend part, you don't need this. Having `connector()` in your controller is enough
1. Ensure `$config['base_url']` is set properly. This repo using following technique:
    ```
    $config['base_url'] = '';
    $config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
    $config['base_url'] .= "://" . $_SERVER['HTTP_HOST'];
    $config['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
    ```
1. Copy from this repo file at **/views/elfinder.php**
1. Coppy **/biq-assets** from this repo, it is contain **app.js** as the main frontend setup,
elFinder frontend stuff. And some other required JS & CSS libs.

