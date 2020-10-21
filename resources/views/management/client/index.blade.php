@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">Gerenciar Clientes</div>

                <div class="card-body">
                    <form id="form-client">
                        <input type="hidden" name="id" value="{{ $client->id ?? '' }}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $client->name ?? '' }}" placeholder="Fulano">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="phone">Telefone</label>
                                <input type="text" class="form-control mask-phones" id="phone" name="phone" value="{{ $client->phone ?? '' }}" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                        <div class="d-flex border-bottom mb-2" style="border-color: #ccc;">
                            <h4 class="mb-2">Endereço</h4>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cep">CEP</label>
                                <input type="text" class="form-control mask-cep" id="cep" name="cep" value="{{ $client->cep ?? '' }}" placeholder="00000-000">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="address">Logradouro</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $client->address ?? '' }}" placeholder="Rua Exemplo">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="number">Número</label>
                                <input type="text" class="form-control mask-number" id="number" name="number" value="{{ $client->number ?? '' }}" placeholder="123">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="complement">Complemento</label>
                                <input type="text" class="form-control" id="complement" name="complement" value="{{ $client->complement ?? '' }}" placeholder="Sala, Apto., Conj.">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="district">Bairro</label>
                                <input type="text" class="form-control" id="district" name="district" value="{{ $client->district ?? '' }}" placeholder="Vila, Bairro, Regiao..">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="city">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $client->city ?? '' }}" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state">Estado</label>
                                <input type="text" class="form-control mask-state" id="state" name="state" value="{{ $client->state ?? '' }}" placeholder="UF">
                            </div>
                        </div>
                        <button type="button" id="btn-save" class="btn btn-primary">Salvar</button>
                        @if (!empty($client))
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
    let cepNumber = null;

    $('.mask-phones').mask(SPMaskBehavior, spOptions);
    $('.mask-cep').mask('00000-000');
    $('.mask-number').mask('00000');
    $('.mask-state').mask('SS');

    $('#cep').on('keyup paste', function (){
        if (this.value.length === 9 && (cepNumber === null || $(this).val().replace('-', '') !== cepNumber)) {
            cepNumber = $(this).val().replace('-', '');
            $.ajax({
                type: 'GET',
                url: `https://viacep.com.br/ws/${cepNumber}/json/`,
                dataType: 'JSON',
                success: function (response) {
                    $('#address').val(response.logradouro);
                    $('#district').val(response.bairro);
                    $('#city').val(response.localidade);
                    $('#state').val(response.uf);

                    $('#number').focus();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    $('#btn-save').on('click', function (e) {
        $.ajax({
            type: 'POST',
            url: '{{ route('management.client.save') }}',
            dataType: 'JSON',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            cache: false,
            data: $('#form-client').serialize(),
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
                if ($('#form-client input[name=id]').val() === "") {
                    $('#form-client :input').val('');
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
            text: "Você realmente quer excluir este cliente?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, sem dúvidas!',
            cancelButtonText: 'Não',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('management.client.delete') }}',
                    dataType: 'JSON',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    cache: false,
                    data: $('#form-client').serialize(),
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
                        if ($('#form-client input[name=id]').val() === "") {
                            $('#form-client :input').val('');
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
