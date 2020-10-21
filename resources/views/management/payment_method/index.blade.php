@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">Gerenciar Forma de Pagamento</div>

                <div class="card-body">
                    <form id="form-payment-method">
                        <input type="hidden" name="id" value="{{ $payment_method->id ?? '' }}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $payment_method->name ?? '' }}" placeholder="Fulano">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_credit" name="is_credit" value="1" @if (!empty($payment_method)) {{ $payment_method->is_credit ? 'checked' : '' }} @endif>
                                    <label class="custom-control-label" for="is_credit">Crédito?</label>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="btn-save" class="btn btn-primary">Salvar</button>
                        @if (!empty($payment_method))
                            <button type="button" id="btn-delete" class="btn btn-danger">Excluir</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#btn-save').on('click', function (e) {
        $.ajax({
            type: 'POST',
            url: '{{ route('management.payment_method.save') }}',
            dataType: 'JSON',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            cache: false,
            data: $('#form-payment-method').serialize(),
            beforeSend: () => {
                Swal.fire({
                    title: 'Carregando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    html: `
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    `
                });
            },
            success: function (response) {
                if ($('#form-payment-method input[name=id]').val() === "") {
                    $('#form-payment-method :input').val('');
                }

                Swal.fire({
                    icon: 'success',
                    html: response.message,
                });
            },
            error: function (error) {
                console.log(error);
                switch (error.status) {
                    case 422:
                        if (error.responseJSON.message === 'The given data was invalid.') {
                            let errors = error.responseJSON.errors;
                            let errorText = '';
                            for (let keyE in errors) {
                                for (let keyV in errors[keyE]) {
                                    errorText += errors[keyE][keyV] + '<br>';
                                }
                            }

                            Swal.fire({
                                icon: 'info',
                                html: errorText,
                            });
                        }
                        break;

                    default:
                        Swal.fire({
                            icon: 'error',
                            text: 'Erro inesperado',
                        });
                        break;
                }
            }
        });
    });

    $('#btn-delete').on('click', function (e) {
        Swal.fire({
            icon: 'warning',
            title: 'Tem certeza?',
            text: "Você realmente quer excluir esta forma de pagamento?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, sem dúvidas!',
            cancelButtonText: 'Não',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('management.payment_method.delete') }}',
                    dataType: 'JSON',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    cache: false,
                    data: $('#form-payment-method').serialize(),
                    beforeSend: () => {
                        Swal.fire({
                            title: 'Carregando...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            html: `
                                <div class="spinner-border text-info" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            `
                        });
                    },
                    success: function (response) {
                        if ($('#form-payment-method input[name=id]').val() === "") {
                            $('#form-payment-method :input').val('');
                        }

                        Swal.fire({
                            icon: 'success',
                            html: response.message,
                        });
                    },
                    error: function (error) {
                        console.log(error);
                        switch (error.status) {
                            case 422:
                                if (error.responseJSON.message === 'The given data was invalid.') {
                                    let errors = error.responseJSON.errors;
                                    let errorText = '';
                                    for (let keyE in errors) {
                                        for (let keyV in errors[keyE]) {
                                            errorText += errors[keyE][keyV] + '<br>';
                                        }
                                    }

                                    Swal.fire({
                                        icon: 'info',
                                        html: errorText,
                                    });
                                }
                                break;

                            default:
                                Swal.fire({
                                    icon: 'error',
                                    text: 'Erro inesperado',
                                });
                                break;
                        }
                    }
                });
            }
        });
    });
</script>
@endpush
