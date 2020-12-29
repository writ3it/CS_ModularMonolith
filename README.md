# ModularMonolith PHP_CodeSniffer standard
PHPCS Standards for modular monolith. **This package is under development.**

## Why?

There are three good reasons.

#### Reason 1. 
PHP doesn't support Java-like packages.
This standard is a liberal version of this feature (you can use or not).
You can force standard in collaborating through CI/CD process.

#### Reason 2.
Monolith it's very popular architecture, but it's a hell for developers.
Monolithic application (sooner or later) is often called "legacy code". 
The alternative - isolation provided by composer package feature often is too strong. 
Package system offers more flexible module implementation.

#### Reason 3.
Deptrac which has a most similar feature isn't integrated with Phpstorm. PHP_CodeSniffer is.

## Required dependencies

- squizlabs/php_codesniffer package
- php >= 5.6
- ext-simplexml


## Getting started

#### Step 1. Install standard in your project

```bash
$ composer require --dev writ3it/cs_modular-monolith
```

#### Step 2. Create or extend your phpcs.xml.dist

If you have another rulset file, add lines below:
```xml
<config name="installed_paths" value="vendor/writ3it/phpcs_modular-monolith"/>
<rule ref="ModularMonolith">
    <properties>
        <property name="modules_definitions_path" value="modules.xml"/>
    </properties>
</rule>
```

Full example file (Symfony):
```xml
<?xml version="1.0" encoding="UTF-8"?>

<ruleset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/squizlabs/php_codesniffer/phpcs.xsd">

    <arg name="basepath" value="."/>
    <arg name="cache" value=".phpcs-cache"/>
    <arg name="colors"/>
    <arg name="extensions" value="php"/>

    <rule ref="PSR2"/>
    <rule ref="PSR12"/>

    <file>bin/</file>
    <file>config/</file>
    <file>public/</file>
    <file>src/</file>
    <file>tests/</file>
    <config name="installed_paths" value="vendor/writ3it/phpcs_modular-monolith"/>
    <rule ref="ModularMonolith">
        <properties>
            <property name="modules_definitions_path" value="modules.xml"/>
        </properties>
    </rule>

</ruleset>
```

#### Step 3. Create modules.xml

modules.xml describes your modules hierarchy and limits module interface to the public source code.

Example file:
```xml
<?xml version='1.0' encoding='UTF-8'?>
<modules>
    <module name="ModuleOne">
        <source namespace="App\ModuleOne\"/>
        <submodules>
            <module name="NestedModule">
                <source namespace="App\ModuleOne\NestedModule\"/>
                <public>
                    <source namespace="App\ModuleOne\NestedModule\Port\"/>
                </public>
            </module>
        </submodules>
    </module>
    <module name="ModuleTwo">
        <source namespace="App\ModuleTwo\"/>
    </module>
</modules>
```

#### Step 4. (CLI) Test your code!

Execute in your project root directory:
```bash
./vendor/bin/phpcs 
```

#### Step 4. (PhpStorm) Test your code **on-the-fly**!

[See instructions from JetBrains.](https://www.jetbrains.com/help/phpstorm/using-php-code-sniffer.html)


## TODO

- [x] Validation of modules boundaries. (MVP, 1.0) 
- [x] Validation of nested modules boundaries.(MVP, 1.0)
- [x] Possible to define public module interface that is accessible through boundaries. (MVP, 1.0)
- [ ] Tests (1.0)
- [ ] Documentation (1.0)
- [ ] Drawing of modules dependency net. (1.0)
- [ ] Validation of length of dependency paths. (future)
- [ ] Validation of dependency cycles. (future)

## Contributing

Before first release contributing is not possible but feel free to make issues.

## License

MIT License, [see details.](LICENSE)