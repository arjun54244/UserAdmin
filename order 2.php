<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <?php
    session_start();
    include 'include/head.php'; ?>

    <?php

    $sizes_result = mysqli_query(
        $conn,
        "SELECT id, size_name, portrait_type, price FROM frame_sizes
     WHERE status = 1 ORDER BY sort_order ASC"
    );
    $frame_sizes = [];
    while ($row = mysqli_fetch_assoc($sizes_result)) {
        $frame_sizes[] = $row;
    }

    // Flash errors from session (set by submit_order.php on validation fail)
    
    $errors = $_SESSION['order_errors'] ?? [];
    $old = $_SESSION['order_old'] ?? [];
    unset($_SESSION['order_errors'], $_SESSION['order_old']);
    ?>

    <div class="page-wrap">
        <div class="page-grid  mt-20">

            <!-- ── LEFT INTRO ───────────────────────────────────────── -->
            <div class="intro-sticky">
                <div class="eyebrow">Start Creating</div>
                <div class="intro">
                    <h1>Transform Your<br><em><span
                                class="block text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-500 to-rose-500">

                                Precious Moments into

                            </span></em></h1>
                    <p class="sub">Upload any photo — a wedding, a birthday, a face you love — and our artisans
                        hand-craft it into a museum-quality illuminated portrait you'll treasure forever.</p>
                </div>

                <div class="perks">
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Free design preview</strong>
                            <span>See your art before we start crafting</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>100% satisfaction guarantee</strong>
                            <span>Not happy? We'll redo it, free of charge</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Pan-India secure shipping</strong>
                            <span>Tracked & insured delivery to your door</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Handwritten gift note</strong>
                            <span>Add a personal message at no extra cost</span>
                        </div>
                    </div>
                    <div class="perk">
                        <div class="perk-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="perk-text">
                            <strong>Ready in 7–14 business days</strong>
                            <span>Rush options available on request</span>
                        </div>
                    </div>
                </div>

                <div class="trust-strip">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>
                    <p><strong>Safe & private</strong><br>Your photos are stored securely and never shared with third
                        parties. Orders processed via encrypted connection.</p>
                </div>
            </div>

            <!-- ── RIGHT FORM ────────────────────────────────────────── -->
            <div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error" style="margin-bottom:20px">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <div>
                            <strong>Please fix the following:</strong>
                            <ul><?php foreach ($errors as $e): ?>
                                    <li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-card">

                    <!-- Step bar -->
                    <div class="step-bar" id="stepBar">
                        <div class="step-item active" data-step="1">
                            <div class="step-dot">1</div>
                            <span>Your details</span>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-dot">2</div>
                            <span>Size &amp; photo</span>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-dot">3</div>
                            <span>Delivery</span>
                        </div>
                    </div>

                    <div class="form-body">
                        <form id="orderForm" method="POST" action="#" enctype="multipart/form-data"
                            novalidate>

                            <!-- <input type="hidden" name="product_id" value="1"> -->
                            <input type="hidden" name="order_type" value="custom_photo">
                            <input type="hidden" name="frame_size_id" id="frameSizeId"
                                value="<?= htmlspecialchars($old['frame_size_id'] ?? '') ?>">

                            <!-- ════ STEP 1 — Your Details ════ -->
                            <div class="step-panel active" id="step1">

                                <div class="sec-head">Your details</div>

                                <div class="field">
                                    <label>Full name</label>
                                    <input type="text" name="customer_name" placeholder="Full Name"
                                        value="<?= htmlspecialchars($old['customer_name'] ?? '') ?>" autocomplete="name"
                                        required>
                                </div>

                                <div class="field">
                                    <label>
                                        WhatsApp number
                                        <span class="opt">+91 India</span>
                                    </label>
                                    <input type="tel" name="whatsapp_number" placeholder="WhatsApp Number"
                                        value="<?= htmlspecialchars($old['whatsapp_number'] ?? '') ?>"
                                        pattern="[6-9][0-9]{9}" maxlength="10" inputmode="numeric" required>
                                    <div class="hint">10-digit mobile number. We'll send order updates here.</div>
                                </div>

                                <div class="field">
                                    <label>Occasion <span class="opt">(optional)</span></label>
                                    <select id="occasionSel">
                                        <option value="">— Select occasion —</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Anniversary">Anniversary</option>
                                        <option value="Birthday">Birthday</option>
                                        <option value="Diwali / Festive">Diwali / Festive</option>
                                        <option value="Valentine's Day">Valentine's Day</option>
                                        <option value="Memorial">Memorial</option>
                                        <option value="Housewarming">Housewarming</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="btn-row">
                                    <button type="button" class="btn btn-primary btn-full" onclick="goStep(2)">
                                        Continue — choose size &amp; photo &rarr;
                                    </button>
                                </div>
                            </div>

                            <!-- ════ STEP 2 — Size & Photo ════ -->
                            <div class="step-panel" id="step2">

                                <div class="sec-head">Frame size</div>

                                <?php if (empty($frame_sizes)): ?>
                                    <p style="color:var(--text-3);font-size:13px;margin-bottom:20px">No frame sizes
                                        available right now. Please try again later.</p>
                                <?php else: ?>
                                    <div class="size-grid" id="sizeGrid">
                                        <?php foreach ($frame_sizes as $i => $fs):
                                            $popular = ($i === 1); // mark 2nd option as popular
                                            $sel = (isset($old['frame_size_id']) && $old['frame_size_id'] == $fs['id']);
                                            ?>
                                            <label class="size-card <?= $sel ? 'selected' : '' ?>" data-id="<?= $fs['id'] ?>"
                                                data-price="<?= $fs['price'] ?>"
                                                data-name="<?= htmlspecialchars($fs['size_name']) ?>">
                                                <?php if ($popular): ?>
                                                    <div class="badge">Most popular</div><?php endif; ?>
                                                <input type="radio" class="size-radio" name="_size_display"
                                                    value="<?= $fs['id'] ?>" <?= $sel ? 'checked' : '' ?>>
                                                <div class="sz-name"><?= htmlspecialchars($fs['size_name']) ?></div>
                                                <div class="sz-dim"><?= htmlspecialchars($fs['portrait_type'] ?? '') ?></div>
                                                <div class="sz-price">
                                                    <small>₹</small><?= number_format($fs['price']) ?>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="sec-head" style="margin-top:28px">Upload photo</div>

                                <div class="field">
                                    <label for="photoInput">Your photo</label>
                                    <label class="upload-zone" id="uploadZone">
                                        <input type="file" name="client_image" id="photoInput"
                                            accept="image/jpeg,image/png,image/webp">
                                        <div id="uploadDefault">
                                            <div class="up-icon">
                                                <svg viewBox="0 0 24 24">
                                                    <polyline points="16 16 12 12 8 16" />
                                                    <line x1="12" y1="12" x2="12" y2="21" />
                                                    <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3" />
                                                </svg>
                                            </div>
                                            <p>Click to upload or drag &amp; drop</p>
                                            <small>JPG, PNG, WebP — max 5 MB · High resolution recommended</small>
                                        </div>
                                        <div class="preview-wrap" id="uploadPreview">
                                            <img id="previewImg" src="" alt="Preview">
                                            <div class="preview-info">
                                                <span id="previewName"></span>
                                                <button type="button" onclick="clearUpload(event)">Remove</button>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="btn-row">
                                    <button type="button" class="btn btn-outline" onclick="goStep(1)">&larr;
                                        Back</button>
                                    <button type="button" class="btn btn-primary" onclick="goStep(3)">Continue —
                                        delivery &rarr;</button>
                                </div>
                            </div>

                            <!-- ════ STEP 3 — Delivery & Summary ════ -->
                            <div class="step-panel" id="step3">

                                <div class="sec-head">Delivery address</div>

                                <div class="field">
                                    <label>Full delivery address</label>
                                    <textarea name="delivery_address" rows="3"
                                        placeholder="Flat no., Building, Street, Area, City, State, PIN code"
                                        required><?= htmlspecialchars($old['delivery_address'] ?? '') ?></textarea>
                                </div>

                                <div class="sec-head">Gift note</div>

                                <div class="field">
                                    <label>Personal message <span class="opt">(optional)</span></label>
                                    <textarea name="notes" id="notesField" rows="2"
                                        placeholder="Write something heartfelt to include with the gift…"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                                    <div class="hint">We'll hand-write this on premium card stock.</div>
                                </div>

                                <!-- Order summary -->
                                <div class="sec-head">Order summary</div>

                                <div class="summary-box">
                                    <div class="sum-row">
                                        <span class="label">
                                            <svg viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <polyline points="21 15 16 10 5 21" />
                                            </svg>
                                            <span id="sumSize">— select a size —</span>
                                        </span>
                                        <span class="val" id="sumPrice">₹0</span>
                                    </div>
                                    <div class="sum-row">
                                        <span class="label">
                                            <svg viewBox="0 0 24 24">
                                                <rect x="1" y="3" width="15" height="13" />
                                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                                <circle cx="5.5" cy="18.5" r="2.5" />
                                                <circle cx="18.5" cy="18.5" r="2.5" />
                                            </svg>
                                            Shipping
                                        </span>
                                        <span class="val" style="color:var(--green)">Free</span>
                                    </div>
                                    <div class="sum-row">
                                        <span class="label">
                                            <svg viewBox="0 0 24 24">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Design preview
                                        </span>
                                        <span class="val" style="color:var(--green)">Included</span>
                                    </div>
                                    <div class="sum-row total">
                                        <span class="label">Total payable</span>
                                        <span class="val" id="sumTotal">₹0</span>
                                    </div>
                                </div>

                                <div class="btn-row">
                                    <button type="button" class="btn btn-outline" onclick="goStep(2)">&larr;
                                        Back</button>
                                    <button type="button" class="btn btn-primary" id="submitBtn" onclick="initiatePayment()" disabled>
                                        <span id="btnLabel">Place order — <span id="btnAmt">₹0</span></span>
                                        <span id="btnSpinner" style="display:none">Processing…</span>
                                    </button>
                                </div>

                                <p class="foot-note">
                                    By placing an order you agree to our <a href="#">terms</a> &amp; <a href="#">privacy
                                        policy</a>.<br>
                                    Payment collected after design approval · 100% secure
                                </p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            /* ── Frame sizes from PHP ─────────────── */
            var SIZES = <?= json_encode(array_values($frame_sizes)) ?>;

            var currentStep = 1;
            var selectedSize = null;

            /* ── Step navigation ──────────────────── */
            function goStep(n) {
                if (n > currentStep && !validateStep(currentStep)) return;
                currentStep = n;
                document.querySelectorAll('.step-panel').forEach(function (p) { p.classList.remove('active') });
                document.getElementById('step' + n).classList.add('active');
                document.querySelectorAll('.step-item').forEach(function (el) {
                    var sn = parseInt(el.dataset.step);
                    el.classList.toggle('active', sn === n);
                    el.classList.toggle('done', sn < n);
                    if (sn < n) el.querySelector('.step-dot').textContent = '✓';
                    else if (sn === n) el.querySelector('.step-dot').textContent = sn;
                    else el.querySelector('.step-dot').textContent = sn;
                });
            }
            window.goStep = goStep;

            /* ── Validation per step ─────────────── */
            function validateStep(n) {
                var ok = true;
                if (n === 1) {
                    var name = document.querySelector('[name="customer_name"]');
                    var wa = document.querySelector('[name="whatsapp_number"]');
                    if (!name.value.trim()) { shake(name); ok = false; }
                    if (!wa.value.trim() || !/^[6-9][0-9]{9}$/.test(wa.value.trim())) { shake(wa); ok = false; }
                }
                if (n === 2) {
                    if (!selectedSize) { shake(document.getElementById('sizeGrid')); showToast('Please select a frame size.'); ok = false; }
                    if (!document.getElementById('photoInput').files.length) { shake(document.getElementById('uploadZone')); showToast('Please upload your photo.'); ok = false; }
                }
                return ok;
            }

            function shake(el) {
                el.style.animation = 'none';
                el.offsetHeight;
                el.style.animation = 'shake .35s ease';
            }

            /* ── Size card selection ─────────────── */
            document.querySelectorAll('.size-card').forEach(function (card) {
                card.addEventListener('click', function () {
                    document.querySelectorAll('.size-card').forEach(function (c) { c.classList.remove('selected') });
                    card.classList.add('selected');
                    selectedSize = { id: card.dataset.id, price: parseFloat(card.dataset.price), name: card.dataset.name };
                    document.getElementById('frameSizeId').value = selectedSize.id;
                    updateSummary();
                });
            });

            /* Auto-select first if only one or if restored from session */
            var savedId = document.getElementById('frameSizeId').value;
            document.querySelectorAll('.size-card').forEach(function (card) {
                if (card.dataset.id === savedId || (!savedId && card.classList.contains('selected'))) {
                    selectedSize = { id: card.dataset.id, price: parseFloat(card.dataset.price), name: card.dataset.name };
                    updateSummary();
                }
            });
            if (!selectedSize && document.querySelectorAll('.size-card').length) {
                var first = document.querySelectorAll('.size-card')[1] || document.querySelectorAll('.size-card')[0];
                if (first) { first.click(); }
            }

            /* ── Summary update ──────────────────── */
            function fmt(n) { return '₹' + Math.round(n).toLocaleString('en-IN'); }
            function updateSummary() {
                if (!selectedSize) return;
                document.getElementById('sumSize').textContent = selectedSize.name;
                document.getElementById('sumPrice').textContent = fmt(selectedSize.price);
                document.getElementById('sumTotal').textContent = fmt(selectedSize.price);
                document.getElementById('btnAmt').textContent = fmt(selectedSize.price);
                document.getElementById('submitBtn').disabled = false;
            }

            /* ── Upload handling ─────────────────── */
            var zone = document.getElementById('uploadZone');
            var input = document.getElementById('photoInput');

            zone.addEventListener('dragover', function (e) { e.preventDefault(); zone.classList.add('drag') });
            zone.addEventListener('dragleave', function () { zone.classList.remove('drag') });
            zone.addEventListener('drop', function (e) {
                e.preventDefault(); zone.classList.remove('drag');
                if (e.dataTransfer.files.length) {
                    setFiles(e.dataTransfer.files);
                }
            });
            input.addEventListener('change', function () { if (input.files.length) setFiles(input.files) });

            function setFiles(files) {
                var file = files[0];
                if (file.size > 5 * 1024 * 1024) { showToast('File must be under 5 MB.'); return; }
                var dt = new DataTransfer(); dt.items.add(file);
                input.files = dt.files;
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('previewName').textContent = file.name;
                    document.getElementById('uploadDefault').style.display = 'none';
                    document.getElementById('uploadPreview').style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }

            window.clearUpload = function (e) {
                e.preventDefault(); e.stopPropagation();
                input.value = '';
                document.getElementById('uploadDefault').style.display = 'block';
                document.getElementById('uploadPreview').style.display = 'none';
            };

            /* ── Append occasion to notes ─────────── */
            function appendOccasion() {
                var occ = document.getElementById('occasionSel').value;
                var notes = document.getElementById('notesField');
                if (occ && notes.value.indexOf('[Occasion:') === -1) {
                    var existing = notes.value.trim();
                    notes.value = (existing ? existing + '\n' : '') + '[Occasion: ' + occ + ']';
                }
            }

            /* ── Show inline payment error ────────── */
            function showPaymentError(msg) {
                var existing = document.getElementById('paymentErrorBox');
                if (existing) existing.remove();
                var box = document.createElement('div');
                box.id = 'paymentErrorBox';
                box.className = 'alert alert-error';
                box.style.marginTop = '16px';
                box.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><div>' + msg + '</div>';
                document.querySelector('.btn-row:last-of-type').before(box);
                setBtnIdle();
            }

            function setBtnLoading() {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('btnLabel').style.display = 'none';
                document.getElementById('btnSpinner').style.display = 'inline';
            }

            function setBtnIdle() {
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('btnLabel').style.display = 'inline';
                document.getElementById('btnSpinner').style.display = 'none';
            }

            /* ── Main Razorpay flow ───────────────── */
            window.initiatePayment = function () {
                if (!validateStep(1) || !validateStep(2)) { goStep(1); return; }

                // validate delivery address
                var addr = document.querySelector('[name="delivery_address"]');
                if (!addr || !addr.value.trim()) {
                    shake(addr);
                    showToast('Please enter your delivery address.');
                    return;
                }

                appendOccasion();
                setBtnLoading();

                // Remove any old error box
                var old = document.getElementById('paymentErrorBox');
                if (old) old.remove();

                // Build FormData (includes file upload)
                var form = document.getElementById('orderForm');
                var fd = new FormData(form);

                // Step 1: Create Razorpay order on server
                fetch('create_order.php', {
                    method: 'POST',
                    body: fd
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.status !== 'success') {
                        var errMsg = (res.errors && res.errors.length) ? res.errors.join('<br>') : (res.message || 'Failed to create order. Please try again.');
                        showPaymentError(errMsg);
                        return;
                    }

                    // Step 2: Open Razorpay checkout popup
                    var options = {
                        key:               res.key_id,
                        amount:            res.amount,
                        currency:          res.currency,
                        name:              'Artistnik',
                        description:       res.product_title || 'Custom Glass Carved Portrait',
                        order_id:          res.razorpay_order_id,
                        prefill: {
                            name:    res.customer_name,
                            contact: res.whatsapp_number
                        },
                        theme: { color: '#C9933A' },
                        modal: {
                            ondismiss: function () {
                                // User closed popup — mark order as Cancelled
                                fetch('cancel_order.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ order_no: res.order_no })
                                }).catch(function(){});
                                showPaymentError('Payment was cancelled. You can try again below.');
                            }
                        },
                        handler: function (paymentData) {
                            // Step 3: Verify payment signature on server
                            setBtnLoading();
                            fetch('verify_payment.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    razorpay_payment_id: paymentData.razorpay_payment_id,
                                    razorpay_order_id:   paymentData.razorpay_order_id,
                                    razorpay_signature:  paymentData.razorpay_signature,
                                    order_no:            res.order_no
                                })
                            })
                            .then(function (r) { return r.json(); })
                            .then(function (vRes) {
                                if (vRes.status === 'success') {
                                    // Redirect to success page
                                    window.location.href = 'order_success.php?order=' + encodeURIComponent(res.order_no);
                                } else {
                                    showPaymentError('Payment received but verification failed: ' + (vRes.message || 'Unknown error.') + ' Please contact support with Order No: ' + res.order_no);
                                }
                            })
                            .catch(function () {
                                showPaymentError('Network error during verification. Please contact support with Order No: ' + res.order_no);
                            });
                        }
                    };

                    var rzp = new Razorpay(options);
                    rzp.on('payment.failed', function (resp) {
                        // Update order as Failed in DB
                        fetch('cancel_order.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ order_no: res.order_no, reason: 'failed' })
                        }).catch(function(){});
                        showPaymentError('Payment failed: ' + (resp.error.description || 'Unknown error.') + ' Please try again.');
                    });
                    rzp.open();
                })
                .catch(function () {
                    showPaymentError('Network error. Please check your connection and try again.');
                });
            };

            /* ── Toast ───────────────────────────── */
            function showToast(msg) {
                var t = document.createElement('div');
                t.textContent = msg;
                Object.assign(t.style, {
                    position: 'fixed', bottom: '24px', left: '50%', transform: 'translateX(-50%)',
                    background: '#201e1b', color: '#f2ede7', padding: '12px 20px',
                    borderRadius: '40px', fontSize: '13px', fontFamily: 'Inter,sans-serif',
                    border: '1px solid rgba(255,255,255,.1)', zIndex: '9999',
                    boxShadow: '0 4px 24px rgba(0,0,0,.4)', whiteSpace: 'nowrap'
                });
                document.body.appendChild(t);
                setTimeout(function () { t.remove() }, 3000);
            }

            /* ── Step bar clicks ─────────────────── */
            document.querySelectorAll('.step-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    var n = parseInt(el.dataset.step);
                    if (n < currentStep) goStep(n);
                });
            });

        })();
    </script>

    <!-- Razorpay Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0)
            }

            20% {
                transform: translateX(-6px)
            }

            40% {
                transform: translateX(6px)
            }

            60% {
                transform: translateX(-4px)
            }

            80% {
                transform: translateX(4px)
            }
        }
    </style>


    <?php include('include/footer.php'); ?>
    <?php include('include/foot.php'); ?>


</body>

</html>
