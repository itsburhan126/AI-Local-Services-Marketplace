<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Center</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; background:#F8FAFC; margin:0; }
    .container { max-width: 880px; margin: 40px auto; padding: 0 20px; }
    .card { background:#fff; border-radius:16px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); padding:24px; }
    .title { font-size:22px; font-weight:700; color:#1E293B; margin:0; }
    .subtitle { color:#64748B; font-size:14px; margin-top:8px; }
    .row { display:flex; gap:16px; }
    .field { margin-top:16px; }
    .label { font-weight:700; color:#1E293B; font-size:14px; margin-bottom:8px; display:block; }
    input[type="text"], select, textarea {
      width:100%; padding:12px; border:1px solid #E2E8F0; border-radius:12px; background:#fff; font-size:14px;
    }
    textarea { min-height:140px; resize:vertical; }
    .btn { background:#1E293B; color:#fff; border:none; padding:12px 16px; border-radius:12px; font-weight:700; cursor:pointer; width:100%; }
    .header { display:flex; align-items:center; gap:12px; }
    .icon { width:40px; height:40px; border-radius:50%; background:#EEF2FF; display:flex; align-items:center; justify-content:center; color:#6366F1; }
    .footer { text-align:center; color:#94A3B8; font-size:12px; margin-top:16px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="header">
        <div class="icon">🎧</div>
        <div>
          <h1 class="title">Support Center</h1>
          <p class="subtitle">Dummy UI page for customer/provider support requests</p>
        </div>
      </div>

      <div class="field">
        <label class="label">Issue Type</label>
        <select>
          <option>Payment</option>
          <option selected>Delivery</option>
          <option>Communication</option>
          <option>App Bug</option>
          <option>Other</option>
        </select>
      </div>

      <div class="row">
        <div class="field" style="flex:1">
          <label class="label">Order ID (optional)</label>
          <input type="text" placeholder="e.g., 12345" />
        </div>
        <div class="field" style="flex:1">
          <label class="label">Contact Email (optional)</label>
          <input type="text" placeholder="you@example.com" />
        </div>
      </div>

      <div class="field">
        <label class="label">Describe the problem</label>
        <textarea placeholder="Write details so we can help faster..."></textarea>
      </div>

      <div class="field">
        <button class="btn">Submit</button>
      </div>

      <div class="footer">This is a dummy UI only, no backend handling yet.</div>
    </div>
  </div>
</body>
</html>
