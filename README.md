ILIAS UserDefaults Plugin
=========================
This EventHook-Plugin allows to auto-generate Portfolios, Skills and Course-/Group-Memberships after creating an new account, based on the choices of UserDefiniedFields.

# Installation
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/EventHandling/EventHook/
cd Customizing/global/plugins/Services/EventHandling/EventHook/
git clone https://github.com/fluxapps/UserDefaults.git UserDefaults
```
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.

## ILIAS 7 core ilCtrl patch
For make this plugin work with ilCtrl in ILIAS 7, you may need to patch the core, before you update the plugin (At your own risk)

Start at the plugin directory

./vendor/srag/dic/bin/ilias7_core_apply_ilctrl_patch.sh

## Contributing :purple_heart:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... write us an email: support@fluxlabs.ch
3. ... we give you access to the projects you like to contribute :fire:


## Adjustment suggestions / bug reporting :feet:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... ask us for a sla: support@fluxlabs.ch :kissing_heart:
3. ... we will give you the access with the possibility to read and create issues or to discuss feature requests with us.
