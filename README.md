Contao Search Screenshot
========================
This module adds a screenshot preview of a page to the search results.

### PhantomJS
PhantomJS is required in order to capture the screenshots. PhantomJS will be automatically 
installed to `/vendor/bin/phantomjs` when the module is installed.

If you wish you can use a custom instance of PhantomJS by downloading it from https://phantomjs.org/.

## Usage
### Generating screenshots
Once installed, the module will begin to automatically generate screenshots via a cron job. It
may take a little while to generate screenshots for all pages depending on the number of pages
your website has.

If you don't wish to wait, you can manually generate screenshots for all pages from the CMS backend.

Screenshots will be automatically updated every 7 days.

### Configuration
Under "Settings" you will find some configuration options for the module.