// TẠO TOAST 
var toastList = document.querySelector('#toasts');

function createToast(status, message = 'Success') {
    let templateIcon = '';
    switch(status) {
        case 'success': 
            templateIcon = '<i class="fa-regular fa-circle-check"></i>';
            break;
        case 'warning': 
            templateIcon = '<i class="fa-solid fa-circle-exclamation"></i>';
            break;
        case 'error': 
            templateIcon = '<i class="fa-solid fa-triangle-exclamation"></i>';
            break;
    }
    var toast = document.createElement('div');
    toast.classList.add('toast');
    toast.classList.add('show');
    toast.classList.add(status);
    toast.innerHTML = ` ${templateIcon}
                        <span class="message">${message}</span>
                        <span class="countdown"></span>
                    `
    toastList.appendChild(toast);
    setTimeout(function() {
        toast.style.animation = 'slide_hide 2s ease forwards';
    }, 4000);

    setTimeout(function() {
        toast.remove();
    }, 10000);
}

window.onload = function() {
    var showToast = localStorage.getItem('showToast');
    var toastMessage = localStorage.getItem('toastMessage');
    if (showToast !== null) {
        createToast(showToast, toastMessage);
        localStorage.removeItem('showToast');
        localStorage.removeItem('toastMessage');
    }
};