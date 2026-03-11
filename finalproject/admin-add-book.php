<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin-login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Book – Admin</title>
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

        .adm-header {
            background: linear-gradient(135deg, #1a103a, #2d1b69);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1.5px solid #6c3ce1;
        }

        .adm-brand {
            font-size: 18px;
            font-weight: 800;
            color: #c084fc;
        }

        .adm-brand span {
            color: #fff;
        }

        .btn-back {
            color: #a78bfa;
            border: 1.5px solid #6c3ce1;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: .2s;
            background: transparent;
        }

        .btn-back:hover {
            background: rgba(108, 60, 225, .2);
            color: #c084fc;
        }

        /* ── Step bar ── */
        .step-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin: 32px auto;
            max-width: 440px;
        }

        .st-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .st-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 55%;
            width: 90%;
            height: 2px;
            background: #3b2270;
            z-index: 0;
        }

        .st-item.done::after {
            background: #7c3aed;
        }

        .st-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            border: 2px solid #3b2270;
            background: #1c1040;
            z-index: 1;
            transition: .3s;
        }

        .st-item.active .st-circle {
            border-color: #a855f7;
            background: #4c1d95;
            color: #e9d5ff;
        }

        .st-item.done .st-circle {
            border-color: #7c3aed;
            background: #7c3aed;
            color: #fff;
        }

        .st-label {
            font-size: 12px;
            color: #7c6fb0;
            margin-top: 6px;
            font-weight: 600;
        }

        .st-item.active .st-label {
            color: #c084fc;
        }

        /* ── Form card ── */
        .fcard {
            max-width: 780px;
            margin: 0 auto 60px;
            background: linear-gradient(160deg, #1c1040, #13082e);
            border: 1.5px solid #3b2270;
            border-radius: 18px;
            padding: 38px 42px;
        }

        .fcard h2 {
            font-size: 20px;
            font-weight: 800;
            color: #c084fc;
            margin-bottom: 26px;
        }

        .fg {
            margin-bottom: 20px;
        }

        .fg label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #a78bfa;
            margin-bottom: 7px;
            letter-spacing: .5px;
        }

        .fg input[type=text],
        .fg input[type=number],
        .fg textarea,
        .fg select {
            width: 100%;
            background: rgba(255, 255, 255, .05);
            border: 1.5px solid #3b2270;
            border-radius: 10px;
            color: #e2d9f3;
            padding: 11px 15px;
            font-size: 14px;
            outline: none;
            transition: border .2s;
            font-family: inherit;
        }

        .fg input:focus,
        .fg textarea:focus {
            border-color: #7c3aed;
            background: rgba(124, 58, 237, .07);
        }

        .fg textarea {
            resize: vertical;
            min-height: 110px;
        }

        .fg input[type=file] {
            width: 100%;
            background: rgba(255, 255, 255, .03);
            border: 1.5px dashed #5b21b6;
            border-radius: 10px;
            color: #c084fc;
            padding: 13px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: border .2s;
        }

        .fg input[type=file]:hover {
            border-color: #a855f7;
        }

        .hint {
            font-size: 11px;
            color: #6b6b8d;
            margin-top: 5px;
        }

        /* ── Cover preview ── */
        .cover-row {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .cover-box {
            width: 150px;
            height: 200px;
            border: 2px dashed #5b21b6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            font-size: 12px;
            text-align: center;
            flex-shrink: 0;
            overflow: hidden;
            background: rgba(91, 33, 182, .06);
        }

        .cover-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .cover-fields {
            flex: 1;
        }

        /* ── Thumb preview ── */
        .thumb-prev {
            margin-top: 8px;
            max-height: 110px;
            border-radius: 8px;
            object-fit: cover;
            display: none;
        }

        /* ── 3 col ── */
        .three-col {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        /* ── Word counter ── */
        .wc {
            font-size: 12px;
            color: #7c6fb0;
            margin-top: 5px;
        }

        .wc.ok {
            color: #4ade80;
        }

        .wc.warn {
            color: #f97316;
        }

        .wc.err {
            color: #f87171;
        }

        /* ── Author inputs ── */
        #authorInputs {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-top: 9px;
        }

        /* ── Fixed badge ── */
        .fixed-badge {
            font-size: 11px;
            background: rgba(124, 58, 237, .2);
            color: #a78bfa;
            border-radius: 5px;
            padding: 2px 7px;
            margin-left: 5px;
            font-weight: 600;
        }

        .sec-hr {
            border: none;
            border-top: 1px solid #2d1b69;
            margin: 22px 0;
        }

        .sub {
            font-size: 15px;
            font-weight: 700;
            color: #e9d5ff;
            margin: 18px 0 14px;
            border-left: 3px solid #7c3aed;
            padding-left: 10px;
        }

        /* ── Buttons ── */
        .btn-next {
            background: linear-gradient(135deg, #6c3ce1, #a855f7);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 0;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            margin-top: 8px;
            transition: .2s;
        }

        .btn-next:hover {
            opacity: .85;
        }

        .btn-prev {
            background: transparent;
            color: #a78bfa;
            border: 1.5px solid #6c3ce1;
            border-radius: 10px;
            padding: 12px 0;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 8px;
            transition: .2s;
        }

        .btn-prev:hover {
            background: rgba(108, 60, 225, .2);
        }

        .btn-pub {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 0;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            margin-top: 8px;
            transition: .2s;
        }

        .btn-pub:hover {
            opacity: .85;
        }

        /* ── Error msgs ── */
        .em {
            color: #f87171;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .em.show {
            display: block;
        }
    </style>
</head>

<body>

    <div class="adm-header">
        <div class="adm-brand">📚 <span>Book Inventory</span> – Add New Book</div>
        <a href="admin-dashboard.php" class="btn-back">← Back</a>
    </div>

    <!-- Step bar -->
    <div class="step-bar">
        <div class="st-item active" id="si1">
            <div class="st-circle">1</div>
            <div class="st-label">Cover Page</div>
        </div>
        <div class="st-item" id="si2">
            <div class="st-circle">2</div>
            <div class="st-label">Book Details</div>
        </div>
    </div>

    <!-- ══════════════════ STEP 1 ══════════════════ -->
    <div id="step1" class="fcard">
        <h2>📖 Step 1 — Cover Page Details</h2>

        <div class="fg">
            <label>Cover Image <span class="hint" style="display:inline">(300 × 400 px recommended)</span></label>
            <input type="file" id="s1cover" accept="image/*" onchange="previewCover(this)" />
            <div class="em" id="e_cov">Please upload a cover image.</div>
        </div>

        <div class="cover-row">
            <div class="cover-box">
                <img id="covPrev" src="" alt="preview">
                <span id="covPh">300×400<br>Preview</span>
            </div>
            <div class="cover-fields">
                <div class="fg">
                    <label>Book Title</label>
                    <input type="text" id="s1title" placeholder="e.g. Artificial Intelligence and Machine Learning" />
                    <div class="em" id="e_ttl">Please enter the book title.</div>
                </div>
                <div class="fg">
                    <label>First Author Name</label>
                    <input type="text" id="s1author" placeholder="e.g. Dr. K. Nagappan" />
                    <div class="em" id="e_aut">Please enter the first author name.</div>
                </div>
            </div>
        </div>

        <button class="btn-next" onclick="goStep2()">Next →</button>
    </div>

    <!-- ══════════════════ STEP 2 ══════════════════ -->
    <div id="step2" class="fcard" style="display:none;">
        <h2>📄 Step 2 — Book Page Details</h2>

        <form id="pubForm" method="POST" action="admin-publish.php" enctype="multipart/form-data">

            <!-- Hidden carry-overs from Step 1 -->
            <input type="hidden" name="first_author" id="h_fauth" />
            <input type="hidden" name="title_hidden" id="h_ttl" />

            <!-- Cover upload (actual file) hidden -->
            <div style="display:none;">
                <input type="file" name="cover_img" id="s2cov" accept="image/*" />
            </div>

            <!-- 2.1 Page images -->
            <div class="sub">🖼 Book Page Images</div>
            <div class="three-col">
                <div class="fg">
                    <label>Page Image 1 (p1)</label>
                    <input type="file" name="p1_img" accept="image/*" onchange="thumb(this,'pv1')" required />
                    <img id="pv1" class="thumb-prev" src="" alt="p1">
                    <div class="hint">Saved to image/bookimg/ as {name}-p1</div>
                </div>
                <div class="fg">
                    <label>Page Image 2 (p2)</label>
                    <input type="file" name="p2_img" accept="image/*" onchange="thumb(this,'pv2')" required />
                    <img id="pv2" class="thumb-prev" src="" alt="p2">
                    <div class="hint">Saved to image/bookimg/ as {name}-p2</div>
                </div>
                <div class="fg">
                    <label>Page Image 3 (p3)</label>
                    <input type="file" name="p3_img" accept="image/*" onchange="thumb(this,'pv3')" required />
                    <img id="pv3" class="thumb-prev" src="" alt="p3">
                    <div class="hint">Saved to image/bookimg/ as {name}-p3</div>
                </div>
            </div>

            <hr class="sec-hr">

            <!-- 2.2 Book title -->
            <div class="sub">📚 Book Information</div>
            <div class="fg">
                <label>Book Title</label>
                <input type="text" name="book_title" id="s2title" placeholder="Book title" required />
            </div>

            <!-- 2.3 Authors -->
            <div class="fg">
                <label>Number of Authors</label>
                <input type="number" id="numAuth" min="1" max="10" placeholder="e.g. 3"
                    oninput="renderAuthors(this.value)" />
            </div>
            <div id="authorInputs"></div>

            <hr class="sec-hr">

            <!-- 2.4 Price -->
            <div class="fg">
                <label>Price (₹)</label>
                <input type="number" name="price" min="1" step="0.01" placeholder="e.g. 1351" required />
            </div>

            <!-- 2.5 Summary -->
            <div class="fg">
                <label>Summary
                    <span class="hint" style="display:inline">(min 30 words – max 100 words)</span>
                </label>
                <textarea name="summary" id="sumField" rows="5" placeholder="Write a short description of the book…"
                    oninput="countWords(this)" required></textarea>
                <div class="wc err" id="wcDisplay">0 words (need ≥ 30)</div>
                <div class="em" id="e_sum"></div>
            </div>

            <hr class="sec-hr">

            <!-- 2.6 Product Details -->
            <div class="sub">📦 Product Details</div>
            <div class="fg">
                <label>Publisher <span class="fixed-badge">pre-filled</span></label>
                <input type="text" name="publisher" value="Pencil Bitz Publications" />
            </div>
            <div class="fg">
                <label>Edition <span class="fixed-badge">pre-filled</span></label>
                <input type="text" name="editor" value="1st Edition" />
            </div>
            <div class="fg">
                <label>Language <span class="fixed-badge">pre-filled</span></label>
                <input type="text" name="language" value="English" />
            </div>
            <div class="fg">
                <label>Number of Pages</label>
                <input type="number" name="pages" min="1" placeholder="e.g. 326" required />
            </div>

            <button type="button" class="btn-prev" onclick="goStep1()">← Previous</button>
            <button type="button" class="btn-pub" onclick="publish()">🚀 Publish Book</button>
        </form>
    </div>

    <script>
        /* ── 1. Cover file memory ── */
        let coverFile = null;

        function previewCover(inp) {
            coverFile = inp.files[0];
            if (!coverFile) return;
            const img = document.getElementById('covPrev');
            const ph = document.getElementById('covPh');
            img.src = URL.createObjectURL(coverFile);
            img.style.display = 'block'; ph.style.display = 'none';
            document.getElementById('e_cov').classList.remove('show');
        }

        function thumb(inp, id) {
            const f = inp.files[0]; if (!f) return;
            const img = document.getElementById(id);
            img.src = URL.createObjectURL(f); img.style.display = 'block';
        }

        /* ── 2. Word counter ── */
        function countWords(ta) {
            const w = ta.value.trim().split(/\s+/).filter(x => x).length;
            const el = document.getElementById('wcDisplay');
            el.textContent = w + ' words';
            el.className = 'wc';
            if (w < 30) { el.classList.add('err'); el.textContent += ` (need ≥ 30)`; }
            else if (w > 100) { el.classList.add('warn'); el.textContent += ` (max 100)`; }
            else { el.classList.add('ok'); el.textContent += ' ✓'; }
        }

        /* ── 3. Dynamic author inputs ── */
        function renderAuthors(n) {
            const c = document.getElementById('authorInputs');
            c.innerHTML = '';
            n = parseInt(n) || 0;
            for (let i = 1; i <= n; i++) {
                const inp = document.createElement('input');
                inp.type = 'text'; inp.name = 'authors[]';
                inp.placeholder = 'Author ' + i + ' name';
                inp.required = true;
                inp.style.cssText = 'width:100%;background:rgba(255,255,255,.05);border:1.5px solid #3b2270;border-radius:10px;color:#e2d9f3;padding:11px 15px;font-size:14px;outline:none;font-family:inherit;margin-bottom:8px;';
                c.appendChild(inp);
            }
        }

        /* ── 4. Step navigation ── */
        function goStep2() {
            let ok = true;
            if (!coverFile) { document.getElementById('e_cov').classList.add('show'); ok = false; }
            else { document.getElementById('e_cov').classList.remove('show'); }
            const ttl = document.getElementById('s1title').value.trim();
            const aut = document.getElementById('s1author').value.trim();
            if (!ttl) { document.getElementById('e_ttl').classList.add('show'); ok = false; }
            else { document.getElementById('e_ttl').classList.remove('show'); }
            if (!aut) { document.getElementById('e_aut').classList.add('show'); ok = false; }
            else { document.getElementById('e_aut').classList.remove('show'); }
            if (!ok) return;

            // pass data to step 2
            document.getElementById('h_ttl').value = ttl;
            document.getElementById('h_fauth').value = aut;
            document.getElementById('s2title').value = ttl;

            // clone cover file to hidden input
            const dt = new DataTransfer(); dt.items.add(coverFile);
            document.getElementById('s2cov').files = dt.files;

            // pre-seed 1 author if empty
            if (!document.getElementById('numAuth').value) {
                document.getElementById('numAuth').value = 1;
                renderAuthors(1);
            }
            const first = document.querySelector('#authorInputs input');
            if (first) first.value = aut;

            // show step 2
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = '';
            document.getElementById('si1').classList.remove('active');
            document.getElementById('si1').classList.add('done');
            document.getElementById('si1').querySelector('.st-circle').textContent = '✓';
            document.getElementById('si2').classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goStep1() {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = '';
            document.getElementById('si2').classList.remove('active');
            document.getElementById('si1').classList.remove('done');
            document.getElementById('si1').classList.add('active');
            document.getElementById('si1').querySelector('.st-circle').textContent = '1';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        /* ── 5. Publish ── */
        function publish() {
            const sum = document.getElementById('sumField').value.trim();
            const w = sum.split(/\s+/).filter(x => x).length;
            const e = document.getElementById('e_sum');
            if (w < 30) { e.textContent = 'Summary needs at least 30 words (currently ' + w + ').'; e.classList.add('show'); return; }
            if (w > 100) { e.textContent = 'Summary must be 100 words or fewer (currently ' + w + ').'; e.classList.add('show'); return; }
            e.classList.remove('show');
            document.getElementById('pubForm').submit();
        }
    </script>
</body>

</html>