<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Movimiento;
use App\Models\Persona;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector; // Importa la clase EscposImage
use Mike42\Escpos\Printer;
use NumberFormatter;
use PDF;

class PDFController extends Controller
{
    public function exportToPDF(Request $request)
    {
        $totalIngresos = 0;
        $TotalEgresos = 0;
        $TotalCaja = 0;
        $Responsable = User::find(Auth::user()->id);
        $persona = Persona::find($Responsable->persona_id);
        $dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $movApertura = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();
        $ResponsableA = User::find($movApertura->usuario_id);
        $personaA = Persona::find($ResponsableA->persona_id);

        $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompra(4) AS 'resultado'");
        $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;

        $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompra(1) AS 'resultado'");
        $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;

        $montoCaja = DB::select('call detalleCierre');
        $movAnulados = DB::select('call movimientosAnulados');

        if (count($montoCaja) > 0) {

            $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
            $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

            if ($ingreso != null) {
                $totalIngresos = $ingreso->totalAcum;
                $TotalCaja = $ingreso->totalAcum;
            }
            if ($egreso != null) {
                $TotalEgresos = $egreso->totalAcum;
                $TotalCaja = $egreso->totalAcum;
            }

            if ($egreso != null && $ingreso != null) {
                $TotalCaja = $ingreso->totalAcum - $egreso->totalAcum;
            }
        }

        $movAperturaTota = $movApertura ? $movApertura->total : 0;
        $turno = $movApertura ? $movApertura->turno : '-';
        $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
        $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;
      
        $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array(null, null));
        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsable' => $persona->nombres,
            'responsableA' => $personaA->nombres,
            'responsableC' => '-',
            'montoApertura' => $movAperturaTota,
            'fechaApertura' => $movApertura->created_at,
            'turno' => $turno,
            'montoCierre' => '-',
            'fechaCierre' => '-',
            'dateTime' => $dateTime,
            'Ventas' => $TotaPagosCliente,
            'Compras' => $TotaCompras,
            'otrosIngresos' => number_format($totalIngresos - $TotaPagosCliente - $movAperturaTota, 2, '.', ','),
            'otrosEgresos' => number_format($TotalEgresos - $TotaCompras, 2, '.', ','),
            'TotalCaja' => number_format($TotalCaja, 2, '.', ','),
            'ingreso' => $ingreso,
            'egreso' => $egreso,
            'movAnulados' => $movAnulados,
            'movimientosCajaEgresos' => $movimientosCajaEgresos,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF
        $pdf = PDF::loadView('export-pdf', $data);
        $pdf->setPaper('a4', 'portrait'); // A4 en formato vertical (retrato)

        $fileName = 'Reporte de arqueo de caja - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }

    public function exportToPDF_Reportes(Request $request, $idApertura, $idCierre)
    {
        $movApertura = Movimiento::where('id', $idApertura)->first();

        $totalIngresos = 0;
        $TotalEgresos = 0;
        $TotalCaja = 0;
        $ResponsableA = User::find($movApertura->usuario_id);
        $personaA = Persona::find($ResponsableA->persona_id);

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        if ($idCierre == 'undefined') {
            $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array(null, null));
            $nombrePersonaCierre = '-';
            $movCierreTota = '-';
            $fechaMovCierre = '-';
            $montoCaja = DB::select('call detalleCierre');

            $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompra(4) AS 'resultado'");
            $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;

            $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompra(1) AS 'resultado'");
            $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;
            $movAnulados = DB::select('call movimientosAnulados');
            $montoCaja = DB::select('call detalleCierre');
        } else {
            $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array($idApertura, $idCierre));
            $movCierre = Movimiento::where('id', $idCierre)->first();
            $ResponsableC = User::find($movCierre->usuario_id);
            $personaC = Persona::find($ResponsableC->persona_id);
            $nombrePersonaCierre = $personaC->nombres;

            $movCierreTota = $movCierre->total;

            $fechaMovCierre = $movCierre->created_at;

            $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompraReportesCierre(4," . $idApertura . "," . $idCierre . ") AS 'resultado'"); // concepto 4 es pago de clientes
            $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;

            $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompraReportesCierre(1," . $idApertura . "," . $idCierre . ") AS 'resultado'"); // concepto 1 es pago compras
            $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;

