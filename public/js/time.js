        // JavaScript untuk menampilkan waktu saat ini di navbar
        function updateTime() {
            const date = new Date();
            const time = date.toLocaleTimeString();
            document.getElementById('current-time').textContent = time;
        }
        setInterval(updateTime, 1000);