# Adding articles to The Writing Room

This is a static GitHub Pages project. To add an article:

1. Open `index.html` and edit it on GitHub, or edit it locally.
2. Find `const stories = [` in the JavaScript near the bottom.
3. Add a new article object inside the array, using a unique `id`.
4. Commit your change and wait for GitHub Pages to redeploy.

Example article object (add a comma after the previous object):

```javascript
{
  id: 7,
  title: "My New Article",
  author: "Your Name",
  category: "Personal Essays",
  date: "October 16, 2026",
  excerpt: "A short introduction to your article.",
  content: `Your article text goes here.

Use a blank line between paragraphs.`
}
```

Available categories can be any text, though matching an existing category keeps the filter tidy.
Use backticks around `content` so normal quotes can appear inside your article. If your article contains a backtick character, escape it with a backslash.

## Publish
Commit the changed `index.html` to the branch configured in Settings → Pages. GitHub Pages will publish the update automatically.
