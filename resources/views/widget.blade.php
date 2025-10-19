<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Feedback Widget</title>
    <style>
        body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;padding:16px;background:#f8fafc}
        .card{max-width:520px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 6px 24px rgba(0,0,0,.08);padding:20px}
        label{display:block;margin:10px 0 6px;font-weight:600}
        input,textarea{width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px}
        button{margin-top:12px;background:#0ea5e9;color:#fff;border:0;border-radius:10px;padding:10px 16px;cursor:pointer}
        .msg{margin-top:12px;padding:10px;border-radius:8px;display:none}
        .msg.err{background:#fee2e2;color:#991b1b}
        .msg.ok{background:#dcfce7;color:#166534}
    </style>
</head>
<body>
<div class="card">
    <h2>Обратная связь</h2>
    <form id="f" enctype="multipart/form-data">
        <label>Имя</label><input name="name" required>
        <label>Email</label><input name="email" type="email" required>
        <label>Телефон (E.164)</label><input name="phone" placeholder="+1234567890" required>
        <label>Тема</label><input name="subject" required>
        <label>Сообщение</label><textarea name="message" rows="4" required></textarea>
        <label>Файлы</label><input name="attachments[]" type="file" multiple>
        <button type="submit">Отправить</button>
    </form>
    <div id="ok" class="msg ok"></div>
    <div id="err" class="msg err"></div>
</div>

<script>
    const form = document.getElementById('f'), ok = document.getElementById('ok'), err = document.getElementById('err');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        ok.style.display = err.style.display = 'none';
        const fd = new FormData(form);

        try {
            const res = await fetch('/api/tickets', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',             // 👈 важно: просим JSON
                    'X-Requested-With': 'XMLHttpRequest',    // 👈 помогает Laravel понимать, что это AJAX
                },
                body: fd
            });

            const text = await res.text();               // читаем как текст
            let data = null;
            try { data = JSON.parse(text); } catch (e) {} // пробуем распарсить в JSON

            if (!res.ok) {
                const message = (data && (data.message || (data.errors && Object.values(data.errors).flat()[0])))
                    || text
                    || 'Ошибка отправки';
                throw new Error(message);
            }

            // OK
            const id = data?.data?.id ?? data?.id ?? '—';
            ok.textContent = 'Заявка отправлена! ID: ' + id;
            ok.style.display = 'block';
            form.reset();
        } catch (ex) {
            err.textContent = ex.message || 'Ошибка';
            err.style.display = 'block';
        }
    });
</script>
</body>
</html>
