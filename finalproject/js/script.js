/* ── Helpers ─────────────────────────────────────────────── */

function getPrice() {
    let raw = document.getElementById("price").innerText.replace(/,/g, "");
    return parseFloat(raw) || 0;
}

function getBookTitle() {
    // Try dedicated hidden element first, then h1.book-main-title, then page title
    let hidden = document.getElementById("bookTitle");
    if (hidden && hidden.value) return hidden.value.trim();
    let h1 = document.querySelector(".book-main-title, h1");
    if (h1) return h1.innerText.trim();
    return document.title.split("–")[0].trim();
}

function getBookCover() {
    let hidden = document.getElementById("bookCoverPath");
    if (hidden && hidden.value) return hidden.value.trim();
    return "";
}

function getBookPage() {
    return window.location.pathname.split("/").pop();
}

// Save last visited book page so cart can link back
localStorage.setItem("lastBookPage", window.location.href);
localStorage.setItem("lastBookTitle", getBookTitle());

/* ── Add to Cart ─────────────────────────────────────────── */
function addToCart() {
    let quantity = document.getElementById("Quantity").value;
    if (!quantity) { alert("Please select a quantity."); return; }

    let price = getPrice();
    let qty = parseInt(quantity);
    let total = price * qty;
    let title = getBookTitle();
    let cover = getBookCover();

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart.push({
        title: title,
        cover: cover,
        page: getBookPage(),
        price: price,
        quantity: qty,
        total: total
    });

    localStorage.setItem("cart", JSON.stringify(cart));
    alert("✅ \"" + title + "\" added to cart!");
    window.location.href = "cart.php";
}

/* ── Buy Now ─────────────────────────────────────────────── */
function buyNow() {
    let quantity = document.getElementById("Quantity").value;
    if (!quantity) { alert("Please select a quantity."); return; }

    let price = getPrice();
    let qty = parseInt(quantity);
    let total = price * qty;
    let title = getBookTitle();
    let cover = getBookCover();

    localStorage.setItem("buyTotal", total);
    localStorage.setItem("buyItems", JSON.stringify([{
        title: title,
        cover: cover,
        page: getBookPage(),
        price: price,
        quantity: qty,
        total: total
    }]));

    window.location.href = "transaction.php";
}