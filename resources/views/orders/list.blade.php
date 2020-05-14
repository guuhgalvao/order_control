@extends('layouts.clean')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 py-3 bg-dark text-white">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                <h1 class="m-0 p-0"><b><a href="{{ url('/') }}" style="text-decoration: none; color: inherit;">PEDIDOS</a></b></h1>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        @foreach ($orders as $order)
            <div class="col-md-4" id="card-order-{{ $order->id }}">
                <div class="card border-0 shadow" style="border-width: 1px !important;">
                    <div class="card-body py-3" style="position: relative;">
                        <div class="d-flex py-3 px-4 text-primary" style="position: absolute; right: 0; top: 0;">
                            <h4 class="m-0 p-0"><b>#{{ $order->id }}</b></h4>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5 class="mb-2">{{ $order->client_name }}</h5>
                        </div>
                        <div class="d-flex border-bottom pb-2 mb-2" style="border-color: #ccc;">
                            <h5 class="m-0 p-0"><b>Produtos</b></h5>
                        </div>
                        <div class="d-flex flex-column border-bottom pb-2 mb-2" style="border-color: #ccc;">
                            <h5 class="mb-2">{{ $order->product_name }} <b>x{{ $order->product_amount }}</b></h5>
                            <h5 class="mb-2">{{ $order->product_name }} <b>x{{ $order->product_amount }}</b></h5>
                        </div>
                        <div class="d-flex flex-column mb-4">
                            <h5 class="mb-2"><b>Previsão:</b> {{ $order->created_at->format('H:i:s') }}</h5>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-remove" data-order-id="{{ $order->id }}">Finalizar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.btn-remove', function (e) {
        let orderId = $(this).attr('data-order-id');

        $.ajax({
            type: 'POST',
            url: '{{ route('orders.delete') }}',
            dataType: 'JSON',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            cache: false,
            data: {order_id: orderId},
            beforeSend: () => {
                Swal.fire({
                    title: 'Carregando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    html: `
                    <div class="spinner-border color-laranja" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    `
                });
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.close();
                    $(`#card-order-${orderId}`).remove();
                } else {
                    Swal.fire({
                        icon: 'info',
                        html: response.message,
                    });
                }
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    text: 'Não foi possível finalizar o pedido',
                });
            }
        });

    });
</script>
@endpush
