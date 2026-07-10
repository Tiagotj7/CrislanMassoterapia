document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('input[name="csrf_token"]')?.value;

    const post = async (url, data) => {
        const res = await fetch(`${window.BASE_URL}${url}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(data)
        });
        return res.json();
    };

    // Atualizar status do agendamento
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async () => {
            const id = select.dataset.id;
            const status = select.value;
            const result = await post('/admin/agenda/status', { id, status, csrf_token: csrfToken });

            if (result.success) {
                showToast('Status atualizado com sucesso!', 'success');
            } else {
                showToast(result.message, 'danger');
            }
        });
    });

    // Excluir agendamento
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Deseja realmente excluir este agendamento?')) return;

            const id = btn.dataset.id;
            const result = await post('/admin/agenda/excluir', { id, csrf_token: csrfToken });

            if (result.success) {
                btn.closest('tr').remove();
                showToast('Agendamento excluído.', 'success');
            }
        });
    });

    // Bloquear período
    document.getElementById('block-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = new FormData(e.target);
        const result = await post('/admin/agenda/bloquear-data', Object.fromEntries(form));

        if (result.success) {
            showToast(result.message, 'success');
            setTimeout(() => window.location.reload(), 800);
        }
    });

    // Remover bloqueio
    document.querySelectorAll('.btn-unblock').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const result = await post('/admin/agenda/desbloquear-data', { id, csrf_token: csrfToken });

            if (result.success) {
                btn.closest('li').remove();
            }
        });
    });

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification alert alert-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});