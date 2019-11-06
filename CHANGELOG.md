Contao Search Screenshot changelog
==================================

Version 1.0.0 (2019-09-20)
--------------------------
Initial version

Version 1.0.1 (2019-09-24)
--------------------------
- Remove RT code as it's not needed and creates compatibility issues

Version 1.0.2 (2019-09-24)
--------------------------
- Fix issue with empty img directory not being included in package

Version 1.0.3 (2019-09-25)
--------------------------
- Update minimum Contao version requirement to 4.6
- Remove screenshot field from page table
- Update README.md

Version 1.0.4 (2019-10-02)
--------------------------
- Add flags to PhantomJS command to help dealing with errors
- Fixed issue with pages that have broken resources

Version 1.0.5 (2019-10-02)
--------------------------
- Make jakoch/phantomjs-installer a suggestion instead of a dependency
- Add toggle all button to BE screenshot generator

Version 1.0.6 (2019-11-06)
--------------------------
- Update README.md
- Use a symfony command run by a server cron job instead of using Contao native cron job to generate screenshots
- Fixed issue where screenshot path isn't saved to DB if the target page has a JS error
