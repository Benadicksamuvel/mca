<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart – Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            background: #f8f9fa;
        }

        .cart-wrapper {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 36px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .cart-wrapper h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #1a1a2e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        th {
            background: #4f46e5;
            color: #fff;
            padding: 12px 14px;
            text-align: left;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        td {
            border-bottom: 1px solid #eee;
            padding: 12px 14px;
            font-size: 14px;
            color: #333;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .empty-msg {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 16px;
        }

        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f0f0ff;
            padding: 16px 20px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .total-section span {
            font-size: 20px;
            font-weight: 700;
            color: #4f46e5;
        }

        .btn-checkout {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-checkout:hover {
            background: #3730a3;
        }

        .btn-clear {
            background: transparent;
            color: #f87171;
            border: 1px solid #f87171;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
            margin-right: 10px;
        }

        .btn-clear:hover {
            background: #fff0f0;
        }
    </style>
</head>

<body>
    <?php require_once "navbar.php"; ?>

    <div class="cart-wrapper">
        <h2>🛒 Your Cart</h2>

        <!-- Back to last book page button -->
        <div id="backToBookWrap" style="margin-bottom:16px;display:none;">
            <a id="backToBookBtn" href="#" style="color:#4f46e5;font-weight:600;font-size:14px;text-decoration:none;">←
                Back to Book Page</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cover</th>
                    <th>Book</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Item Total (₹)</th>
                </tr>
            </thead>
            <tbody id="cartData">
                <!-- Filled by JS -->
            </tbody>
        </table>

        <div id="emptyMsg" class="empty-msg" style="display:none;">
            🛒 Your cart is empty. <a href="dashboard.php">Browse books</a>
        </div>

        <div class="total-section">
            <div>Grand Total: <span>₹<span id="grandTotal">0</span></span></div>
            <div>
                <button class="btn-clear" onclick="clearCart()">🗑 Clear Cart</button>
                <button class="btn-checkout" onclick="checkout()">Proceed to Checkout →</button>
            </div>
        </div>
    </div>

    <script>
        // Back to last book page
        const lastPage  = localStorage.getItem("lastBookPage");
        const lastTitle = localStorage.getItem("lastBookTitle");
        if (lastPage) {
            const wrap = document.getElementById('backToBookWrap');
            const btn  = document.getElementById('backToBookBtn');
            if (wrap && btn) {
                wrap.style.display = 'block';
                btn.href = lastPage;
                btn.textContent = '← Back to: ' + (lastTitle || 'Book Page');
            }
        }

        let cart       = JSON.parse(localStorage.getItem("cart")) || [];
        let html       = "";
        let grandTotal = 0;

        if (cart.length === 0) {
            document.getElementById("emptyMsg").style.display = "block";
        }

        cart.forEach(function (item, index) {
            let itemPrice = Number(String(item.price).replace(/,/g, '')) || 0;
            let itemQty   = Number(item.quantity) || 0;
            let itemTotal = itemPrice * itemQty;
            grandTotal   += itemTotal;

            let bookTitle = item.title || 'Book';
            let bookPage  = item.page  || '#';
            let coverSrc  = item.cover || '';

            let coverHtml = coverSrc
                ? `<img src="${coverSrc}" alt="cover"
                       style="width:46px;height:60px;object-fit:cover;border-radius:5px;
                              border:1px solid #ddd;display:block;margin:auto;"
                       onerror="this.style.display='none'">`
                : '<span style="color:#ccc;">—</span>';

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td style="text-align:center;">${coverHtml}</td>
                    <td><a href="${bookPage}" style="color:#4f46e5;font-weight:600;text-decoration:none;">${bookTitle}</a></td>
                    <td>₹${itemPrice}</td>
                    <td>${itemQty}</td>
                    <td>₹${itemTotal}</td>
                </tr>
            `;
        });

        document.getElementById("cartData").innerHTML = html;
        document.getElementById("grandTotal").innerText = grandTotal;

        function checkout() {
            if (grandTotal > 0) {
                localStorage.setItem("buyTotal", grandTotal);
                localStorage.setItem("buyItems", JSON.stringify(cart));
                window.location.href = "transaction.php";
            } else {
                alert("Your cart is empty!");
            }
        }

        function clearCart() {
            if (confirm("Clear all items from cart?")) {
                localStorage.removeItem("cart");
                location.reload();
            }
        }
    </script>
</body>

</html>