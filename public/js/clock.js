// public/js/clock.js
function updateClock() {
    const now = new Date();

    // Format the date and time
    const formattedDate = formatDate(now);
    const formattedTime = formatTime(now);

    // Update the HTML elements
    document.getElementById("date").textContent = formattedDate;
    document.getElementById("time").textContent = formattedTime;
}

function formatDate(date) {
    const months = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];

    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();

    return `${day} ${month} ${year}`;
}

function formatTime(date) {
    const hours = date.getHours();
    const minutes = date.getMinutes();
    const seconds = date.getSeconds();

    return `${hours}:${addLeadingZero(minutes)}:${addLeadingZero(seconds)} WIB`;
}

function addLeadingZero(number) {
    return number < 10 ? "0" + number : number;
}

// Update the clock every second
setInterval(updateClock, 1000);

// Initial update
updateClock();
