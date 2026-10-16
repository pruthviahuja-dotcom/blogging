<?php
// Simple writing blog starter. PHP 7.4+ recommended.
// Posts are stored in posts.json. For a public production site, add authentication,
// CSRF protection, input limits, and a database before enabling admin publishing.

$dataFile = __DIR__ . '/posts.json';
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}
$posts = json_decode(file_get_contents($dataFile), true);
if (!is_array($posts)) $posts = [];

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function save_posts($file, $posts) {
    file_put_contents($file, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'publish') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? 'Guest Writer');
    $category = trim($_POST['category'] ?? 'Personal Essays');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title !== '' && $content !== '') {
        array_unshift($posts, [
            'id' => bin2hex(random_bytes(6)),
            'title' => mb_substr($title, 0, 140),
            'author' => mb_substr($author ?: 'Guest Writer', 0, 80),
            'category' => mb_substr($category ?: 'Personal Essays', 0, 50),
            'excerpt' => mb_substr($excerpt, 0, 280),
            'content' => $content,
            'date' => date('Y-m-d')
        ]);
        save_posts($dataFile, $posts);
        header('Location: index.php?published=1#stories');
        exit;
    } else {
        $notice = 'Please add a title and your writing before publishing.';
    }
}
if (isset($_GET['published'])) $notice = 'Your story has been published locally.';
$featured = $posts[0] ?? [
    'title' => 'The beauty of beginning',
    'author' => 'The Editorial Desk',
    'category' => 'Creative Living',
    'excerpt' => 'Every writer starts somewhere. A thought, a memory, a small observation—and the courage to put it into words.',
    'content' => 'Every writer starts somewhere. A thought, a memory, a small observation—and the courage to put it into words.',
    'date' => date('Y-m-d')
];
$categories = ['All stories'];
foreach ($posts as $p) if (!in_array($p['category'], $categories, true)) $categories[] = $p['category'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="A calm, welcoming space for stories, essays, ideas and everyday observations.">
<title>The Writing Room — Stories & Ideas</title>
<style>
:root{--ink:#25251f;--muted:#74736b;--paper:#faf8f3;--card:#fffdf9;--accent:#7a4938;--line:#e8e1d7;--sage:#dfe5d7}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--paper);color:var(--ink);font-family:Georgia,"Times New Roman",serif;line-height:1.6}
a{color:inherit;text-decoration:none}.wrap{width:min(1120px,92%);margin:auto}
header{border-bottom:1px solid var(--line);background:rgba(250,248,243,.94)}.nav{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:20px 0;font-family:Arial,sans-serif}
.brand{font-weight:800;letter-spacing:.04em;font-size:1.08rem}.brand span{color:var(--accent)}.navlinks{display:flex;gap:24px;font-size:.9rem;color:#555}.navlinks a:hover{color:var(--accent)}
.hero{padding:78px 0 66px;display:grid;grid-template-columns:1.2fr .8fr;align-items:center;gap:55px}
.eyebrow{text-transform:uppercase;letter-spacing:.18em;font:700 .72rem Arial,sans-serif;color:var(--accent)}
h1{font-size:clamp(3rem,7vw,5.7rem);line-height:.99;letter-spacing:-.055em;font-weight:500;margin:20px 0}
.hero p{font-size:1.15rem;color:var(--muted);max-width:550px}
.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.btn{display:inline-block;padding:13px 20px;background:var(--accent);color:white;border:1px solid var(--accent);border-radius:3px;font:600 .88rem Arial,sans-serif;cursor:pointer}.btn.secondary{background:transparent;color:var(--ink);border-color:#cfc5b8}.btn:hover{opacity:.88}
.art{min-height:350px;background:var(--sage);position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;border-radius:48% 48% 4px 4px}
.art:before{content:"";position:absolute;width:210px;height:280px;background:#b8c5ac;border-radius:48% 48% 4px 4px;transform:rotate(-13deg);left:20%;top:18%}
.art:after{content:"";position:absolute;width:180px;height:240px;background:#d9bba2;border-radius:48% 48% 4px 4px;transform:rotate(15deg);right:16%;top:27%}
.artnote{z-index:1;background:var(--card);padding:25px 28px;max-width:220px;box-shadow:0 12px 35px #5a4d3a20;transform:rotate(-4deg);font-size:1.35rem;line-height:1.2}
.sectionhead{display:flex;justify-content:space-between;align-items:end;gap:20px;margin:24px 0}.sectionhead h2{font-size:2.2rem;font-weight:500;letter-spacing:-.04em;margin:0}.sectionhead p{color:var(--muted);margin:0}
.filters{display:flex;gap:8px;flex-wrap:wrap;margin:18px 0 28px}.filter{padding:8px 13px;border:1px solid var(--line);border-radius:30px;background:transparent;font: .82rem Arial,sans-serif;cursor:pointer}.filter.active,.filter:hover{background:var(--ink);color:white;border-color:var(--ink)}
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}.story{background:var(--card);border:1px solid var(--line);padding:25px;display:flex;flex-direction:column;min-height:260px;transition:transform .2s}.story:hover{transform:translateY(-3px)}.tag{font:700 .7rem Arial,sans-serif;letter-spacing:.1em;text-transform:uppercase;color:var(--accent)}.story h3{font-size:1.55rem;line-height:1.15;font-weight:500;margin:15px 0 10px}.story p{color:var(--muted);margin:0 0 22px;font-size:.98rem}.meta{margin-top:auto;color:var(--muted);font: .78rem Arial,sans-serif;display:flex;justify-content:space-between;gap:10px}.read{color:var(--accent);font-weight:700}
.empty{grid-column:1/-1;padding:28px;border:1px dashed var(--line);color:var(--muted);text-align:center}
.about{margin:90px 0;background:#eeece3;padding:50px;display:grid;grid-template-columns:.8fr 1.2fr;gap:40px;align-items:center}.about h2{font-size:2.5rem;line-height:1.1;font-weight:500;margin:0}.about p{color:#5f5e56}
.writebox{margin:70px auto;max-width:760px}.writebox h2{font-size:2.5rem;font-weight:500;margin:0 0 8px}.writebox>p{color:var(--muted)}
form{background:var(--card);border:1px solid var(--line);padding:28px;display:grid;grid-template-columns:1fr 1fr;gap:16px}.field{display:flex;flex-direction:column;gap:7px}.field.full{grid-column:1/-1}label{font:700 .8rem Arial,sans-serif}input,select,textarea{width:100%;padding:12px;border:1px solid #d9d1c6;border-radius:3px;background:#fffefb;color:var(--ink);font:1rem Arial,sans-serif}textarea{min-height:150px;resize:vertical}.hint{font: .78rem Arial,sans-serif;color:var(--muted)}.notice{padding:12px 16px;background:#e7efdf;margin:15px 0;color:#34432e}
footer{border-top:1px solid var(--line);padding:28px 0;color:var(--muted);font: .82rem Arial,sans-serif}.foot{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap}
dialog{width:min(720px,92%);border:1px solid var(--line);padding:32px;background:var(--card);color:var(--ink);max-height:85vh;overflow:auto}dialog::backdrop{background:#17171499}dialog h2{font-size:2.2rem;line-height:1.15;font-weight:500}dialog .body{white-space:pre-wrap;font-size:1.08rem}
@media(max-width:760px){.hero{grid-template-columns:1fr;padding:52px 0}.art{min-height:250px}.grid{grid-template-columns:1fr}.about{grid-template-columns:1fr;padding:30px;margin:55px 0}.navlinks{gap:12px}.navlinks a:nth-child(2){display:none}form{grid-template-columns:1fr}.field.full{grid-column:auto}.sectionhead{align-items:start;flex-direction:column}}
</style>
</head>
<body>
<header><div class="wrap nav">
<a class="brand" href="#">THE <span>WRITING</span> ROOM</a>
<nav class="navlinks"><a href="#stories">Stories</a><a href="#about">About</a><a href="#write">Write with us</a></nav>
</div></header>
<main>
<section class="wrap hero">
<div><div class="eyebrow">A home for curious minds</div><h1>Words have a place <em>here.</em></h1>
<p>A thoughtful corner of the internet for personal essays, small discoveries, untold stories and ideas worth sharing.</p>
<div class="actions"><a class="btn" href="#stories">Explore stories</a><a class="btn secondary" href="#write">Share your writing</a></div></div>
<div class="art" aria-label="Abstract editorial artwork"><div class="artnote">“Write what you feel. The rest will find its way.”</div></div>
</section>
<section class="wrap" id="stories">
<div class="sectionhead"><div><div class="eyebrow">Fresh from the page</div><h2>Stories & thoughts</h2></div><p>Ideas, in their own words.</p></div>
<div class="filters" id="filters">
<?php foreach ($categories as $i => $cat): ?><button class="filter <?= $i === 0 ? 'active' : '' ?>" data-category="<?= e($cat) ?>"><?= e($cat) ?></button><?php endforeach; ?>
</div>
<div class="grid" id="storyGrid">
<?php if (!$posts): ?>
<article class="story" data-cat="Creative Living"><div class="tag">Creative Living</div><h3>The beauty of beginning</h3><p>Every writer starts somewhere. A thought, a memory, a small observation—and the courage to put it into words.</p><div class="meta"><span>Editorial Desk</span><span>Sample story</span></div></article>
<article class="story" data-cat="Personal Essays"><div class="tag">Personal Essays</div><h3>Things we learn slowly</h3><p>Some lessons arrive quietly, through ordinary days and conversations we remember long after they end.</p><div class="meta"><span>Guest Writer</span><span>Sample story</span></div></article>
<article class="story" data-cat="Everyday Notes"><div class="tag">Everyday Notes</div><h3>Noticing the little things</h3><p>A walk, a window full of rain, a familiar song: sometimes the smallest details make the best stories.</p><div class="meta"><span>Guest Writer</span><span>Sample story</span></div></article>
<?php else: foreach ($posts as $p): ?>
<article class="story" data-cat="<?= e($p['category']) ?>">
<div class="tag"><?= e($p['category']) ?></div><h3><?= e($p['title']) ?></h3><p><?= e($p['excerpt'] ?: mb_substr(strip_tags($p['content']), 0, 150) . '…') ?></p>
<div class="meta"><span>By <?= e($p['author']) ?></span><span><?= e($p['date']) ?></span></div>
<div style="margin-top:15px"><button class="filter read" type="button" onclick="openStory(this)" data-title="<?= e($p['title']) ?>" data-author="<?= e($p['author']) ?>" data-category="<?= e($p['category']) ?>" data-date="<?= e($p['date']) ?>" data-content="<?= e($p['content']) ?>">Read story ↗</button></div>
</article>
<?php endforeach; endif; ?>
</div>
</section>
<section class="wrap about" id="about"><div><div class="eyebrow">A little about us</div><h2>Every voice has a story.</h2></div><div><p>The Writing Room is a welcoming space for people who love putting thoughts into words. Whether you write every day or are sharing your first paragraph, there is room for your perspective here.</p><p>Read with curiosity. Write with honesty. Leave a little inspiration for the next person.</p></div></section>
<section class="wrap writebox" id="write"><div class="eyebrow">Your turn</div><h2>Put your words out there.</h2><p>Have an idea, a story or a reflection to share? Draft it below and publish it to this demo blog.</p>
<?php if ($notice): ?><div class="notice"><?= e($notice) ?></div><?php endif; ?>
<form method="post" action="#write">
<input type="hidden" name="action" value="publish">
<div class="field full"><label for="title">Story title *</label><input id="title" name="title" maxlength="140" required placeholder="Give your story a name"></div>
<div class="field"><label for="author">Writer name</label><input id="author" name="author" maxlength="80" placeholder="Your name or pen name"></div>
<div class="field"><label for="category">Category</label><select id="category" name="category"><option>Personal Essays</option><option>Creative Writing</option><option>Poetry</option><option>Everyday Notes</option><option>Culture & Society</option><option>Travel</option><option>Technology</option><option>Other</option></select></div>
<div class="field full"><label for="excerpt">Short introduction</label><input id="excerpt" name="excerpt" maxlength="280" placeholder="A sentence that invites people to read"></div>
<div class="field full"><label for="content">Your writing *</label><textarea id="content" name="content" required placeholder="Start writing here…"></textarea><span class="hint">Please keep a copy of your writing. This demo stores posts in a local JSON file on the server.</span></div>
<div class="field full"><button class="btn" type="submit">Publish story ↗</button></div>
</form>
</section>
</main>
<footer><div class="wrap foot"><span>© <?= date('Y') ?> The Writing Room</span><span>Made for people who love words.</span></div></footer>
<dialog id="storyDialog"><button class="btn secondary" onclick="document.getElementById('storyDialog').close()">Close ×</button><div class="eyebrow" id="dialogCategory" style="margin-top:25px"></div><h2 id="dialogTitle"></h2><div class="meta"><span id="dialogAuthor"></span><span id="dialogDate"></span></div><hr style="border:0;border-top:1px solid var(--line);margin:24px 0"><div class="body" id="dialogContent"></div></dialog>
<script>
const filters = document.querySelectorAll('.filter[data-category]');
filters.forEach(btn => btn.addEventListener('click', () => {
  filters.forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const category = btn.dataset.category;
  document.querySelectorAll('.story[data-cat]').forEach(card => {
    card.style.display = (category === 'All stories' || card.dataset.cat === category) ? 'flex' : 'none';
  });
}));
function openStory(btn) {
  document.getElementById('dialogTitle').textContent = btn.dataset.title;
  document.getElementById('dialogAuthor').textContent = 'By ' + btn.dataset.author;
  document.getElementById('dialogCategory').textContent = btn.dataset.category;
  document.getElementById('dialogDate').textContent = btn.dataset.date;
  document.getElementById('dialogContent').textContent = btn.dataset.content;
  document.getElementById('storyDialog').showModal();
}
</script>
</body>
</html>
