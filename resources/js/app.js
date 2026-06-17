window.nexoLoadSweetAlert = () => {
    if (window.Swal) {
        return Promise.resolve(window.Swal)
    }

    if (window.nexoSweetAlertLoading) {
        return window.nexoSweetAlertLoading
    }

    window.nexoSweetAlertLoading = new Promise((resolve, reject) => {
        const script = document.createElement('script')
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11'
        script.async = true
        script.onload = () => resolve(window.Swal)
        script.onerror = reject
        document.head.appendChild(script)
    })

    return window.nexoSweetAlertLoading
}

window.nexoSweetConfirm = async (options, onConfirm) => {
    try {
        const Swal = await window.nexoLoadSweetAlert()
        const result = await Swal.fire({
            icon: options.icon || 'warning',
            title: options.title,
            text: options.text,
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText || 'Confirmar',
            cancelButtonText: options.cancelButtonText || 'Cancelar',
            confirmButtonColor: '#0B1D3A',
            cancelButtonColor: '#64748b',
        })

        if (result.isConfirmed) {
            onConfirm()
        }
    } catch (error) {
        if (window.confirm(options.text || options.title)) {
            onConfirm()
        }
    }
}

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-nexo-confirm]')

    if (! trigger || trigger.disabled || trigger.getAttribute('aria-disabled') === 'true') {
        return
    }

    const method = trigger.dataset.nexoMethod
    const componentElement = trigger.closest('[wire\\:id]')

    if (! method || ! componentElement || ! window.Livewire) {
        return
    }

    event.preventDefault()
    event.stopPropagation()

    window.nexoSweetConfirm(
        {
            title: trigger.dataset.nexoTitle,
            text: trigger.dataset.nexoText,
            confirmButtonText: trigger.dataset.nexoConfirmText,
        },
        () => {
            const args = JSON.parse(trigger.dataset.nexoArgs || '[]')

            window.Livewire.find(componentElement.getAttribute('wire:id')).call(method, ...args)
        },
    )
})
