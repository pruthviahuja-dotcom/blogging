THE WRITING ROOM — PHP BLOG STARTER

Files:
- index.php: responsive blogging page and post submission form
- posts.json: JSON storage for submitted posts

Run locally:
1. Install PHP 7.4+.
2. Put both files in a folder.
3. In that folder run: php -S localhost:8000
4. Open http://localhost:8000/index.php

Hosting:
Upload both files to a PHP-enabled host (for example, your hosting public_html folder).
Ensure the PHP process can write to posts.json.

Important:
This is a starter/demo. It currently allows anyone who can access the page to publish.
Before public launch, add a secure admin login, CSRF protection, spam prevention/rate limiting,
moderation, backups, and preferably a database. Do not use this unauthenticated publisher for
sensitive/private writing.