            $montoCaja = DB::select('call detalleCierreReportesCierre(?, ?)', [$idApertura, $idCierre]);
            $movAnulados = DB::select('call movimientosAnuladosReportes(?, ?)', [$idApertura, $idCierre]);
        }

        if (count($montoCaja) > 0) {

            $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
            $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

            if ($ingreso != null) {
                $totalIngresos = $ingreso->totalAcum;
                $TotalCaja = $ingreso->totalAcum;
            }
            if ($egreso != null) {
                $TotalEgresos = $egreso->totalAcum;
                $TotalCaja = $egreso->totalAcum;
            }

            if ($egreso != null && $ingreso != null) {
                $TotalCaja = $ingreso->totalAcum - $egreso->totalAcum;
            }
        }

        $movAperturaTota = $movApertura ? $movApertura->total : 0;
        $turno = $movApertura ? $movApertura->turno : '-';
      

        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsableA' => $personaA->nombres,

            'montoApertura' => $movAperturaTota,
            'fechaApertura' => $movApertura->created_at,
            'responsableC' => $nombrePersonaCierre,
            'montoCierre' => $movCierreTota,
            'fechaCierre' => $fechaMovCierre,
            'turno' => $turno,

            'dateTime' => $dateTime,
            'Ventas' => $TotaPagosCliente,
            'Compras' => $TotaCompras,
            'otrosIngresos' => number_format($totalIngresos - $TotaPagosCliente - $movAperturaTota, 2, '.', ','),
            'otrosEgresos' => number_format($TotalEgresos - $TotaCompras, 2, '.', ','),
            'TotalCaja' => number_format($TotalCaja, 2, '.', ','),
            'ingreso' => $ingreso,
            'egreso' => $egreso,
            'movAnulados' => $movAnulados,
            'movimientosCajaEgresos' => $movimientosCajaEgresos,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF
        $pdf = PDF::loadView('export-pdf', $data);
        $pdf->setPaper('a4', 'portrait'); // A4 en formato vertical (retrato)

        $fileName = 'Reporte de arqueo de caja - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }

    public function exportToTicketCaja(Request $request)
    {
        $totalIngresos = 0;
        $TotalEgresos = 0;
        $TotalCaja = 0;
        $Responsable = User::find(Auth::user()->id);
        $persona = Persona::find($Responsable->persona_id);
        $dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $movApertura = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();

        $ResponsableA = User::find($movApertura->usuario_id);
        $personaA = Persona::find($ResponsableA->persona_id);

        $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompra(4) AS 'resultado'");
        $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;

        $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompra(1) AS 'resultado'");
        $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;

        $montoCaja = DB::select('call detalleCierre');
        $movAnulados = DB::select('call movimientosAnulados');

        if (count($montoCaja) > 0) {

            $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
            $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

            if ($ingreso != null) {
                $totalIngresos = $ingreso->totalAcum;
                $TotalCaja = $ingreso->totalAcum;
            }
            if ($egreso != null) {
                $TotalEgresos = $egreso->totalAcum;
                $TotalCaja = $egreso->totalAcum;
            }

            if ($egreso != null && $ingreso != null) {
                $TotalCaja = $ingreso->totalAcum - $egreso->totalAcum;
            }
        }

        $movAperturaTota = $movApertura ? $movApertura->total : 0;

        $turno = $movApertura ? $movApertura->turno : '-';
        $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
        $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;
       
        $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array(null, null));
        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsable' => $persona->nombres,
            'responsableA' => $personaA->nombres,
            'responsableC' => '-',
            'montoApertura' => $movAperturaTota,
            'fechaApertura' => $movApertura->created_at,
            'turno' => $turno,
            'montoCierre' => '-',
            'fechaCierre' => '-',

            'dateTime' => $dateTime,
            'Ventas' => $TotaPagosCliente,
            'Compras' => $TotaCompras,
            'otrosIngresos' => number_format($totalIngresos - $TotaPagosCliente - $movAperturaTota, 2, '.', ','),
            'otrosEgresos' => number_format($TotalEgresos - $TotaCompras, 2, '.', ','),
            'TotalCaja' => number_format($TotalCaja, 2, '.', ','),
            'ingreso' => $ingreso,
            'egreso' => $egreso,
            'movAnulados' => $movAnulados,
            'movimientosCajaEgresos' => $movimientosCajaEgresos,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF
        $pdf = PDF::loadView('export-ticketCaja', $data);

        $canvas = $pdf->getDomPDF()->get_canvas();
        // $contenidoAncho = $canvas->get_width();
        $contenidoAlto = $canvas->get_height();
        $pdf->setPaper([15, 5, 172, $contenidoAlto - 270], 'portrait');

        $fileName = 'Ticket De Cierre de caja - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }

    public function exportToTicketCaja_Reportes(Request $request, $idApertura, $idCierre)
    {
        $movApertura = Movimiento::find($idApertura);
        $movCierre = Movimiento::find($idCierre);
        $totalIngresos = 0;
        $TotalEgresos = 0;
        $TotalCaja = 0;
        $ResponsableA = User::find($movApertura->usuario_id);
        $personaA = Persona::find($ResponsableA->persona_id);

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        if ($idCierre == 'undefined') {
            $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array(null, null));
            $nombrePersonaCierre = '-';
            $movCierreTota = '-';
            $fechaMovCierre = '-';

            $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompra(4) AS 'resultado'");
            $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;

            $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompra(1) AS 'resultado'");
            $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;
            $movAnulados = DB::select('call movimientosAnulados');
            $montoCaja = DB::select('call detalleCierre');
        } else {
            $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array($idApertura, $idCierre));
            $movCierre = Movimiento::where('id', $idCierre)->first();
            $ResponsableC = User::find($movCierre->usuario_id);
            $personaC = Persona::find($ResponsableC->persona_id);
            $nombrePersonaCierre = $personaC->nombres;

            $movCierreTota = $movCierre->total;

            $fechaMovCierre = $movCierre->created_at;

            $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompraReportesCierre(4," . $idApertura . "," . $idCierre . ") AS 'resultado'"); // concepto 4 es pago de clientes
            $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;

            $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompraReportesCierre(1," . $idApertura . "," . $idCierre . ") AS 'resultado'"); // concepto 1 es pago compras
            $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;

            $montoCaja = DB::select('call detalleCierreReportesCierre(?, ?)', [$idApertura, $idCierre]);
            $movAnulados = DB::select('call movimientosAnuladosReportes(?, ?)', [$idApertura, $idCierre]);
        }

        if (count($montoCaja) > 0) {

            $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
            $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

            if ($ingreso != null) {
                $totalIngresos = $ingreso->totalAcum;
                $TotalCaja = $ingreso->totalAcum;
            }
            if ($egreso != null) {
                $TotalEgresos = $egreso->totalAcum;
                $TotalCaja = $egreso->totalAcum;
            }

            if ($egreso != null && $ingreso != null) {
                $TotalCaja = $ingreso->totalAcum - $egreso->totalAcum;
            }
        }

        $movAperturaTota = $movApertura ? $movApertura->total : 0;
        $turno = $movApertura ? $movApertura->turno : '-';
        $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
        $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

       

        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsableA' => $personaA->nombres,

            'montoApertura' => $movAperturaTota,
            'fechaApertura' => $movApertura->created_at,
            'turno' => $turno,
            'responsableC' => $nombrePersonaCierre,
            'montoCierre' => $movCierreTota,
            'fechaCierre' => $fechaMovCierre,
       
            'dateTime' => $dateTime,
            'Ventas' => $TotaPagosCliente,
            'Compras' => $TotaCompras,
            'otrosIngresos' => number_format($totalIngresos - $TotaPagosCliente - $movAperturaTota, 2, '.', ','),
            'otrosEgresos' => number_format($TotalEgresos - $TotaCompras, 2, '.', ','),
            'TotalCaja' => number_format($TotalCaja, 2, '.', ','),
            'ingreso' => $ingreso,
            'egreso' => $egreso,
            'movAnulados' => $movAnulados,
            'movimientosCajaEgresos' => $movimientosCajaEgresos,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF

        $pdf = PDF::loadView('export-ticketCaja', $data);

        $canvas = $pdf->getDomPDF()->get_canvas();
        // $contenidoAncho = $canvas->get_width();
        $contenidoAlto = $canvas->get_height();
        $pdf->setPaper([15, 5, 172, $contenidoAlto - 270], 'portrait');

        $fileName = 'Ticket De Cierre de caja - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }

       //VISTA PDF
       public function exportToPDF_ticket(Request $request, $data, $tipoImpresion)
       {
   
           error_log('AQUI EMPIEZA ABRIR VISTA PDF TICKET CON FACTURACION: tipo:' . $tipoImpresion);
   
   
           $data = json_decode($data);
           $tipoDocumento = $data->tipo_documento == "F" ? 'FACTURA DE VENTA ELECTRÓNICA' : 'BOLETA DE VENTA ELECTRÓNICA';
           $dateTime = Carbon::now()->format('Y-m-d H:i:s');
   
           // $num = rtrim($data->nombre_solicitud, '.');
           // $num = explode("-", $num);
           $direccion = "-";
           switch ($data->tipo_documento) {
               case 'F':
                   $rucOdni = $data->data_solicitud->ruc == null ? '11111111111' : $data->data_solicitud->ruc;
                   $direccion = $data->data_solicitud->direccion;
                   break;
               case 'B':
                   $rucOdni = $data->data_solicitud->dni == null ? '11111111' : $data->data_solicitud->dni;
                   break;
           }
           $MovimientoCierre = Movimiento::withTrashed()
               ->where('numero', $data->nombre_solicitud)
               ->first();
   
           $MovimientoPadre = Movimiento::withTrashed()->find($MovimientoCierre->movimiento_id);
           $fechaInicio = $MovimientoPadre->created_at;
   
           $resultado = DB::select('SELECT DeterminarFormaPago(?) AS resultado', [$MovimientoPadre->id]);
           $formaPago = $resultado[0]->resultado;
   
           $habitacion = Habitacion::find($MovimientoPadre->habitacion_id);
   
           $resultado = DB::select("SELECT calcular_suma_movimiento(?) as suma", [$MovimientoCierre->id]);
   
           // Extrae el valor de la suma
           $sumaTotalPagado = $resultado[0]->suma;
   
           $dataE = [
               'title' => 'DOCUMENTO DE PAGO',
               'ruc_dni' => $rucOdni,
               'direccion' => $direccion,
               'tipoElectronica' => $tipoDocumento,
               'numeroVenta' => $data->nombre_solicitud,
               'fechaemision' => $data->data_solicitud->fechaemision . " " . $data->data_solicitud->horaemision,
               'cliente' => $data->data_solicitud->usuario,
               'vuelto' => $MovimientoCierre->vuelto,
               'totalPagado' => $sumaTotalPagado,
               'detalles' => $data->data_solicitud->detalles,
               'linkRevisarFact' => true,
               'numeroHab' => $habitacion->numero,
               'formaPago' => $formaPago,
               'fechaInicio' => $fechaInicio,
           ];
   
           // Utiliza el método loadView() directamente en la fachada PDF
           $pdf = PDF::loadView('export-pdf-ticket', $dataE);
           $canvas = $pdf->getDomPDF()->get_canvas();
           // $contenidoAncho = $canvas->get_width();
           $contenidoAlto = $canvas->get_height();
           $pdf->setPaper([15, 5, 172, $contenidoAlto - 180], 'portrait');
   
           $fileName = 'DOCUMENTO DE PAGO- ' . $dateTime . '.pdf';
           $fileName = str_replace(' ', '_', $fileName);
   
           error_log($data->nombre_solicitud);
           error_log('AQUI ACABA ABRIR VISTA PDF TICKET CON FACTURACION: tipo:' . $tipoImpresion);
           return $pdf->stream($fileName);
       }
   
       public function exportToPDF_ticket_NoFacturacion(Request $request, $idMov, $tipoImpresion)
       {
           error_log('AQUI EMPIEZA ABRIR VISTA PDF TICKET SIN FACTURACION: tipo:' . $tipoImpresion);
           $MovimientoCierre = Movimiento::find($idMov);
           $MovimientoPadre = Movimiento::find($MovimientoCierre->movimiento_id);
           $detalles = DB::select('call detalleMovimientosCarrito(?)', [$MovimientoPadre->id]);
           $tipoDocumento = 'TICKET ELECTRÓNICO';
           $dateTime = Carbon::now()->format('Y-m-d H:i:s');
           $personaCliente = Persona::find($MovimientoCierre->persona_id);
           $num = $MovimientoCierre->numero;
           $habitacion = Habitacion::find($MovimientoPadre->habitacion_id);
           $fechaInicio = $MovimientoPadre->created_at;
   
           if ($personaCliente->dni == null) {
               $rucOdni = $personaCliente->ruc;
               $nombreCliente = $personaCliente->razonsocial;
           } else {
               $rucOdni = $personaCliente->dni;
               $nombreCliente = $personaCliente->nombres . ' ' . $personaCliente->apellidopaterno;
           }
   
           if ($personaCliente->nombres == 'VARIOS') {
               $rucOdni = '11111111';
               $nombreCliente = "VARIOS";
           }
   
           $resultado = DB::select('SELECT DeterminarFormaPago(?) AS resultado', [$MovimientoPadre->id]);
   
           $formaPago = $resultado[0]->resultado;
   
           $direccion = $personaCliente->direccion == null ? '-' : $personaCliente->direccion;
   
           $resultado = DB::select("SELECT calcular_suma_movimiento(?) as suma", [$idMov]);
   
           // Extrae el valor de la suma
           $sumaTotalPagado = $resultado[0]->suma;
   
           error_log($num);
           $dataE = [
               'title' => 'DOCUMENTO DE PAGO',
               'ruc_dni' => $rucOdni,
               'direccion' => $direccion,
               'tipoElectronica' => $tipoDocumento,
               'numeroVenta' => $num,
               'fechaemision' => $MovimientoCierre->created_at,
               'cliente' => $nombreCliente,
               'detalles' => $detalles,
               'vuelto' => $MovimientoCierre->vuelto,
               'totalPagado' => $sumaTotalPagado,
               'linkRevisarFact' => false,
               'numeroHab' => $habitacion->numero,
               'formaPago' => $formaPago,
               'fechaInicio' => $fechaInicio,
           ];
   
           // Utiliza el método loadView() directamente en la fachada PDF
           $pdf = PDF::loadView('export-pdf-ticket', $dataE);
           $canvas = $pdf->getDomPDF()->get_canvas();
           // $contenidoAncho = $canvas->get_width();
           $contenidoAlto = $canvas->get_height();
           $pdf->setPaper([15, 5, 172, $contenidoAlto - 300], 'portrait');
   
           $fileName = 'DOCUMENTO DE PAGO- ' . $dateTime . '.pdf';
           $fileName = str_replace(' ', '_', $fileName);
   
           error_log('AQUI ACABA ABRIR VISTA PDF TICKET SIN FACTURACION: tipo:' . $tipoImpresion);
           return $pdf->stream($fileName);
   
       }
   //IMPRESION TICKETERA
       public function exportToPDF_ticket_ImpTicketera(Request $request, $data, $tipoImpresion)
       {
           error_log('AQUI EMPIEZA EL ENVIO DE TICKET CON FACTURACION: tipo:' . $tipoImpresion);
   
           $data = json_decode($data);
           $tipoDocumento = $data->tipo_documento == "F" ? 'FACTURA DE VENTA ELECTRÓNICA' : 'BOLETA DE VENTA ELECTRÓNICA';
           $dateTime = Carbon::now()->format('Y-m-d H:i:s');
   
           // $num = rtrim($data->nombre_solicitud, '.');
           // $num = explode("-", $num);
           $direccion = "-";
           switch ($data->tipo_documento) {
               case 'F':
                   $rucOdni = $data->data_solicitud->ruc == null ? '11111111111' : $data->data_solicitud->ruc;
                   $direccion = $data->data_solicitud->direccion;
                   break;
               case 'B':
                   $rucOdni = $data->data_solicitud->dni == null ? '11111111' : $data->data_solicitud->dni;
                   break;
           }
           $MovimientoCierre = Movimiento::withTrashed()
               ->where('numero', $data->nombre_solicitud)
               ->first();
   
           $MovimientoPadre = Movimiento::withTrashed()->find($MovimientoCierre->movimiento_id);
           $fechaInicio = $MovimientoPadre->created_at;
   
           $resultado = DB::select('SELECT DeterminarFormaPago(?) AS resultado', [$MovimientoPadre->id]);
           $formaPago = $resultado[0]->resultado;
   
           $habitacion = Habitacion::find($MovimientoPadre->habitacion_id);
   
           $resultado = DB::select("SELECT calcular_suma_movimiento(?) as suma", [$MovimientoCierre->id]);
   
           // Extrae el valor de la suma
           $sumaTotalPagado = $resultado[0]->suma;
           error_log($data->nombre_solicitud);
           $dataE = [
               'title' => 'DOCUMENTO DE PAGO',
               'ruc_dni' => $rucOdni,
               'direccion' => $direccion,
               'tipoElectronica' => $tipoDocumento,
               'numeroVenta' => $data->nombre_solicitud,
               'fechaemision' => $data->data_solicitud->fechaemision . " " . $data->data_solicitud->horaemision,
               'cliente' => $data->data_solicitud->usuario,
               'vuelto' => $MovimientoCierre->vuelto,
               'totalPagado' => $sumaTotalPagado,
               'detalles' => $data->data_solicitud->detalles,
               'linkRevisarFact' => true,
               'numeroHab' => $habitacion->numero,
               'formaPago' => $formaPago,
               'fechaInicio' => $fechaInicio,
           ];
   
           for ($i = 0; $i < 2; $i++) {
               try {
   
                   // $nombreImpresora = "CAJA";
                   $nombreImpresora = "CAJA";
   
                   // Conexión con la impresora de tickets
                   $connector = new WindowsPrintConnector($nombreImpresora);
   
                   // Crear una instancia de la impresora
                   $printer = new Printer($connector);
                   $printer->setJustification(Printer::JUSTIFY_CENTER);
                   //Contenido del ticket (reemplaza esto con tu contenido)
   
                   $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
   
                   // Texto en negrita
                   $printer->setEmphasis(true);
   
                   $printer->text("SUIT MONTERRICO");
                   $printer->feed();
                   $printer->text("S.A.C.");
                   $printer->feed();
                   $printer->feed();
                   $printer->text("RUC:20602664946");
                   // Restaurar formato regular (sin negrita ni tamaño especial)
                   $printer->setEmphasis(false);
                   $printer->selectPrintMode();
   
                   $printer->feed();
                   //$printer->text("PRO. BOLOGNESI NRO. SN LAMBAYEQUE");
                $printer->text("CHICLAYO CHICLAYO LAMBAYEQUE");
                   $printer->feed();
   
                   $printer->setEmphasis(true);
                   $printer->text($tipoDocumento);
                   $printer->feed();
                   $printer->text($data->nombre_solicitud);
                   $printer->feed();
                   $printer->text(str_repeat("-", 47) . "\n"); // Línea separadora de 40 guiones
                   $printer->feed();
                   $printer->setEmphasis(false);
   
                   $datos = [
                       'FECHA:' => Carbon::now()->format('Y-m-d H:i:s'),
                       'DNI/RUC:' => $rucOdni,
                       'NOMBRE:' => $data->data_solicitud->usuario,
                       'DIRECCIÓN:' => $direccion,
                       'N°HABITACIÓN:' => $habitacion->numero,
                       'FECHA INICIO:' => $fechaInicio,
                   ];
   
                   $maxLength = max(array_map('strlen', array_keys($datos)));
   
                   $printer->feed();
   
                   foreach ($datos as $label => $value) {
                       // Alinea el título a la izquierda y el contenido a la derecha
                       $formattedLine = str_pad($label, $maxLength + 2, ' ', STR_PAD_RIGHT) . str_pad($value, $maxLength + 2, ' ', STR_PAD_LEFT);
                       $printer->text($formattedLine . "\n");
                   }
   
                   $printer->feed();
                   $printer->text(str_repeat("-", 47) . "\n");
                   // Detalles de los productos o servicios
                   $printer->setEmphasis(true);
                   $printer->text(str_pad("Producto", 27) . str_pad("Cant.", 6) . str_pad("Unit.", 6) . str_pad("Subt.", 6) . "\n");
                   // $printer->text(str_repeat("-", 47) . "\n");
                   $printer->setEmphasis(false);
   
                   // $printer->feed();
   
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->feed();
   
                   $totalDetalle = 0;
                   foreach ($data->data_solicitud->detalles as $detalle) {
   
                       $subtotal = $detalle->precioventaunitarioxitem * $detalle->cantidad;
   
                       // Alinea cada columna de la tabla
                       $producto = str_pad($detalle->descripcion, 28);
                       $cantidad = str_pad($detalle->cantidad, 5);
                       $precioUnitario = str_pad($detalle->precioventaunitarioxitem, 6);
                       $subtotalFormatted = number_format($subtotal, 2);
                       $subtotalColumn = str_pad($subtotalFormatted, 6);
   
                       $line = $producto . $cantidad . $precioUnitario . $subtotalColumn . "\n";
   
                       $printer->text($line);
   
                       $totalDetalle += $subtotal;
   
                   }
   
                   // Total
                   $printer->setEmphasis(true);
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->text("Total: " . number_format($totalDetalle, 2) . "\n");
   
                   $printer->feed();
                   $printer->text("Forma de Pago: " . $formaPago . "\n");
   
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->setEmphasis(false);
   
                   $datos = [
                       'Op. Gravada:' => number_format(($totalDetalle / 1.18), 2),
                       'I.G.V.(18%):' => number_format(($totalDetalle - ($totalDetalle / 1.18)), 2),
                       'Op. Inafecta:' => '0.00',
                       'Op. Exonerada:' => '0.00',
                   ];
                   $maxLength = max(array_map('strlen', array_keys($datos)));
   
                   foreach ($datos as $label => $value) {
                       // Alinea el título a la izquierda y el contenido a la derecha
                       $formattedLine = str_pad($label, $maxLength + 2, ' ', STR_PAD_RIGHT) . str_pad($value, $maxLength + 2, ' ', STR_PAD_LEFT);
                       $printer->text($formattedLine . "\n");
                   }
   
                   $printer->text(str_repeat("-", 47) . "\n");
                   // Información de pago
   
                   if ($formaPago != 'Tarjeta' && $formaPago != 'Yape') {
                       $printer->feed();
                       $printer->text("TOTAL APAGAR: " . number_format($totalDetalle, 2) . "\n");
                       $printer->text("EFECTIVO: " . number_format($totalDetalle, 2) + number_format($MovimientoCierre->vuelto, 2) . "\n");
                       $printer->text("VUELTO: " . number_format($MovimientoCierre->vuelto, 2) . "\n");
   
                   }
                   $printer->feed();
                   $printer->setJustification(Printer::JUSTIFY_CENTER);
                   //Contenido del ticket (reemplaza esto con tu contenido)
   
                   // Texto en negrita
                   $printer->setEmphasis(true);
   // Calcular el monto total en palabras y centavos
   
                   $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                   $totalEnPalabras = $formatter->format(floor($totalDetalle));
   
                   if ($totalDetalle != floor($totalDetalle)) {
                       $parteDecimal = round(($totalDetalle - floor($totalDetalle)) * 100);
                       $totalEnPalabras .= " CON $parteDecimal/100";
                   } else {
                       $totalEnPalabras .= " CON 00/100";
                   }
   
   // Luego, puedes imprimirlo en el ticket
   
                   $printer->text("SON: $totalEnPalabras SOLES\n");
   
                   $printer->feed();
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->text("GRACIAS POR SU PREFERENCIA");
   
                   $printer->feed();
                   $printer->feed();
                   // Restaurar formato regular (sin negrita ni tamaño especial)
   
                   $printer->text("Representación impresa del");
                   $printer->feed();
                   $printer->text("Comprobante Electrónico, consulta en");
                   $printer->feed();
                   $printer->text("http://facturae-garzasoft.com");
                   $printer->setEmphasis(false);
                   $printer->selectPrintMode();
   
                   $printer->feed();
                   $printer->cut();
                   $printer->pulse();
   
                   /* Cierre de la impresora */
                   $printer->close();
                   // Redirigir o realizar cualquier otra acción después de la impresión
   
               } catch (\Exception $e) {
   
                   error_log($e->getMessage());
                   return "Error al imprimir el ticket: " . $e->getMessage();
               }
   
           }
           error_log('AQUI ACABA EL ENVIO DE TICKET CON FACTURACION: tipo:' . $tipoImpresion);
           return response('Envio a impresora Exitoso');
       }
   
       public function exportToPDF_ticket_NoFacturacion_ImpTicketera(Request $request, $idMov, $tipoImpresion)
       {
           error_log('AQUI EMPIEZA EL ENVIO DE TICKET SIN FACTURACION: tipo:' . $tipoImpresion);
           $MovimientoCierre = Movimiento::find($idMov);
           $MovimientoPadre = Movimiento::find($MovimientoCierre->movimiento_id);
           $detalles = DB::select('call detalleMovimientosCarrito(?)', [$MovimientoPadre->id]);
           $tipoDocumento = 'TICKET ELECTRÓNICO';
           $dateTime = Carbon::now()->format('Y-m-d H:i:s');
           $personaCliente = Persona::find($MovimientoCierre->persona_id);
           $num = $MovimientoCierre->numero;
           $habitacion = Habitacion::find($MovimientoPadre->habitacion_id);
           $fechaInicio = $MovimientoPadre->created_at;
   
           if ($personaCliente->dni == null) {
               $rucOdni = $personaCliente->ruc;
               $nombreCliente = $personaCliente->razonsocial;
           } else {
               $rucOdni = $personaCliente->dni;
               $nombreCliente = $personaCliente->nombres . ' ' . $personaCliente->apellidopaterno;
           }
   
           if ($personaCliente->nombres == 'VARIOS') {
               $rucOdni = '11111111';
               $nombreCliente = "VARIOS";
           }
   
           $resultado = DB::select('SELECT DeterminarFormaPago(?) AS resultado', [$MovimientoPadre->id]);
   
           $formaPago = $resultado[0]->resultado;
   
           $direccion = $personaCliente->direccion == null ? '-' : $personaCliente->direccion;
   
           $resultado = DB::select("SELECT calcular_suma_movimiento(?) as suma", [$idMov]);
   
           // Extrae el valor de la suma
           $sumaTotalPagado = $resultado[0]->suma;
   
           error_log($num);
           $dataE = [
               'title' => 'DOCUMENTO DE PAGO',
               'ruc_dni' => $rucOdni,
               'direccion' => $direccion,
               'tipoElectronica' => $tipoDocumento,
               'numeroVenta' => $num,
               'fechaemision' => $MovimientoCierre->created_at,
               'cliente' => $nombreCliente,
               'detalles' => $detalles,
               'vuelto' => $MovimientoCierre->vuelto,
               'totalPagado' => $sumaTotalPagado,
               'linkRevisarFact' => false,
               'numeroHab' => $habitacion->numero,
               'formaPago' => $formaPago,
               'fechaInicio' => $fechaInicio,
           ];
           //IMPRIMIR DOS VECES
   
           for ($i = 0; $i < 2; $i++) {
               try {
   
                   // $nombreImpresora = "CAJA";
                   $nombreImpresora = "CAJA";
   
                   // Conexión con la impresora de tickets
                   $connector = new WindowsPrintConnector($nombreImpresora);
   
                   // Crear una instancia de la impresora
                   $printer = new Printer($connector);
   
                   $printer->setJustification(Printer::JUSTIFY_CENTER);
                   //Contenido del ticket (reemplaza esto con tu contenido)
   
                   $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
   
                   // Texto en negrita
                   $printer->setEmphasis(true);
   
                   $printer->text("SUIT MONTERRICO");
                   $printer->feed();
                   $printer->text("S.A.C.");
                   $printer->feed();
                   $printer->feed();
                   $printer->text("RUC:20602664946");
                   // Restaurar formato regular (sin negrita ni tamaño especial)
                   $printer->setEmphasis(false);
                   $printer->selectPrintMode();
   
                   $printer->feed();
                  //$printer->text("PRO. BOLOGNESI NRO. SN LAMBAYEQUE");
                $printer->text("CHICLAYO CHICLAYO LAMBAYEQUE");
                   $printer->feed();
   
                   $printer->setEmphasis(true);
                   $printer->text($tipoDocumento);
                   $printer->feed();
                   $printer->text($num);
                   $printer->feed();
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->feed();
                   $printer->setEmphasis(false);
   
                   // Datos del cliente
                   $datos = [
                       'FECHA:' => Carbon::now()->format('Y-m-d H:i:s'),
                       'DNI/RUC:' => $rucOdni,
                       'NOMBRE:' => $nombreCliente,
                       'DIRECCIÓN:' => $direccion,
                       'N°HABITACIÓN:' => $habitacion->numero,
                       'FECHA INICIO:' => $fechaInicio,
                   ];
   
                   $maxLength = max(array_map('strlen', array_keys($datos)));
   
                   $printer->feed();
   
                   foreach ($datos as $label => $value) {
                       // Alinea el título a la izquierda y el contenido a la derecha
                       $formattedLine = str_pad($label, $maxLength + 2, ' ', STR_PAD_RIGHT) . str_pad($value, $maxLength + 2, ' ', STR_PAD_LEFT);
                       $printer->text($formattedLine . "\n");
                   }
   
                   $printer->feed();
   
                   $printer->text(str_repeat("-", 47) . "\n");
                   // Detalles de los productos o servicios
                   $printer->text(str_pad("Producto", 27) . str_pad("Cant.", 6) . str_pad("Unit.", 6) . str_pad("Subt.", 6) . "\n");
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->setEmphasis(false);
   
                   $printer->feed();
   
                   $totalDetalle = 0;
                   foreach ($detalles as $detalle) {
   
                       $subtotal = $detalle->precioventaunitarioxitem * $detalle->cantidad;
   
                       // Alinea cada columna de la tabla
   
                       $producto = str_pad($detalle->descripcion, 28);
                       $cantidad = str_pad($detalle->cantidad, 5);
                       $precioUnitario = str_pad($detalle->precioventaunitarioxitem, 5);
                       $subtotalFormatted = number_format($subtotal, 2);
                       $subtotalColumn = str_pad($subtotalFormatted, 6);
   
                       $separator = str_repeat(' ', 1); // Espacio entre las columnas
   
                       $line = $producto . $separator . $cantidad . $separator . $precioUnitario . $separator . $subtotalColumn . "\n";
   
                       $printer->text($line);
   
                       $totalDetalle += $subtotal;
                   }
   
                   // Total
                   $printer->setEmphasis(true);
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->text("Total: " . number_format($totalDetalle, 2) . "\n");
   
                   $printer->feed();
                   $printer->text("Forma de Pago: " . $formaPago . "\n");
   
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->setEmphasis(false);
                   // Información de pago
   
                   if ($formaPago != 'Tarjeta' && $formaPago != 'Yape') {
                       $printer->feed();
                       $printer->text("TOTAL APAGAR: " . number_format($totalDetalle, 2) . "\n");
                       $printer->text("EFECTIVO: " . number_format($totalDetalle, 2) + number_format($MovimientoCierre->vuelto, 2) . "\n");
                       $printer->text("VUELTO: " . number_format($MovimientoCierre->vuelto, 2) . "\n");
   
                   }
   
                   $printer->feed();
   
                   $printer->setJustification(Printer::JUSTIFY_CENTER);
                   //Contenido del ticket (reemplaza esto con tu contenido)
   
                   // Texto en negrita
                   $printer->setEmphasis(true);
   // Calcular el monto total en palabras y centavos
   
                   $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                   $totalEnPalabras = $formatter->format(floor($totalDetalle));
   
                   if ($totalDetalle != floor($totalDetalle)) {
                       $parteDecimal = round(($totalDetalle - floor($totalDetalle)) * 100);
                       $totalEnPalabras .= " CON $parteDecimal/100";
                   } else {
                       $totalEnPalabras .= " CON 00/100";
                   }
   
   // Luego, puedes imprimirlo en el ticket
   
                   $printer->text("SON: $totalEnPalabras SOLES\n");
                   $printer->feed();
                   $printer->text(str_repeat("-", 47) . "\n");
                   $printer->text("GRACIAS POR SU PREFERENCIA");
   
                   $printer->feed();
   
                   // Pie de página
                   $printer->feed();
   
                   $printer->cut();
                   $printer->pulse();
   
                   /* Cierre de la impresora */
                   $printer->close();
                   // Redirigir o realizar cualquier otra acción después de la impresión
   
               } catch (\Exception $e) {
   
                   error_log($e->getMessage());
                   return "Error al imprimir el ticket: " . $e->getMessage();
               }
           }
   
           error_log('AQUI ACABA EL ENVIO DE TICKET SIN FACTURACION: tipo:' . $tipoImpresion);
           return response('Envio a impresora Exitoso');
       }
   

    public function exportReportesEmergencia(Request $request)
    {

        $Responsable = Persona::find(Auth::user()->persona_id);

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        $habitacionesOcupadas = DB::select('call habitacionesOcupadasYmov');

        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsable' => $Responsable->nombres . " " . $Responsable->apellidopaterno,
            'dateTime' => $dateTime,
            'habitacionesOcupadas' => $habitacionesOcupadas,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF
        $pdf = PDF::loadView('export-pdf-emergencia', $data);
        $pdf->setPaper('a4', 'portrait'); // A4 en formato vertical (retrato)

        $fileName = 'Reporte de Emergencia de Habitaciones - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }

    public function exportDetalleVenta(Request $request)
    {

        $Responsable = Persona::find(Auth::user()->persona_id);

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        $habitacionesOcupadas = DB::select('call habitacionesOcupadasYmov');

        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsable' => $Responsable->nombres . " " . $Responsable->apellidopaterno,
            'dateTime' => $dateTime,
            'habitacionesOcupadas' => $habitacionesOcupadas,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF
        $pdf = PDF::loadView('export-pdf-emergencia', $data);
        $pdf->setPaper('a4', 'portrait'); // A4 en formato vertical (retrato)

        $fileName = 'Reporte de Emergencia de Habitaciones - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }
    public function exportToCuadreCaja(Request $request, $idApertura, $idCierre)
    {

        $Responsable = User::find(Auth::user()->id);
        $persona = Persona::find($Responsable->persona_id);
        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        $totalIngresos = 0;
        $TotalEgresos = 0;
        $TotalCaja = 0;



       

        if ($idCierre == 'undefined' || $idCierre == 'null') {

            $nombrePersonaCierre = '-';
            $movCierreTota = '-';
            $fechaMovCierre = '-';
            $idC = null;
            $movApertura = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();

            $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompra(1) AS 'resultado'");
            $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;

            $ResponsableA = User::find($movApertura->usuario_id);
            $personaA = Persona::find($ResponsableA->persona_id);
            $montoCaja = DB::select('call detalleCierre');
            
     $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompra(4) AS 'resultado'");
            $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;
        } else {
            $idC = $idCierre;
            $movCierre = Movimiento::where('id', $idCierre)->first();
            $ResponsableC = User::find($movCierre->usuario_id);
            $personaC = Persona::find($ResponsableC->persona_id);
            $nombrePersonaCierre = $personaC->nombres;
            $movCierreTota = $movCierre->total;
            $fechaMovCierre = $movCierre->created_at;
            $movApertura = Movimiento::where('id', $idApertura)->first();
            $resultadoVenta = DB::select("SELECT calcularSumaPorVentaoCompraReportesCierre(4," . $idApertura . "," . $idCierre . ") AS 'resultado'"); // concepto 4 es pago de clientes
            $TotaPagosCliente = $resultadoVenta[0]->resultado != null ? $resultadoVenta[0]->resultado : 0;


            $montoCaja = DB::select('call detalleCierreReportesCierre(?, ?)', [$idApertura, $idCierre]);
            $resultadoCompra = DB::select("SELECT calcularSumaPorVentaoCompraReportesCierre(1," . $idApertura . "," . $idCierre . ") AS 'resultado'"); // concepto 1 es pago compras
            $TotaCompras = $resultadoCompra[0]->resultado != null ? $resultadoCompra[0]->resultado : 0;
          

            $ResponsableA = User::find($movApertura->usuario_id);
            $personaA = Persona::find($ResponsableA->persona_id);

        }

        
        if (count($montoCaja) > 0) {

            $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
            $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

            if ($ingreso != null) {
                $totalIngresos = $ingreso->totalAcum;
                $TotalCaja = $ingreso->totalAcum;
            }
            if ($egreso != null) {
                $TotalEgresos = $egreso->totalAcum;
                $TotalCaja = $egreso->totalAcum;
            }

            if ($egreso != null && $ingreso != null) {
                $TotalCaja = $ingreso->totalAcum - $egreso->totalAcum;
            }
        }

        $ingreso = isset($montoCaja[0]) ? $montoCaja[0] : null;
        $egreso = isset($montoCaja[1]) ? $montoCaja[1] : null;

        $movAnulados = DB::select('call movimientosAnuladosReportes(?, ?)', [$idApertura, $idC]);
        $movAperturaTota = $movApertura ? $movApertura->total : 0;

        $turno = $movApertura ? $movApertura->turno : '-';

        $movXEfectivoId = $this->obtenerValorMovimiento('Efectivo', $idApertura, $idC);
        $movXTarjetaId = $this->obtenerValorMovimiento('Tarjeta', $idApertura, $idC);
        $movXDepositoId = $this->obtenerValorMovimiento('Deposito', $idApertura, $idC);
        $movXYapeId = $this->obtenerValorMovimiento('Yape', $idApertura, $idC);
        $movXPlinId = $this->obtenerValorMovimiento('Plin', $idApertura, $idC);
        $movXCombinadoId = $this->obtenerValorMovimiento('Combinado', $idApertura, $idC);

        $movXEfectivoIdOtrosMov = $this->obtenerValorOtrosMovimiento('Efectivo', $idApertura, $idC, 'Ingreso');
        $movXTarjetaIdOtrosMov = $this->obtenerValorOtrosMovimiento('Tarjeta', $idApertura, $idC, 'Ingreso');
        $movXDepositoIdOtrosMov = $this->obtenerValorOtrosMovimiento('Deposito', $idApertura, $idC, 'Ingreso');
        $movXYapeIdOtrosMov = $this->obtenerValorOtrosMovimiento('Yape', $idApertura, $idC, 'Ingreso');
        $movXPlinIdOtrosMov = $this->obtenerValorOtrosMovimiento('Plin', $idApertura, $idC, 'Ingreso');
        $movXCombinadoIdOtrosMov = $this->obtenerValorOtrosMovimiento('Combinado', $idApertura, $idC, 'Ingreso');

        $movXEfectivoIdOtrosMovEgresados = $this->obtenerValorOtrosMovimiento('Efectivo', $idApertura, $idC, 'Egreso');
        $movXTarjetaIdOtrosMovEgresados = $this->obtenerValorOtrosMovimiento('Tarjeta', $idApertura, $idC, 'Egreso');
        $movXDepositoIdOtrosMovEgresados = $this->obtenerValorOtrosMovimiento('Deposito', $idApertura, $idC, 'Egreso');
        $movXYapeIdOtrosMovEgresados = $this->obtenerValorOtrosMovimiento('Yape', $idApertura, $idC, 'Egreso');
        $movXPlinIdOtrosMovEgresados = $this->obtenerValorOtrosMovimiento('Plin', $idApertura, $idC, 'Egreso');
        $movXCombinadoIdOtrosMovEgresados = $this->obtenerValorOtrosMovimiento('Combinado', $idApertura, $idC, 'Egreso');

        $resumenCuadreCaja = DB::select('CALL resumenCuadreCaja(?, ?)', [$idApertura, $idC]);
        $data = [
            'title' => 'ARQUEO DE CAJA',
            'responsable' => $persona->nombres,

            'responsableA' => $personaA->nombres,

            'montoApertura' => $movAperturaTota,
            'fechaApertura' => $movApertura->created_at,
            'turno' => $turno,
            'Compras' => $TotaCompras,
            'TotalCaja' => number_format($TotalCaja, 2, '.', ','),

            'responsableC' => $nombrePersonaCierre,
            'montoCierre' => $movCierreTota,
            'fechaCierre' => $fechaMovCierre,
            'Ventas' => $TotaPagosCliente,
            
            'otrosIngresos' => number_format($totalIngresos - $TotaPagosCliente - $movAperturaTota, 2, '.', ','),
            'otrosEgresos' => number_format($TotalEgresos - $TotaCompras, 2, '.', ','),


            'movXEfectivo' => $movXEfectivoId,
            'movXTarjeta' => $movXTarjetaId,
            'movXDeposito' => $movXDepositoId,
            'movXYape' => $movXYapeId,
            'movXPlin' => $movXPlinId,
            'movXCombinado' => $movXCombinadoId,

            'movXEfectivoOtrosMov' => $movXEfectivoIdOtrosMov,
            'movXTarjetaOtrosMov' => $movXTarjetaIdOtrosMov,
            'movXDepositoOtrosMov' => $movXDepositoIdOtrosMov,
            'movXYapeOtrosMov' => $movXYapeIdOtrosMov,
            'movXPlinOtrosMov' => $movXPlinIdOtrosMov,
            'movXCombinadoOtrosMov' => $movXCombinadoIdOtrosMov,

            'movXEfectivoOtrosMovEgreso' => $movXEfectivoIdOtrosMovEgresados,
            'movXTarjetaOtrosMovEgreso' => $movXTarjetaIdOtrosMovEgresados,
            'movXDepositoOtrosMovEgreso' => $movXDepositoIdOtrosMovEgresados,
            'movXYapeOtrosMovEgreso' => $movXYapeIdOtrosMovEgresados,
            'movXPlinOtrosMovEgreso' => $movXPlinIdOtrosMovEgresados,
            'movXCombinadoOtrosMovEgreso' => $movXCombinadoIdOtrosMovEgresados,
            'resumenCuadreCaja' => $resumenCuadreCaja,

            'movAnulados' => $movAnulados,
            'dateTime' => $dateTime,
            'ingreso' => $ingreso,
            'egreso' => $egreso,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF
        $pdf = PDF::loadView('export-pdf-cuadreCaja', $data);
        $pdf->setPaper('a4', 'portrait'); // A4 en formato vertical (retrato)

        $fileName = 'Reporte de Cuadre Caja - ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }

    private function obtenerValorMovimiento($formaPago, $movIdApertura, $movIdCierre)
    {
        $resultados = DB::select('CALL ConsultarMovimientosPorFormaPago(?, ?, ?)', [$formaPago, $movIdApertura, $movIdCierre]);

        if (!empty($resultados)) {
            return $resultados;
        } else {
            return "No se encontraron Movimientos";
        }
    }
    private function obtenerValorOtrosMovimiento($formaPago, $movIdApertura, $movIdCierre, $tipo)
    {
        $resultados = DB::select('CALL ConsultarOtrosMovimientosPorFormaPago(?, ?, ?, ?)', [$movIdApertura, $movIdCierre, $formaPago, $tipo]);

        if (!empty($resultados)) {
            return $resultados;
        } else {
            return "No se encontraron Movimientos";
        }
    }
    public function exportToPDF_Egresos(Request $request)
    {


        $dateTime = Carbon::now()->format('Y-m-d H:i:s');
 
        $movimientosCajaEgresos = DB::select('CALL showEgresos(?, ?)', array(null, null));

        $Responsable = User::find(Auth::user()->id);
        $persona = Persona::find($Responsable->persona_id);
        $dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $movApertura = Movimiento::where('situacion', 'Normal')->where('conceptopago_id', '5')->first();

        $ResponsableA = User::find($movApertura->usuario_id);
        $personaA = Persona::find($ResponsableA->persona_id);

        $movAperturaTota = $movApertura ? $movApertura->total : 0;

        $turno = $movApertura ? $movApertura->turno : '-';
        
        $dataE = [
            'title' => 'ARQUEO DE CAJA',
            'movimientosCajaEgresos' => $movimientosCajaEgresos,
            'responsable' => $persona->nombres,
            'responsableA' => $personaA->nombres,
            'montoApertura' => $movAperturaTota,
            'fechaApertura' => $movApertura->created_at,
            'turno' => $turno,
            'dateTime' => $dateTime,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF


        $pdf = PDF::loadView('export-pdf-Egreso', $dataE);
        $canvas = $pdf->getDomPDF()->get_canvas();
        // $contenidoAncho = $canvas->get_width();
        $contenidoAlto = $canvas->get_height();
        $pdf->setPaper([15, 5, 172, $contenidoAlto -400], 'portrait');

        $fileName = 'Reporte de Egresos ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }
    public function exportToPDF_Stock(Request $request)
    {


        $dateTime = Carbon::now()->format('Y-m-d H:i:s');
 
        $movProductos = DB::select('CALL showProductos');
        $movProductos = $this->filtrarProductosActivos($movProductos);
        $movProductos = $this->enriquecerProductosConStockHabitacion($movProductos);

        $Responsable = User::find(Auth::user()->id);
        $persona = Persona::find($Responsable->persona_id);
        $dateTime = Carbon::now()->format('Y-m-d H:i:s');




        
        $dataE = [
            'title' => 'ARQUEO DE CAJA',
            'Productos' => $movProductos,
            'responsable' => $persona->nombres,


            'dateTime' => $dateTime,
        ];
        // Utiliza el método loadView() directamente en la fachada PDF


        $pdf = PDF::loadView('export-pdf-StockProd', $dataE);
        $canvas = $pdf->getDomPDF()->get_canvas();
        // $contenidoAncho = $canvas->get_width();
        $contenidoAlto = $canvas->get_height();
        $pdf->setPaper([15, 5, 172, $contenidoAlto -300], 'portrait');

        $fileName = 'Reporte de Stock Productos ' . $dateTime . '.pdf';
        $fileName = str_replace(' ', '_', $fileName);

        return $pdf->stream($fileName);
    }
}
