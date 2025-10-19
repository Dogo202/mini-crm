<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Заявки
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form class="flex flex-wrap gap-2 mb-4">
                    <input class="border rounded px-3 py-2" type="text" name="email" placeholder="email" value="{{ request('email') }}">
                    <input class="border rounded px-3 py-2" type="text" name="phone" placeholder="phone" value="{{ request('phone') }}">
                    <select class="border rounded px-3 py-2" name="status">
                        <option value="">-- статус --</option>
                        @foreach(['new'=>'Новый','in_progress'=>'В работе','resolved'=>'Обработан'] as $k=>$v)
                            <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <input class="border rounded px-3 py-2" type="date" name="from" value="{{ request('from') }}">
                    <input class="border rounded px-3 py-2" type="date" name="to" value="{{ request('to') }}">
                    <button class="bg-sky-600 text-white rounded px-4 py-2">Фильтр</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="text-left border-b">
                            <th class="py-2 pr-4">ID</th>
                            <th class="py-2 pr-4">Клиент</th>
                            <th class="py-2 pr-4">Тема</th>
                            <th class="py-2 pr-4">Статус</th>
                            <th class="py-2 pr-4">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $t)
                            <tr class="border-b">
                                <td class="py-2 pr-4">
                                    <a class="text-sky-700 underline" href="{{ route('admin.tickets.show',$t) }}">{{ $t->id }}</a>
                                </td>
                                <td class="py-2 pr-4">
                                    {{ $t->customer->name }}
                                    ({{ $t->customer->email }}, {{ $t->customer->phone }})
                                </td>
                                <td class="py-2 pr-4">{{ $t->subject }}</td>
                                <td class="py-2 pr-4">{{ $t->status }}</td>
                                <td class="py-2 pr-4">{{ $t->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
