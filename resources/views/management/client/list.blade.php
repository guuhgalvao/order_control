@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-hover border-bottom mb-2" id="client-list">
                <thead class="thead-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->phone ?? '-' }}</td>
                            <td>{{ $client->cep.' - '.$client->address.', '.$client->number.(!empty($client->complement) ? ' '.$client->complement : '').' - '.$client->district.' - '.$client->city.'/'.$client->state }}</td>
                            <td class="text-center">
                                <span class="text-primary btn-edit mr-2" data-id="{{ $client->id }}" style="font-size: 18px; cursor: pointer;"><i class="fas fa-pen"></i></span>
                                <span class="text-danger btn-delete" data-id="{{ $client->id }}" style="font-size: 18px; cursor: pointer;"><i class="fas fa-trash"></i></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var customTable;
    $(document).ready(function() {
        $.noConflict();
        customTable = $('#client-list').DataTable({
            language: {
                "decimal":        "",
                "emptyTable":     "Nenhum dado disponível",
                "info":           "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "infoEmpty":      "Exibindo de 0 ate 0 de 0 registros",
                "infoFiltered":   "(filtrado de _MAX_ registros no total)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Exibir _MENU_ registros",
                "loadingRecords": "Carregando...",
                "processing":     "Processando...",
                "search":         "Pesquisar:",
                "zeroRecords":    "Nenhum registro encontrado",
                "paginate": {
                    "first":      "<<",
                    "last":       ">>",
                    "next":       ">",
                    "previous":   "<"
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            }
        });

        $('#client-list_filter').append('<a href="{{ route("management.client") }}" class="btn btn-sm btn-success ml-3"><i class="fas fa-plus mr-2"></i>NOVO CLIENTE</a>');
    });

    $('.btn-edit').on('click', function (e) {
        let itemId = $(this).attr('data-id');

        window.location.href = '{{ route("management.client") }}/' + itemId;
    });

    $('.btn-delete').on('click', function (e) {
        let tableRow = $(this).closest('tr');
        let itemId = $(this).attr('data-id');

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
                    data: {id: itemId},
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
                        customTable.row(tableRow).remove().draw();
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
