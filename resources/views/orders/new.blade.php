@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">Novo Pedido</div>

                <div class="card-body">
                    <form id="form-order">
                        <div class="d-flex" style="border-color: #ccc;">
                            <h4 class="mb-2">Cliente</h4>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Nome">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="client_phone" name="client_phone" placeholder="Telefone">
                            </div>
                        </div>
                        <div class="d-flex" style="border-color: #ccc;">
                            <h4 class="mb-2">Pedido</h4>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Produto">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="product_amount" name="product_amount" placeholder="Quantidade">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Observacoes"></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="total_value" name="total_value" placeholder="Valor Total">
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" id="payment_method" name="payment_method" placeholder="Forma de Pagamento">
                            </div>
                        </div>
                        <button type="button" id="btn-add" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#btn-add').on('click', function (e) {
        $.ajax({
            type: 'POST',
            url: '{{ route('orders.store') }}',
            dataType: 'JSON',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            cache: false,
            data: $('#form-order').serialize(),
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
                    $('#form-order')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        html: response.message,
                    });
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
                    text: 'Não foi possível adicionar o pedido',
                });
            }
        });

    });
</script>
@endpush
