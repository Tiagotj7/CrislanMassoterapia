document.addEventListener('DOMContentLoaded', () => {
    const card = document.getElementById('booking-card');
    if (!card) return;

    const steps = card.querySelectorAll('.booking-step');
    const goToStep = (n) => {
        steps.forEach(s => s.classList.toggle('d-none', s.dataset.step != n));
    };

    // Etapa 1 -> 2
    document.getElementById('btn-step-1')?.addEventListener('click', () => {
        const selected = card.querySelector('input[name="service_id"]:checked');
        if (!selected) {
            alert('Selecione um serviço para continuar.');
            return;
        }
        document.getElementById('input-service-id').value = selected.value;
        goToStep(2);
    });

    // Botões "voltar"
    card.querySelectorAll('[data-back]').forEach(btn => {
        btn.addEventListener('click', () => goToStep(btn.dataset.back));
    });

    // Data selecionada -> buscar horários via AJAX
    document.getElementById('appointment-date')?.addEventListener('change', async (e) => {
        const date = e.target.value;
        const serviceId = document.getElementById('input-service-id').value;
        const container = document.getElementById('slots-container');
        container.innerHTML = '<p class="text-muted">Carregando horários...</p>';

        try {
            const res = await fetch(`${window.BASE_URL}/agendamento/horarios-disponiveis`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `date=${date}&service_id=${serviceId}`
            });
            const data = await res.json();

            container.innerHTML = '';
            if (!data.success || data.slots.length === 0) {
                container.innerHTML = '<p class="text-muted">Nenhum horário disponível nesta data.</p>';
                return;
            }

            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-primary btn-sm slot-btn';
                btn.textContent = slot;
                btn.addEventListener('click', () => {
                    document.getElementById('input-date').value = date;
                    document.getElementById('input-time').value = slot;
                    goToStep(3);
                });
                container.appendChild(btn);
            });
        } catch (err) {
            container.innerHTML = '<p class="text-danger">Erro ao carregar horários. Tente novamente.</p>';
        }
    });

    // Envio do formulário final
    document.getElementById('booking-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('btn-submit');
        const feedback = document.getElementById('form-feedback');

        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.spinner-border').classList.remove('d-none');

        try {
            const res = await fetch(`${window.BASE_URL}/agendamento/salvar`, {
                method: 'POST',
                body: new FormData(form)
            });
            const data = await res.json();

            feedback.classList.remove('d-none', 'alert-success', 'alert-danger');
            feedback.classList.add(data.success ? 'alert-success' : 'alert-danger');
            feedback.textContent = data.message;

            if (data.success) {
                setTimeout(() => window.location.href = data.redirect, 800);
            } else {
                btn.disabled = false;
            }
        } catch (err) {
            feedback.classList.remove('d-none');
            feedback.classList.add('alert-danger');
            feedback.textContent = 'Erro de conexão. Tente novamente.';
            btn.disabled = false;
        } finally {
            btn.querySelector('.btn-text').classList.remove('d-none');
            btn.querySelector('.spinner-border').classList.add('d-none');
        }
    });
});