<?php
// Load dynamically published books from books.json
$jsonFile = __DIR__ . '/books.json';
$published = file_exists($jsonFile) ? json_decode(file_get_contents($jsonFile), true) : [];
if (!is_array($published))
    $published = [];

// Build display list: start with hardcoded existing books
$books = [
    [
        'img' => 'image/cover/Artificial-Intelligence-and-Machine-Learning-nagappan.png',
        'alt' => 'Artificial Intelligence and Machine Learning',
        'title' => 'Artificial Intelligence and Machine Learning',
        'authors' => 'Nagappan',
        'link' => 'Artificial-Intelligence-and-Machine-Learning-nagappan-book.php',
    ],
];

// Append books published via admin
foreach ($published as $b) {
    $authors = is_array($b['authors']) ? implode(', ', $b['authors']) : $b['authors'];
    $books[] = [
        'img' => $b['cover'],
        'alt' => $b['title'],
        'title' => $b['title'],
        'authors' => $authors,
        'link' => $b['slug'] . '-book.php',
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Books – Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        .book-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            transition: .3s;
            background: #fff;
        }

        .book-card:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
        }

        .book-card img {
            width: 250px;
            height: 300px;
            object-fit: cover;
            margin: auto;
            display: block;
            border-radius: 5px;
        }

        .book-title {
            color: #000;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
        }

        .book-author {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<?php require_once "navbar.php"; ?>

<body>
    <div class="container mt-5">
        <h3 class="mb-4">Books</h3>
        <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search books by title or author…"
            onkeyup="searchBooks()">

        <div class="row" id="bookGrid">
            <?php foreach ($books as $book): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 book-card-wrapper">
                    <div class="book-card">
                        <img src="<?= htmlspecialchars($book['img']) ?>" alt="<?= htmlspecialchars($book['alt']) ?>"
                            onerror="this.src='image/new-logo-pencilbitz.jpg'">
                        <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                        <div class="book-author"><?= htmlspecialchars($book['authors']) ?></div>
                        <a href="<?= htmlspecialchars($book['link']) ?>" class="btn btn-primary btn-sm mt-2">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function searchBooks() {
            const q = document.getElementById("searchInput").value.toLowerCase();
            document.querySelectorAll(".book-card-wrapper").forEach(c => {
                const t = c.querySelector(".book-title").innerText.toLowerCase();
                const a = c.querySelector(".book-author").innerText.toLowerCase();
                c.style.display = (t.includes(q) || a.includes(q)) ? "block" : "none";
            });
        }
    </script>
</body>

</html>