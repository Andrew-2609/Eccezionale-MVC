<!--Mesmo esquema da comidas.blade.php-->
@extends('layout.main')
@section('content')


<!--Se ouver algum erro, ele é exibido dentro dessa div-->
<div class="">
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif

    <h1>Cadastrar Comida</h1>
    <!-- Formulário para o cadastro das comidas. No action está o nome da rota (definido no web.php) para onde
    esses dados serão enviados. Esse "enctype" é para o formulário interpretar o arquivo da imagem direito.-->
        <form class="text-center border border-light p-5" enctype="multipart/form-data" action="{{route('comida_store')}}" method="POST">
        <!--Código obrigatório. Nem sei direito o por quê.-->
        {{ csrf_field() }}

        <p class="h4 mb-4">Cadastro de Comidas</p>

        <div class="form-row mb-4">
            <div class="col-md-4">
                <input type="text" id="" class="form-control" name="nome_comida" placeholder="Nome da comida" required>
            </div>
            <div class="col-md-4">
                <input type="text" id="" class="form-control" name="preco_comida" placeholder="Preço da comida" required>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Enviar</span>
                    </div>
                    <div class="custom-file" style="text-align: left;">
                        <input type="file" class="custom-file-input" id="inputGroupFile01"
                            aria-describedby="inputGroupFileAddon01" name="imagem_comida" required>
                        <label class="custom-file-label" for="inputGroupFile01">Escolher arquivo</label>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row mb-4">
            <div class="col-md-6">
                <div class="form-group purple-border">
                    <textarea class="form-control" id="" name="descricao_comida" placeholder="Descrição da comida"
                        rows="3" required></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <select class="browser-default custom-select" name="nacionalidade_comida" required>
                    <option selected value="1">Alemanha</option>
                    <option value="2">Itália</option>
                    <option value="3">Japão</option>
                </select>
            </div>
        </div>

        <!-- Botão de inserir os dados durr -->
        <button class="btn btn-info my-4 btn-block" type="submit">Inserir dados</button>
    </form>
</div>
@endsection
