window.addEventListener('load', () => {
    document.querySelectorAll('.animate-fadeInUp').forEach((element) => {
        element.style.opacity = '0'

        setTimeout(() => {
            element.style.opacity = '1'
        }, 100)
    })
})

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', (event) => {
        event.preventDefault()

        const target = document.querySelector(anchor.getAttribute('href'))

        if (target) {
            target.scrollIntoView({ behavior: 'smooth' })
        }
    })
})
