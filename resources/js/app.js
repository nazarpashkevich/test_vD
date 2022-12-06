import './bootstrap';
import jQuery from "jquery";

Object.assign(window, {$: jQuery, jQuery})


let cinemaService = {
    EVENTS_ACTION: '/events',
    PLACES_ACTION: '/events/{event}/vacancies',
    RESERVE_ACTION: '/reservation/reserve',

    init: function () {
        this.initFormTrigger();
        this.initCinemaSessionItems();
        this.initBookingForm();
    },
    initCinemaSessionItems: function () {
        let items = LocalStorageHelper.get('cinema_session_items');
        if (items === null) {
            RequestHelper.get(this.EVENTS_ACTION, {}, function (result) {
                let data = JSON.parse(result);
                if (data.status === 'success' && data.places.length > 0) {
                    LocalStorageHelper.set('cinema_session_items', data.places);
                    cinemaService.showCinemaSessionItems(data.places);
                }
            });
        } else {
            cinemaService.showCinemaSessionItems(items);
        }
    },
    showCinemaSessionItems: function (data) {
        let options = '';
        for (let i = 0; i < data.length; i++) {
            let item = data[i];
            options += '<option value="' + item.id + '">' + item.name + '</option>';
        }
        let select = document.querySelector('select[name="item_id"]');
        if (select !== null) {
            select.innerHTML = options;
        }
    },
    initFormTrigger: function () {
        let form = document.getElementById('date-form');
        if (form !== null) {
            form.addEventListener("submit", this.dateFormSubmitAction, false);  //Modern browsers
        }
    },
    dateFormSubmitAction: function (e) {
        e.preventDefault();
        document.getElementById('booking-form').classList.add('hidden');
        let formData = RequestHelper.serializeForm(e.target);
        // check data if local storage, if not have - load by API
        RequestHelper.get(
            cinemaService.PLACES_ACTION.replace('{event}', formData.get('item_id')),
            formData,
            function (result) {
                let data = JSON.parse(result);
                if (data.status == 'success' && data.places.length > 0) {
                    cinemaService.showPlaces(data.places, data.readonly ?? false);
                }
            });
    },
    showPlaces: function (data, readonly = false) {
        let bookingItems = '', bookingForm = document.getElementById('booking-form');
        for (let i = 0; i < data.length; i++) {
            let objClass = 'booking-item' + (data[i] === true ? '' : ' booked');
            bookingItems += '<div class="' + objClass + '"><label for=""></label><input type="radio"' +
                ' name="booking_item_id" value="' + i + '"' + (data[i] === true ? '' : 'disabled') + '/></div>';
        }
        document.querySelector('.booking-map').innerHTML = bookingItems;

        if (readonly) {
            bookingForm.classList.add('readonly')
        } else {
            bookingForm.classList.remove('readonly')
        }
        bookingForm.classList.remove('hidden');
        this.initBookingItems();
    },
    initBookingItems: function () {
        let items = document.querySelectorAll('.booking-item input');
        for (let j = 0; j < items.length; j++) {
            items[j].onclick = function (e) {
                for (let i = 0; i < items.length; i++) {
                    items[i].parentNode.classList.remove('active');
                }
                e.target.parentNode.classList.add('active');
            }
        }
    },
    initBookingForm: function () {
        let form = document.getElementById('booking-form');
        if (form !== null) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                let formData = RequestHelper.serializeForm(document.getElementById('date-form')),
                placeInput = document.querySelector('input[name="booking_item_id"]:checked');
                if (placeInput !== null) {
                    formData.append('booking_item_id', placeInput.value);
                    RequestHelper.post(cinemaService.RESERVE_ACTION, formData, function () {
                        RequestHelper.showAlert('Забронировано');
                        placeInput.readonly = true;
                        placeInput.parentNode.classList.add('booked');
                    });
                }
            });
        }
    }
};

let RequestHelper = {
    get: function (url, data, callback = null) {
        let xhttp = new XMLHttpRequest();
        xhttp.open("GET", url + '?' + new URLSearchParams(data).toString());
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
        if (callback !== null && this.isFunction(callback)) {
            xhttp.onload = () => {
                if (xhttp.status === 200) {
                    callback(xhttp.responseText)
                }
            }
        }
    },
    post: function (url, data, callback = null) {
        let xhttp = new XMLHttpRequest();
        xhttp.open("POST", url);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(new URLSearchParams(data).toString());
        if (callback !== null && this.isFunction(callback)) {
            xhttp.onload = () => {
                if (xhttp.status === 200) {
                    callback(xhttp.responseText)
                }
            }
        }
    },
    isFunction: function (f) {
        return f && {}.toString.call(f) === '[object Function]';
    },
    serializeForm: function (form) {
        return new FormData(form);
    },
    showAlert: function (message) {
        let alertContent = document.createElement('div');
        alertContent.classList.add('alert');
        alertContent.classList.add('alert-success');
        alertContent.innerHTML = message;
        document.querySelector('.main-content-container').prepend(alertContent);
        setTimeout(function () {
            alertContent.parentElement.removeChild(alertContent);
        }, 5000);
    }
};

let LocalStorageHelper = {
    EXPIRED: 1000 * 60 * 60 * 2,
    get: function (key) {
        const itemStr = localStorage.getItem(key)
        // if the item doesn't exist, return null
        if (!itemStr) {
            return null
        }
        const item = JSON.parse(itemStr)
        const now = new Date()
        // compare the expiry time of the item with the current time
        if (now.getTime() > item.expiry) {
            // If the item is expired, delete the item from storage
            // and return null
            localStorage.removeItem(key)
            return null
        }
        return item.value
    },
    set: function (key, value) {
        const now = new Date()
        const item = {
            value: value,
            expiry: now.getTime() + this.EXPIRED,
        }

        localStorage.setItem(key, JSON.stringify(item))
    }
};

window.onload = function () {
    cinemaService.init();
};


