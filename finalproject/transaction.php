<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment – Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            background: #f8f9fa;
        }

        .pay-wrapper {
            max-width: 480px;
            margin: 80px auto;
            background: #fff;
            border-radius: 14px;
            padding: 40px 36px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .pay-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .pay-wrapper h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .pay-wrapper p {
            color: #666;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .amount-box {
            background: #f0f0ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 28px;
        }

        .amount-label {
            font-size: 13px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: 800;
            color: #4f46e5;
        }

        .btn-pay {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 9px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: 0.2s;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
        }

        .btn-pay:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .back-link {
            display: block;
            margin-top: 18px;
            font-size: 13px;
            color: #888;
        }

        .back-link a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php require_once "navbar.php"; ?>

    <div class="pay-wrapper">
        <div class="pay-icon">💳</div>
        <h2>Complete Your Payment</h2>
        <p>Review your order below and confirm your purchase.</p>

        <!-- Order Summary -->
        <div id="orderSummary" style="margin-bottom:22px;text-align:left;"></div>

        <div class="amount-box">
            <div class="amount-label">Total Amount</div>
            <div class="amount-value">₹<span id="amount">0</span></div>
        </div>

        <button class="btn-pay" onclick="confirmPayment()">Pay Now</button>

        <div class="back-link">
            Changed your mind? <a href="cart.php">← Back to Cart</a>
        </div>
    </div>

    <script>
        // Read items (from cart checkout OR buyNow)
        let items = JSON.parse(localStorage.getItem("buyItems")) || [];

        // Recompute grand total from items for accuracy
        let grandTotal = 0;
        items.forEach(function (item) {
            let p = Number(String(item.price).replace(/,/g, '')) || 0;
            let q = Number(item.quantity) || 0;
            grandTotal += p * q;
        });

        // Fallback: if no items stored, use buyTotal
        if (items.length === 0) {
            grandTotal = parseFloat(localStorage.getItem("buyTotal")) || 0;
        }

        // Render order summary table with cover image
        if (items.length > 0) {
            let rows = '';
            items.forEach(function (item, i) {
                let p = Number(String(item.price).replace(/,/g, '')) || 0;
                let q = Number(item.quantity) || 0;
                let total = p * q;
                let title = item.title || 'Book';
                let page = item.page || '#';
                let cover = item.cover || '';

                let coverHtml = cover
                    ? `<img src="${cover}" alt="cover"
                           style="width:40px;height:52px;object-fit:cover;border-radius:4px;
                                  border:1px solid #ddd;vertical-align:middle;margin-right:8px;"
                           onerror="this.style.display='none'">`
                    : '';

                rows += `<tr>
                    <td style="padding:10px 10px;">
                        ${coverHtml}
                        <a href="${page}" style="color:#4f46e5;font-weight:600;text-decoration:none;vertical-align:middle;">${title}</a>
                    </td>
                    <td style="padding:10px;text-align:center;">${q}</td>
                    <td style="padding:10px;text-align:right;">₹${p}</td>
                    <td style="padding:10px;text-align:right;">₹${total}</td>
                </tr>`;
            });
            document.getElementById('orderSummary').innerHTML = `
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f0f0ff;">
                            <th style="padding:10px;text-align:left;color:#4f46e5;">Book</th>
                            <th style="padding:10px;text-align:center;color:#4f46e5;">Qty</th>
                            <th style="padding:10px;text-align:right;color:#4f46e5;">Price</th>
                            <th style="padding:10px;text-align:right;color:#4f46e5;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>`;
        }

        document.getElementById("amount").innerText = grandTotal;

        function confirmPayment() {
            if (grandTotal <= 0) { alert("No amount to pay!"); return; }
            alert("✅ Payment of ₹" + grandTotal + " successful! Thank you for your purchase.");
            localStorage.removeItem("cart");
            localStorage.removeItem("buyTotal");
            localStorage.removeItem("buyItems");
            window.location.href = "dashboard.php";
        }
    </script>
</body>

</html>