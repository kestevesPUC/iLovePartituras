<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Dados da Nota</span>
        </h3>
        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" data-kt-initialized="1">
       @if ($order->status == 1)
           <a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#lowOrder">
               <span class="svg-icon svg-icon-3">
                   <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                       <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                             transform="rotate(-90 11.364 20.364)" fill="currentColor"></rect>
                       <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                             fill="currentColor"></rect>
                   </svg>
               </span>
               Baixa Manual
           </a>
       @elseif ($order->status == 2)
           <a onclick="donwloadFile('{{route('order.download.file', ['id' => $order->id])}}')"
              title="Download NF-se"
              class="btn btn-primary m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
               <i style="color: white" class="la la-cloud-download"></i>
               Download do Pedido
           </a>
       @endif
   </div>
    </div>
</div>
<div class="card p-15 mb-5 mb-xl-10">
    <div class="panel panel-default">
        <div class="panel-body">
            <br>
            <table class="table m-table table-condensed table-hover table-striped">
                <thead>
                <tr>
                    <th class="text-center">Número NFS-e</th>
                    <th class="text-center">Código Verificação</th>
                    <th class="text-center">Data da Emissão</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">NFS-e Substituida</th>
                    <th class="text-center" style="width: 330px;">Ações</th>
                </tr>
                </thead>
                @foreach ($invoices as $invoice)
                    <tbody>
                    <tr class="{{empty($invoice->code_cancellation) ? '' : 'm-table__row--danger' }}">
                        <td class="text-center">
                            {{ $invoice->number_nfse != '' ? $invoice->number_nfse : 'RPS: ' . $invoice->number_rps }}
                        </td>
                        <td class="text-center">{{ $invoice->code_verification }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($invoice->date_emission_nfse)->format('d/m/Y H:i:s') }}
                        </td>
                        @if (!empty($invoice->code_cancellation))
                            <td class="text-center">Cancelada</td>
                        @else
                            <td class="text-center">
                                {{ $invoice->number_nfse != '' ? 'Emitida' : 'Lote enviado' }}</td>
                        @endif
                        <td class="text-center">{{ $invoice->number_nfse_substitute }} </td>
                        <td class="text-center">
                            <div class="m-demo-icon__preview">
                                @if ($invoice->number_nfse != '' && empty($invoice->code_cancellation || \Carbon\Carbon::parse($invoice->date_emission_nfse)->addMonths(1)->format('Y-m-03') <= \Carbon\Carbon::now()->format('Y-m-03')))
                                    <a onclick="cancelNFse('{{route('order.action.nfse', ['numberNfse' => Illuminate\Support\Facades\Crypt::encrypt($invoice->number_nfse), 'cnpjProvider' => Illuminate\Support\Facades\Crypt::encrypt($invoice->cnpj_provider), 'action' => 1])}}')"
                                       title="Cancelar NFse"
                                       class="btn btn-danger m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                        <i style="color: white" class="la la-ban"></i>
                                    </a>
                                @endif
                                @if ($invoice->number_nfse != '')
                                    <a onclick="donwloadFile('{{route('order.action.nfse', ['numberNfse' => Illuminate\Support\Facades\Crypt::encrypt($invoice->number_nfse), 'cnpjProvider' => Illuminate\Support\Facades\Crypt::encrypt($invoice->cnpj_provider), 'action' => 2])}}')"
                                       title="Download XML"
                                       class="btn btn-warning m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                        <i style="color: white" class="la la-file-code-o"></i>
                                    </a>
                                    <a onclick="donwloadFile('{{route('order.action.nfse', ['numberNfse' => Illuminate\Support\Facades\Crypt::encrypt($invoice->number_nfse), 'cnpjProvider' => Illuminate\Support\Facades\Crypt::encrypt($invoice->cnpj_provider), 'action' => 3])}}')"
                                       title="Download NF-se"
                                       class="btn btn-primary m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                        <i style="color: white" class="la la-cloud-download"></i>
                                    </a>
                                    @if (empty($invoice->code_cancellation))
                                        <a onclick="viewNFe('{{route('order.action.nfse', ['numberNfse' => Illuminate\Support\Facades\Crypt::encrypt($invoice->number_nfse), 'cnpjProvider' => Illuminate\Support\Facades\Crypt::encrypt($invoice->cnpj_provider), 'action' => 4])}}')"
                                           title="Visualizar NF-se"
                                           class="btn btn-info m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                            <i style="color: white" class="la la-eye"></i>
                                        </a>
                                    @endif
                                    <a onclick="modalSendEmail('{{route('order.action.nfse', ['numberNfse' => Illuminate\Support\Facades\Crypt::encrypt($invoice->number_nfse), 'cnpjProvider' => Illuminate\Support\Facades\Crypt::encrypt($invoice->cnpj_provider), 'action' => 5, 'emailTaker' => Illuminate\Support\Facades\Crypt::encrypt($invoice->email_taker)]) }}')"
                                       class="btn btn-success m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air">
                                        <i style="color: white" class="la la-send"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGeral" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true"></div>
<div id="myModal"></div>

@include('order.modals.notaFiscal')



