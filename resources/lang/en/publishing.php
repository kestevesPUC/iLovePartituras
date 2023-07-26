<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System Language Lines
    |--------------------------------------------------------------------------
     */
    'init' => [
        'my_files' => 'My files',
        'publish_file' => 'Publish File',
        'type' => 'Type',
        'status' => 'Status',
        'signature_pattern' => 'Signature Pattern',
        'description' => 'Description',
        'asked_by' => 'Asked by',
        'signed_by' => 'Signed by',
        'published_by' => 'Published by',
        'published_to' => 'Published to',
        'cancel' => 'Cancel',
        'clear_filters' => 'Clear Filters',
        'search' => 'Search',
        'subscribe_batch' => 'Subscribe Batch',
        'bulk_download' => 'Bulk Download',
    ],
    'step_one' => [
        'digital_signature' => 'Digital signature',
        'digital_signature_tooltip' => 'PKI-Brazil signature only',
        'eletronic_signature' => 'Eletronic Signature',
        'eletronic_signature_tooltip' => 'Signature validity by proving email / sms in PDF files<br>',
        'hybrid' => 'Hybrid',
        'hybrid_tooltip' => 'It includes the two previous forms of signature in a file',
        'other_formats' => 'Other formats',
        'other_formats_tooltip' => 'For any type of document.',
        'other_formats_description' => 'You can sign any kind of document, spreadsheets, docs, songs, zip, etc.',
        'pdf' => 'PDF',
        'pdf_description' => 'Only for PDF files.',
        'pdf_tooltip' => 'You can only subscribe to PDF files.',
        'eletronic' => 'Eletronic',
        'drop_files' => 'Drop your files or click here.',
        'description' => 'Description',
        'deadline_question' => 'Deadline for signature?',
        'deadline' => 'Deadline',
        'file_description' => 'Enter file description',
        'upload' => 'Upload file without signature only',
        'document' => 'Document',
        'document_tooltip' => 'Choose which form and documents you want to sign',
        'subs' => 'Subcribers',
        'subs_tooltip' => 'Choose who subscribes your document',
        'final' => 'Finalization',
        'final_tooltip' => 'Position your sign and publish your file',
        'sign_tooltip' => 'Choose which sign you will use in your document:',
        'new_folder' => 'New Folder'
    ],
    'step_two' => [
        'configuration' => 'Configuration',
        'folder' => 'Folder',
        'folder_tooltip' => '(Choose on which folder the file will be saved)',
        'hash' => 'Do you want to generate the authentication hash?',
        'typo_upload' => 'Do you need to digitally sign document?',
        'yes' => 'Yes',
        'no' => 'No',
        'compress' => 'Compress files?',
        'save_where' => 'Save where?',
        'folder' => 'Folder',
        'folder_choice_tooltip' => '(Choose which folder the document will be saved in, or click add to create a new folder)'
    ],
    'step_three' => [
        'participants' => 'Participants',
        'add_participants' => 'Add participants',
        'find_participants' => 'Search for registered participant',
        'cpf_cnpj' => 'CPF/CNPJ',
        'name' => 'Name',
        'email' => 'E-mail',
        'type' => 'Type',
        'phone' => 'Phone',
        'order' => 'Order of',
        'signature' => 'Signature',
        'action' => 'Action',
        'participants_validation' => 'No participants added in this document.',
        'participants_who_sign_document' => 'Participants who will sign the document',
        'who_signs' => 'Who signs?',
        'add_me' => 'Add me',
        'signed_document_to_third_party' => 'Do you want to send a signed document to a third party?',
        'add_third_party_email' => 'Add third party email',
        'email_notified_signature' => 'E-mails that will be notified of the document without the need for a signature!',
        'customize_email_text' => 'Do you want to customize email text?',
        'signature_position' => 'Positioning Signatures',
        'signature_position_tooltip' => "This is not an obligatry step. If you don't position your sign now they will be in the final of the document!",
        'documents' => 'Documents',
        'tools' => 'Tools',
        'date_time' => 'Date and Hour',
        'date_time_stamp' => 'Date Time Stamp',
        'initial' => 'Initial',
        'initial_tooltip' => 'Initial sign stamp',
        'digital_sign' => 'Digital Sign',
        'digital_sign_tooltip' => 'Digital sign stamp',
        'eletronic_sign' => 'Eletronic Sign',
        'eletronic_sign_tooltip' => 'Eletronic sign stamp',
        'sign_eletronically' => 'Sign Eletronically',
        'sign_certificate' => 'Sign with digital certificate',
    ],
    'step_four' => [
        'send' => 'Send',
        'send_decription' => 'Inform below the data that will be sent in the subscription request email.',
        'subject' => 'Subject',
        'message' => 'Message',
    ],
    'step_guide' => [
        'files' => 'Files',
        'configuration' => 'Configuration',
        'participants' => 'Participants',
        'send' => 'Send',
        'come_back' => 'Back',
        'publish' => 'Publish',
        'next' => 'Next',
    ],
    'check_signature_with_login' => [
        'title' => 'Verify signature',
        'subtitle' => 'The easy and fast way to check signatures on your documents.',
        'text' => 'To verify the authenticity of the document, enter the login and password entered next to the document.',
        'check' => 'Verify',
        'password' => 'Password',
    ],
    'check_signature' => [
        'download' => 'Download',
        'image_autentication' => 'Image Authentication',
        'participants' => 'Participants',
        'cpf_cnpj' => 'CPF/CNPJ',
        'name' => 'Name',
        'status' => 'Status',
        'date' => 'Date',
        'signature_not_found' => 'No signature found.',
        'resend_email' => 'Resend E-mail',
        'come_back_page' => 'Back to login page and check another document',
        'check_other' => 'Check another document',
    ],
    'sign' => [
        'sign' => 'Sign',
        'reject' => 'Reject',
        'limit_date_message' => 'The deadline to sign this document is over, ask the applicant to extend it.',
        'download_file_signed' => 'Download file with the signatures',
        'download' => 'Download',
        'download_file_original_without_signature' => 'Download original unsigned file',
        'download_file_original' => 'Download Original',
        'download_autenticate_image' => 'Download the signatures authentication image separately',
        'image_autentication' => 'Image Authentication',
        'history' => 'History',
        'participants' => 'Participants',
        'cpf_cnpj' => 'CPF/CNPJ',
        'name' => 'Name',
        'status' => 'Status',
        'date' => 'Date',
        'signature_not_found' => 'No signature found.',
        'resend' => 'Resend',
        'deadline' => 'Deadline',
        'preview' => 'Preview',
        'save_changes' => 'Save changes',
    ],
    'resend_link' => [
        'title' => 'Resend Link',
        'text' => 'This link has expired! Click the button below to send a new email.',
        'send' => 'Send Email',
    ],
];