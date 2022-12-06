@extends('layouts.app')

@section('content')
    <h3 class="page-title">{{ $title }}</h3>
    <div class="date-container">
        <form action="" class="sub-container" id="date-form">
            @csrf
            <div class="date-form-fields">
                <div class="date-control">
                    <label for="date">Дата:</label>
                    <input type="date" name="date" id="date" min="{{ date('Y-m-d', strtotime('-1 week')) }}"
                           max="{{ date('Y-m-d', strtotime('+1 week')) }}" value="{{ date('Y-m-d') }}"/>
                </div>
                <div class="date-control">
                    <label for="time">Время:</label>
                    <select name="time" id="time">
                        <option value="10:00">10:00</option>
                        <option value="12:00">12:00</option>
                        <option value="14:00">14:00</option>
                        <option value="16:00">16:00</option>
                        <option value="18:00">18:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                </div>
            </div>
            <div class="date-form-fields">
                <div class="date-control">
                    <label for="item_id">Сеанс:</label>
                    <select name="item_id" id=""></select>
                </div>
            </div>
            <div class="date-form-submit">
                <button class="btn btn-date-submit">Проверить</button>
            </div>
        </form>
    </div>
    <div class="date-container">
        <form action="" id="booking-form" class="booking-container sub-container hidden">
            <h3 class="page-title">Места для броннирования</h3>
            <div class="booking-map">
            </div>
            <div class="date-form-submit">
                <button class="btn btn-date-submit">Забронировать</button>
            </div>
        </form>
    </div>
@endsection
