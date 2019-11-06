Contao Search Screenshot
========================
This module adds a screenshot preview of a page to the search results.

### PhantomJS
PhantomJS is required in order to capture the screenshots. 

To automate installing PhantomJS you can use the package `jakoch/phantomjs-installer`. Once installed, 
add `PhantomInstaller\\Installer::installPhantomJS` to the post install and update commands of your 
project's composer.json:

```
"scripts": {
    "post-install-cmd": [          
        "PhantomInstaller\\Installer::installPhantomJS"
    ],
    "post-update-cmd": [
        "PhantomInstaller\\Installer::installPhantomJS"
    ]
}
```

Now when you run `composer install`, PhantomJS will be automatically 
installed to `/vendor/bin/phantomjs`.

If you wish you can use a custom instance of PhantomJS by downloading it from https://phantomjs.org/. If
you do this you may need to set a custom path for PhantomJS under Settings.

## Usage
### Generating screenshots
In order to generate the screenshots, a server cron job must be set up that
runs the following command every minute:

`vendor/bin/contao-console search-screenshot:generate`

If you don't wish to wait, you can manually trigger the generation of screenshots for all pages from 
the CMS backend under System > Search screenshots.

Screenshots will be automatically updated every 7 days.

### Displaying screenshots in results
The module makes a new variable `$this->screenshot` available in the search results template. This
variable will hold the URL of the screenshot if it exists or be empty if not.

A template which includes the screenshot is included and can be selected when configuring the search
module. Select `search_with_screenshot.html5` as the search template to use it.

### Configuration
Under System > Settings you will find some configuration options for the module in 
the "Search screenshot configuration" section.