<div class="modal fade" id="modalSendEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Enviar E-mail
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                &times;
                </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                            <strong style="color:#575962">
                                <i class="la la-exclamation-triangle"></i> Atenção!
                            </strong>
                            <p style="color:#575962">
                            Para adicionar outros destinatários informe os emails no campo ao lado separado por ponto e virgula.<br><br>
                            Ex: exemplo@assinar.com.br; teste@assinar.com.br
                            <p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input id="email_email_taker" class="form-control" name="email_email_taker" style="text-transform:uppercase" value="{{ Illuminate\Support\Facades\Crypt::decrypt($email_taker) }}" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-info" onclick="sendEmail('{{route('order.action.nfse', ['numberNfse' => $number_nfse, 'cnpjProvider' => $cnpj_provider, 'action' => 6]) }}');">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
