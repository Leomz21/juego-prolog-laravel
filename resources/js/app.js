document.addEventListener('DOMContentLoaded', () => {
    const result = document.querySelector('#resultado-aventura');

    if (result) {
        setTimeout(() => {
            result.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            });
        }, 250);
    }

    document.querySelectorAll('.rpg-form').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('.rpg-main-button');

            if (!button) {
                return;
            }

            const loadingLabel = button.dataset.loadingLabel || 'Procesando...';

            button.dataset.originalLabel = button.textContent.trim();
            button.textContent = loadingLabel;
            button.classList.add('is-loading');
        });
    });

    document.querySelectorAll('.rpg-team-form').forEach((form) => {
        const counter = form.querySelector('[data-team-counter]');
        const checkboxes = form.querySelectorAll('input[name="grupo[]"]');

        const updateCounter = () => {
            if (!counter) {
                return;
            }

            const selected = Array.from(checkboxes).filter((checkbox) => checkbox.checked).length;
            counter.textContent = `Equipo seleccionado: ${selected}`;
        };

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateCounter);
        });

        updateCounter();
    });

    document.querySelectorAll('[data-combat-form="individual"]').forEach((form) => {
        const characterInputs = form.querySelectorAll('input[name="personaje"]');
        const weaponInputs = form.querySelectorAll('input[name="arma"]');

        const updateWeapons = () => {
            const selectedCharacter = form.querySelector('input[name="personaje"]:checked');

            weaponInputs.forEach((weaponInput) => {
                const option = weaponInput.closest('[data-weapon-option]');
                const label = option?.querySelector('.rpg-choice-card');
                const state = option?.querySelector('.rpg-weapon-state');

                weaponInput.disabled = false;
                option?.classList.remove('is-unavailable', 'is-available');

                if (state) {
                    state.textContent = 'Disponible según inventario';
                }

                if (label) {
                    label.style.pointerEvents = 'auto';
                }
            });

            if (!selectedCharacter) {
                return;
            }

            const inventory = selectedCharacter.dataset.inventory
                ? selectedCharacter.dataset.inventory.split(',').map((item) => item.trim())
                : [];

            weaponInputs.forEach((weaponInput) => {
                const weaponId = weaponInput.dataset.weaponId;
                const option = weaponInput.closest('[data-weapon-option]');
                const label = option?.querySelector('.rpg-choice-card');
                const state = option?.querySelector('.rpg-weapon-state');
                const canUse = inventory.includes(weaponId);

                if (canUse) {
                    option?.classList.add('is-available');

                    if (state) {
                        state.textContent = 'Disponible para este héroe';
                    }

                    return;
                }

                weaponInput.checked = false;
                weaponInput.disabled = true;
                option?.classList.add('is-unavailable');

                if (state) {
                    state.textContent = 'No está en su inventario';
                }

                if (label) {
                    label.style.pointerEvents = 'none';
                }
            });
        };

        characterInputs.forEach((input) => {
            input.addEventListener('change', updateWeapons);
        });

        updateWeapons();
    });
});