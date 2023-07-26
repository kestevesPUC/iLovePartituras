// -----------------------------------------------------------------------------------------------------
// This file contains logic for calling the Web PKI component to sign a batch of documents. It is only
// an example, feel free to alter it to meet your application's needs.
// -----------------------------------------------------------------------------------------------------
var batchSignatureForm = (function() {

    // The Javascript class "Queue" defined here helps to process the documents in the batch. You don't necessarily need
    // to understand this code, only how to use it (see the usage below on the function startBatch)
    (function() {
        window.Queue = function() {
            this.items = [];
            this.writerCount = 0;
            this.readerCount = 0;
        };
        window.Queue.prototype.add = function(e) {
            this.items.push(e);
        };
        window.Queue.prototype.addRange = function(array) {
            for (var i = 0; i < array.length; i++) {
                this.add(array[i]);
            }
        };
        var _process = function(inQueue, processor, outQueue, endCallback) {
            var obj = inQueue.items.shift();
            if (obj !== undefined) {
                processor(obj, function(result) {
                    if (result != null && outQueue != null) {
                        outQueue.add(result);
                    }
                    _process(inQueue, processor, outQueue, endCallback);
                });
            } else if (inQueue.writerCount > 0) {
                setTimeout(function() {
                    _process(inQueue, processor, outQueue, endCallback);
                }, 200);
            } else {
                --inQueue.readerCount;
                if (outQueue != null) {
                    --outQueue.writerCount;
                }
                if (inQueue.readerCount == 0 && endCallback) {
                    endCallback();
                }
            }
        };
        window.Queue.prototype.process = function(processor, options) {
            var threads = options.threads || 1;
            this.readerCount = threads;
            if (options.output) {
                options.output.writerCount = threads;
            }
            for (var i = 0; i < threads; i++) {
                _process(this, processor, options.output, options.completed);
            }
        };
    })();

    // Auxiliary global variables
    var selectedCertThumbprint = null;
    var startQueue = null;
    var performQueue = null;
    var completeQueue = null;
    var batchDocIds = null;

    var currentNumber = 0;
    var successDocs = [];
    var errorDocs = [];

    // Create an instance of the LacunaWebPKI object
    var pki = new LacunaWebPKI('AmECYXBpaW50cmFuZXQua3J5cHRvbmJwby5jb20uYnIsYXJpbmZvZ2VkLmNvbS5icixhcmxnLmNvbS5icixhc3NpbmFkb2RpZ2l0YWxtZW50ZS5jb20uYnIsYXNzaW5hZG9yLmxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyLGFzc2luYXJ3ZWIuY29tLmJyLGFzc2luYXR1cmEubW9zdHNpZ24uY29tLmJyLGNoYXQua3J5cHRvbmJwby5jb20uYnIsZW1pc3Nvci5yZWRla3J5cHRvbi5jb20uYnIsaG9tb2xvZ2FjYW8ubW9zdHNpZ24uY29tLmJyLGtyeXB0b25wYXkuY29tLmJyLGxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyLGxvb2tjZXJ0aWZpY2FjYW8uY29tLmJyLG5mLnJlZGVrcnlwdG9uLmNvbS5icixyZW5vdmFjYW8ubGlua2NlcnRpZmljYWNhby5jb20uYnIsc2lzdGVtYXMua3J5cHRvbmJwby5jb20uYnIsc2lzdGVtYXMubGlua2NlcnRpZmljYWNhby5jb20uYnIsc2lzdGVtYXMucmVkZWtyeXB0b24uY29tLmJyLHNpc3RlbWFzMi5saW5rY2VydGlmaWNhY2FvLmNvbS5icix0ZXN0ZXRlY25pY28ubGlua2NlcnRpZmljYWNhby5jb20uYnIsd3d3LmxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyLHd3dy5sb29rY2VydGlmaWNhY2FvLmNvbS5icix3d3cyLmxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyfgBob21vbG9nYWFzc2luYWRvci5saW5rY2VydGlmaWNhY2FvLmNvbS5icixpcDQ6MTAuMC4wLjAvOCxpcDQ6MTI3LjAuMC4wLzgsaXA0OjE3Mi4xNi4wLjAvMTIsaXA0OjE5Mi4xNjguMC4wLzE2LGxvY2FsLmFzc2luYXR1cmEDAFBybwAAAAEgKyJf3DaqglEbhc0+EIhzxu202G4e6O19o1bCB8lxmPrPLbdk/kcFs8MpGLebX0yXLu5Jdf6CwtZ3KuJkugDVnPRdwxPUUmCfZK6z02TPQtkvF0VWxg3po6O2cHPiOklJKTeuMWAb4ae282uYgBQFVe1Oi+S6rC1/bOnVIUUXqisOkQZ95zKYYFExqVldWZeuddLgcwrMBVnIxVVgWQ7TKEWBTFSCzz3YxPYJezMzqaVXAf/D3qYb/Jy3p7h9thMPoqzJ53FUPJPEIkaLw/rAVyrNgxee2i79JDPSgwJXxKW8saNlFxpjBEYgaWHPLceuhypyiX2XutyOLmaQZHOz');

    // -------------------------------------------------------------------------------------------------
    // Function called once the page is loaded
    // -------------------------------------------------------------------------------------------------
    function init(args) {

        // Receive the documents ids
        batchDocIds = args.documentsIds;

        // Wireup of button clicks
        args.signButton.click(sign);
        args.refreshButton.click(refresh);

        // Block the UI while we get things ready
        BlockUI.block('body', 'Carregando certificados...');

        // Render documents to be signed
        var docList = $('#docList');
        for (var i = 0; i < batchDocIds.length; i++) {
            var docId = batchDocIds[i];
            docList.append(
                $('<li />').append(
                    $('<a />').text('Document ' + docId).attr('href', "content/" + docId + ".pdf")
                )
            );
        }

        // Call the init() method on the LacunaWebPKI object, passing a callback for when
        // the component is ready to be used and another to be called when an error occurrs
        // on any of the subsequent operations. For more information, see:
        // https://webpki.lacunasoftware.com/#/Documentation#coding-the-first-lines
        // http://webpki.lacunasoftware.com/Help/classes/LacunaWebPKI.html#method_init
        pki.init({
            restPkiUrl: 'https://restpki.linkcertificacao.com.br/',
            ready: loadCertificates, // as soon as the component is ready we'll load the certificates
            defaultError: onWebPkiError, // generic error callback
            brand: 'link'
        });
    }

    // -------------------------------------------------------------------------------------------------
    // Function called when the user clicks the "Refresh" button
    // -------------------------------------------------------------------------------------------------
    function refresh() {
        // Block the UI while we load the certificates
        $.blockUI();
        // Invoke the loading of the certificates
        loadCertificates();
    }

    // -------------------------------------------------------------------------------------------------
    // Function that loads the certificates, either on startup or when the user
    // clicks the "Refresh" button. At this point, the UI is already blocked.
    // -------------------------------------------------------------------------------------------------
    function loadCertificates() {

        var validarCpfCnpj = (window.user.nome.length == 11) ? pki.filters.pkiBrazilCpfEquals(window.user.nome) : pki.filters.pkiBrazilCnpjEquals(window.user.nome);

        // Call the listCertificates() method to list the user's certificates. For more information see
        // http://webpki.lacunasoftware.com/Help/classes/LacunaWebPKI.html#method_listCertificates
        pki.listCertificates({

            // specify that expired certificates should be ignored
            filter: pki.filters.all(pki.filters.isWithinValidity, validarCpfCnpj),

            // in order to list only certificates within validity period and having a CPF (ICP-Brasil), use this instead:
            //filter: pki.filters.all(pki.filters.hasPkiBrazilCpf, pki.filters.isWithinValidity),

            // id of the select to be populated with the certificates
            selectId: 'certificateSelect',

            // function that will be called to get the text that should be displayed for each option
            selectOptionFormatter: function(cert) {
                return cert.subjectName + ' (Emitido por ' + cert.issuerName + ')';
            }

        }).success(function(certificados) {

            if (certificados.length == 0) {
                alert(
                    (window.user.nome.length == 11) ?
                    'Você não possui nenhum certificado com seu número de e-CPF instalado/acoplado ao seu dispositivo, verifique seu certificado antes de prosseguir!' :
                    'Você não possui nenhum certificado com seu número de e-CNPJ instalado/acoplado ao seu dispositivo, verifique seu certificado antes de prosseguir!'
                );
            }
            // once the certificates have been listed, unblock the UI
            $.unblockUI();

        });
    }

    // -------------------------------------------------------------------------------------------------
    // Function called when the user clicks the "Sign Batch" button
    // -------------------------------------------------------------------------------------------------
    function sign() {

        // Block the UI while we perform the signature
        BlockUI.block('body', 'Iniciando processo de assinatura...');

        // Get the thumbprint of the selected certificate and store it in a global variable (we'll need it later)
        selectedCertThumbprint = $('#certificateSelect').val();

        // Call Web PKI to preauthorize the signatures, so that the user only sees one confirmation dialog
        pki.preauthorizeSignatures({
            certificateThumbprint: selectedCertThumbprint,
            signatureCount: batchDocIds.length // number of signatures to be authorized by the user
        }).success(startBatch); // callback to be called if the user authorizes the signatures
    }

    // -------------------------------------------------------------------------------------------------
    // Function called when the user authorizes the signatures
    // -------------------------------------------------------------------------------------------------
    function startBatch() {

        /*
         For each document, we must perform 3 actions in sequence:

         1. Start the signature    : call batch-signature-start.php to start the signature and get the signature process token
         2. Perform the signature  : call Web PKI's method signWithRestPki with the token
         3. Complete the signature : call batch-signature-complete.php to notify that the signature is complete

         We'll use the Queue Javascript class defined above in order to perform these steps simultaneously.
         */

        // Create the queues
        startQueue = new Queue();
        performQueue = new Queue();
        completeQueue = new Queue();

        // Add all documents to the first ("start") queue
        for (var i = 0; i < batchDocIds.length; i++) {
            startQueue.add({ index: i, docId: batchDocIds[i] });
        }

        /*
         Process each queue placing the result on the next queue, forming a sort of "assembly line":

         startQueue                              performQueue                               completeQueue
         -------------                            -------------                              -------------
         XXXXXXX        ->  (startSignature)  ->             XX  ->  (performSignature)  ->            XXX  ->  (completeSignature)
         -------------         2 threads          -------------          2 threads           -------------           2 threads
         */
        startQueue.process(startSignature, { threads: 2, output: performQueue });
        performQueue.process(performSignature, { threads: 2, output: completeQueue });
        completeQueue.process(completeSignature, { threads: 2, completed: onBatchCompleted }); // onBatchCompleted is a callback for when the last queue is completely processed

        // Notice: the thread count on each call above is already optimized, increasing the number of threads will
        // not enhance the performance significatively
    }

    // -------------------------------------------------------------------------------------------------
    // Function that performs the first step described above for each document, which is the call
    // batch-signature-start.php in order to start the signature and get the token associated with the
    // signature process.
    //
    // This function is called by the Queue.process function, taking documents from the "start" queue.
    // Once we're done, we'll call the "done" callback passing the document, and the Queue.process
    // function will place the document on the "perform" queue to await processing.
    // -------------------------------------------------------------------------------------------------
    function startSignature(step, done) {
        // Call the server asynchronously to start the signature (the server will call REST PKI and will return the signature operation token)
        $.ajax({
            url: '/' + sistemaAssinador.prefix + '/api/assinatura/iniciar',
            method: 'POST',
            data: {
                id: step.docId,
                '_token': $("meta[name='csrf-token']").attr('content')
            },
            dataType: 'json',
            success: function(token) {
                currentNumber++;
                $.blockUI({ message: '<h2>Processando documento ' + (currentNumber) + '/' + (batchDocIds.length) + '</h2>', baseZ: 99999999 });
                // Add the token to the document information (we'll need it in the second step)
                step.token = token;
                // Call the "done" callback signalling we're done with the document
                done(step);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Render error
                renderFail(step, errorThrown || textStatus);
                // Call the "done" callback with no argument, signalling the document should not go to the next queue
                done();
            }
        });
    }

    // -------------------------------------------------------------------------------------------------
    // Function that performs the second step described above for each document, which is the call to
    // Web PKI's signWithRestPki function using the token acquired on the first step.
    //
    // This function is called by the Queue.process function, taking documents from the "perform" queue.
    // Once we're done, we'll call the "done" callback passing the document, and the Queue.process
    // function will place the document on the "complete" queue to await processing.
    // -------------------------------------------------------------------------------------------------
    function performSignature(step, done) {
        // Call signWithRestPki() on the Web PKI component passing the token received from REST PKI and the certificate selected by the user.
        pki.signWithRestPki({
            token: step.token,
            thumbprint: selectedCertThumbprint
        }).success(function() {
            // Call the "done" callback signalling we're done with the document
            done(step);
        }).error(function(error) {

            errorDocs.push(step);
            // Render error
            renderFail(step, error);
            // Call the "done" callback with no argument, signalling the document should not go to the next queue
            done();
        });
    }

    // -------------------------------------------------------------------------------------------------
    // Function that performs the third step described above for each document, which is the call
    // batch-signature-complete.php in order to complete the signature.
    //
    // This function is called by the Queue.process function, taking documents from the "complete" queue.
    // Once we're done, we'll call the "done" callback passing the document. Once all documents are
    // processed, the Queue.process will call the "onBatchCompleted" function.
    // -------------------------------------------------------------------------------------------------
    function completeSignature(step, done) {
        // Call the server asynchronously to notify that the signature has been performed
        $.ajax({
            url: '/' + sistemaAssinador.prefix + '/api/assinatura/completar',
            method: 'POST',
            data: {
                id: step.docId,
                token: step.token,
                '_token': $("meta[name='csrf-token']").attr('content')
            },
            dataType: 'json',
            success: function(filename) {
                successDocs.push(step);
                step.filename = filename;
                // Render success
                renderSuccess(step);
                // Call the "done" callback signalling we're done with the document
                done(step);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                errorDocs.push(step);
                // Render error
                renderFail(step, errorThrown || textStatus);
                // Call the "done" callback with no argument, signalling the document should not go to the next queue
                done();
            }
        });
    }

    // -------------------------------------------------------------------------------------------------
    // Function called once the batch is completed.
    // -------------------------------------------------------------------------------------------------
    function onBatchCompleted() {

        console.log(successDocs);
        console.log(errorDocs);

        // Notify the user and unblock the UI
        addAlert('info', 'Batch processing completed');
        // Prevent user from clicking "sign batch" again (our logic isn't prepared for that)
        $('#signButton').prop('disabled', true);

        var texto = "Todos os documentos foram assinados com sucesso!";

        if (successDocs.length > 0 && errorDocs.length == 0) {

            swal({
                type: 'success',
                title: 'Documento assinado',
                text: texto,
            }).then((result) => {
                if (result.value) {
                    window.location.reload(true);
                }
            });

        } else if (successDocs.length > 0 && errorDocs.length > 0) {

            texto = 'Documentos assinados parcialmente, nem todos os documentos puderam ser assinados.';

            swal({
                type: 'warning',
                title: 'Documentos assinados parcialmente',
                text: texto,
            }).then((result) => {
                if (result.value) {
                    window.location.reload(true);
                }
            });

        } else {
            texto = 'Nenhum documento foi assinado. Por favor tente novamente dentro de alguns instantes.';
            swal("Falha", texto, "error");
        }

        // Unblock the UI
        $.unblockUI();
    }

    // -------------------------------------------------------------------------------------------------
    // Function that renders a document as completed successfully
    // -------------------------------------------------------------------------------------------------
    function renderSuccess(step) {
        var docLi = $('#docList li').eq(step.index);
        docLi.append(
            document.createTextNode(' ')
        ).append(
            $('<span />').addClass('glyphicon glyphicon-arrow-right')
        ).append(
            document.createTextNode(' ')
        ).append(
            $('<a />').text(step.filename).attr('href', 'app-data/' + step.filename)
        );
    }

    // -------------------------------------------------------------------------------------------------
    // Function that renders a document as failed
    // -------------------------------------------------------------------------------------------------
    function renderFail(step, error) {
        addAlert('danger', 'An error has occurred while signing Document ' + step.docId + ': ' + error);
        var docLi = $('#docList li').eq(step.index);
        docLi.append(
            document.createTextNode(' ')
        ).append(
            $('<span />').addClass('glyphicon glyphicon-remove')
        );
    }

    // -------------------------------------------------------------------------------------------------
    // Function called if an error occurs on the Web PKI component
    // -------------------------------------------------------------------------------------------------
    function onWebPkiError(message, error, origin) {
        // Unblock the UI
        $.unblockUI();
        // Log the error to the browser console (for debugging purposes)
        if (console) {
            console.log('An error has occurred on the signature browser component: ' + message, error);
        }
        // Show the message to the user. You might want to substitute the alert below with a more user-friendly UI
        // component to show the error.
        alert(message);
    }

    // -------------------------------------------------------------------------------------------------
    // Function called to notify the user with some message
    // -------------------------------------------------------------------------------------------------
    function addAlert(type, message) {
        $('#messagesPanel').append(
            '<div class="alert alert-' + type + ' alert-dismissible">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<span>' + message + '</span>' +
            '</div>');
    }

    return {
        init: init
    };

})();
