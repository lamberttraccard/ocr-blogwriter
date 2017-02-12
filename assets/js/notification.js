let notifications = document.querySelectorAll('.notification');

export default function init() {
    notifications.forEach(notification => {
        let deleteEl = notification.querySelector('.delete');
        deleteEl.addEventListener('click', (e) => {
                e.preventDefault();
                notification.remove();
            }
        )
    });
}
