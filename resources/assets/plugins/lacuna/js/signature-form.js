// -----------------------------------------------------------------------------------------------------
// This file contains logic for calling the Web PKI component. It is only an example, feel free to alter
// it to meet your application's needs.
// -----------------------------------------------------------------------------------------------------
var signatureForm = (function() {

    var pki = new LacunaWebPKI('AmECYXBpaW50cmFuZXQua3J5cHRvbmJwby5jb20uYnIsYXJpbmZvZ2VkLmNvbS5icixhcmxnLmNvbS5icixhc3NpbmFkb2RpZ2l0YWxtZW50ZS5jb20uYnIsYXNzaW5hZG9yLmxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyLGFzc2luYXJ3ZWIuY29tLmJyLGFzc2luYXR1cmEubW9zdHNpZ24uY29tLmJyLGNoYXQua3J5cHRvbmJwby5jb20uYnIsZW1pc3Nvci5yZWRla3J5cHRvbi5jb20uYnIsaG9tb2xvZ2FjYW8ubW9zdHNpZ24uY29tLmJyLGtyeXB0b25wYXkuY29tLmJyLGxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyLGxvb2tjZXJ0aWZpY2FjYW8uY29tLmJyLG5mLnJlZGVrcnlwdG9uLmNvbS5icixyZW5vdmFjYW8ubGlua2NlcnRpZmljYWNhby5jb20uYnIsc2lzdGVtYXMua3J5cHRvbmJwby5jb20uYnIsc2lzdGVtYXMubGlua2NlcnRpZmljYWNhby5jb20uYnIsc2lzdGVtYXMucmVkZWtyeXB0b24uY29tLmJyLHNpc3RlbWFzMi5saW5rY2VydGlmaWNhY2FvLmNvbS5icix0ZXN0ZXRlY25pY28ubGlua2NlcnRpZmljYWNhby5jb20uYnIsd3d3LmxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyLHd3dy5sb29rY2VydGlmaWNhY2FvLmNvbS5icix3d3cyLmxpbmtjZXJ0aWZpY2FjYW8uY29tLmJyfgBob21vbG9nYWFzc2luYWRvci5saW5rY2VydGlmaWNhY2FvLmNvbS5icixpcDQ6MTAuMC4wLjAvOCxpcDQ6MTI3LjAuMC4wLzgsaXA0OjE3Mi4xNi4wLjAvMTIsaXA0OjE5Mi4xNjguMC4wLzE2LGxvY2FsLmFzc2luYXR1cmEDAFBybwAAAAEgKyJf3DaqglEbhc0+EIhzxu202G4e6O19o1bCB8lxmPrPLbdk/kcFs8MpGLebX0yXLu5Jdf6CwtZ3KuJkugDVnPRdwxPUUmCfZK6z02TPQtkvF0VWxg3po6O2cHPiOklJKTeuMWAb4ae282uYgBQFVe1Oi+S6rC1/bOnVIUUXqisOkQZ95zKYYFExqVldWZeuddLgcwrMBVnIxVVgWQ7TKEWBTFSCzz3YxPYJezMzqaVXAf/D3qYb/Jy3p7h9thMPoqzJ53FUPJPEIkaLw/rAVyrNgxee2i79JDPSgwJXxKW8saNlFxpjBEYgaWHPLceuhypyiX2XutyOLmaQZHOz');
    var token = null;
    var formElement = null;
    var selectElement = null;

    // -------------------------------------------------------------------------------------------------
    // Initializes the signature form
    // -------------------------------------------------------------------------------------------------
    function init(args) {

        token = args.token;
        formElement = args.form;
        selectElement = args.certificateSelect;

        // Wireup of button clicks
        args.signButton.click(sign);
        args.refreshButton.click(refresh);

        // Block the UI while we get things ready
        BlockUI.block('body', 'Carregando certificados');

        // Call the init() method on the LacunaWebPKI object, passing a callback for when
        // the component is ready to be used and another to be called when an error occurs
        // on any of the subsequent operations. For more information, see:
        // https://webpki.lacunasoftware.com/#/Documentation#coding-the-first-lines
        // http://webpki.lacunasoftware.com/Help/classes/LacunaWebPKI.html#method_init
        pki.init({
            restPkiUrl: 'https://restpki.linkcertificacao.com.br/',
            ready: loadCertificates, // as soon as the component is ready we'll load the certificates
            defaultError: onWebPkiError,
            brand: 'link'
        });

        if($(".blockUI").length > 0)
            BlockUI.unBlock();
    }

    // -------------------------------------------------------------------------------------------------
    // Function called when the user clicks the "Refresh" button
    // -------------------------------------------------------------------------------------------------
    function refresh() {
        // Block the UI while we load the certificates
        BlockUI.block('body', 'Carregando certificados');
        // Invoke the loading of the certificates
        loadCertificates();
    }

    // -------------------------------------------------------------------------------------------------
    // Function that loads the certificates, either on startup or when the user
    // clicks the "Refresh" button. At this point, the UI is already blocked.
    // -------------------------------------------------------------------------------------------------
    function loadCertificates() {

        // Call the listCertificates() method to list the user's certificates
        pki.listCertificates({

            // specify that expired certificates should be ignored
            filter: pki.filters.isWithinValidity,

            // in order to list only certificates within validity period and having a CPF (ICP-Brasil), use this instead:
            //filter: pki.filters.all(pki.filters.hasPkiBrazilCpf, pki.filters.isWithinValidity),

            // id of the select to be populated with the certificates
            selectId: selectElement.attr('id'),

            // function that will be called to get the text that should be displayed for each option
            selectOptionFormatter: function (cert) {
                return cert.subjectName + ' (Emitido por ' + cert.issuerName + ')';
            }

        }).success(function () {

            // once the certificates have been listed, unblock the UI
            BlockUI.unBlock();
        });

    }

    // -------------------------------------------------------------------------------------------------
    // Function called when the user clicks the "Sign" button
    // -------------------------------------------------------------------------------------------------
    function sign() {

        // Block the UI while we perform the signature
        BlockUI.block('body', 'Processando certificado...');

        // Get the thumbprint of the selected certificate
        var selectedCertThumbprint = selectElement.val();

        // Call signWithRestPki() on the Web PKI component passing the token received from REST PKI and the certificate
        // selected by the user.
        pki.signWithRestPki({
            token: token,
            thumbprint: selectedCertThumbprint
        }).success(function() {
            // Once the operation is completed, we submit the form
            formElement.submit();
        });
    }

    // -------------------------------------------------------------------------------------------------
    // Function called if an error occurs on the Web PKI component
    // -------------------------------------------------------------------------------------------------
    function onWebPkiError(message, error, origin) {
        // Unblock the UI
        BlockUI.unBlock();
        // Log the error to the browser console (for debugging purposes)
        if (console) {
            console.log('An error has occurred on the signature browser component: ' + message, error);
        }
        // Show the message to the user. You might want to substitute the alert below with a more user-friendly UI
        // component to show the error.
        alert(message);
    }

    return {
        init: init
    };

})();
