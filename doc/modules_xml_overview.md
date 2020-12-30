# ModularMonolith PHP_CodeSniffer standard

## Modules.xml file overview
[Go to README](../README.md)

This file describes the modules in the project which defines their boundaries.

#### Example file

```xml
<?xml version='1.0' encoding='UTF-8'?>
<modules>
    <module name="ModuleOne">
        <source namespace="\App\ModuleOne\"/>
        <submodules>
            <module name="NestedModule">
                <source namespace="\App\ModuleOne\NestedModule\"/>
                <public>
                    <source namespace="\App\ModuleOne\NestedModule\Port\"/>
                </public>
            </module>
        </submodules>
    </module>
    <module name="ModuleTwo">
        <source namespace="\App\ModuleTwo\"/>
    </module>
</modules>
```

Schema description:
- **modules** - Root of the file. Contains list of modules.
- **module** - Describes one module.
- **module[@name]** - Module name. The name is visible in error messages.
- **module/source** - When <source> is in a <module> element, it represents the namespace belonging to the module.
- **source[@namespace]** - Full namespace with leading and trailing backslash.
- **module/submodules** - Same meaning like <modules> but in <module> level. Determines the hierarchy of modules for future features.
- **module/public** - It describes the module's public interface that can be used by others.
- **public/source** - When <source> is in a <public> element, it represents the namespace belonging to the module that
  is public.
  
Additional comments:
Actually validation of the file is vestigial. Every attribute is required. Validation will be implemented in the future. 




