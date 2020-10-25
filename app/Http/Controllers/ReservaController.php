<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Reserva;
use App\Mesa;
use DateTime;

class ReservaController extends Controller
{
    public function index() {
        
        DB::table('mesas')->whereNotExists(function ($query) {
            $query->select(DB::raw(1))->from('reservas')->whereRaw('mesas.id_mesa = id_mesa')->whereRaw('data_reserva > timestampadd(hour, -3, now())');
        })->update([
            'disponivel' => true
        ]);
        $mesa = Mesa::where('disponivel', '=', '1')->orderby('qtd_cadeiras', 'asc')->orderby('tipo_mesa')->get();
        $reserva_marcada = Reserva::where('id_user','=', Auth::user()->id)->where('data_reserva', '>=', date('Y-m-d H:i:s'))->get();
        $dataAtual = date('d-m-Y');
        return view('reservas', compact('mesa', 'reserva_marcada', 'dataAtual'));
    }

    public function store(Request $req) {
        $this->validate($req, [
            'cpf' => 'required',
            'mesa' => 'required',
            'data_reserva' => 'required|date|after:' . date('Y-m-d H:i:s')
        ], [
            'cpf.required' => 'Por favor, insira seu CPF.',
            'mesa.required' => 'Por favor, informe a mesa que você quer.',
            'data_reserva.required' => 'Por favor, preencha o campo da data da reserva.',
            'data_reserva.date' => 'Por favor, insira uma data coerente.',
            'data_reserva.after' => 'Você está tentando marcar a reserva para um horário que já passou.'
        ]);
        
        // Registrando os dados da reserva no banco de dados.
        $reservas = new Reserva;
        $reservas->id_user = Auth::user()->id;
        $reservas->cpf_user = $req->cpf;
        $reservas->id_mesa = $req->mesa;
        $reservas->data_reserva = $req->data_reserva;
        
        $mesaCompleta = Mesa::where('id_mesa', '=', $req->mesa)->first();
        $reservas->preco_total = $mesaCompleta->preco_mesa * $mesaCompleta->qtd_cadeiras;
        $reservas->save();
       
        $mesa = Mesa::find($mesaCompleta->id_mesa);
        $mesa->disponivel = 0;
        $mesa->save();
        
        // Depois de salva a reserva, há uma mensagem.
        return 'Reservado com sucesso!';
    }
}