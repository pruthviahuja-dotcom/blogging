# The Writing Room — Full Static Website Project

A responsive writing/blog site that can be deployed directly to GitHub Pages for free. No PHP or build process is needed.

## Project files
- `index.html` — complete site (HTML, CSS and JavaScript), with six sample stories, category filters, search, story modal and draft-copy box.
- `README.md` — deployment instructions and limitations.
- `CONTRIBUTING.md` — step-by-step instructions for adding articles.
- `ARTICLE_TEMPLATE.js.txt` — copyable article object template.
- `.nojekyll` — tells GitHub Pages not to run Jekyll processing.

## Publish on GitHub Pages
1. Create a GitHub repository, for example `writers-blog`.
2. Upload all files in this ZIP to the repository root.
3. Go to **Settings → Pages**.
4. Under **Build and deployment**, choose **Deploy from a branch**.
5. Choose branch `main` and folder `/(root)`, then click **Save**.
6. Wait for deployment. The URL will appear in Settings → Pages. A project site commonly looks like `https://YOUR-USERNAME.github.io/writers-blog/`.

## Add articles
Open `index.html` and find `const stories = [` near the bottom. Add a new article object with a unique `id`, title, author, category, date, excerpt and content. See `CONTRIBUTING.md` and `ARTICLE_TEMPLATE.js.txt` for an example. Commit the change; GitHub Pages will redeploy automatically.

## Important limitations
GitHub Pages serves static files only. It does not execute PHP and cannot persist reader submissions. The draft editor is only temporary in the browser. Articles are part of `index.html` until you add a backend or CMS. A backend/database or hosted CMS is required for reader accounts, moderation, direct visitor publishing and persistent storage.
