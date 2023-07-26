<!--begin::Modal-->
<div class="modal fade" id="modalNota" tabindex="-1" role="dialog" aria-labelledby="modalNota" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Nota Fiscal
                </h5>

                <button type="button" onclick="(function(){location.reload();})()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="htmlNota"></div>
            </div>
            <div class="modal-footer">
                <button type="button"  onclick="(function(){location.reload();})()"  class="btn btn-default pull-left" data-dismiss="modal">
                    Fechar
                </button>

                <button type="button" class="btn btn-info" onclick="javascript: $('.htmlNota').printThis();">
                    <i class="la la-print"></i> Imprimir Nota
                </button>

                <button type="button" class="btn btn-primary baixarNota" onclick="">
                    <i class="la la-cloud-download"></i> Download Nota
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
