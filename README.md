PassSecurityBundle
=========

The PassSecurityBundle help you or your users check your passwords

#How to use

In below example, we imagine, that you want check passwords for user before they submit form

    ...
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
    ...

In this case you must use HTTPS, if you use HTTP - it is very dangerous because of the possibility of [MITM](https://en.wikipedia.org/wiki/Man-in-the-middle_attack)

You also can use console command

`bin/console passbundle:check 123456`

Where `123456` - your custom password

#Documentation
Default configuration:

    pass_security:
            type: "file"

Value "type" can be `file` (default), `base`, `custom`

#Type "file"

In this case, the password will be read from the file. Default file have 100 000 passwords, and you can use you own file:

    pass_security:
            type: "file"
            file: "path/custom.txt"

Where:
* ` castom.txt` - must have ".txt" etentions;
* `path` - is absolute path;
* Each new password in the file begins on a new line;

Default bundle have some pass files:
* `Pass100k` (selected by default) - list of 100 000 offen used passwords
* `Pass1M` - list of 1 000 000 offen used passwords

Example (select file with 1 000 000 passwords):
 
    pass_security:
            type: "file"
            file: Pass1M
            
#Type "base"

In this case, the passwords will be read from the database. Default configuration looks like this:

    pass_security:
            type: "base"

You can configure the fololowing variables:

    pass_security:
            type: "base"
            class: \AcmeBundle\Entity\MyCustomEntity.php
            repository: AcmeBundle:MyCustomEntity

Requirements:
* `MyCustomEntity `  must implement the interface `InterfacePassSecurityEntity`

You can use you own passwords data in database, or you can transfer all the data from file with following console command:

`bin/console passbundle:base`

This command will write all passwords wrom "file" (by default Pass100K) in table, who define in entity "class" (by default 'pass_security_base')

#Type "custom"
You can also create your own service, for check passwords.

    pass_security:
            type: "custom"
            custom_service: "acme_bundle.my_service"

Requirements:
* Service must implement the interface `InterfaceReader`;
* The service must be available for download from container;