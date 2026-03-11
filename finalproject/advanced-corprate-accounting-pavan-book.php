<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Corprate Accounting – Book Inventory</title>
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
                    <img id="page1" src="image/bookimg/advanced-corprate-accounting-pavan-p1.png"
                        alt="Advanced Corprate Accounting – Page 1">
                    <img id="page2" src="image/bookimg/advanced-corprate-accounting-pavan-p2.png"
                        alt="Advanced Corprate Accounting – Page 2">
                    <img id="page3" src="image/bookimg/advanced-corprate-accounting-pavan-p3.png"
                        alt="Advanced Corprate Accounting – Page 3">
                </div>
            </div>

            <!-- RIGHT: Book Info -->
            <div class="col-md-9">
                <div class="book-info-panel">

                    <h1 class="book-main-title" style="color: rgb(102, 0, 255);">Advanced Corprate Accounting</h1>

                    <!-- Authors -->
                    <div class="section-label">Authors</div>
                    <ul class="authors-list">
                        <li>Pavan</li>
                    </ul>

                    <!-- Price -->
                    <div class="section-label">Price</div>
                    <div class="price-row">
                        <span class="price-symbol">₹</span>
                        <span class="price-current" id="price">1200</span>
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
                    <p class="book-summary">Advanced Corporate Accounting deals with accounting practices for large
                        companies. It includes preparation of consolidated financial statements, accounting for mergers
                        and acquisitions, liquidation of companies, valuation of shares and goodwill, and inter-company
                        transactions. The subject helps understand complex financial structures and reporting required
                        for corporate organizations and group companies.</p>

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
                                <td>300</td>
                            </tr>
                        </table>
                    </div>
                    <br><br><br>
                </div><!-- /.book-info-panel -->
            </div>

        </div>
    </div>

    <!-- Book metadata for cart/transaction -->
    <input type="hidden" id="bookTitle" value="Advanced Corprate Accounting">
    <input type="hidden" id="bookCoverPath" value="image/cover/advanced-corprate-accounting-pavan.png">
    <script src="js/script.js"></script>
</body>

</html>
