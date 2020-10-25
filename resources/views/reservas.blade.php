@extends('layout.main')

@section('css-reservas')
<link rel="stylesheet" href="{{asset('/css/reservas.css')}}">
<style>
    .modal {
      display: none;
      position: fixed; 
      z-index: 1; 
      padding-top: 100px; 
      left: 0;
      top: 0;
      width: 100%; 
      height: 100%; 
      overflow: auto; 
      background-color: #BA432C; 
      background-color: rgba(0,0,0,0.4);
      text-align: center;
    }
    
    .modal-content {
      position: relative;
      background-color: #BA432C;
      color:white;
      margin: auto;
      padding: 0;
      border: 1px solid #888;
      width: 50%;
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
      -webkit-animation-name: animatetop;
      -webkit-animation-duration: 0.4s;
      animation-name: animatetop;
      animation-duration: 0.4s
    }
    
    @-webkit-keyframes animatetop {
      from {top:-300px; opacity:0} 
      to {top:0; opacity:1}
    }
    
    @keyframes animatetop {
      from {top:-300px; opacity:0}
      to {top:0; opacity:1}
    }
    
    .close {
      color: #white;
      float: right;
      font-size: 28px;
      font-weight: bold;
      margin-right: 16px;
    }
    
    .close:hover,
    .close:focus {
      color: #aaaaaa;
      text-decoration: none;
      cursor: pointer;
    }
    
    .modal-body {padding: 2px 16px;}
    
    .modal-footer {
      padding: 2px 16px;
      background-color: #5cb85c;
      color: white;
    }
    #botaoModal{
        background-color: #ba432c;
    }
    </style>
@endsection

@section('content')
    @if($errors->all())
        <div id="modalErro" class="modal">
            <div class="modal-content">
                <div class="modal-body">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}      
                </div>
            </div>
        </div>
    @endif

    <div class="container">
        <div style="text-align: right;">
            <button class="btn btn-danger mt-4" id="botaoModal">
                <i class="fa fa-list"></i> Visualizar reservas 
            </button>
        </div>
        <div class="container">
            @if($reserva_marcada->count() > 0)
                <div id="meuModal" class="modal">
                    <div class="modal-content">
                        <span class="close" data-dismiss="modal">&times;</span>
                        <div class="modal-body">
                            @if($reserva_marcada->count() == 1)
                                <p>Você possui reserva marcada! Te esperamos aqui no dia
                                    @foreach($reserva_marcada as $reservaAtual)
                                        <p>{{ date('d/m/Y', strtotime($reservaAtual->data_reserva)) }} às {{ date('H:i', strtotime($reservaAtual->data_reserva)) }} horas.</p>
                                    @endforeach
                                </p>
                            @elseif($reserva_marcada->count() > 1)
                                <p>Você possui reservas marcadas nos dias:</p>
                                @foreach($reserva_marcada as $reservaAtual)
                                    <p>{{ date('d/m/Y', strtotime($reservaAtual->data_reserva)) }} às {{ date('H:i', strtotime($reservaAtual->data_reserva)) }} horas.</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div id="meuModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="modal-body">
                            <p>Você não possui nenhuma reserva marcada no nosso restaurante :(</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xm-6 mt-5">
                <div class="texto-esquerda">
                    <div class="titulo">
                        <h1 class="titulo mt-3 mb-2" style="color: white;">Reserva</h1>
                    </div>
                    <p class="p-texto">Mussum Ipsum, cacilds vidis litro abertis. Mauris nec dolor in eros commodo
                        tempor. Aenean aliquam molestie leo, vitae iaculis nisl. Sapien in monti palavris qui num
                        significa nadis i pareci latim. Detraxit consequat et quo num tendi nada. Suco de cevadiss, é um
                        leite divinis, qui tem lupuliz, matis, aguis e fermentis.
                    </p>
                    <p class="p-texto">Mussum Ipsum, cacilds vidis litro abertis. Mauris nec dolor in eros commodo
                        tempor. Aenean aliquam molestie leo, vitae iaculis nisl. Sapien in monti palavris qui num
                        significa nadis i pareci latim. Detraxit consequat et quo num tendi nada. Suco de cevadiss, é um
                        leite divinis, qui tem lupuliz, matis, aguis e fermentis.    
                    </p>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xm-6 mt-5 pb-5">
                <div class="formularioReserva mt-5">
                    <div class="container-form text-right">
                        <form action="store" method="post">
                            {{ csrf_field() }}

                            <div class="form-group text-lg-right">
                                <input id="inputCpf" class="form-control col-lg-12 inputRed" type="text" name="cpf" placeholder="Digite seu CPF" required>
                            </div>

                            <div class="form-group">
                                <label style="font-style:bold;" class="text-left warning">Data da reserva</label>
                                <input id="inputDataReserva" name="data_reserva" class="form-control col-lg-12 inputYellow"
                                    type="datetime-local" min="{{ date('Y-m-d')}}T{{date('H:i') }}"
                                    max="2024-12-30 23:00:00" onkeypress="return false;" onfocus="validarDatas();" required>
                            </div>

                            <div class="form-group">
                                <label class="text-left">Selecione a mesa</label>
                                <select name="mesa" class="form-control col-lg-12 inputRed"; required>
                                    @foreach($mesa as $mesaAtual)
                                    <option id="" value="{{ $mesaAtual->id_mesa }}">
                                        {{ ucfirst($mesaAtual->tipo_mesa) }}
                                        para
                                        {{ ucfirst($mesaAtual->qtd_cadeiras) }}
                                        @if($mesaAtual->qtd_cadeiras == 1)
                                        pessoa
                                        @else
                                        pessoas
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <input type="submit" class=" col-lg-12 btn  btn-warning" value="cadastrar reserva">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    // Função para colocar uma máscara no CPF
    $(document).ready(function () {
        $('#inputCpf').mask('000.000.000-00');
    });

    // Função para abrir o modal de erro assim que a página carregar
    $(window).on('load',function(){
        $('#modalErro').modal('show');
        $('.modal-backdrop').remove();
    });

    var modal = document.getElementById("meuModal");
    var btn = document.getElementById("botaoModal");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
    modal.style.display = "block";
    }

    span.onclick = function() {
    modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

</script>
@endsection