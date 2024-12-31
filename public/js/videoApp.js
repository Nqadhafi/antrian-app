document.addEventListener("DOMContentLoaded", function () {
    const videos = json($videos); // Daftar video dari backend
    const videoElement = document.getElementById("dynamic-video");
    let currentIndex = 0;

    if (videos.length > 0) {
        // Fungsi untuk memutar video berikutnya
        const playNextVideo = () => {
            currentIndex = (currentIndex + 1) % videos.length; // Pergantian indeks secara melingkar
            const nextVideo = videos[currentIndex].path;
            videoElement.src = `/storage/${nextVideo}`;
            videoElement.play(); // Mulai pemutaran video berikutnya
        };

        // Event Listener saat video selesai
        videoElement.addEventListener("ended", playNextVideo);

        // Inisialisasi pertama
        videoElement.src = `/storage/${videos[currentIndex].path}`;
        videoElement.play();
    }
});
