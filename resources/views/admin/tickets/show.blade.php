<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a class="text-sky-700 underline" href="{{ route('admin.tickets.index') }}">← Назад</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Заявка #{{ $ticket->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                <div><b>Клиент:</b> {{ $ticket->customer->name }} ({{ $ticket->customer->email }}, {{ $ticket->customer->phone }})</div>
                <div><b>Тема:</b> {{ $ticket->subject }}</div>
                <div><b>Сообщение:</b> {{ $ticket->message }}</div>
                <div><b>Статус:</b> {{ $ticket->status }}
                    @if($ticket->manager_replied_at)
                        ({{ $ticket->manager_replied_at }})
                    @endif
                </div>

                <div>
                    <h4 class="font-semibold mb-2">Файлы</h4>
                    <ul class="list-disc pl-5">
                        @foreach($ticket->getMedia('attachments') as $m)
                            <li>
                                <a class="text-sky-700 underline" href="{{ $m->getUrl() }}" download>
                                    {{ $m->file_name }} ({{ number_format($m->size/1024,1) }} KB)
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <form method="post" action="{{ route('admin.tickets.status',$ticket) }}" class="flex gap-2 items-center">
                    @csrf @method('PATCH')
                    <select class="border rounded px-3 py-2" name="status" required>
                        @foreach(['new','in_progress','resolved'] as $s)
                            <option value="{{ $s }}" @selected($ticket->status===$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                    <button class="bg-sky-600 text-white rounded px-4 py-2">Обновить статус</button>
                </form>

                @if(session('success'))
                    <p class="text-green-700">{{ session('success') }}</p>
                @endif
                @if($errors->any())
                    <p class="text-red-700">{{ $errors->first() }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
