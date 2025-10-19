@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Заявки</h1>
        <form class="row g-2 mb-3">
            <input type="text" name="email" placeholder="email" value="{{ request('email') }}">
            <input type="text" name="phone" placeholder="phone" value="{{ request('phone') }}">
            <select name="status">
                <option value="">-- статус --</option>
                @foreach(['new'=>'Новый','in_progress'=>'В работе','resolved'=>'Обработан'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
            <input type="date" name="from" value="{{ request('from') }}">
            <input type="date" name="to" value="{{ request('to') }}">
            <button>Фильтр</button>
        </form>

        <table class="table">
            <thead><tr><th>ID</th><th>Клиент</th><th>Тема</th><th>Статус</th><th>Дата</th></tr></thead>
            <tbody>
            @foreach($tickets as $t)
                <tr>
                    <td><a href="{{ route('admin.tickets.show',$t) }}">{{ $t->id }}</a></td>
                    <td>{{ $t->customer->name }} ({{ $t->customer->email }}, {{ $t->customer->phone }})</td>
                    <td>{{ $t->subject }}</td>
                    <td>{{ $t->status }}</td>
                    <td>{{ $t->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $tickets->links() }}
    </div>
@endsection
