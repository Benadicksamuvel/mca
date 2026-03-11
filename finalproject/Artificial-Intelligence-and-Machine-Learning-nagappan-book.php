<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artificial Intelligence and Machine Learning – Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/book.css" />
    <!-- Guaranteed page-level override: light theme for this page -->

</head>


<body>
    <?php require_once "navbar.php"; ?>

    <div class="container book-page">
        <div class="row g-4">

            <!-- LEFT: Image Gallery -->
            <div class="col-md-3">
                <div class="book-gallery-panel">
                    <img id="page1" src="image/bookimg/Artificial-Intelligence-and-Machine-Learning-nagappan-p1.png"
                        alt="AI Book Cover">
                    <img id="page2" src="image/bookimg/Artificial-Intelligence-and-Machine-Learning-nagappan-p2.png"
                        alt="AI Book Page 2">
                    <img id="page3" src="image/bookimg/Artificial-Intelligence-and-Machine-Learning-nagappan-p3.png"
                        alt="AI Book Page 3">
                </div>
            </div>

            <!-- RIGHT: Book Info -->
            <div class="col-md-9">
                <div class="book-info-panel">


                    <h1 class="book-main-title " style="color: rgb(102, 0, 255);;">Artificial Intelligence and Machine
                        Learning
                    </h1>

                    <!-- Authors -->
                    <div class="section-label">Authors</div>
                    <ul class="authors-list">
                        <li>Dr. K. Nagappan</li>
                        <li>G. Bharathikannan</li>
                        <li>Sivaranjani S</li>
                    </ul>

                    <!-- Price -->
                    <div class="section-label">Price</div>
                    <div class="price-row">
                        <span class="price-symbol">₹</span>
                        <span class="price-current" id="price">1351</span>
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
                    <p class="book-summary">
                        Artificial Intelligence and Machine Learning focus on creating systems that can simulate human
                        intelligence and learn from data. AI enables machines to reason, perceive, and make decisions,
                        while Machine Learning uses algorithms to improve performance through experience. Together, they
                        power applications like speech recognition, recommendation systems, automation, and predictive
                        analytics across industries.
                    </p>

                    <hr class="book-divider">

                    <!-- Product Details -->
                    <div class="product-details-card">
                        <h3>📦 Product Details</h3>
                        <table>
                            <tr>
                                <td>Publisher</td>
                                <td>Pencil Bitz Publications</td>
                            </tr>
                            <tr>
                                <td>Edition</td>
                                <td>1st Edition</td>
                            </tr>
                            <tr>
                                <td>Language</td>
                                <td>English</td>
                            </tr>

                            <tr>
                                <td>Pages</td>
                                <td>326</td>
                            </tr>

                        </table>
                    </div>
                    <br><br><br><br><br><br>
                </div><!-- /.book-info-panel -->
            </div>

        </div>
    </div>

    <!-- Book metadata for cart/transaction -->
    <input type="hidden" id="bookTitle" value="Artificial Intelligence and Machine Learning">
    <input type="hidden" id="bookCoverPath"
        value="image/cover/Artificial-Intelligence-and-Machine-Learning-nagappan.png">
    <script src="js/script.js"></script>
</body>

</html>