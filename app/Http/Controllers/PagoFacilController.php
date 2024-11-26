<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PagoFacil;
use App\Models\Placa;
use App\Models\Venta;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoFacilController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function consultar(Request $request)
     { 
         try {
             $pago_id = $request->input('pago_id');
             if (!$pago_id) {
                 return response()->json([
                     'error' => 'ID de pago no proporcionado.',
                 ], 400);
             }

             $pagoEnCurso = Pago::findOrFail($pago_id);

             $idUltimaVenta = $pago_id;
             $loClientEstado = new Client();
             $lcUrlEstadoTransaccion = "https://serviciostigomoney.pagofacil.com.bo/api/servicio/consultartransaccion";
             $laHeaderEstadoTransaccion = [
                 'Accept' => 'application/json',
             ];
             $laBodyEstadoTransaccion = [
                 "TransaccionDePago" => $idUltimaVenta,
             ];

             // Realizar la solicitud al servicio externo
             $loEstadoTransaccion = $loClientEstado->post($lcUrlEstadoTransaccion, [
                 'headers' => $laHeaderEstadoTransaccion,
                 'json' => $laBodyEstadoTransaccion,
             ]);

             // Decodificar la respuesta JSON
             $laResultEstadoTransaccion = json_decode($loEstadoTransaccion->getBody()->getContents());

             if ($laResultEstadoTransaccion && isset($laResultEstadoTransaccion->values->messageEstado)) {
                 // Procesar el mensaje recibido
                 $cadenaCompleta = $laResultEstadoTransaccion->values->messageEstado;
                 $elementos = explode(' - ', $cadenaCompleta);

                 if (count($elementos) >= 2) {
                     $textoExtraido = $elementos[0] . '-' . $elementos[1];

                     // Diferenciar entre solicitud Inertia y AJAX
                     if ($request->header('X-Inertia')) {
                         return Inertia::render('PagoFacil/create', [
                             'texto' => $textoExtraido,
                         ]);
                     } else {
                         return response()->json([
                             'texto' => $textoExtraido,
                         ]);
                     }
                 }
             }

             // Respuesta de error si no se puede procesar el mensaje
             return response()->json([
                 'error' => 'No se pudo procesar el estado de la transacción.',
             ], 500);

         } catch (\Exception $e) {
             // Manejar excepciones generales
             return response()->json([
                 'error' => 'Ocurrió un error al procesar la transacción.',
                 'detalle' => $e->getMessage(),
             ], 500);
         }
     }




    /**
     * Show the form for creating a new resource.
     */
    public function create(Placa $placa)
    {
        return Inertia::render('PagoFacil/Create', [
            'placa' => $placa,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
     // GENERAR COBRO
     public function generar(Request $request)
     {
        // dd($request);
         do {
             $nroPago = rand(100000, 999999);
             $existe = Pago::where('id', $nroPago)->exists();
         } while ($existe);

         try {
             $lcComerceID           = "d029fa3a95e174a19934857f535eb9427d967218a36ea014b70ad704bc6c8d1c";  // credencia dado por pagofacil
             $lnMoneda              = 1;
             $lnTelefono            = $request->tnTelefono;
             $lcNombreUsuario       = $request->tcRazonSocial;
             $lnCiNit               = $request->tcCiNit;
             $lcNroPago             = $nroPago; // Genera un número aleatorio entre 100,000 y 999,999   sirve para callback , pedidoID
             $lnMontoClienteEmpresa = $request->tnMonto;
             $lcCorreo              = $request->tcCorreo;
             $lcUrlCallBack         = route('pagofacil.callback'); //"https://mail.tecnoweb.org.bo/inf513/grupo03sa/ultimo/public/cursos/pagos/callback";
             $lcUrlReturn           = "";
             $laPedidoDetalle       =  $request->taPedidoDetalle;
             $lcUrl                 = "";

             $loClient = new Client();

             if ($request->tnTipoServicio == 1) {
                 $lcUrl = "https://serviciostigomoney.pagofacil.com.bo/api/servicio/generarqrv2";
             } elseif ($request->tnTipoServicio == 2) {
                 $lcUrl = "https://serviciostigomoney.pagofacil.com.bo/api/servicio/realizarpagotigomoneyv2";
             }

             $laHeader = [
                 'Accept' => 'application/json'
             ];

             $laBody   = [
                 "tcCommerceID"          => $lcComerceID,
                 "tnMoneda"              => $lnMoneda,
                 "tnTelefono"            => $lnTelefono,
                 'tcNombreUsuario'       => $lcNombreUsuario,
                 'tnCiNit'               => $lnCiNit,
                 'tcNroPago'             => $lcNroPago,
                 "tnMontoClienteEmpresa" => $lnMontoClienteEmpresa,
                 "tcCorreo"              => $lcCorreo,
                 'tcUrlCallBack'         => $lcUrlCallBack,
                 "tcUrlReturn"           => $lcUrlReturn,

             ];

             $loResponse = $loClient->post($lcUrl, [
                 'headers' => $laHeader,
                 'json' => $laBody
             ]);

             $laResult = json_decode($loResponse->getBody()->getContents());

             if ($request->tnTipoServicio == 1) {

                 $csrfToken = csrf_token();
                 $laValues = explode(";", $laResult->values)[1];
                 $nroTransaccion = explode(";", $laResult->values)[0];

                 Pago::create([
                    'id' =>   $nroTransaccion,
                    'fechapago' => now(),
                    'estado' => 1,
                    'metodopago' => 4,   // 4 es Qr
                ]);


                 $laQrImage = "data:image/png;base64," . json_decode($laValues)->qrImage;

                 return response()->json([
                     'qrImage' => $laQrImage,
                     'nroTransaccion' =>  $nroTransaccion,
                 ]);
             }
         } catch (\Throwable $th) {

             return $th->getMessage() . " - " . $th->getLine();
         }
     }

    /**
     * Display the specified resource.
     */
    public function show(PagoFacil $pagoFacil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PagoFacil $pagoFacil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoFacil $pagoFacil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoFacil $pagoFacil)
    {
        //
    }
}
