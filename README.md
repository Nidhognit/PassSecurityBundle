PassSecurityBundle
=========
[![Build Status](https://travis-ci.org/Nidhognit/PassSecurityBundle.svg?branch=master)](https://travis-ci.org/Nidhognit/PassSecurityBundle)

The PassSecurityBundle It is designed to help test passwords for entry into the list of unsafe.

Bundle only checks the password in the list, and tells you under what number it was found, the decision about how much it is safe, take you (or you can report it to your users, and to shift the responsibility on them).

I recommend not to use any password from those that have been found in the list.
# Instalation

If you use composer, open a command console, enter your project directory and execute the following command:

```console
$ composer require nidhognit/pass-security-bundle "0.1-beta"
```
Enable the Bundle in AppKernel like this:
```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new \Nidhognit\PassSecurityBundle\PassSecurityBundle(),
        );

        // ...
    }
    // ...
}
```
The bundle comes with a sensible default configuration. If you need to change them, you can define these in `config.yml` (more information for bundle configuration below).

# How to use

In below example, we imagine, that you want check passwords for user before they submit form:

```php
   // ...
    /**
     * @Route("/ajax_password_check")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxPasswordCheckAction(Request $request)
    {
        $password = $request->get('password');  //password from user
        $limit = null;  // if you want, you can limit password search (type of this variable must bu integer)
        $passManager = $this->get('pass_security.manager');
        $number = $passManager->getNumberOrNull($password, $limit);

        return new JsonResponse(['number' => $number]);
    }
   // ...
```

In this case you must use HTTPS, if you use HTTP - it is very dangerous because of the possibility of [MITM](https://en.wikipedia.org/wiki/Man-in-the-middle_attack).

You also can use console command:
```
$ bin/console passbundle:check 123456
```

Where `123456` - your custom password.

# Documentation
Default configuration:
```yml
pass_security:
    type: "file"
```
Value "type" can be `file` (default), `base`, `custom`.

### Type "file"

In this case, the password will be read from the file. Default file have 100 000 passwords, and you can use you own file:
```yml
pass_security:
    type: "file"
    file: "path/custom.txt"
```
Where:
* ` castom.txt` - must have ".txt" etentions;
* `path` - is absolute path;
* Each new password in the file begins on a new line;

Default bundle have some pass files:
* `Pass100k` (selected by default) - list of 100 000 offen used passwords;
* `Pass1M` - list of 1 000 000 offen used passwords;

Example (select file with 1 000 000 passwords):
 ```yml
pass_security:
    type: "file"
    file: Pass1M
            
```
### Type "base"

In this case, the passwords will be read from the database. Default configuration looks like this:
```yml
pass_security:
    type: "base"
```
You can configure the fololowing variables:
```yml
pass_security:
type: "base"
    class: \AcmeBundle\Entity\MyCustomEntity
    repository: AcmeBundle:MyCustomEntity
```
Requirements:
* `MyCustomEntity `  must implement the interface `InterfacePassSecurityEntity`.

You can use you own passwords data in database, or you can transfer all the data from file with following console command:
```
$ bin/console passbundle:base
```
This command will write all passwords from "file" (by default Pass100K) in table, who define in entity "class" (by default 'pass_security_base').
By default this command will use Entity, and if you use very big file, it can take a lot of time and memory.

If you do not need to create entities, you can use the option "--sql" like this
```
$ bin/console passbundle:base --sql
```

### Type "custom"
You can also create your own service, for check passwords.
```yml
pass_security:
    type: "custom"
    custom_service: "acme_bundle.my_service"
```
Requirements:
* Service must implement the interface `InterfaceReader`;
* The service must be available for download from container;
