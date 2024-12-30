const pusher = new Pusher('local-app-key', {
    cluster: 'mt1',
    wsHost: '192.168.100.102',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    reconnectionAttempts: Infinity, // Tidak ada batasan reconnect
    reconnectInterval: 5000, // Coba reconnect setiap 5 detik
});

const channel = pusher.subscribe('queue-updates');
channel.bind('App\\Events\\QueueUpdated', function (data) {
console.log('Event diterima:', data);

// Update jumlah sisa antrian
const queueElement = document.getElementById(`queue-${data.categoryId}`);
if (queueElement) {
    queueElement.innerText = data.remainingQueues;
}

// Mulai pemanggilan suara hanya jika berasal dari admin panel
if (data.isAdminCall && data.queueNumber) {
    // Ambil abjad dari queueNumber (misalnya, "A" dari "A-003")
    const type = data.queueNumber.split('-')[0]; // Pisahkan abjad sebelum "-"
    const number = parseInt(data.queueNumber.split('-')[1], 10); // Pisahkan angka setelah "-"

    console.log(`Tipe Antrian: ${type}, Nomor: ${number}`);

    // Generate audio queue dan mulai pemutaran
    const audioQueue = generateAudioQueue(type, number);
    playAudioQueue(audioQueue);
}
});