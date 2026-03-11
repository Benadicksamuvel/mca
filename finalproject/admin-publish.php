<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin-login.php");
    exit;
}

/* ── helpers ─────────────────────────────────────────── */
function slug(string $s): string
{
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9\s\-]/', '', $s);
    $s = preg_replace('/[\s\-]+/', '-', $s);
    return trim($s, '-');
}

function uploadFile(array $f, string $dir, string $name): string|false
{
    if (!is_dir($dir))
        mkdir($dir, 0755, true);
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    $dest = $dir . $name . '.' . $ext;
    return move_uploaded_file($f['tmp_name'], $dest) ? $dest : false;
}

function fail(string $msg): void
{
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>
          <link rel='stylesheet' href='css/style.css'></head>
          <body style='background:#0f0c1d;color:#f87171;padding:48px;font-family:sans-serif;'>
          <h2>⚠️ Publish failed</h2><p>" . htmlspecialchars($msg) . "</p>
          <a href='javascript:history.back()' style='color:#a78bfa;'>← Go back</a></body></html>";
    exit;
}

/* ── collect fields ──────────────────────────────────── */
$title = trim($_POST['book_title'] ?? '');
$firstAuthor = trim($_POST['first_author'] ?? '');
$price = (float) ($_POST['price'] ?? 0);
$summary = trim($_POST['summary'] ?? '');
$publisher = trim($_POST['publisher'] ?? 'Pencil Bitz Publications');
$editor = trim($_POST['editor'] ?? '1st Edition');
$language = trim($_POST['language'] ?? 'English');
$pages = (int) ($_POST['pages'] ?? 0);

$rawAuthors = array_map('trim', (array) ($_POST['authors'] ?? []));
$authors = array_values(array_filter($rawAuthors));
if (empty($authors))
    $authors = [$firstAuthor];

/* ── basic validation ────────────────────────────────── */
if (!$title)
    fail("Book title is required.");
if ($price <= 0)
    fail("Price must be greater than 0.");
if ($pages <= 0)
    fail("Page count must be greater than 0.");

$wordCount = count(array_filter(explode(' ', preg_replace('/\s+/', ' ', $summary))));
if ($wordCount < 30)
    fail("Summary must be at least 30 words (currently $wordCount).");
if ($wordCount > 100)
    fail("Summary must be 100 words or fewer (currently $wordCount).");

/* ── build slug ──────────────────────────────────────── */
$lastNamePart = slug(explode(' ', trim($firstAuthor))[count(explode(' ', trim($firstAuthor))) - 1]);
$baseSlug = slug($title) . '-' . $lastNamePart;
$slug = $baseSlug;

// Ensure uniqueness against existing generated files
$i = 1;
while (file_exists(__DIR__ . '/' . $slug . '-book.php')) {
    $slug = $baseSlug . '-' . $i++;
}

/* ── upload images ───────────────────────────────────── */
// Cover
if (empty($_FILES['cover_img']['tmp_name']))
    fail("Cover image is required.");
$coverPath = uploadFile($_FILES['cover_img'], 'image/cover/', $slug);
if ($coverPath === false)
    fail("Cover image upload failed.");

// Page images
$pPaths = [];
foreach ([1 => 'p1_img', 2 => 'p2_img', 3 => 'p3_img'] as $n => $field) {
    if (empty($_FILES[$field]['tmp_name']))
        fail("Page $n image is required.");
    $res = uploadFile($_FILES[$field], 'image/bookimg/', $slug . '-p' . $n);
    if ($res === false)
        fail("Page $n image upload failed.");
    $pPaths[$n] = $res;
}

/* ── save to books.json ──────────────────────────────── */
$jsonFile = __DIR__ . '/books.json';
$books = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
if (!is_array($books))
    $books = [];

$books[] = [
    'slug' => $slug,
    'title' => $title,
    'authors' => $authors,
    'cover' => $coverPath,
    'price' => $price,
    'summary' => $summary,
    'publisher' => $publisher,
    'editor' => $editor,
    'language' => $language,
    'pages' => $pages,
    'p1' => $pPaths[1],
    'p2' => $pPaths[2],
    'p3' => $pPaths[3],
];

file_put_contents($jsonFile, json_encode($books, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

/* ── generate {slug}-book.php from template ─────────── */
$titleE = htmlspecialchars($title);
$summaryE = htmlspecialchars($summary);
$priceInt = (int) $price;  // plain integer, no comma – getPrice() in script.js reads this
$publisherE = htmlspecialchars($publisher);
$editorE = htmlspecialchars($editor);
$languageE = htmlspecialchars($language);
$p1E = htmlspecialchars($pPaths[1]);
$p2E = htmlspecialchars($pPaths[2]);
$p3E = htmlspecialchars($pPaths[3]);

$authorsHtml = '';
foreach ($authors as $a) {
    $authorsHtml .= '                        <li>' . htmlspecialchars($a) . "</li>\n";
}

$pageHtml = <<<HTML
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$titleE} – Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/book.css" />
</head>

<body>
    <?php require_once "navbar.php"; ?>

    <div class="container book-page">
        <div class="row g-4">

            <!-- LEFT: Image Gallery -->
            <div class="col-md-3">
                <div class="book-gallery-panel">
                    <img id="page1" src="{$p1E}" alt="{$titleE} – Page 1">
                    <img id="page2" src="{$p2E}" alt="{$titleE} – Page 2">
                    <img id="page3" src="{$p3E}" alt="{$titleE} – Page 3">
                </div>
            </div>

            <!-- RIGHT: Book Info -->
            <div class="col-md-9">
                <div class="book-info-panel">

                    <h1 class="book-main-title" style="color: rgb(102, 0, 255);">{$titleE}</h1>

                    <!-- Authors -->
                    <div class="section-label">Authors</div>
                    <ul class="authors-list">
{$authorsHtml}                    </ul>

                    <!-- Price -->
                    <div class="section-label">Price</div>
                    <div class="price-row">
                        <span class="price-symbol">₹</span>
                        <span class="price-current" id="price">{$priceInt}</span>
                        <span class="price-note">✓ In Stock</span>
                    </div>

                    <!-- Quantity -->
                    <label class="qty-label" for="Quantity">Select Quantity</label>
                    <select id="Quantity" name="number">
                        <option value="">-- Choose --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>

                    <!-- Buttons -->
                    <div class="btn-group-custom">
                        <button class="btn-buy-now" onclick="buyNow()">⚡ Buy Now</button>
                        <button class="btn-add-cart" onclick="addToCart()">🛒 Add to Cart</button>
                    </div>

                    <hr class="book-divider">

                    <!-- Summary -->
                    <div class="section-label">Summary</div>
                    <p class="book-summary">{$summaryE}</p>

                    <hr class="book-divider">

                    <!-- Product Details -->
                    <div class="product-details-card">
                        <h3>📦 Product Details</h3>
                        <table>
                            <tr><td>Publisher</td><td>{$publisherE}</td></tr>
                            <tr><td>Edition</td><td>{$editorE}</td></tr>
                            <tr><td>Language</td><td>{$languageE}</td></tr>
                            <tr><td>Pages</td><td>{$pages}</td></tr>
                        </table>
                    </div>
                    <br><br><br>
                </div><!-- /.book-info-panel -->
            </div>

        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
HTML;

file_put_contents(__DIR__ . '/' . $slug . '-book.php', $pageHtml);

/* ── redirect ────────────────────────────────────────── */
header("Location: admin-dashboard.php?success=1");
exit;
