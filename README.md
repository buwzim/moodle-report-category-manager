# Moodle Report: Category Manager
We use a custom role to allow specific teachers to create courses in a specific course category. This report lets users be able to see who has this role in the current course category.

__Repository__: https://github.com/buwzim/moodle-category-manager

## Compability
* __Moodle__: 3.3
* __PostgreSQL__: 9.4

## Setup
1. Download the release zip file.
2. Install it like any other plugin.
3. Create or rename a role shortname to: ___categorymanager___. If this is not possible change the DB query (index.php) to your needs.
4. Configure the capabilities of your roles.

## Capabilities

### categorymanager:view
In order to see the link to the report, this right has to be allowed.

### categorymanager:viewName
Allow to see the names of the category managers.

### categorymanager:viewAccount
Allow to see the usernames of the category managers.

### categorymanager:viewEmail
Allow to see the e-mail addresses of the category managers.
