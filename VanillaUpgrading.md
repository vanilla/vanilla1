# How to upgrade Vanilla #

-

**Before any upgrade, back up your current Vanilla files.**

_Problems?_ Ask for help at the [community forum](http://vanillaforums.org/discussions).

-

## Upgrading from 1.1+: ##

If your existing Vanilla installation is unmodified, simply overwrite your existing files with the latest ones. **That is all.**

If you have made your own modifications to the Vanilla core and/or extensions, you may want to read the specific instructions below to ensure you do not overwrite your changes.

-

## Version-specific notes: ##

#### Upgrading from Vanilla 1.2.1 to Vanilla 1.2.2 ####

Unzip the package and overwrite your existing files with its contents.


#### Upgrading from Vanilla 1.1.10 to Vanilla 1.2.1 ####

Unzip the package and overwrite your existing files with its contents.


#### Upgrading from Vanilla 1.1.9 to Vanilla 1.1.10 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
appg/settings.php
appg/version.php
js/prototype.js
js/builder.js
js/sound.js
js/effects.js
js/unittest.js
js/scriptaculous.js
js/dragdrop.js
js/slider.js
js/controls.js
library/Vanilla/Vanilla.Class.Search.php
library/Vanilla/Vanilla.Class.DiscussionManager.php
library/Framework/Framework.Functions.php
languages/English/definitions.php
setup/installer.php
setup/style.css
themes/vanilla/styles/default/utility.css
themes/vanilla/styles/default/vanilla.print.css
themes/vanilla/styles/default/people.css
themes/vanilla/styles/default/vanilla.css
```


#### Upgrading from Vanilla 1.1.8 to Vanilla 1.1.9 ####

Unzip the package and overwrite your existing files with its contents.

Also, if you installed Vanilla 1.1.8, you should check `$Configuration['HTTP_METHOD']`
in `conf/settings.php` does not end with "://" (there might not be any `$Configuration['HTTP_METHOD']`). It should either be set to "http" or
"https".

The following files were changed:

```
appg/version.php
setup/installer.php
```


#### Upgrading from Vanilla 1.1.7 to Vanilla 1.1.8 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
ajax/updatecheck.php
appg/version.php
```


#### Upgrading from Vanilla 1.1.6 to Vanilla 1.1.7 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
appg/version.php
setup/installer.php
setup/mysql.sql
```


#### Upgrading from Vanilla 1.1.5a to Vanilla 1.1.6 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
comments.php
termsofservice.php
ajax/sortcategories.php
ajax/sortroles.php
ajax/switch.php
appg/database.php
appg/settings.php
appg/version.php
js/global.js
js/jquery.js
languages/English/definitions.php
library/Framework/*
library/People/*
library/Vanilla/Vanilla.Class.CategoryManager.php
library/Vanilla/Vanilla.Class.DiscussionManager.php
library/Vanilla/Vanilla.Control.CategoryForm.php
library/Vanilla/Vanilla.Functions.php
setup/installer.php
setup/mysql.sql
setup/style.css
themes/comments.php
themes/people_foot.php
themes/vanilla/styles/default/people.css
themes/vanilla/styles/default/vanilla.css
```


#### Upgrading from Vanilla 1.1.5 to Vanilla 1.1.5a ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
appg/version.php
library/Framework/Framework.Class.IntegrityChecker.php
library/Framework/Framework.Class.DirectoryScanner.php
library/People/People.Class.UserManager.php
setup/installer.php
```


#### Upgrading from Vanilla 1.1.4 to Vanilla 1.1.5 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
comments.php
termsofservice.php
ajax/blockcategory.php
ajax/sortcategories.php
ajax/sortroles.php
ajax/switch.php
ajax/switchextension.php
ajax/updatecheck.php
appg/init_ajax.php
appg/init_people.php
appg/init_vanilla.php
appg/md5.csv
appg/settings.php
appg/version.php
js/global.js
js/jquery.js
js/vanilla.js
languages/English/definitions.php
library/Framework/Framework.Class.AsyncUploader.php
library/Framework/Framework.Class.Context.php
library/Framework/Framework.Class.Control.php
library/Framework/Framework.Class.Delegation.php
library/Framework/Framework.Class.DirectoryScanner.php
library/Framework/Framework.Class.Email.php
library/Framework/Framework.Class.IntegrityChecker.php
library/Framework/Framework.Class.ObjectFactory.php
library/Framework/Framework.Class.SqlBuilder.php
library/Framework/Framework.Class.SqlSearch.php
library/Framework/Framework.Class.XmlManager.php
library/Framework/Framework.Control.Head.php
library/Framework/Framework.Control.UpdateCheck.php
library/Framework/Framework.Functions.php
library/People/People.Class.Authenticator.php
library/People/People.Class.PasswordHash.php
library/People/People.Class.Session.php
library/People/People.Class.User.php
library/People/People.Class.UserManager.php
library/People/People.Control.Leave.php
library/People/People.Control.SignInForm.php
library/Vanilla/Vanilla.Class.Comment.php
library/Vanilla/Vanilla.Class.CommentManager.php
library/Vanilla/Vanilla.Class.Discussion.php
library/Vanilla/Vanilla.Class.DiscussionManager.php
library/Vanilla/Vanilla.Control.CommentGrid.php
library/Vanilla/Vanilla.Control.DiscussionForm.php
library/Vanilla/Vanilla.Control.IdentityForm.php
library/Vanilla/Vanilla.Control.Menu.php
library/Vanilla/Vanilla.Control.PasswordForm.php
library/Vanilla/Vanilla.Control.SearchForm.php
library/Vanilla/Vanilla.Functions.php
setup/installer.php
setup/upgrader.php
themes/account_preferences_form.php
themes/categories.php
themes/comment_form.php
themes/comments.php
themes/discussion.php
themes/discussion_form.php
themes/discussions.php
themes/menu.php
themes/people_foot.php
themes/people_signout_form_nopostback.php
themes/search_results_comments.php
themes/search_results_users.php
themes/settings_update_check_validpostback.php
themes/vanilla/styles/default/people.css
themes/vanilla/styles/default/utility.css
themes/vanilla/styles/default/vanilla.css
themes/vanilla/styles/default/vanilla.print.css
```


#### Upgrading from Vanilla 1.1.3 to Vanilla 1.1.4 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
/ajax/sortcategories.php
/ajax/sortroles.php
/languages/English/definitions.php
/themes/settings_category_list.php
/themes/settings_role_list.php
/appg/settings.php
/appg/version.php
/js/*.* (all files in this folder)
/setup/index.php
/setup/installer.php
/setup/upgrader.php
```


#### Upgrading from Vanilla 1.1.2 to Vanilla 1.1.3 ####

Unzip the package and overwrite your existing files with its contents.

All files were changed.


#### Upgrading from Vanilla 1.1 or 1.1.1 to Vanilla 1.1.2 ####

Unzip the package and overwrite your existing files with its contents.

The following files were changed:

```
/comments.php
/ajax/blockcategory.php
/ajax/switch.php
/ajax/switchextension.php
/ajax/updatecheck.php
/appg/settings.php
/js/global.js
/js/vanilla.js
/library/Framework/Framework.Functions.php
/library/People/People.Class.Authenticator.php
/library/People/People.Class.UserManager.php
/themes/account_preferences_form.php
/themes/categories.php
/themes/comment_form.php
/themes/comments.php
/themes/discussion_form.php
/themes/settings_applicants_form.php
/readme.html
/setup/index.html
/setup/installer.php
/setup/upgrader.php
```


#### Upgrading from Vanilla 1, 1.0.1, 1.0.2, or 1.0.3 to Vanilla 1.1.x ####

Assuming you haven't made any changes to the core Vanilla files, upgrading is easy. By "core Vanilla files", I am referring to all files NOT in the conf or extensions directories. All you need to do is:

  * Back up your current Vanilla files.
  * Download the new version from http://getvanilla.com.
  * Unzip the files to your local machine.
  * Delete the extensions and conf folders on your downloaded, unzipped files (You do not want to copy the conf or extensions folders because you want your existing configurations and extensions to remain intact).
  * Upload all remaining New Vanilla files on top of the existing Vanilla files on your server.
  * Take any additional language and theme files that you were using from your backup and re-upload them into the appropriate places in Vanilla.


#### Upgrading from Vanilla 0.9.2.x to Vanilla 1.x ####

  * Back up your current files **just in case**.
  * Download the new version from http://getvanilla.com.
  * Unzip the files to your local machine.
  * Remove the existing files from your server.
  * Upload the new Vanilla files to your server.
  * Find the appg/settings.php file from your backup version. Upload it to your new Vanilla's conf folder and rename it to old\_settings.php.
  * Navigate to your vanilla's folder in your web browser and follow the instructions.