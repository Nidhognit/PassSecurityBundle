PassSecurityBundle
=========

The PassSecurityBundle help you or your users check your passwords

#How to use

...
You also can use console command

`bin/console passbundle:check 123456`


#Documentation
Default configuration:

    pass_security:
            type: "file"

Value "type" can be 'file'(default), 'base', 'custom'

#Type "file"

In this case, the password will be read from the file. Default file have 100 000 passwords, and you can use you own file:

    pass_security:
            type: "file"
            file: "path/custom.txt"

Where:
* ` castom.txt` - must have ".txt" etentions;
* `path` - is absolute path;
* Each new password in the file begins on a new line;

#Type "base"

#Type "custom"
You can also create your own service, for check passwords.

    pass_security:
            type: "custom"
            custom_service: "acme_bundle.ny_service"

Requirements:
* Service must implement the interface `InterfaceReader`;
* The service must be available for download from container;