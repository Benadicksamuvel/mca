<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin-login.php");
    exit;
}

// Load books from JSON
$jsonFile = __DIR__ . '/books.json';
$books = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
if (!is_array($books))
    $books = [];

$success = isset($_GET['success']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard – Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0f0c1d;
            color: #e2d9f3;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Header */
        .adm-header {
            background: linear-gradient(135deg, #1a103a, #2d1b69);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #6c3ce1;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .adm-brand {
            font-size: 20px;
            font-weight: 800;
            color: #c084fc;
        }

        .adm-brand span {
            color: #fff;
        }

        .adm-badge {
            background: rgba(168, 85, 247, .18);
            color: #a855f7;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-left: 8px;
        }

        .header-btns {
            display: flex;
            gap: 12px;
        }

        .btn-add {
            background: linear-gradient(135deg, #6c3ce1, #a855f7);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: .2s;
        }

        .btn-add:hover {
            opacity: .85;
            color: #fff;
        }

        .btn-lo {
            color: #f87171;
            border: 1.5px solid #f87171;
            background: rgba(239, 68, 68, .1);
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: .2s;
        }

        .btn-lo:hover {
            background: rgba(239, 68, 68, .25);
            color: #fca5a5;
        }

        /* Content */
        .content {
            padding: 40px 32px;
        }

        .sec-title {
            font-size: 22px;
            font-weight: 800;
            color: #c084fc;
            margin-bottom: 24px;
        }

        /* Search */
        .search-bar {
            width: 100%;
            background: rgba(255, 255, 255, .05);
            border: 1.5px solid #3b2270;
            border-radius: 10px;
            color: #e2d9f3;
            padding: 12px 18px;
            font-size: 14px;
            margin-bottom: 28px;
            outline: none;
            transition: .2s;
        }

        .search-bar:focus {
            border-color: #7c3aed;
        }

        .search-bar::placeholder {
            color: #6b6b8d;
        }

        /* Grid */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 22px;
        }

        /* Card */
        .bk-card {
            background: linear-gradient(160deg, #1c1040, #13082e);
            border: 1.5px solid #3b2270;
            border-radius: 14px;
            overflow: hidden;
            transition: .25s;
            cursor: default;
        }

        .bk-card:hover {
            transform: translateY(-5px);
            border-color: #7c3aed;
            box-shadow: 0 8px 28px rgba(124, 58, 237, .3);
        }

        .bk-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
        }

        .bk-body {
            padding: 16px;
        }

        .bk-title {
            font-size: 14px;
            font-weight: 700;
            color: #e9d5ff;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .bk-author {
            font-size: 12px;
            color: #a78bfa;
            margin-bottom: 12px;
        }

        .btn-rm {
            display: block;
            text-align: center;
            background: rgba(124, 58, 237, .18);
            color: #a78bfa;
            border: 1.5px solid #7c3aed;
            border-radius: 8px;
            padding: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: .2s;
        }

        .btn-rm:hover {
            background: #7c3aed;
            color: #fff;
        }

        /* Empty */
        .no-books {
            grid-column: 1/-1;
            text-align: center;
            color: #7c3aed;
            padding: 60px 0;
            font-size: 18px;
        }

        /* Toast */
        .toast-ok {
            position: fixed;
            top: 76px;
            right: 28px;
            z-index: 9999;
            background: linear-gradient(135deg, #166534, #15803d);
            color: #fff;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(22, 101, 52, .5);
            animation: slideIn .4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(40px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }
    </style>
</head>

<body>

    <?php if ($success): ?>
        <div class="toast-ok" id="toast">✅ Book published successfully!</div>
        <script>setTimeout(() => { let t = document.getElementById('toast'); if (t) { t.style.transition = 'opacity .5s'; t.style.opacity = 0; setTimeout(() => t.remove(), 500); } }, 3200);</script>
    <?php endif; ?>

    <div class="adm-header">
        <div class="adm-brand">📚 <span>Book Inventory</span><span class="adm-badge">Admin</span></div>
        <div class="header-btns">
            <a href="admin-add-book.php" class="btn-add">＋ Add New Book</a>
            <a href="admin-logout.php" class="btn-lo">🚪 Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="sec-title">📖 Published Books</div>
        <input class="search-bar" id="srch" type="text" placeholder="🔍  Search by title or author…" oninput="filter()">

        <div class="book-grid" id="grid">
            <?php if (empty($books)): ?>
                <div class="no-books">📭 No books yet — click <strong>＋ Add New Book</strong> to get started!</div>
            <?php else:
                foreach ($books as $b):
                    $authors = is_array($b['authors']) ? implode(', ', $b['authors']) : $b['authors'];
                    ?>
                    <div class="bk-card" data-title="<?= strtolower(htmlspecialchars($b['title'])) ?>"
                        data-author="<?= strtolower(htmlspecialchars($authors)) ?>">
                        <img src="<?= htmlspecialchars($b['cover']) ?>" alt="<?= htmlspecialchars($b['title']) ?>"
                            onerror="this.src='image/new-logo-pencilbitz.jpg'">
                        <div class="bk-body">
                            <div class="bk-title"><?= htmlspecialchars($b['title']) ?></div>
                            <div class="bk-author"><?= htmlspecialchars($authors) ?></div>
                            <a href="<?= htmlspecialchars($b['slug']) ?>-book.php" class="btn-rm">Read More →</a>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
        </div>
    </div>

    <script>
        function filter() {
            const q = document.getElementById('srch').value.toLowerCase();
            document.querySelectorAll('.bk-card').forEach(c => {
                c.style.display = (c.dataset.title.includes(q) || c.dataset.author.includes(q)) ? '' : 'none';
            });
        }
    </script>
</body>

</html>