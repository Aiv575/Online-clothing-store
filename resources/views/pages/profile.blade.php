@include('includes.header')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="my-4">Профиль</h1>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ Auth::user()->avatar == 'default.jpg' ? asset('assets/images/default.jpg') : Storage::url(Auth::user()->avatar) }}" class="img-fluid rounded-circle" alt="Аватар" style="width: 200px; height: 200px;">
                        </div>
                        <div class="col-md-9">
                            <h2>{{ Auth::user()->name }}</h2>
                            <p>Email: {{ Auth::user()->email }}</p>
                            <p>Адрес: {{ Auth::user()->address }}</p>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">Редактировать профиль</button>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="my-4">История заказов</h2>
            @foreach(Auth::user()->receipts as $receipt)
                @php
                    $return = $receipt->returnRequests;
                @endphp
                <div class="card my-2">
                    <div class="card-body">
                        <h5 class="card-title">Заказ #{{ $receipt->id }}</h5>

                        <div class="position-absolute" style="top: 15px; right: 15px; display: flex; gap: 10px; align-items: center;">
                            @if($receipt->status === 'completed' && !$return)
                                <a href="{{ route('invoice.generate', $receipt->id) }}" class="btn btn-sm btn-success mb-1">Скачать чек</a>
                                <a href="{{ route('receipt.return.request', $receipt->id) }}" class="btn btn-sm btn-danger">Оформить возврат</a>
                            @elseif($return && $return->status === 'approved')
                                <a href="{{ route('invoice.generate', $receipt->id) }}" class="btn btn-sm btn-success mb-1">Скачать чек</a>
                                <a href="{{ route('invoice.refund.generate', $return->id) }}" class="btn btn-sm btn-warning">Скачать чек на возврат</a>
                            @elseif($return && $return->status === 'pending')
                                <a href="{{ route('invoice.generate', $receipt->id) }}" class="btn btn-sm btn-success mb-1">Скачать чек</a>
                                <span class="badge badge-info">Возврат на рассмотрении</span>
                            @elseif($return && $return->status === 'rejected')
                                <a href="{{ route('invoice.generate', $receipt->id) }}" class="btn btn-sm btn-success mb-1">Скачать чек</a>
                                <span class="badge badge-danger">Возврат отклонён</span>
                            @endif
                        </div>

                        <p class="card-text">Адрес доставки: {{ $receipt->address }}</p>
                        <p class="card-text">Дата: {{ $receipt->created_at }}</p>
                        <p class="card-text">Товары:</p>
                        <ul class="list-group">
                            @foreach($receipt->items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $item->thing->name }} — {{ $item->quantity }} шт. x {{ $item->price }} ₽</span>
                                    <a href="{{ route('thing.show', $item->thing->id) }}#review-form" class="btn btn-sm btn-outline-primary">
                                        Оставить отзыв
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <br>
                        <div class="card-text">
                            @if($receipt->promoCode)
                                <p class="text-muted">
                                    <small>Изначально: {{ $receipt->original_total }} ₽</small>
                                    <br>
                                    <small>Промокод ({{ $receipt->promoCode->code }}): -{{ $receipt->original_total - $receipt->total }} ₽</small>
                                </p>
                            @endif
                            <p class="{{ $receipt->promoCode ? 'text-success' : '' }}">
                                <strong>Итого: {{ $receipt->total }} ₽</strong>
                            </p>
                        </div>
                        <p><strong>Статус заказа:</strong>
                            @switch($receipt->status)
                                @case('route') В пути @break
                                @case('processing') В обработке @break
                                @case('completed') Завершён @break
                                @case('paid') Оплачен @break
                            @endswitch
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Редактировать профиль</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
          </div>
          <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
          <div class="form-group">
            <label for="password">Адрес</label>
            <input type="text" class="form-control" id="address" name="address" value="{{ Auth::user()->address }}">
          </div>
          <div class="form-group">
            <label for="avatar">Аватар</label>
            <input type="file" class="form-control-file" id="avatar" name="avatar">
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </div>
      </form>
    </div>
  </div>
</div>

