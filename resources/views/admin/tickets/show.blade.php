@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('admin.tickets.index') }}">← Назад</a>
        <h2>Заявка #{{ $ticket->id }}</h2>
        <p><b>Клиент:</b> {{ $ticket->customer->name }} ({{ $ticket->customer->email }}, {{ $ticket->customer->phone }})</p>
        <p><b>Тема:</b> {{ $ticket->subject }}</p>
        <p><b>Сообщение:</b> {{ $ticket->message }}</p>
        <p><b>Статус:</b> {{ $ticket->status }} @if($ticket->manager_replied_at) ({{ $ticket->manager_replied_at }}) @endif</p>

        <h4>Файлы</h4>
        <ul>
            @foreach($ticket->getMedia('attachments') as $m)
                <li><a href="{{ $m->getUrl() }}" download>{{ $m->file_name }} ({{ number_format($m->size/1024,1) }} KB)</a></li>
            @endforeach
        </ul>

        <form method="post" action="{{ route('admin.tickets.status',$ticket) }}">
            @csrf @method('PATCH')
            <select name="status" required>
                @foreach(['new','in_progress','resolved'] as $s)
                    <option value="{{ $s }}" @selected($ticket->status===$s)>{{ $s }}</option>
                @endforeach
            </select>
            <button>Обновить статус</button>
        </form>

        @if(session('success')) <p style="color:green">{{ session('success') }}</p> @endif
        @if($errors->any()) <p style="color:red">{{ $errors->first() }}</p> @endif
    </div>
@endsection
