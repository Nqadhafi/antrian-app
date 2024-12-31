let isPlaying = false; // Variabel global untuk melacak status pemutaran audio

/**
 * Fungsi untuk memutar audio queue secara berurutan.
 * @param {Array} queue - Daftar nama file audio yang akan diputar.
 */
function playAudioQueue(queue) {
    if (isPlaying) {
        console.warn('Pemutaran audio sedang berlangsung. Menunggu hingga selesai.');
        return;
    }

    if (!Array.isArray(queue) || queue.length === 0) {
        console.error('Queue audio kosong atau bukan array.');
        return;
    }

    isPlaying = true; // Tandai bahwa pemutaran sedang berlangsung

    // Map queue ke dalam objek Audio
    const audioFiles = queue.map(file => {
        const audio = new Audio(`/assets/sounds/${file}`);
        audio.onerror = () => console.error(`Error memuat file audio: ${file}`);
        return audio;
    });

    // Fungsi rekursif untuk memutar audio satu per satu
    const playNext = (index) => {
        if (index < audioFiles.length) {
            console.log(`Memutar: ${queue[index]}`);
            const audio = audioFiles[index];

            audio.play().catch(error => {
                console.error(`Error saat memutar: ${queue[index]}`, error);
                playNext(index + 1); // Lewati ke audio berikutnya jika terjadi error
            });

            audio.addEventListener('ended', () => {
                console.log(`Selesai memutar: ${queue[index]}`);
                playNext(index + 1); // Lanjut ke audio berikutnya
            });
        } else {
            console.log('Semua audio selesai diputar.');
            isPlaying = false; // Reset status setelah selesai
        }
    };

    playNext(0);
}


/**
 * Fungsi untuk memecah nomor menjadi bagian audio yang sesuai.
 * @param {number} number - Nomor yang akan diubah menjadi bagian audio.
 * @returns {Array} parts - Daftar file audio yang mewakili nomor tersebut.
 */
function getNumberParts(number) {
    const parts = [];

    if (number === 10) {
        parts.push('10.mp3');
    } else if (number === 11) {
        parts.push('11.mp3');
    } else if (number >= 12 && number <= 19) {
        const ones = number % 10;
        parts.push(`${ones}.mp3`);
        parts.push('belas.mp3');
    } else if (number === 100) {
        parts.push('100.mp3');
    } else {
        const hundreds = Math.floor(number / 100);
        const tens = Math.floor((number % 100) / 10);
        const ones = number % 10;

        if (hundreds > 0) {
            parts.push(`${hundreds}.mp3`);
            parts.push('ratus.mp3');
        }

        if (tens > 0) {
            if (tens === 1 && ones > 0) {
                parts.push(`${10 + ones}.mp3`);
                return parts;
            } else {
                parts.push(`${tens}.mp3`);
                parts.push('puluh.mp3');
            }
        }

        if (ones > 0) {
            parts.push(`${ones}.mp3`);
        }
    }

    return parts;
}

/**
 * Fungsi untuk membentuk queue audio lengkap berdasarkan tipe dan nomor antrian.
 * @param {string} type - Tipe antrian (misalnya, 'A', 'B', 'C', dll.).
 * @param {number} number - Nomor antrian.
 * @returns {Array} queue - Daftar file audio yang harus diputar.
 */
function generateAudioQueue(type, number) {
    const queue = [];

    queue.push('Airport_Bell.mp3'); // Suara notifikasi awal
    queue.push('nomor_antrian.mp3'); // Kata "Nomor Antrian"
    queue.push(`${type}.mp3`); // Tipe antrian (misalnya, 'A.mp3')

    const numberParts = getNumberParts(number); // Pecah nomor menjadi bagian audio
    queue.push(...numberParts); // Tambahkan ke queue

    queue.push('harap_menuju.mp3'); // Kata "Harap Menuju"

    // Tentukan tujuan
    if (type === 'C') {
        queue.push('kasir.mp3'); // Tujuan ke kasir
    } else {
        queue.push('cs.mp3'); // Tujuan ke CS
    }

    console.log('Queue audio yang dihasilkan:', queue); // Debugging
    return queue;
}
