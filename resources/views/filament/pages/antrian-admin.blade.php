<x-filament::page>
    
    <h1 class="text-xl font-bold">Kelola Antrian</h1>

    <div class="space-y-6 mt-6">
        @foreach ($this->categories as $category)
       
            <div class="border p-4 rounded shadow">
                <h2 class="text-lg font-semibold">{{ $category->name }}</h2>
                <p>Sisa Antrian: {{ $category->queues_count }}</p>

                <div class="flex space-x-4 mt-4">
                    <form wire:submit.prevent="callNext({{ $category->id }})">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded" style="background-color: blue">
                            Panggil Selanjutnya
                        </button>
                    </form>

                    <form wire:submit.prevent="recallLast({{ $category->id }})">
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded" style="background-color: green">
                            Panggil Ulang
                        </button>
                    </form>

                    <form wire:submit.prevent="resetQueue({{ $category->id }})">
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded" style="background-color: red">
                            Reset Antrian
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

</x-filament::page>
