export async function sendPrintRequest(queue) {
    console.log('Mengirim permintaan ke:', '/print-queue');
    console.log('Data:', queue);

    try {
        const response = await fetch('/print-queue', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                category: queue.category_name,
                number: queue.queue.number,
                remaining_queues: queue.remaining_queues,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Respons:', data);

        if (data.success) {
            console.log('Cetak berhasil:', data.message);
        } else {
            alert('Gagal mencetak: ' + data.message);
        }
    } catch (error) {
        console.error('Kesalahan dalam proses cetak:', error);
        alert('Kesalahan dalam proses cetak: ' + error.message);
    }
}
